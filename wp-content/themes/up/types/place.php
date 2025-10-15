<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function place_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Local', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Local', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Local', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Local', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar novo local', 'textdomain' ),
    'new_item'              => __( 'Novo local', 'textdomain' ),
    'edit_item'             => __( 'Editar item', 'textdomain' ),
    'view_item'             => __( 'Ver Local', 'textdomain' ),
    'all_items'             => __( 'Todas os locais', 'textdomain' )
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
  );

  register_post_type( 'place', $args );
}
add_action( 'init', 'place_custom_post_type_init' );
