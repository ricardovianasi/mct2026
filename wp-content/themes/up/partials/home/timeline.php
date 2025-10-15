<?php
$up_home_timeline = get_field('up_home_timeline', 'option');
if (!empty($up_home_timeline['up_home_timeline_enabled'])) {
  $up_home_timeline_title = $up_home_timeline['up_home_timeline_title'] ?? __('Por dentro da mostra', 'up');

  $up_home_timeline_link = $up_home_timeline['up_home_timeline_link'] ?? '';

  $up_home_timeline_items = get_posts([
    'post_type' => 'timeline',
    'showposts' => 6,
  ]);

  $timeline_thumbs = '';

  ?>

  <div class="timeline">
    <div class="container container-medium flex-col">
      <h2 class="home-title"><?php echo $up_home_timeline_title ?></h2>
      <div class="timeline-items">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php foreach ($up_home_timeline_items as $timeline):
              $timeline_slogan = get_field('timeline_slogan', $timeline);
              $timeline_description = get_field('timeline_description', $timeline);
              $timeline_gallery = get_field('timeline_gallery', $timeline);
              $timeline_gallery_home = count($timeline_gallery) ? array_splice($timeline_gallery, 0, 3) : [];
              $timeline_thumbs .= '<div class="swiper-slide">' . get_the_title($timeline) . '</div>';

              $title = "<div><h3><span class='date'>" . get_the_title($timeline) . "</span><span class='slogan uppercase'>$timeline_slogan</span></h3></div>";
              $content = [];
              $cont = 1;
              foreach ($timeline_gallery_home as $img) {
                $content[] = '<figure>' . wp_get_attachment_image($img['ID'], 'expo') . '</figure>';
                if ($cont === 1) {
                  $content[] = $title;
                }
                $cont++;
              }
              ?>
              <div class="timeline-item swiper-slide">
                <?php echo implode('', $content); ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="container container-1216 flex-col">
      <div class="timeline-years">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php echo $timeline_thumbs; ?>
          </div>
        </div>
      </div>
      <div class="slider-controls">
        <div class="slider-navigation">
          <button class="slider-button-prev">
            <i class="icon-arrow-left-2"></i>
          </button>
          <button class="slider-button-next">
            <i class="icon-arrow-right-2"></i>
          </button>
        </div>
        <div class="slider-pagination"></div>
        <?php if ($up_home_timeline_link): ?>
          <a class="btn-red with-decorator black" href="<?php echo $up_home_timeline_link ?>">
            <?php echo __('Ver exposição completa', 'up') ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php
}

