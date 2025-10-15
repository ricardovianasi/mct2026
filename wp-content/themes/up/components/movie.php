<?php

class Movie
{
  private static $instance;
  private static $block = 'movie';

  private $movies;
  private $mostra;
  private $tematica;
  private $destaque;
  private $type;
  private $limit;
  private $order;
  private $title;
  private $link;
  private $bgc;
  private $filter;
  private $autoplay;
  private $slidesPerView = 4;

  public function __construct()
  {
    if (self::$instance === null) {
      self::$instance = $this;
    }
  }

  public static function newInstance()
  {
    self::$instance = new self();
    return self::$instance;
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public static function register() {
    if(function_exists('acf_register_block_type')) {
      acf_register_block_type(array(
        'name'              => self::$block,
        'title'             => __('Filme'),
        'description'       => __('Filme'),
        'render_callback'   => [self::class, 'callback'],
        'category'          => 'up',
        'icon'              => 'admin-appearance'
      ));
    }
  }

  /**
   * CTA Block Callback Function.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */
  public static function callback($block, $content = '', $is_preview = false, $post_id = 0) {
    $instance = self::getInstance();

    $block_movie_items = get_field('block_movie_items');
    $instance->setMovies($block_movie_items);

    $block_movie_categories = get_field('block_movie_categories');
    if(!empty($block_movie_categories['mostra'])) {
      $instance->setMostra($block_movie_categories['mostra']);
    }

    if(!empty($block_movie_categories['tematica'])) {
      $instance->setTematica($block_movie_categories['tematica']);
    }

    if(!empty($block_movie_categories['destaque'])) {
      $instance->setDestaque($block_movie_categories['destaque']);
    }

    $block_movie_type = get_field('block_movie_type');
    $instance->setType($block_movie_type ?? 'list');

    $block_movie_limit = get_field('block_movie_limit');
    $instance->setLimit($block_movie_limit ?? '10');

    $block_movie_order = get_field('block_movie_order');
    $instance->setOrder($block_movie_order ?? 'title');

    $block_movie_title = get_field('block_movie_title');
    $instance->setTitle($block_movie_title);

    $block_movie_link = get_field('block_movie_link');
    $instance->setLink($block_movie_link);

    $block_movie_bgc = get_field('block_movie_bgc');
    $instance->setBgc($block_movie_bgc);

    $block_movie_filter = get_field('block_movie_filter');
    $instance->setFilter($block_movie_filter);

    $block_movie_autoplay = get_field('block_movie_autoplay');
    $instance->setAutoplay($block_movie_autoplay);

    // Create id attribute allowing for custom "anchor" value.
    $id = self::$block . '-' . $block['id'];
    if( !empty($block['anchor']) ) {
      $id = $block['anchor'];
    }

    // Create class attribute allowing for custom "className" and "align" values.
    $className = 'movie-block';
    if( !empty($block['className']) ) {
      $className .= ' ' . $block['className'];
    }

    if($is_preview) {
      $className .= ' block-preview';
    }
    ?>

    <div id="<?php echo $id ?>" class="<?php echo $className ?>">
      <?php if($is_preview): ?>
        <label for="<?php echo $id ?>" class="components-placeholder__label">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" role="img" aria-hidden="true" focusable="false"><path d="M16 4.2v1.5h2.5v12.5H16v1.5h4V4.2h-4zM4.2 19.8h4v-1.5H5.8V5.8h2.5V4.2h-4l-.1 15.6zm5.1-3.1l1.4.6 4-10-1.4-.6-4 10z"></path></svg>
          Bloco - Filmes
        </label>
      <?php else: ?>
        <?php echo self::getInstance()->renderBlock($block['id']); ?>
      <?php endif;?>
    </div>
    <?php
  }

  public static function getMovies() {
    if(self::getInstance()->movies) {
      return self::getInstance()->movies;
    }

    $query_args = [
      'post_type' => 'movie',
      'suppress_filters' => 0,
      'posts_per_page' => -1,
      'meta_query' => array(
        'relation' => 'AND',
        array (
          'relation' => 'OR',
          array(
            'key'   => 'senha_de_protecao',
            'value' => '',
            'compare' => '='
          ),
          array(
            'key'   => 'senha_de_protecao',
            'compare' => 'NOT EXISTS',
          ),
        ),
      )
    ];

    $taxQueries  =[];
    if(self::getInstance()->getTematica()) {
      $taxQueries[] = [
        'taxonomy' => 'cat_thematic',
        'field'    => 'term_id',
        'terms'    => self::getInstance()->getTematica()
      ];
    }

    if(self::getInstance()->getDestaque()) {
      $taxQueries[] = [
        'taxonomy' => 'cat_highlight',
        'field'    => 'term_id',
        'terms'    => self::getInstance()->getDestaque()
      ];
    }

    if(self::getInstance()->getMostra()) {
      $taxQueries[] = [
        'taxonomy' => 'cat_mostra',
        'field'    => 'term_id',
        'terms'    => self::getInstance()->getMostra()
      ];
    } elseif( !empty($_GET['tematica']) ) {
      $taxQueries[] = [
        'taxonomy' => 'cat_mostra',
        'field'    => 'slug',
        'terms'    => $_GET['tematica']
      ];
    }

    if($taxQueries) {
      $query_args['tax_query'][] = [
        'relation' => 'AND',
        $taxQueries
      ];
    }

    if(self::getInstance()->getOrder() == 'rand') {
      $query_args['orderby'] = 'rand';
    } elseif(self::getInstance()->getOrder() == 'title') {
      $query_args['order'] = 'ASC';
      $query_args['orderby'] = 'title';
    }

    if( !empty($_GET['categoria']) ) {
      $query_args['meta_query'][] = array(
        'key'   => 'movie_cat',
        'value' => esc_attr( $_GET['categoria'] ),
      );
    }

    if( !empty($_GET['titulo']) ) {
      $query_args['s'] = esc_attr( $_GET['titulo'] );
    }

    $movies = get_posts($query_args);
    self::getInstance()->setMovies($movies);

    return self::getInstance()->movies;
  }

  public static function single($movie) {
    //get ACF fields
    $movie_id             = get_field( 'movie_id', $movie );
    $movie_dir            = get_field( 'movie_dir', $movie );
    $descricao_direcao          = get_field( 'descricao_direcao', $movie );
    $movie_resume         = get_field( 'movie_resume', $movie );
    $movie_img_cover      = get_field( 'movie_img_cover', $movie );
    $hero_banner          = get_field( 'hero_banner', $movie );
    $movie_online         = get_field( 'player_online', $movie );
    $estado_de_producao        = get_field( 'estado_de_producao', $movie );
    $pais        = get_field( 'pais', $movie );
    
    if (!$movie_dir && $descricao_direcao) {
      foreach ($descricao_direcao as $des) {
        $nome = !empty($des['nome']) ? $des['nome'] : null;
        if($nome) {
	        $movie_dir[] = $nome;
        }
      }
      
	    $movie_dir = implode(', ', $movie_dir);
    }
    
    $estadoPais = [];
    if ($estado_de_producao) {
	    $estadoPais[] = $estado_de_producao;
    }
    if ($pais) {
	    $estadoPais[] = $pais;
    }
    

    $cover_img = "";
    if( $movie_img_cover ) {
      $cover_img = wp_get_attachment_image( $movie_img_cover['id'], 'movie_poster', false, ['class' => 'movie-img'] );
    }

    $movie_player_obj = new UP_Player( $movie );

    ob_start(); ?>
    <div class="movie-item <?php echo self::getInstance()->getType() == 'carousel' ? 'swiper-slide' : '' ?> ">
      <a href="<?php echo get_the_permalink($movie) ?>">
        <?php echo $cover_img; ?>

        <?php if($movie_online): ?>
          <span class="notice tag uppercase"><?php echo __('assista online', 'up')?></span>
        <?php endif; ?>
        <span class="movie-content">
          <span class="title"><?php echo get_the_title($movie); ?></span>
          <?php if ($movie_dir): ?>
            <span class="direction on-hover"><?php echo __('Direção', 'up') ?>: <?php echo $movie_dir ?></span>
          <?php endif; ?>
          <?php if ($movie_resume): ?>
            <span class="info on-hover"><?php echo $movie_resume ?></span>
          <?php endif; ?>
          <?php if (count($estadoPais) > 0): ?>
            <span class="country on-hover"><?php echo implode( ' / ', $estadoPais ); ?></span>
          <?php endif; ?>
          <?php if ($movie_player_obj->isPlayerOpen()): ?>
            <span class="read-more btn-link on-hover uppercase">
              <?php echo __('Assista', 'up') ?> <i class="icon-arrow-right"></i></span>
          <?php else: ?>
            <span class="read-more btn-link on-hover uppercase">
              <?php echo __('Saiba mais', 'up') ?> <i class="icon-arrow-right"></i></span>
          <?php endif; ?>
        </span>
      </a>
    </div>
    <?php return ob_get_clean();
  }

  public static function renderBlock($blockId = null) {
    $movies = self::getInstance()->getMovies();
    $isCarousel = self::getInstance()->getType() === 'carousel';
    $isBgc = self::getInstance()->getBgc() ?? false;
    $slidesPerView = 4;

    $classes = 'movie expanded movie-block';
    if($isCarousel == 'carousel') {
      $classes.= ' movie-slider';
      if($isBgc) {
        $classes.= ' movie-bgc';
      }
      if(self::getInstance()->getAutoplay()) {
        $classes.= ' movie-autoplay';
      }
    } else {
      $classes.= ' movie-list';
    }

    $blockId = $blockId ?? uniqid();

    ob_start(); ?>
    <div data-slides-per-view="<?php echo $slidesPerView ?>" id="<?php echo $blockId ?>" class="<?php echo $classes ?>">
      <?php if(!$isCarousel && self::getInstance()->getFilter()) {
        echo self::filter();
      }?>
      <div class="container container-1216 flex-col">
        <div class="">
          <?php if(self::getInstance()->getTitle()): ?>
            <h2 class="movie-header-title home-title mb-6"><?php echo self::getInstance()->getTitle(); ?></h2>
          <?php endif; ?>
          <div class="<?php echo $isCarousel ? 'swiper' : ''; ?> movie-items">
            <div class="<?php echo $isCarousel ? 'swiper-wrapper' : 'movie-items-wrapper'; ?>">
              <?php foreach ($movies as $movie) {
                echo self::single($movie);
              }?>
            </div>
          </div>
          <?php if($isCarousel): ?>
            <div class="section-controls flex flex-col md:flex-row items-center justify-between w-full gap-4">
              <div class="slider-controls dark ">
                <button class="slider-button-prev">
                  <i class="icon-arrow-left"></i>
                </button>
                <div class="slider-pagination"></div>
                <button class="slider-button-next">
                  <i class="icon-arrow-right"></i>
                </button>
              </div>
              <?php if(self::getInstance()->getLink()): ?>
                <div><a class="btn-link" href="<?php echo self::getInstance()->getLink(); ?>">
                    <i class="icon-lines"></i><?php echo __('Ver todos os filmes', 'up') ?> <i class="icon-arrow-right"></i></a></div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php return ob_get_clean();
  }

  public static function filter() {
    $thematics_to_filter = get_terms(array(
      'taxonomy' => 'cat_mostra',
      'orderby' => 'name',
      'hide_empty' => true
    ));
    ob_start(); ?>
    <div class="movie-filter container container-1216">
      <div class="movie-id"></div>
      <div class="movie-filter-wrapper">
        <span><?php echo __('Filtrar filmes por', 'up'); ?>:</span>
        <form action="" method="get">
          <div class="row">
            <div class="col">
              <input name="titulo" type="text" placeholder="Título">
            </div>
            <div class="col">
              <select name="tematica">
                <option value="">Mostra</option>
                <?php foreach ($thematics_to_filter as $terms) {
                  $selected = "";
                  if(isset($_GET['tematica']) && $_GET['tematica'] == $terms->slug) {
                    $selected = 'selected';
                  }
                  echo '<option '.$selected.' value="'.$terms->slug.'" > '.$terms->name.' </option>';
                } ?>
              </select>
            </div>
            <div class="col">
              <select name="categoria">
                <option value="">Formato</option>
                <option <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'curta') ? 'selected' : '' ?> value="curta">Curta</option>
                <option <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'media') ? 'selected' : '' ?> value="media">Média</option>
                <option <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'longa') ? 'selected' : '' ?> value="longa">Longa</option>
              </select>
            </div>
            <div class="col">
              <button class="btn-red" type="submit"><?php echo __('Buscar', 'up')?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php return ob_get_clean( );
  }

  /**
   * @return string
   */
  public static function getBlock(): string
  {
    return self::$block;
  }

  /**
   * @param string $block
   */
  public static function setBlock(string $block): void
  {
    self::$block = $block;
  }

  /**
   * @param mixed $movies
   */
  public function setMovies($movies): void
  {
    $this->movies = $movies;
  }

  /**
   * @return mixed
   */
  public function getMostra()
  {
    return $this->mostra;
  }

  /**
   * @param mixed $mostra
   */
  public function setMostra($mostra)
  {
    $this->mostra = $mostra;
  }

  /**
   * @return mixed
   */
  public function getTematica()
  {
    return $this->tematica;
  }

  /**
   * @param mixed $tematica
   */
  public function setTematica($tematica)
  {
    $this->tematica = $tematica;
  }

  /**
   * @return mixed
   */
  public function getDestaque()
  {
    return $this->destaque;
  }

  /**
   * @param mixed $destaque
   */
  public function setDestaque($destaque)
  {
    $this->destaque = $destaque;
  }

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * @return mixed
   */
  public function getLimit()
  {
    return $this->limit;
  }

  /**
   * @param mixed $limit
   */
  public function setLimit($limit): void
  {
    $this->limit = $limit;
  }

  /**
   * @return mixed
   */
  public function getOrder()
  {
    return $this->order;
  }

  /**
   * @param mixed $order
   */
  public function setOrder($order): void
  {
    $this->order = $order;
  }

  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title): void
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function getLink()
  {
    return $this->link;
  }

  /**
   * @param mixed $link
   */
  public function setLink($link): void
  {
    $this->link = $link;
  }

  /**
   * @return mixed
   */
  public function getBgc()
  {
    return $this->bgc;
  }

  /**
   * @param mixed $bgc
   */
  public function setBgc($bgc): void
  {
    $this->bgc = $bgc;
  }

  /**
   * @return mixed
   */
  public function getFilter()
  {
    return $this->filter;
  }

  /**
   * @param mixed $filter
   */
  public function setFilter($filter): void
  {
    $this->filter = $filter;
  }

  /**
   * @return mixed
   */
  public function getAutoplay()
  {
    return $this->autoplay;
  }

  /**
   * @param mixed $autoplay
   */
  public function setAutoplay($autoplay): void
  {
    $this->autoplay = $autoplay;
  }

  /**
   * @return int
   */
  public function getSlidesPerView()
  {
    return $this->slidesPerView;
  }

  /**
   * @param int $slidesPerView
   */
  public function setSlidesPerView($slidesPerView) {
    $this->slidesPerView = $slidesPerView;
  }


}

add_action('acf/init', [Movie::class, 'register']);
