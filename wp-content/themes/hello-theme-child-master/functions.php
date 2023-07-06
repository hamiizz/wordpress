<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);
    wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/custom.js', array(), '', true );

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

//------ Function to redirect users to 404 page if their ip starts with 77.29 ------//
function redirect_user_by_ip() {
	
	if(!is_page(5)){
		if (isset($_SERVER['REMOTE_ADDR']) && strpos($_SERVER['REMOTE_ADDR'], '77.29.') === 0) {
			$url = get_option( 'siteurl' );
			wp_redirect($url."'/404-2/'");
			exit;
		}
	}
		
}
add_action('template_redirect', 'redirect_user_by_ip');

//------ Register Projects custom post type ------//
function register_custom_post_type() {
    $labels = array(
        'name' => 'Projects',
        'singular_name' => 'Project',
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('projects', $args);
}
add_action('init', 'register_custom_post_type');

//------ Register custom taxonomy Project types ------//
function register_custom_taxonomy() {
    $labels = array(
        'name' => 'Project Types',
        'singular_name' => 'Project Type',
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
		'show_admin_column' => true,
    );
    register_taxonomy('project_type', 'projects', $args);
}
add_action('init', 'register_custom_taxonomy');

//------ Ajax end point to show 3 projects when lot logged in and 6 when logged in ------//
function get_projects_ajax() {
    $is_logged_in = is_user_logged_in();
    $number_of_projects = $is_logged_in ? 6 : 3;

    $args = array(
        'post_type' => 'projects',
        'posts_per_page' => $number_of_projects,
        'tax_query' => array(
            array(
                'taxonomy' => 'project_type',
                'field' => 'slug',
                'terms' => 'architecture',
            ),
        ),
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);

    $data = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $project_object = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_permalink(),
            );

            $data[] = $project_object;
        }
    }
    wp_reset_postdata();

    $response = array(
        'success' => true,
        'data' => $data,
    );
    wp_send_json($response);
}
add_action('wp_ajax_nopriv_get_projects', 'get_projects_ajax');
add_action('wp_ajax_get_projects', 'get_projects_ajax');

//------ Random coffee api to return a link to coffee ------//
function hs_give_me_coffee() {
    $api_url = 'https://coffee.alexflipnote.dev/random.json';
    $response = wp_remote_get($api_url);

    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['file'])) {
            return $data['file'];
        }
    }

    return 'no response';
}