<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function categories_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Mostras', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Mostras', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Mostras', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Mostras', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar nova mostra', 'textdomain' ),
    'new_item'              => __( 'Nova mostra', 'textdomain' ),
    'edit_item'             => __( 'Editar mostra', 'textdomain' ),
    'view_item'             => __( 'Ver mostra', 'textdomain' ),
    'all_items'             => __( 'Todas as mostras', 'textdomain' )
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
    'supports'           => array( 'title'),
  );

  register_post_type( 'categories', $args );
}
add_action( 'init', 'categories_custom_post_type_init' );
