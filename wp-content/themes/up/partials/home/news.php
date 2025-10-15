<?php
$up_home_news  = get_field('up_home_news', 'option');
if($up_home_news && !empty($up_home_news['up_home_news_enabled'])) {
	$homeNewsItems = get_posts([
		'post_type' => 'news',
		'numberposts' => 3,
		'suppress_filters' => 0,
		'meta_query' => [
			[
				'key' => 'news_highlight',
				'value' => '1'
			]
		]
	]);
}

$newsShortcode = News_Shortcode::getInstance();

if($homeNewsItems): ?>
  <div class="news">
    <h2 class="home-title">
	    <?php echo !empty($up_home_news['up_home_news_title']) ? $up_home_news['up_home_news_title'] : __('Notícias') ?>
    </h2>
    <div class="news-wrapper">
	    <?php foreach ($homeNewsItems as $news) {
		    echo $newsShortcode->render($news);
	    } ?>
    </div>
    <div class="read-more">
	  <?php if($up_home_news['up_home_news_link']): ?>
      <a class="btn-red with-decorator black" href="<?php echo $up_home_news['up_home_news_link'] ?>">
        <?php echo !empty($up_home_news['up_home_news_link_text'])
              ? $up_home_news['up_home_news_link_text']
              : __('Veja todas as notícia') ?></a>
    <?php endif; ?>
    </div>
  </div>
<?php endif;
