<?php
$home_training = get_field('up_home_training', 'option');
if (!empty($home_training['up_home_training_enabled'])) {
  $home_training_title = !empty($home_training['up_home_training_title']) ?
    $home_training['up_home_training_title']
    : __('Programa de Formação', 'up');


  $home_training_link = $home_training['up_home_training_link'];
  $home_training_link_url = !empty($home_training_link['up_home_training_link_url'])
    ? $home_training_link['up_home_training_link_url']
    : false;
  $home_training_link_label = !empty($home_training_link['up_home_training_link_label'])
    ? $home_training_link['up_home_training_link_label']
    : __('Ver todas as oficinas', 'up');

  $home_training_items = $home_training['up_home_training_activities'];
  $orderly_trainning_items = [];
  foreach ($home_training_items as $item) {
    $presentation_start_date_time = get_field('presentation_start_date_time', $item);
    $start_date_time = DateTime::createFromFormat('d/m/Y H:i:s', $presentation_start_date_time);
    if ($start_date_time) {
      $orderly_trainning_items[$start_date_time->getTimestamp() . $item->ID] = $item;
    } else {
      $orderly_trainning_items[$item->ID] = $item;
    }
  }
  ksort($orderly_trainning_items);

  $masterclassDots = "";
  ?>
  <div class="workshop">
    <div class="container container-big flex-col">
      <h2 class="home-title"><?php echo $home_training_title ?></h2>
    </div>
    <div class="container container-big flex-col">
      <div class="workshop-slider">
        <div class="workshop-slider-main swiper">
          <div class="swiper-wrapper">
            <?php foreach ($orderly_trainning_items as $item):
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
              <div class="workshop-item swiper-slide">
                <figure><?php echo wp_get_attachment_image($item_image['ID'], 'masterclass') ?></figure>
                <div class="workshop-desc">
                  <h3 class="title uppercase">
                    <?php echo get_the_title($item) ?>
                  </h3>
                  <?php if ($item_subtitle): ?>
                    <span class="subtitle uppercase"><?php echo $item_subtitle ?></span>
                  <?php endif; ?>
                  <div class="workshop-desc-wrapper">
                    <?php if ($item_date): ?>
                      <span class="date uppercase">
                        <i class="icon-mdi-calendar"></i>
                        <?php echo $item_date ?>
                      </span>
                    <?php endif; ?>
                    <?php if ($item_hour): ?>
                      <span class="hour uppercase">
                        <i class="icon-clock"></i>
                        <?php echo $item_hour ?>
                      </span>
                    <?php endif; ?>
                    <?php if ($item_place): ?>
                      <span class="uppercase">
                        <i class="icon-pin-f"></i>
                        <?php echo $item_place ?>
                      </span>
                    <?php endif; ?>
                  </div>
                  <a class="btn-white with-decorator red" href="<?php echo get_the_permalink($item) ?>"><?php echo __('Saiba Mais')?></a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="workshop-controls slider-controls">
        <div class="slider-navigation">
          <button class="slider-button-prev">
            <i class="icon-arrow-left-2"></i>
          </button>
          <button class="slider-button-next">
            <i class="icon-arrow-right-2"></i>
          </button>
        </div>
        <div class="slider-pagination"></div>
        <?php if ($home_training_link_url): ?>
          <a class="btn-dark with-decorator red"
             href="<?php echo $home_training_link_url ?>"><?php echo $home_training_link_label ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php
}
