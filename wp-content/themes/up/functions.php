<?php

require_once __DIR__ . '/utils/variables.php';

if( !function_exists('template_setup')):
  function template_setup() {

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support( 'align-wide' );
    add_theme_support( 'disable-custom-colors' );

    add_theme_support(
      'editor-color-palette',
      [
        [
          'name' => esc_html__('Laranja', 'up'),
          'slug' => 'laranja',
          'color' => '#DC481A',
        ],
        [
          'name' => esc_html__('Amarelo', 'up'),
          'slug' => 'amarelo',
          'color' => '#EAC304',
        ],
        [
          'name' => esc_html__('Azul', 'up'),
          'slug' => 'amarelo',
          'color' => '#3F7BB7',
        ],
      ]
    );

    // This theme uses wp_nav_menu() in main menu
    register_nav_menus(
      array(
        'main-menu' => __( 'Menu Principal' ),
        'language-menu' => __( 'Seletor de idiomas' ),
      )
    );

    // Banner
    add_image_size( 'banner_desktop_size', 1244, 700, true);
    add_image_size( 'banner_mobile_size', 624, 368, true);
	  
	  add_image_size( 'intro_gallery', 800, 600, false);

    // Thematic
    add_image_size( 'thematic', 880, 1188, true);
    add_image_size( 'honor_1', 720, 720, true);
    add_image_size( 'honor_2', 1172, 716, true);

    // News
    add_image_size( 'news', 464, 320, true);
    add_image_size( 'tv', 592, 333, true);

    // Gallery
    add_image_size( 'gallery', 600, 600, false);

    // Debate
    add_image_size( 'debate', 416, 624, true);
    add_image_size( 'debate_grid', 230, 230, true);
    add_image_size( 'debate_list', 1172, 548, true);

    // Block
    add_image_size( 'block_workshop', 464, 464, true);

    // Masterclass
    add_image_size( 'masterclass', 464, 550, true);

    // Movie
    add_image_size( 'movie_poster', 280, 432, true);
    add_image_size( 'movie_dir', 176, 176, true);

    // Banner Hero
    add_image_size( 'hero_banner', 1440, 500, true);
    
    add_image_size( 'prog', 360, 360, true);

    // Timeline
    add_image_size( 'timeline', 592, 400, true);

    // Art
    add_image_size( 'art', 592, 371, true);

    add_image_size( 'expo', 592, 354, true);

    add_image_size( 'mostra', 366, 151, true);
  }
endif;
add_action( 'after_setup_theme', 'template_setup' );

/**
 * Removes Top Level Menu
 */
function prefix_remove_comments_tl() {
  remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'prefix_remove_comments_tl' );

/**
 * Filter the excerpt length to 50 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function theme_slug_excerpt_length( $length ) {
  if ( is_admin() ) {
    return $length;
  }
  return 30;
}
add_filter( 'excerpt_length', 'theme_slug_excerpt_length', 999 );

function excerpt($limit) {
  return wp_trim_words(get_the_excerpt(), $limit);
}

function allow_nbsp_in_tinymce( $mceInit ) {
  $mceInit['entities'] = '160,nbsp,38,amp,60,lt,62,gt';
  $mceInit['entity_encoding'] = 'named';
  return $mceInit;
}
add_filter( 'tiny_mce_before_init', 'allow_nbsp_in_tinymce' );

function get_custom_excerpt($content, $length = 20) {
  // Verifica se o conteúdo está vazio
  if (empty($content)) {
    return '';
  }

  // Remove tags HTML e shortcodes
  $content = strip_tags(strip_shortcodes($content));

  // Divide o conteúdo em palavras
  $words = explode(' ', $content);

  // Limita o número de palavras ao valor definido
  if (count($words) > $length) {
    $words = array_slice($words, 0, $length);
    $content = implode(' ', $words) . '...'; // Adiciona reticências
  }

  return $content;
}

function thumborize($image_url, $width, $height) {
  //$thumborURL = 'http://162.243.253.29:8888/unsafe/';
  $thumborURL = 'https://thumbor.universoproducao.com.br/unsafe/';

  return $thumborURL
    ."{$width}x{$height}"
    ."/$image_url";
}

/**
 * Mostra
 */
function cat_mostra_taxonomy() {
  register_taxonomy(
    'cat_mostra',
    '',
    array(
      'label' => __( 'Mostra' ),
      'hierarchical' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    )
  );
}
add_action( 'init', 'cat_mostra_taxonomy' );

/**
 * Thematic
 */
function cat_thematic_taxonomy() {
  register_taxonomy(
    'cat_thematic',
    '',
    array(
      'label' => __( 'Temática' ),
      'hierarchical' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    )
  );
}
add_action( 'init', 'cat_thematic_taxonomy' );

/**
 * Local
 */
function cat_place_taxonomy() {
  register_taxonomy(
    'cat_place',
    '',
    array(
      'label' => __( 'Local' ),
      'hierarchical' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    )
  );
}
add_action( 'init', 'cat_place_taxonomy' );

/**
 * Destaque
 */
function cat_highlights_taxonomy() {
  register_taxonomy(
    'cat_highlight',
    '',
    array(
      'label' => __( 'Destaque' ),
      'hierarchical' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    )
  );
}
add_action( 'init', 'cat_highlights_taxonomy' );

function cat_activities_taxonomy() {
  register_taxonomy(
    'cat_activities',
    '',
    array(
      'label' => __('Tipo de atividade'),
      'hierarchical' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
    )
  );
}
add_action( 'init', 'cat_activities_taxonomy' );

function get_terms_by_post_type( $taxonomies, $post_types ) {

  global $wpdb;

  $query = $wpdb->prepare(
    "SELECT t.*, COUNT(*) from $wpdb->terms AS t
        INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
        INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
        WHERE p.post_type IN('%s') AND tt.taxonomy IN('%s')
        GROUP BY t.term_id
        ORDER BY  t.name ASC",

    join( "', '", $post_types ),
    join( "', '", $taxonomies )
  );

  $results = $wpdb->get_results( $query );

  return $results;

}

/**
 * Utils
 */
require_once __DIR__ . '/utils/custom-walker-nav-menu-class.php';
require_once __DIR__ . '/utils/post-date.php';
require_once __DIR__ . '/utils/acf.php';
require_once __DIR__ . '/utils/tags.php';

/**
 * Custom post types
 */
require __DIR__ . '/types/banner.php';
require __DIR__ . '/types/activities.php';
require __DIR__ . '/types/tv.php';
require __DIR__ . '/types/live.php';
require __DIR__ . '/types/news.php';
//require __DIR__ . '/types/thematic.php';
//require __DIR__ . '/types/exposure.php';
//require __DIR__ . '/types/exposure-home.php';
//require __DIR__ . '/types/gallery.php';
//require __DIR__ . '/types/masterclass.php';
//require __DIR__ . '/types/workshop.php';
require __DIR__ . '/types/movie.php';
//require __DIR__ . '/types/art.php';
//require __DIR__ . '/types/debate.php';
require __DIR__ . '/types/prog.php';
//require __DIR__ . '/types/categories.php';
require __DIR__ . '/types/editions.php';
require __DIR__ . '/types/clipping.php';
require __DIR__ . '/types/timeline.php';
require __DIR__ . '/types/ficha.php';

/**
 *  Components
 */
require __DIR__ . '/components/news.php';
require __DIR__ . '/components/formation.php';
require __DIR__ . '/components/block.php';
require __DIR__ . '/components/movie.php';
require __DIR__ . '/components/up-player.php';
