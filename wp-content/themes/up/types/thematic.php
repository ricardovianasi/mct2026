<?php
/**
 * Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function thematic_custom_post_type_init()
{
  $labels = array(
    'name' => _x('Temáticas', 'Post type general name', 'textdomain'),
    'singular_name' => _x('Temática', 'Post type singular name', 'textdomain'),
    'menu_name' => _x('Temáticas', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Temáticas', 'Add New on Toolbar', 'textdomain'),
    'add_new' => __('Novo', 'textdomain'),
    'add_new_item' => __('Adicionar nova temática', 'textdomain'),
    'new_item' => __('Novo temática', 'textdomain'),
    'edit_item' => __('Editar temática', 'textdomain'),
    'view_item' => __('Ver temática', 'textdomain'),
    'all_items' => __('Todos as temáticas', 'textdomain'),
    'search_items' => __('Procurar temática', 'textdomain'),
    'not_found' => __('Nenhuma temática encontrada.', 'textdomain'),
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title'),
    'exclude_from_search' => true
  );

  register_post_type('thematic', $args);
}

add_action('init', 'thematic_custom_post_type_init');
