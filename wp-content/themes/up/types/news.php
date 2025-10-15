<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */

const POST_TYPE_NEWS = 'news';

function news_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Notícias', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Notícia', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Notícias', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Notícia', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar nova notícia', 'textdomain' ),
    'new_item'              => __( 'Nova notícia', 'textdomain' ),
    'edit_item'             => __( 'Editar notícia', 'textdomain' ),
    'view_item'             => __( 'Ver notícia', 'textdomain' ),
    'all_items'             => __( 'Todas as notícias', 'textdomain' ),
    'search_items'          => __( 'Procurar Notícias', 'textdomain' ),
    'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
    'not_found'             => __( 'Nenhuma notícia encontrada.', 'textdomain' ),
    'not_found_in_trash'    => __( 'Nenhuma notícia encontrada no lixo.', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'n' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'show_in_rest'       => true,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( POST_TYPE_NEWS, $args );
}
add_action( 'init', 'news_custom_post_type_init' );
