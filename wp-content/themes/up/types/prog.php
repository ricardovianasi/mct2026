<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function prog_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Programação', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Programação', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Programação', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Programação', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar novo item', 'textdomain' ),
    'new_item'              => __( 'Novo item', 'textdomain' ),
    'edit_item'             => __( 'Editar item', 'textdomain' ),
    'view_item'             => __( 'Ver Programação', 'textdomain' ),
    'all_items'             => __( 'Todas os items', 'textdomain' )
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
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
    'taxonomies'         => array('cat_highlight', 'cat_thematic', 'cat_place', 'cat_activities'),
  );

  register_post_type( 'prog', $args );
}
add_action( 'init', 'prog_custom_post_type_init' );
