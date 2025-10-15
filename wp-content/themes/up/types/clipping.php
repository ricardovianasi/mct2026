<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function clipping_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Clipping', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Clipping', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Clipping', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Clipping', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar novo clipping', 'textdomain' ),
    'new_item'              => __( 'Novo clipping', 'textdomain' ),
    'edit_item'             => __( 'Editar clipping', 'textdomain' ),
    'view_item'             => __( 'Ver clipping', 'textdomain' ),
    'all_items'             => __( 'Todos os clipping', 'textdomain' ),
    'search_items'          => __( 'Procurar Clipping', 'textdomain' ),
    'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
    'not_found'             => __( 'Nenhumo clipping encontrada.', 'textdomain' ),
    'not_found_in_trash'    => __( 'Nenhumo clipping encontrada no lixo.', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'clip' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor'),
  );

  register_post_type( 'clipping', $args );
}
add_action( 'init', 'clipping_custom_post_type_init' );