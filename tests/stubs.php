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

if ( ! function_exists( 'esc_html' ) ) {
	/**
	 * Escape for HTML.
	 *
	 * @param string $text Text to escape.
	 * @return string
	 */
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_html__' ) ) {
	/**
	 * Pass-through translation, escaped for HTML.
	 *
	 * @param string $text   Text to translate.
	 * @param string $domain Text domain.
	 * @return string
	 */
	function esc_html__( $text, $domain = 'default' ) {
		return esc_html( $text );
	}
}

if ( ! function_exists( 'esc_html_e' ) ) {
	/**
	 * Echo a pass-through translation, escaped for HTML.
	 *
	 * @param string $text   Text to translate.
	 * @param string $domain Text domain.
	 * @return void
	 */
	function esc_html_e( $text, $domain = 'default' ) {
		echo esc_html( $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html() is the stub above.
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	/**
	 * URL pass-through. The stub does not sanitize.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	function esc_url( $url ) {
		return (string) $url;
	}
}

if ( ! function_exists( 'wp_kses_post' ) ) {
	/**
	 * Content pass-through. The stub does not filter tags.
	 *
	 * @param string $data Content.
	 * @return string
	 */
	function wp_kses_post( $data ) {
		return (string) $data;
	}
}

if ( ! function_exists( 'admin_url' ) ) {
	/**
	 * Admin URL on a fixed example host.
	 *
	 * @param string $path Path relative to the admin URL.
	 * @return string
	 */
	function admin_url( $path = '' ) {
		return 'https://example.test/wp-admin/' . ltrim( $path, '/' );
	}
}

if ( ! function_exists( 'menu_page_url' ) ) {
	/**
	 * URL of a registered admin page.
	 *
	 * @param string $menu_slug Page slug.
	 * @param bool   $display   Whether to echo the URL.
	 * @return string
	 */
	function menu_page_url( $menu_slug, $display = true ) {
		return UCSCTravel_Test_State::$menu_page_url;
	}
}

if ( ! function_exists( 'add_options_page' ) ) {
	/**
	 * Record the page so tests can assert its arguments.
	 *
	 * @param string   $page_title Page title.
	 * @param string   $menu_title Menu title.
	 * @param string   $capability Required capability.
	 * @param string   $menu_slug  Page slug.
	 * @param callable $callback   Render callback.
	 * @param int      $position   Menu position.
	 * @return string
	 */
	function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $callback = '', $position = null ) {
		UCSCTravel_Test_State::$options_pages[] = array( $page_title, $menu_title, $capability, $menu_slug, $callback );
		return 'settings_page_' . $menu_slug;
	}
}

if ( ! function_exists( 'current_user_can' ) ) {
	/**
	 * Whether the current user has a capability.
	 *
	 * @param string $capability Capability name.
	 * @param mixed  ...$args    Optional object ID and more.
	 * @return bool
	 */
	function current_user_can( $capability, ...$args ) {
		return UCSCTravel_Test_State::$current_user_can;
	}
}

if ( ! function_exists( 'get_plugin_data' ) ) {
	/**
	 * Read the Name, Version and Description headers from a plugin file.
	 *
	 * @param string $plugin_file Path to the main plugin file.
	 * @param bool   $markup      Whether to apply HTML markup.
	 * @param bool   $translate   Whether to translate the headers.
	 * @return array<string,string>
	 */
	function get_plugin_data( $plugin_file, $markup = true, $translate = true ) {
		UCSCTravel_Test_State::$plugin_data_args = array( $plugin_file, $markup, $translate );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a file from this repository, not making a request.
		$contents = (string) file_get_contents( $plugin_file );
		$headers  = array(
			'Name'        => 'Plugin Name',
			'Version'     => 'Version',
			'Description' => 'Description',
		);
		$data     = array();
		foreach ( $headers as $key => $header ) {
			$data[ $key ] = preg_match( '/^\s*\*\s*' . preg_quote( $header, '/' ) . ':\s*(.+)$/m', $contents, $match ) ? trim( $match[1] ) : '';
		}
		return $data;
	}
}
