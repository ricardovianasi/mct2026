<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function movie_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Filme', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Filme', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Filmes', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Filmes', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar novo filme', 'textdomain' ),
    'new_item'              => __( 'Novo filme', 'textdomain' ),
    'edit_item'             => __( 'Editar filme', 'textdomain' ),
    'view_item'             => __( 'Ver filmes', 'textdomain' ),
    'all_items'             => __( 'Todas os filmes', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'filme' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
    'taxonomies'         => array('cat_highlight', 'cat_thematic', 'cat_mostra'),
  );

  register_post_type( 'movie', $args );
}
add_action( 'init', 'movie_custom_post_type_init' );
