<?php

/**
 * News Shortcode
 */

class News_Shortcode {
  private static $instance;

  public static function getInstance() {
    if(self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public static function shortcode($atts = [], $content = "") {
    $config = shortcode_atts([
      'id' => '',
      'offset' => '',
      'posts_per_page' => '',
      'showposts' => -1,
      'suppress_filters' => 0,
      'exclude' => ''
    ], $atts);

    $news = !empty($config['id']) ? get_post($config['id']) : null;

    if(!$news) {
      $news_args = [
        'post_type' => POST_TYPE_NEWS,
        'suppress_filters' => 0,
        'posts_per_page' => -1,
      ];

      if(!empty($config['exclude'])) {
        $news_args['exclude'] = explode('', $config['exclude']);
      }

      $news = get_posts($news_args);
    }

    if(!$news) {
      return '';
    }

    return self::getInstance()->renderItems($news);
  }

  public function renderItems($items) {
    if(!is_array($items)) {
      return self::getInstance()->render($items);
    }

    ob_start(); ?>
    <div class="news-wrapper">
      <?php foreach ($items as $item) {
        echo self::getInstance()->render($item);
      }?>
    </div>
    <?php return ob_get_clean( );
  }

  public function render($news, $home = false) {
    $news_thumbnail = get_field('news_thumbnail', $news);
    $news_read_time = get_field('news_read_time', $news);
    $post_date = PostDate::format($news, PostDate::MEDIUM);

    ob_start(); ?>
      <div class="news-item">
        <a href="<?php echo get_the_permalink($news) ?>">
          <figure>
            <?php echo wp_get_attachment_image($news_thumbnail['ID'], 'news') ?>
            <span class="btn-short-cta"><i class="icon-arrow-right"></i></span>
          </figure>
          <span class="content">
            <span class="title"><?php echo get_the_title($news) ?></span>
            <span class="heading flex">
              <span><?php echo $post_date ?></span>
              <?php if (!$home): ?>
                <span class="btn-link red"><?php echo __('Saiba Mais', 'up')?> <i class="icon-arrow-right-2"></i></span>
              <?php endif; ?>
            </span>

          </span>
        </a>
      </div>
    <?php return ob_get_clean();
  }
}

add_shortcode('news', [News_Shortcode::class, 'shortcode']);
