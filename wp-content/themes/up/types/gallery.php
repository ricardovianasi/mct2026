<?php
/**
 * Register a custom post type
 *
 * @see get_post_type_labels() for label keys.
 */
function gallery_15_custom_post_type_init() {
	$labels = array(
		'name'                  => _x( 'Fotos', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Foto', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Galeria Flickr', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Fotos', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Nova foto', 'textdomain' ),
		'add_new_item'          => __( 'Adicionar nova foto', 'textdomain' ),
		'new_item'              => __( 'Nova foto', 'textdomain' ),
		'edit_item'             => __( 'Editar foto', 'textdomain' ),
		'view_item'             => __( 'Ver foto', 'textdomain' ),
		'all_items'             => __( 'Todos as fotos', 'textdomain' ),
		'search_items'          => __( 'Procurar fotos', 'textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail'),
	);

	register_post_type( 'gallery', $args );
}
add_action( 'init', 'gallery_15_custom_post_type_init' );

function get_gallery_15()
{
	$args = array(
		'post_type' => 'photos',
		'showposts' => -1
	);

	return get_posts($args);
}