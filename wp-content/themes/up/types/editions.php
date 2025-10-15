<?php
/**
 * Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function edition_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Edições', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Edição', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Edições anteriores', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Edições', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar nova edição', 'textdomain' ),
    'new_item'              => __( 'Nova edição', 'textdomain' ),
    'edit_item'             => __( 'Editar edição', 'textdomain' ),
    'view_item'             => __( 'Ver edição', 'textdomain' ),
    'all_items'             => __( 'Todas as ediçãoes', 'textdomain' ),
    'search_items'          => __( 'Procurar edições', 'textdomain' ),
    'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
    'not_found'             => __( 'Nenhuma edição encontrada.', 'textdomain' ),
    'not_found_in_trash'    => __( 'Nenhuma edição encontrada no lixo.', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'edicao' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( 'edition', $args );
}
add_action( 'init', 'edition_custom_post_type_init' );
