<?php
/**
 * Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */

const POST_TYPE_BANNER = 'banner';

function banner_custom_post_type_init()
{
  $labels = array(
    'name' => _x('Banner', 'Post type general name', 'textdomain'),
    'singular_name' => _x('Banner', 'Post type singular name', 'textdomain'),
    'menu_name' => _x('Banner', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Banner', 'Add New on Toolbar', 'textdomain'),
    'add_new' => __('Novo', 'textdomain'),
    'add_new_item' => __('Adicionar novo banner', 'textdomain'),
    'new_item' => __('Novo banner', 'textdomain'),
    'edit_item' => __('Editar banner', 'textdomain'),
    'view_item' => __('Ver banner', 'textdomain'),
    'all_items' => __('Todos as banner', 'textdomain'),
    'search_items' => __('Procurar Banner', 'textdomain'),
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

  register_post_type(POST_TYPE_BANNER, $args);
}

add_action('init', 'banner_custom_post_type_init');
