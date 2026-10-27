<?php
/**
 * Settings page functions
 *
 * This file adds an information page for the plugin under the WordPress
 * Settings menu, and a Settings link to the plugin row on the Plugins screen.
 *
 * @since 0.2.0
 * @package ucsc-travel-functionality
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'ucsctravel_add_settings_page' );

/**
 * Add the plugin page under the Settings menu
 *
 * @since 0.2.0
 * @return void
 */
function ucsctravel_add_settings_page() {
	add_options_page(
		__( 'UCSC Travel Functionality', 'ucsctravel' ),
		__( 'UCSC Travel Functionality', 'ucsctravel' ),
		'manage_options',
		'ucsc-travel-functionality-settings',
		'ucsctravel_render_settings_page'
	);
}

/**
 * Render the plugin settings page
 *
 * The page gives information only. It lists what the plugin changes and links
 * to the release notes. The plugin has no options, so the page registers no
 * settings and shows no form.
 *
 * @since 0.2.0
 * @link https://developer.wordpress.org/plugins/settings/custom-settings-page/
 * @return void
 */
function ucsctravel_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// With markup on, core appends "By <author>" to the description.
	$plugin_data = get_plugin_data( UCSCTRAVEL_PLUGIN_DIR . 'plugin.php', false );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( $plugin_data['Name'] ); ?></h1>
		<p>
			<?php
			/* translators: %s: Plugin version number. */
			echo esc_html( sprintf( __( 'Version %s', 'ucsctravel' ), $plugin_data['Version'] ) );
			?>
			<a href="<?php echo esc_url( 'https://github.com/ucsc/ucsc-travel-functionality/releases' ); ?>"><?php esc_html_e( '(release notes)', 'ucsctravel' ); ?></a>
		</p>
		<p><?php echo esc_html( $plugin_data['Description'] ); ?></p>
		<hr>
		<h2><?php esc_html_e( 'What this plugin changes', 'ucsctravel' ); ?></h2>
		<ul class="ul-disc">
			<li><?php esc_html_e( 'The admin, the block editor, and the Site Editor show "Expedition" and "Expeditions" in place of "Category" and "Categories".', 'ucsctravel' ); ?></li>
			<li><?php esc_html_e( 'Category archive headings show "Expedition:" in place of "Category:".', 'ucsctravel' ); ?></li>
		</ul>
		<h2><?php esc_html_e( 'What stays the same', 'ucsctravel' ); ?></h2>
		<ul class="ul-disc">
			<li><?php esc_html_e( 'The taxonomy name stays "category". Existing posts, terms, and code keep working.', 'ucsctravel' ); ?></li>
			<li>
				<?php esc_html_e( 'Archive URLs stay at /category/. To change that base, set the Category base on the Permalinks screen.', 'ucsctravel' ); ?>
				<a href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>"><?php esc_html_e( 'Go to Permalinks', 'ucsctravel' ); ?></a>
			</li>
			<li><?php esc_html_e( 'Some help text on the Expeditions screen still says "Categories", because WordPress writes that text directly into the screen.', 'ucsctravel' ); ?></li>
		</ul>
	</div>
	<?php
}

add_filter( 'plugin_action_links_' . UCSCTRAVEL_PLUGIN_BASE, 'ucsctravel_settings_link' );

/**
 * Add a Settings link to the plugin row on the Plugins screen
 *
 * The link uses menu_page_url(), which returns the URL under the parent menu
 * that registered the page. That function returns an empty string when the page is not registered, for
 * example for a user without manage_options. The link then falls back to the
 * known Settings URL.
 *
 * @since 0.2.0
 * @param string[] $links Plugin action links.
 * @return string[] Action links with the Settings link added.
 */
function ucsctravel_settings_link( $links ) {
	$url = menu_page_url( 'ucsc-travel-functionality-settings', false );

	if ( empty( $url ) ) {
		$url = admin_url( 'options-general.php?page=ucsc-travel-functionality-settings' );
	}

	$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'ucsctravel' ) . '</a>';

	return $links;
}
