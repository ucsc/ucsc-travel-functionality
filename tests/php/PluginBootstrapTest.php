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
	 * `npm run release` keeps the two in step through the
	 * `@ucsantacruz/wordpress-version-updater` package. A hand edit to one
	 * of them breaks that.
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

	/**
	 * The release runs the header check before it writes a file.
	 *
	 * The `prerelease` script stops `npm run release` if the updater package
	 * is not installed or if plugin.php has no Version line. Without it,
	 * commit-and-tag-version skips plugin.php with only a warning.
	 *
	 * @return void
	 */
	public function test_prerelease_script_checks_plugin_header() {
		$config = $this->release_config();

		$this->assertSame(
			'wordpress-version-updater check plugin.php',
			$config['scripts']['prerelease'] ?? null
		);
	}

	/**
	 * The plugin.php updater path points into the updater dev dependency.
	 *
	 * The release tool, commit-and-tag-version, loads the updater path
	 * relative to the repository root. If the file is missing, it skips plugin.php with only
	 * a warning, and the release tags a version that the header does not
	 * have. The prerelease check does not catch a wrong path, because the
	 * package itself is installed.
	 *
	 * The CI checks job does not run `npm ci`, so the file check runs only
	 * where node_modules exists.
	 *
	 * @return void
	 */
	public function test_plugin_header_updater_path_is_the_package() {
		$root    = dirname( __DIR__, 2 );
		$package = $this->package_json();
		$updater = null;

		foreach ( $package['commit-and-tag-version']['bumpFiles'] as $bump_file ) {
			if ( 'plugin.php' === ( $bump_file['filename'] ?? null ) ) {
				$updater = $bump_file['updater'] ?? null;
			}
		}

		$this->assertSame(
			'node_modules/@ucsantacruz/wordpress-version-updater/index.js',
			$updater
		);
		$this->assertArrayHasKey(
			'@ucsantacruz/wordpress-version-updater',
			$package['devDependencies']
		);

		if ( is_dir( $root . '/node_modules' ) ) {
			$this->assertFileExists( $root . '/' . $updater );
		}
	}

	/**
	 * Returns the commit-and-tag-version configuration from package.json.
	 *
	 * @return array<string, mixed>
	 */
	private function release_config() {
		return $this->package_json()['commit-and-tag-version'];
	}

	/**
	 * Returns package.json as an array.
	 *
	 * @return array<string, mixed>
	 */
	private function package_json() {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a file from this repository, not making a request.
		return json_decode( (string) file_get_contents( dirname( __DIR__, 2 ) . '/package.json' ), true );
	}
}
