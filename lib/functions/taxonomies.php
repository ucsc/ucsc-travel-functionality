<?php
/**
 * Taxonomy functions
 *
 * This file contains taxonomy functions for the UCSC Travel Functionality Plugin
 *
 * @since 0.1.0
 * @package ucsc-travel-functionality
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'taxonomy_labels_category', 'ucsctravel_filter_category_labels' );

/**
 * Relabel the Category taxonomy as Expeditions
 *
 * Runs through the dynamic `taxonomy_labels_{$taxonomy}` filter, which
 * WordPress applies in get_taxonomy_labels(). The new labels reach the admin,
 * the block editor, and any theme that reads get_taxonomy( 'category' )->labels.
 *
 * The taxonomy itself, its slug and its /category/ URL base do not change.
 *
 * @since 0.1.0
 * @param object $labels Category labels, with each label as a property.
 * @return object Filtered labels.
 */
function ucsctravel_filter_category_labels( $labels ) {
	$labels->name                  = __( 'Expeditions', 'ucsctravel' );
	$labels->singular_name         = __( 'Expedition', 'ucsctravel' );
	$labels->menu_name             = __( 'Expeditions', 'ucsctravel' );
	$labels->all_items             = __( 'All Expeditions', 'ucsctravel' );
	$labels->edit_item             = __( 'Edit Expedition', 'ucsctravel' );
	$labels->view_item             = __( 'View Expedition', 'ucsctravel' );
	$labels->update_item           = __( 'Update Expedition', 'ucsctravel' );
	$labels->add_new_item          = __( 'Add New Expedition', 'ucsctravel' );
	$labels->new_item_name         = __( 'New Expedition Name', 'ucsctravel' );
	$labels->parent_item           = __( 'Parent Expedition', 'ucsctravel' );
	$labels->parent_item_colon     = __( 'Parent Expedition:', 'ucsctravel' );
	$labels->search_items          = __( 'Search Expeditions', 'ucsctravel' );
	$labels->not_found             = __( 'No expeditions found.', 'ucsctravel' );
	$labels->back_to_items         = __( '&larr; Go to Expeditions', 'ucsctravel' );
	$labels->item_link             = __( 'Expedition Link', 'ucsctravel' );
	$labels->item_link_description = __( 'A link to an expedition.', 'ucsctravel' );
	$labels->no_terms              = __( 'No expeditions', 'ucsctravel' );
	$labels->filter_by_item        = __( 'Filter by expedition', 'ucsctravel' );
	$labels->items_list_navigation = __( 'Expeditions list navigation', 'ucsctravel' );
	$labels->items_list            = __( 'Expeditions list', 'ucsctravel' );
	$labels->name_admin_bar        = __( 'Expedition', 'ucsctravel' );
	$labels->template_name         = __( 'Expedition Archives', 'ucsctravel' );
	return $labels;
}

add_filter( 'get_the_archive_title_prefix', 'ucsctravel_filter_category_archive_title_prefix' );

/**
 * Replace the "Category:" prefix on category archive titles
 *
 * Core's get_the_archive_title() ignores the taxonomy labels and hardcodes the
 * "Category:" prefix, so the label filter above does not reach it. This
 * filter covers that prefix in most themes and in the Query Title block.
 *
 * @since 0.1.0
 * @param string $prefix Archive title prefix.
 * @return string Filtered prefix.
 */
function ucsctravel_filter_category_archive_title_prefix( $prefix ) {
	if ( ! is_category() ) {
		return $prefix;
	}
	return _x( 'Expedition:', 'category archive title prefix', 'ucsctravel' );
}
