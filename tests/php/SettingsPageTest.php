<?php
/**
 * Tests for the settings page.
 *
 * @package ucsc-travel-functionality
 */

use PHPUnit\Framework\TestCase;

/**
 * Covers lib/functions/settings.php.
 */
class SettingsPageTest extends TestCase {

	/**
	 * Reset the stubs before each test.
	 *
	 * @return void
	 */
	protected function setUp(): void {
		UCSCTravel_Test_State::reset();
	}

	/**
	 * The page is added on admin_menu.
	 *
	 * @return void
	 */
	public function test_page_is_registered_on_admin_menu() {
		$this->assertContains(
			array( 'admin_menu', 'ucsctravel_add_settings_page', 10, 1 ),
			UCSCTravel_Test_State::$filters
		);
	}

	/**
	 * The page sits under Settings, for administrators, with the known slug.
	 *
	 * The Settings link on the Plugins screen depends on that slug.
	 *
	 * @return void
	 */
	public function test_page_is_an_options_page_for_administrators() {
		ucsctravel_add_settings_page();

		$this->assertSame(
			array(
				array(
					'UCSC Travel Functionality',
					'UCSC Travel Functionality',
					'manage_options',
					'ucsc-travel-functionality-settings',
					'ucsctravel_render_settings_page',
				),
			),
			UCSCTravel_Test_State::$options_pages
		);
	}

	/**
	 * The page shows the plugin name and version from the plugin header.
	 *
	 * @return void
	 */
	public function test_page_shows_name_and_version_from_header() {
		$data = get_plugin_data( UCSCTRAVEL_PLUGIN_DIR . 'plugin.php' );

		$html = $this->render();

		$this->assertStringContainsString( '<h1>UCSC Travel Functionality</h1>', $html );
		$this->assertStringContainsString( 'Version ' . $data['Version'], $html );
		$this->assertStringContainsString( $data['Description'], $html );
	}

	/**
	 * The page reads the header without markup.
	 *
	 * With markup on, core appends "By UC Santa Cruz" to the description.
	 *
	 * @return void
	 */
	public function test_page_reads_header_without_markup() {
		$this->render();

		$this->assertSame( UCSCTRAVEL_PLUGIN_DIR . 'plugin.php', UCSCTravel_Test_State::$plugin_data_args[0] );
		$this->assertFalse( UCSCTravel_Test_State::$plugin_data_args[1] );
	}

	/**
	 * A user without manage_options gets no output.
	 *
	 * @return void
	 */
	public function test_page_renders_nothing_without_capability() {
		UCSCTravel_Test_State::$current_user_can = false;

		$this->assertSame( '', $this->render() );
	}

	/**
	 * The Settings link filter is registered for this plugin's row only.
	 *
	 * @return void
	 */
	public function test_settings_link_is_registered_for_this_plugin() {
		$this->assertContains(
			array( 'plugin_action_links_' . UCSCTRAVEL_PLUGIN_BASE, 'ucsctravel_settings_link', 10, 1 ),
			UCSCTravel_Test_State::$filters
		);
	}

	/**
	 * The link uses the URL that WordPress reports for the page.
	 *
	 * @return void
	 */
	public function test_settings_link_uses_menu_page_url() {
		UCSCTravel_Test_State::$menu_page_url = 'https://example.test/wp-admin/options-general.php?page=ucsc-travel-functionality-settings';

		$links = ucsctravel_settings_link( array( '<a href="#">Deactivate</a>' ) );

		$this->assertSame(
			array(
				'<a href="#">Deactivate</a>',
				'<a href="https://example.test/wp-admin/options-general.php?page=ucsc-travel-functionality-settings">Settings</a>',
			),
			$links
		);
	}

	/**
	 * If the page is not registered, the link falls back to the Settings URL.
	 *
	 * @return void
	 */
	public function test_settings_link_falls_back_when_page_is_not_registered() {
		$links = ucsctravel_settings_link( array() );

		$this->assertSame(
			array( '<a href="https://example.test/wp-admin/options-general.php?page=ucsc-travel-functionality-settings">Settings</a>' ),
			$links
		);
	}

	/**
	 * Capture the page output.
	 *
	 * @return string
	 */
	private function render() {
		ob_start();
		ucsctravel_render_settings_page();
		return (string) ob_get_clean();
	}
}
