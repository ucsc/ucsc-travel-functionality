<?php
/**
 * Capture core's default Category labels into the test fixture
 *
 * Run this in a WordPress site where the plugin is active, after each
 * WordPress release that adds a taxonomy label. It rewrites
 * tests/fixtures/core-category-labels.php, which CategoryLabelsTest uses to
 * check that the plugin relabels every label core ships.
 *
 * With wp-env, from the site folder:
 *
 *     npx wp-env run cli wp eval-file wp-content/plugins/ucsc-travel-functionality/tests/tools/capture-core-labels.php
 *
 * With WP-CLI on any other site:
 *
 *     wp eval-file <plugin folder>/tests/tools/capture-core-labels.php
 *
 * The script does not read get_taxonomy( 'category' )->labels. WordPress
 * builds those labels once, at registration, with the plugin's filter
 * applied, so they stay filtered after remove_filter(). The script removes
 * the filter and builds a new WP_Taxonomy instead.
 *
 * The file loads without side effects outside WP-CLI, so the unit tests can
 * load the formatter.
 *
 * @package ucsc-travel-functionality
 */

/**
 * Format core's Category labels as the fixture file
 *
 * @param array<string, string|null> $labels     Label keys and their core text.
 * @param string                     $wp_version WordPress version the labels came from.
 * @return string The fixture file contents.
 */
function ucsctravel_format_label_fixture( array $labels, $wp_version ) {
	// Label keys are plain identifiers, so each quoted key is two characters longer.
	$width = max( array_map( 'strlen', array_keys( $labels ) ) ) + 2;

	$lines = '';
	foreach ( $labels as $key => $value ) {
		$lines .= "\t" . str_pad( var_export( $key, true ), $width ) . ' => ' . ( null === $value ? 'null' : var_export( $value, true ) ) . ",\n"; // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export -- Writes PHP source, not debug output.
	}

	return <<<PHP
<?php
/**
 * Core's default labels for the Category taxonomy.
 *
 * Captured from WordPress {$wp_version} by tests/tools/capture-core-labels.php,
 * which removes the plugin's label filter and exports the labels of a new
 * WP_Taxonomy. Run that script again when a WordPress release adds a label,
 * so CategoryLabelsTest keeps checking every label core ships.
 *
 * @package ucsc-travel-functionality
 */

return array(
{$lines});

PHP;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	remove_filter( 'taxonomy_labels_category', 'ucsctravel_filter_category_labels' );

	$ucsctravel_taxonomy = new WP_Taxonomy( 'category', 'post', array( 'hierarchical' => true ) );
	$ucsctravel_labels   = (array) $ucsctravel_taxonomy->labels;
	$ucsctravel_fixture  = dirname( __DIR__ ) . '/fixtures/core-category-labels.php';

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- A development tool that writes into this repository.
	if ( false === file_put_contents( $ucsctravel_fixture, ucsctravel_format_label_fixture( $ucsctravel_labels, $GLOBALS['wp_version'] ) ) ) {
		WP_CLI::error( "Could not write {$ucsctravel_fixture}." );
	}

	WP_CLI::success( sprintf( 'Wrote %d labels from WordPress %s to %s.', count( $ucsctravel_labels ), $GLOBALS['wp_version'], $ucsctravel_fixture ) );
}
