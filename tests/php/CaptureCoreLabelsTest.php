<?php
/**
 * Tests for the core label capture script.
 *
 * @package ucsc-travel-functionality
 */

use PHPUnit\Framework\TestCase;

require_once dirname( __DIR__ ) . '/tools/capture-core-labels.php';

/**
 * Covers ucsctravel_format_label_fixture().
 *
 * The script only writes the fixture when WP-CLI runs it, so requiring it
 * here loads the formatter and nothing else.
 */
class CaptureCoreLabelsTest extends TestCase {

	/**
	 * Path to the committed fixture.
	 *
	 * @return string
	 */
	private function fixture_path() {
		return dirname( __DIR__ ) . '/fixtures/core-category-labels.php';
	}

	/**
	 * The WordPress version named in the committed fixture's header.
	 *
	 * @return string
	 */
	private function fixture_version() {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a file from this repository, not making a request.
		preg_match( '/Captured from WordPress (\S+) /', (string) file_get_contents( $this->fixture_path() ), $matches );
		return $matches[1];
	}

	/**
	 * Formatting the committed labels gives the committed file.
	 *
	 * The committed file passes PHPCS, so this also keeps the script's
	 * output within the coding standard.
	 *
	 * @return void
	 */
	public function test_formatter_reproduces_the_committed_fixture() {
		$labels = require $this->fixture_path();

		$this->assertStringEqualsFile(
			$this->fixture_path(),
			ucsctravel_format_label_fixture( $labels, $this->fixture_version() )
		);
	}

	/**
	 * The formatted file returns the labels it was given.
	 *
	 * @return void
	 */
	public function test_formatted_fixture_returns_the_same_labels() {
		$labels = array(
			'name'          => 'Categories',
			'popular_items' => null,
			'quoted'        => "It's a \\ test",
		);

		// phpcs:disable WordPress.WP.AlternativeFunctions -- The suite runs without WordPress, so WP_Filesystem does not exist.
		$path = tempnam( sys_get_temp_dir(), 'ucsctravel' );
		file_put_contents( $path, ucsctravel_format_label_fixture( $labels, '1.2.3' ) );
		$result = require $path;
		unlink( $path );
		// phpcs:enable WordPress.WP.AlternativeFunctions

		$this->assertSame( $labels, $result );
	}
}
