<?php
/**
 * WordPress function stand-ins for the unit test suite.
 *
 * Each stub is the smallest thing that lets the plugin load and its functions
 * be exercised. None of them reproduce WordPress behaviour faithfully. Values
 * the tests need to vary or inspect live on UCSCTravel_Test_State.
 *
 * @package ucsc-travel-functionality
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

if ( ! function_exists( 'add_filter' ) ) {
	/**
	 * Record the filter so tests can assert it was registered.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted argument count.
	 * @return bool
	 */
	function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		UCSCTravel_Test_State::$filters[] = array( $hook, $callback, $priority, $accepted_args );
		return true;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * Actions are filters in core, so record them the same way.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted argument count.
	 * @return bool
	 */
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		return add_filter( $hook, $callback, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'plugin_dir_path' ) ) {
	/**
	 * Directory of the given file, with a trailing slash.
	 *
	 * @param string $file File path.
	 * @return string
	 */
	function plugin_dir_path( $file ) {
		return rtrim( dirname( $file ), '/' ) . '/';
	}
}

if ( ! function_exists( 'plugin_basename' ) ) {
	/**
	 * Plugin basename, as "directory/file.php".
	 *
	 * @param string $file File path.
	 * @return string
	 */
	function plugin_basename( $file ) {
		return basename( dirname( $file ) ) . '/' . basename( $file );
	}
}

if ( ! function_exists( '__' ) ) {
	/**
	 * Pass-through translation.
	 *
	 * @param string $text   Text to translate.
	 * @param string $domain Text domain.
	 * @return string
	 */
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( '_x' ) ) {
	/**
	 * Pass-through translation with context.
	 *
	 * @param string $text    Text to translate.
	 * @param string $context Context for translators.
	 * @param string $domain  Text domain.
	 * @return string
	 */
	function _x( $text, $context, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'is_category' ) ) {
	/**
	 * Whether the current query is a category archive.
	 *
	 * @param int|string|int[]|string[] $category Category to check against.
	 * @return bool
	 */
	function is_category( $category = '' ) {
		return UCSCTravel_Test_State::$is_category;
	}
}
