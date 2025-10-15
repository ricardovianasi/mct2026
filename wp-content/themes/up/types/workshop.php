<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function workshop_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Oficina', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Oficina', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Oficinas', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Oficinas', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar nova oficina', 'textdomain' ),
    'new_item'              => __( 'Nova oficina', 'textdomain' ),
    'edit_item'             => __( 'Editar oficina', 'textdomain' ),
    'view_item'             => __( 'Ver oficinas', 'textdomain' ),
    'all_items'             => __( 'Todas as oficinas', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'oficina' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
    'taxonomies'         => array( 'cat_thematic' ),
  );

  register_post_type( 'workshop', $args );
}
add_action( 'init', 'workshop_custom_post_type_init' );
