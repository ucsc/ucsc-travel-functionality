<?php
/**
 * PHPUnit bootstrap file.
 *
 * The suite is unit-level. tests/stubs.php supplies the few WordPress
 * functions the plugin calls, so the suite runs on bare PHP plus Composer,
 * with no WordPress install and no database. To check behaviour inside real
 * WordPress, use a local WordPress site with this plugin mapped into it.
 *
 * The stubs must load before plugin.php: plugin.php calls plugin_dir_path()
 * and plugin_basename() at file scope, and lib/functions/*.php registers its
 * filters at file scope through add_filter().
 *
 * Run:
 *   composer test
 *
 * @package ucsc-travel-functionality
 */

$ucsctravel_tests_dir = __DIR__;

require_once $ucsctravel_tests_dir . '/doubles/class-ucsctravel-test-state.php';
require_once $ucsctravel_tests_dir . '/stubs.php';
require_once dirname( $ucsctravel_tests_dir ) . '/plugin.php';
