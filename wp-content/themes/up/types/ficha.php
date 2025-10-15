<?php
/**
 * Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */

const POST_TYPE_FICHA = 'ficha';

function ficha_custom_post_type_init()
{
  $labels = array(
    'name' => _x('Ficha Técnica', 'Post type general name', 'textdomain'),
    'singular_name' => _x('Ficha Técnica', 'Post type singular name', 'textdomain'),
    'menu_name' => _x('Ficha Técnica', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Ficha Técnica', 'Add New on Toolbar', 'textdomain'),
    'add_new' => __('Novo', 'textdomain'),
    'add_new_item' => __('Adicionar novo ficha técnica', 'textdomain'),
    'new_item' => __('Novo ficha técnica', 'textdomain'),
    'edit_item' => __('Editar ficha técnica', 'textdomain'),
    'view_item' => __('Ver ficha técnica', 'textdomain'),
    'all_items' => __('Todos as ficha técnica', 'textdomain'),
    'search_items' => __('Procurar Ficha Técnica', 'textdomain'),
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

  register_post_type(POST_TYPE_FICHA, $args);
}

add_action('init', 'ficha_custom_post_type_init');
