<?php

function exposure_home_custom_post_type_init() {
	$labels = array(
		'name'                  => _x( 'Exposição - Home', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Exposição - Home', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Exposição - Home', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Exposição - Home', 'Add New on Toolbar', 'textdomain' ),
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

	register_post_type( 'exposure_home', $args );
}
add_action( 'init', 'exposure_home_custom_post_type_init' );
