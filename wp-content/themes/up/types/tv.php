<?php
/**
* Register a custom post type called "news".
 *
 * @see get_post_type_labels() for label keys.
 */
function tv_custom_post_type_init() {
  $labels = array(
    'name'                  => _x( 'TV Mostra', 'Post type general name', 'textdomain' ),
    'singular_name'         => _x( 'TV Mostra', 'Post type singular name', 'textdomain' ),
    'menu_name'             => _x( 'TV Mostra', 'Admin Menu text', 'textdomain' ),
    'name_admin_bar'        => _x( 'TV', 'Add New on Toolbar', 'textdomain' ),
    'add_new'               => __( 'Novo', 'textdomain' ),
    'add_new_item'          => __( 'Adicionar novo item', 'textdomain' ),
    'new_item'              => __( 'Novo item', 'textdomain' ),
    'edit_item'             => __( 'Editar item', 'textdomain' ),
    'view_item'             => __( 'Ver item', 'textdomain' ),
    'all_items'             => __( 'Todas os itens', 'textdomain' ),
    'search_items'          => __( 'Procurar item', 'textdomain' ),
    'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
    'not_found'             => __( 'Nenhum item encontrado.', 'textdomain' ),
    'not_found_in_trash'    => __( 'Nenhum item encontrado no lixo.', 'textdomain' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'noticia' ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' ),
  );

  register_post_type( 'tv', $args );
}
add_action( 'init', 'tv_custom_post_type_init' );

function render_tv_item($tv) {
  $tv_thumbnail = get_field('tv_thumbnail', $tv);
  $tv_media = get_field('tv_media', $tv);
  ob_start();
  ?>
  <div class="tv-item">
    <a rel="noopener" href="<?php echo $tv_media ?>" target="_blank">
      <figure>
        <?php echo wp_get_attachment_image($tv_thumbnail['id'], 'tv_thumbnail_size' ); ?>
        <span class="icon icon-play2 tv-play"></span>
      </figure>
      <p><?php echo get_the_title($tv) ?></p>
    </a>
  </div>
  <?php return ob_get_clean();
}
