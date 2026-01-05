<?php
$home_art = get_field('up_home_art', 'option');
if (!empty($home_art['up_home_art_enabled'])) {
  $home_art_title = !empty($home_art['up_home_art_title']) ?
    $home_art['up_home_art_title']
    : __('Arte', 'up');

  $home_art_link = $home_art['up_home_art_link'];

  $home_art_link_url = !empty($home_art_link['up_home_art_link'])
    ? $home_art_link['up_home_art_link']
    : false;
  $home_art_link_label = !empty($home_art_link['up_home_art_link_label'])
    ? $home_art_link['up_home_art_link_label']
    : __('Ver Mais', 'up');

  $home_art_items = $home_art['up_home_art_activities'];
  $orderly_art_items = [];
  foreach ($home_art_items as $item) {
    $presentation_start_date_time = get_field('presentation_start_date_time', $item);
    $start_date_time = DateTime::createFromFormat('d/m/Y H:i:s', $presentation_start_date_time);
    if ($start_date_time) {
      $orderly_art_items[$start_date_time->getTimestamp() . $item->ID] = $item;
    } else {
      $orderly_art_items[$item->ID] = $item;
    }
  }
  ksort($orderly_art_items);

  ?>
  <div class="workshop">
    <div class="art-wrapper">
      <div class="container flex-col">
        <h2 class="home-title uppercase"><?php echo $home_art_title ?></h2>
        <div class="art-items">
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php foreach ($orderly_art_items as $item):
                $item_image_list = get_field('presentation_image', $item);
                $item_image_home = get_field('presentation_image_home', $item);
                $item_image = $item_image_home ?? $item_image_list;

                $item_date = get_field('presentation_date', $item);
                $item_hour = get_field('presentation_hour', $item);
                $item_subtitle = get_field('presentation_subtitle', $item);
                $item_subtitle = str_replace('<p>', '', $item_subtitle);
                $item_subtitle = str_replace('</p>', '<br />', $item_subtitle);
                $item_sub = get_field('presentation_subscription', $item);
                $item_tag = get_field('presentation_tag', $item);
                $item_obs = get_field('presentation_obs', $item);

                $item_place = '';
                $cat_place_terms = get_the_terms($item->ID, 'cat_place');
                $cat_place_terms = is_array($cat_place_terms) ? $cat_place_terms[0] : $cat_place_terms;
                if ($cat_place_terms) {
                  $item_place = $cat_place_terms->name;
                }

                $item_workload = get_field('presentation_workload', $item);
                $item_vacancies = get_field('presentation_vacancies', $item);
                $item_age_range = get_field('presentation_age_range', $item);

                $vacations_age_range = [];
                if ($item_vacancies) {
                  $vacations_age_range[] = $item_vacancies . ' vagas';
                }

                if ($item_age_range) {
                  $vacations_age_range[] = 'faixa etária: ' . $item_age_range;
                }

                $activity_terms = get_the_terms($item, 'cat_activities');
                $playerObj = new UP_Player($item); ?>
                <div class="art-item swiper-slide">
                  <div class="left">
                    <figure>
                      <?php echo wp_get_attachment_image($item_image['ID'], 'art') ?>
                    </figure>
                  </div>
                  <div class="right">
                    <div class="art-desc">
                    <h3 class="title uppercase">
                      <?php echo get_the_title($item) ?>
                    </h3>
                    <?php if ($item_subtitle): ?>
                      <span class="subtitle uppercase"><?php echo $item_subtitle ?></span>
                    <?php endif; ?>
                    <a class="btn-link" href="<?php echo get_the_permalink($item) ?>"><?php echo __('Saiba Mais')?></a>
                  </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="art-controls slider-controls red">
          <div class="slider-navigation white flex gap-4">
            <button class="slider-button-prev">
              <i class="icon-arrow-left-2"></i>
            </button>
            <button class="slider-button-next">
              <i class="icon-arrow-right-2"></i>
            </button>
          </div>
          <?php if ($home_art_link_url): ?>
            <a class="btn-red"
               href="<?php echo $home_art_link_url ?>"><?php echo $home_art_link_label ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
