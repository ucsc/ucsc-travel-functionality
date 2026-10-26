<?php
/**
 * Tests for the Category label filter.
 *
 * @package ucsc-travel-functionality
 */

use PHPUnit\Framework\TestCase;

/**
 * Covers ucsctravel_filter_category_labels().
 */
class CategoryLabelsTest extends TestCase {

	/**
	 * Labels that keep their core text on purpose.
	 *
	 * These are help text or generic words that never name the taxonomy.
	 *
	 * @var string[]
	 */
	private const UNCHANGED = array(
		'name_field_description',
		'slug_field_description',
		'parent_field_description',
		'desc_field_description',
		'most_used',
	);

	/**
	 * Run the filter over core's default Category labels.
	 *
	 * @return object Filtered labels.
	 */
	private function filtered_core_labels() {
		$labels = (object) require dirname( __DIR__ ) . '/fixtures/core-category-labels.php';
		return ucsctravel_filter_category_labels( $labels );
	}

	/**
	 * The filter is registered on the Category labels hook.
	 *
	 * @return void
	 */
	public function test_filter_is_registered() {
		$this->assertContains(
			array( 'taxonomy_labels_category', 'ucsctravel_filter_category_labels', 10, 1 ),
			UCSCTravel_Test_State::$filters
		);
	}

	/**
	 * No core label still names the Category taxonomy after filtering.
	 *
	 * This is the test that catches a label core ships but the filter
	 * misses, such as filter_by_item on the Posts list.
	 *
	 * @return void
	 */
	public function test_no_label_still_says_category() {
		foreach ( (array) $this->filtered_core_labels() as $key => $value ) {
			if ( null === $value ) {
				continue;
			}
			$this->assertDoesNotMatchRegularExpression( '/categor/i', $value, "Label '{$key}' still names the Category taxonomy." );
		}
	}

	/**
	 * The main names read Expedition and Expeditions.
	 *
	 * @return void
	 */
	public function test_main_names_use_expedition() {
		$labels = $this->filtered_core_labels();

		$this->assertSame( 'Expeditions', $labels->name );
		$this->assertSame( 'Expedition', $labels->singular_name );
		$this->assertSame( 'Expeditions', $labels->menu_name );
		$this->assertSame( 'Expedition Archives', $labels->template_name );
		$this->assertSame( 'Filter by expedition', $labels->filter_by_item );
		$this->assertSame( 'Add Expedition', $labels->add_new_item );
	}

	/**
	 * Labels that do not name the taxonomy keep their core text.
	 *
	 * @return void
	 */
	public function test_unrelated_labels_are_left_alone() {
		$core     = require dirname( __DIR__ ) . '/fixtures/core-category-labels.php';
		$filtered = (array) $this->filtered_core_labels();

		foreach ( self::UNCHANGED as $key ) {
			$this->assertSame( $core[ $key ], $filtered[ $key ], "Label '{$key}' changed." );
		}
	}

	/**
	 * Labels core leaves null for hierarchical taxonomies stay null.
	 *
	 * Core uses null to mean "not applicable", so a value here would show up
	 * on screens where Categories never show it.
	 *
	 * @return void
	 */
	public function test_null_labels_stay_null() {
		$filtered = $this->filtered_core_labels();

		$this->assertNull( $filtered->popular_items );
		$this->assertNull( $filtered->separate_items_with_commas );
		$this->assertNull( $filtered->add_or_remove_items );
		$this->assertNull( $filtered->choose_from_most_used );
	}

	/**
	 * The filter returns the same object it was given.
	 *
	 * Other filters on the same hook may hold a reference to it.
	 *
	 * @return void
	 */
	public function test_returns_the_same_object() {
		$labels = (object) require dirname( __DIR__ ) . '/fixtures/core-category-labels.php';

		$this->assertSame( $labels, ucsctravel_filter_category_labels( $labels ) );
	}
}
