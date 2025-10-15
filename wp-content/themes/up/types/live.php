<?php

function live_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'Ao vivo', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'Ao vivo', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'Ao vivo', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'Adicionar alerta', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo alerta', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar alerta', 'textdomain' ),
    'new_item'              => __( 'Novo alerta', 'textdomain' ),
    'edit_item'             => __( 'Editar alerta', 'textdomain' ),
    'view_item'             => __( 'Ver alerta', 'textdomain' ),
    'all_items'             => __( 'Todos os alertas', 'textdomain' ),
    'search_items'          => __( 'Procurar alerta', 'textdomain' ),
    'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
    'not_found'             => __( 'Nenhum alerta encontrado.', 'textdomain' ),
    'not_found_in_trash'    => __( 'Nenhum alerta encontrado no lixo.', 'textdomain' )
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
    'supports'           => array( 'title' ),
  );

  register_post_type( 'live', $args );
}
add_action( 'init', 'live_custom_post_type_init' );