<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function activities_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Programação de Formação', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Atividade', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Programa de Formação', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Adicionar novo', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar nova atividade', 'textdomain' ),
    'new_item'              => __( 'Nova atividade', 'textdomain' ),
    'edit_item'             => __( 'Editar atividade', 'textdomain' ),
    'view_item'             => __( 'Ver atividade', 'textdomain' ),
    'all_items'             => __( 'Todas as atividades', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'atividades' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
    'show_in_rest'       => true,
    'taxonomies'         => array('cat_activities', 'cat_mostra', 'cat_place'),
  );

  register_post_type( 'activities', $args );
}
add_action( 'init', 'activities_custom_post_type_init' );
