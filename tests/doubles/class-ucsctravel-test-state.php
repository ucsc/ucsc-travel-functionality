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
	 * Restore every per-test stub to its default.
	 *
	 * @return void
	 */
	public static function reset() {
		self::$is_category = false;
	}
}
