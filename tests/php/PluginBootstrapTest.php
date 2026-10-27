<?php
/**
 * Tests for the plugin bootstrap.
 *
 * @package ucsc-travel-functionality
 */

use PHPUnit\Framework\TestCase;

/**
 * Covers plugin.php: its constants, its includes and its header.
 */
class PluginBootstrapTest extends TestCase {

	/**
	 * The plugin directory constant points at the plugin root.
	 *
	 * @return void
	 */
	public function test_plugin_dir_constant_points_at_plugin_root() {
		$this->assertSame( dirname( __DIR__, 2 ) . '/', UCSCTRAVEL_PLUGIN_DIR );
	}

	/**
	 * The base name is "directory/plugin.php", as WordPress expects.
	 *
	 * @return void
	 */
	public function test_plugin_base_constant_names_the_main_file() {
		$this->assertSame( basename( dirname( __DIR__, 2 ) ) . '/plugin.php', UCSCTRAVEL_PLUGIN_BASE );
	}

	/**
	 * The include in plugin.php loaded the taxonomy functions.
	 *
	 * The include is guarded by file_exists(), so a renamed or moved file
	 * fails silently in WordPress. This test catches that.
	 *
	 * @return void
	 */
	public function test_taxonomy_functions_are_loaded() {
		$this->assertTrue( function_exists( 'ucsctravel_filter_category_labels' ) );
		$this->assertTrue( function_exists( 'ucsctravel_filter_category_archive_title_prefix' ) );
	}

	/**
	 * The include in plugin.php loaded the settings page functions.
	 *
	 * @return void
	 */
	public function test_settings_functions_are_loaded() {
		$this->assertTrue( function_exists( 'ucsctravel_add_settings_page' ) );
		$this->assertTrue( function_exists( 'ucsctravel_render_settings_page' ) );
		$this->assertTrue( function_exists( 'ucsctravel_settings_link' ) );
	}

	/**
	 * The Version header in plugin.php matches package.json.
	 *
	 * `npm run release` keeps the two in step through
	 * wp-plugin-version-updater.js. A hand edit to one of them breaks that.
	 *
	 * @return void
	 */
	public function test_version_header_matches_package_json() {
		$root = dirname( __DIR__, 2 );
		// phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading files from this repository, not making a request.
		$package = json_decode( (string) file_get_contents( $root . '/package.json' ), true );
		$header  = (string) file_get_contents( $root . '/plugin.php' );
		// phpcs:enable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		$this->assertMatchesRegularExpression(
			'/^\s*\*\s*Version:\s*' . preg_quote( $package['version'], '/' ) . '\s*$/m',
			$header
		);
	}
}
