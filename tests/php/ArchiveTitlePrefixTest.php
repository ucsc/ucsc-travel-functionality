<?php
/**
 * Tests for the category archive title prefix filter.
 *
 * @package ucsc-travel-functionality
 */

use PHPUnit\Framework\TestCase;

/**
 * Covers ucsctravel_filter_category_archive_title_prefix().
 */
class ArchiveTitlePrefixTest extends TestCase {

	/**
	 * Reset the stubs before each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		UCSCTravel_Test_State::reset();
	}

	/**
	 * The filter is registered on the archive title prefix hook.
	 *
	 * @return void
	 */
	public function test_filter_is_registered() {
		$this->assertContains(
			array( 'get_the_archive_title_prefix', 'ucsctravel_filter_category_archive_title_prefix', 10, 1 ),
			UCSCTravel_Test_State::$filters
		);
	}

	/**
	 * A category archive gets the Expedition prefix.
	 *
	 * @return void
	 */
	public function test_category_archive_uses_expedition_prefix() {
		UCSCTravel_Test_State::$is_category = true;

		$this->assertSame( 'Expedition:', ucsctravel_filter_category_archive_title_prefix( 'Category:' ) );
	}

	/**
	 * Every other archive keeps the prefix it was given.
	 *
	 * @return void
	 */
	public function test_other_archives_keep_their_prefix() {
		UCSCTravel_Test_State::$is_category = false;

		$this->assertSame( 'Tag:', ucsctravel_filter_category_archive_title_prefix( 'Tag:' ) );
		$this->assertSame( '', ucsctravel_filter_category_archive_title_prefix( '' ) );
	}
}
