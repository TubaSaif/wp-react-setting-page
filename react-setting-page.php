<?php
/**
 * Plugin Name: React Setting Page
 * Description: A react based setting page.
 * Version: 1.0.0
 * Author: Tuba Saif
 */
function me_add_admin_menu() {

	add_menu_page(
		esc_html__( 'UT', 'react-settings-page' ),
		esc_html__( 'UI Test', 'react-settings-page' ),
		'manage_options',
		'react-settings-page-options',
		'me_display_menu_options'
	);

	global $screen_id_options;
	$screen_id_options = add_submenu_page(
		'react-settings-page-options',
		esc_html__( 'UT - Options', 'react-settings-page' ),
		esc_html__( 'Options', 'react-settings-page' ),
		'manage_options',
		'react-settings-page-options',
		'me_display_menu_options'
	);

}
add_action( 'admin_menu', 'me_add_admin_menu');

function me_display_menu_options() {
	include_once( 'react-options.php' );
}

// function enqueue_admin_scripts(){
// 	global $screen_id_options;
// 	if ( $screen_id_options == $screen_id_options ) {
// 		$plugin_url  = plugin_dir_url( __FILE__ );
// 		wp_enqueue_script('my-react-app',
// 			$plugin_url . '/build/index.js',
// 			array('wp-element', 'wp-api-fetch'),
// 			'1.00',
// 			true);
// 	}
//     // Localize ajaxurl so it's available to your JS file
//     wp_localize_script('my-react-app', 'ajaxurl', admin_url('admin-ajax.php'));
// }
// add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );


function enqueue_admin_scripts($hook_suffix) {
    global $screen_id_options;

    if ($hook_suffix === $screen_id_options) {
        $plugin_url = plugin_dir_url(__FILE__);
        wp_enqueue_script(
            'my-react-app',
            $plugin_url . '/build/index.js',
            ['wp-element', 'wp-api-fetch'],
            '1.0.0',
            true
        );

        wp_localize_script('my-react-app', 'myAppData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

add_action('admin_enqueue_scripts', 'enqueue_admin_styles');
function enqueue_admin_styles() {
    wp_enqueue_style('your-plugin-admin-style', plugin_dir_url(__FILE__) . 'build/style-index.css', [], '1.0.0');
}

//CREATE DATABASE --------------------------------------------------------------
function create_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'form_submissions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_custom_table');

//FORM SUBMISSION AND AJAX ------------------------------------------------------

function handle_form_submission() {
    global $wpdb;

    // Check for required fields
    if (isset($_POST['name']) && isset($_POST['email'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        // Save data to the custom table
        $table_name = $wpdb->prefix . 'form_submissions';
        $result = $wpdb->insert(
            $table_name,
            [
                'name' => $name,
                'email' => $email,
            ],
            ['%s', '%s']
        );

        if ($result) {
            wp_send_json_success('Data saved successfully.');
        } else {
            wp_send_json_error('Failed to save data.');
        }
    } else {
        wp_send_json_error('Missing required fields.');
    }

    wp_die();
}
add_action('wp_ajax_my_form_submission', 'handle_form_submission');
add_action('wp_ajax_nopriv_my_form_submission', 'handle_form_submission');






