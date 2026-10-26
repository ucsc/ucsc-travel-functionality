<?php
/**
 * Mutable state backing the WordPress function stubs.
 *
 * @package ucsc-travel-functionality
 */

/**
 * Values the WordPress stand-ins in tests/stubs.php read from and write to.
 *
 * Tests set what they need in setUp() and call reset() so nothing leaks
 * between cases.
 */
final class UCSCTravel_Test_State {

	/**
	 * Filters registered through add_filter(), oldest first.
	 *
	 * Each entry is array( $hook, $callback, $priority, $accepted_args ). The
	 * plugin registers its filters once, at file scope, when the bootstrap
	 * loads it. reset() therefore leaves this list alone.
	 *
	 * @var array<int,array<int,mixed>>
	 */
	public static $filters = array();

	/**
	 * Value returned by is_category().
	 *
	 * @var bool
	 */
	public static $is_category = false;

	/**
	 * Pages registered through add_options_page(), oldest first.
	 *
	 * Each entry is array( $page_title, $menu_title, $capability, $menu_slug,
	 * $callback ).
	 *
	 * @var array<int,array<int,mixed>>
	 */
	public static $options_pages = array();

	/**
	 * Value returned by current_user_can().
	 *
	 * @var bool
	 */
	public static $current_user_can = true;

	/**
	 * Value returned by menu_page_url().
	 *
	 * @var string
	 */
	public static $menu_page_url = '';

	/**
	 * Arguments of the last get_plugin_data() call.
	 *
	 * The value is array( $plugin_file, $markup, $translate ).
	 *
	 * @var array<int,mixed>
	 */
	public static $plugin_data_args = array();

	/**
	 * Restore every per-test stub to its default.
	 *
	 * @return void
	 */
	public static function reset() {
		self::$is_category      = false;
		self::$options_pages    = array();
		self::$current_user_can = true;
		self::$menu_page_url    = '';
		self::$plugin_data_args = array();
	}
}
