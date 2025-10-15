<?php

class Formation
{
  private static $instance;
  private static $block = 'formation';

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public static function register()
  {
    if (function_exists('acf_register_block_type')) {
      acf_register_block_type(array(
        'name' => self::$block,
        'title' => __('Formação'),
        'description' => __('Formação'),
        'render_callback' => [self::class, 'callback'],
        'category' => 'up',
        'icon' => 'admin-appearance'
      ));
    }
  }

  /**
   * CTA Block Callback Function.
   *
   * @param array $block The block settings and attributes.
   * @param string $content The block inner HTML (empty).
   * @param bool $is_preview True during AJAX preview.
   * @param (int|string) $post_id The post ID this block is saved to.
   */
  public static function callback($block, $content = '', $is_preview = false, $post_id = 0)
  {
    $block_formation_category = get_field('block_formation_category');
    $block_formation_layout = get_field('block_formation_layout');
    $block_formation_layout = get_field('block_formation_layout');

    // Create id attribute allowing for custom "anchor" value.
    $id = self::$block . '-' . $block['id'];
    if (!empty($block['anchor'])) {
      $id = $block['anchor'];
    }

    // Create class attribute allowing for custom "className" and "align" values.
    $className = 'formation-items';
    if (!empty($block['className'])) {
      $className .= ' ' . $block['className'];
    }

    if ($block_formation_layout === 'debate') {
      $className .= ' formation-items-debate ';
    }

    if ($block_formation_layout === 'arte') {
      $className .= ' formation-items-art ';
    }

    if ($block_formation_layout === 'juri') {
      $className .= ' formation-items-juri ';
    }

    if ($is_preview) {
      $className .= ' block-preview';
    }

    $query_args = [
      'post_type' => 'activities',
      'suppress_filters' => 0,
      'posts_per_page' => -1,
      'order' => 'ASC',
      'orderby' => 'meta_value',
      'meta_key' => 'presentation_start_date_time'
    ];

    if ($block_formation_category) {
      $query_args['tax_query'][] = [
        'taxonomy' => 'cat_activities',
        'terms' => $block_formation_category,
      ];
    }

    $posts = get_posts($query_args);

    if (!$is_preview) {
      echo '<div class="expanded"><div class="container container-1216">';
    }
    ?>

    <div id="<?php echo $id ?>" class="<?php echo $className ?>">
      <?php if ($is_preview): ?>
        <label for="<?php echo $id ?>" class="components-placeholder__label">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" role="img"
               aria-hidden="true" focusable="false">
            <path
              d="M16 4.2v1.5h2.5v12.5H16v1.5h4V4.2h-4zM4.2 19.8h4v-1.5H5.8V5.8h2.5V4.2h-4l-.1 15.6zm5.1-3.1l1.4.6 4-10-1.4-.6-4 10z"></path>
          </svg>
          Bloco - Programa de Formação
        </label>
      <?php endif; ?>
      <?php if (!$is_preview) {
        foreach ($posts as $block):
          $block_subtitle = get_field('presentation_subtitle', $block);
          $block_date = get_field('presentation_date', $block);
          $activity_hour = get_field('presentation_hour');
          $block_image = get_field('presentation_image', $block);
          $block_image_grid = get_field('presentation_image_grid', $block);
          $block_workload = get_field('presentation_workload', $block);
          $block_vacancies = get_field('presentation_vacancies', $block);
          $block_age_range = get_field('presentation_age_range', $block);
          $block_subscription = get_field('presentation_subscription', $block);
          $block_tag = get_field('presentation_tag', $block);
          $block_obs = get_field('presentation_obs', $block);
          $block_readmore = get_field('presentation_readmore', $block);

          $playerObj = new UP_Player($block);

          $block_place = '';
          $cat_place_terms = get_the_terms($block->ID, 'cat_place');
          $cat_place_terms = is_array($cat_place_terms) ? $cat_place_terms[0] : $cat_place_terms;
          if ($cat_place_terms) {
            $block_place = $cat_place_terms->name;
          }

          $block_image_size = 'block_workshop';
          if ($block_formation_layout === 'debate') {
            $block_image_size = 'debate';
          }

          if ($block_formation_layout === 'arte') {
            $block_image_size = 'debate_list';
          }

          ?>
          <div class="formation-item">

            <?php if ($block_formation_layout === 'arte'): ?>
              <div class="title">
                <h2 class="uppercase"><?php echo get_the_title($block) ?></h2>
                <?php if ($block_subtitle): ?><p class="subtitle"><?php echo $block_subtitle ?></p><?php endif; ?>
                <div class="info">
                  <?php if ($block_date): ?><span class="date"><i
                    class="icon-mdi-calendar"></i><?php echo $block_date ?>
                    </span><?php endif; ?>
                  <?php if ($activity_hour): ?><span class="hour"><i
                    class="icon-clock"></i><?php echo $activity_hour; ?>
                    </span><?php endif; ?>
                  <?php if ($block_place): ?><span class="place"><i
                    class="icon-pin-fill"></i><?php echo $block_place; ?>
                    </span><?php endif; ?>
                  <?php if ($block_workload): ?>
                    <span><i
                        class="icon-clock"></i><?php echo __('carga horária', 'up') ?>: <?php echo $block_workload; ?></span>
                  <?php endif; ?>
                  <?php if ($block_vacancies): ?>
                    <span><i
                        class="icon-user"></i><?php echo __('vagas', 'up') ?>: <?php echo $block_vacancies; ?></span>
                  <?php endif; ?>
                  <?php if ($block_age_range): ?>
                    <span><i
                        class="icon-user"></i><?php echo __('faixa etária', 'up') ?>: <?php echo $block_age_range; ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($block_image_grid): ?>
              <div class="img-grid">
                <?php foreach ($block_image_grid as $key => $img) {
                  echo wp_get_attachment_image($img['ID'], 'prog');
                } ?>
              </div>
            <?php elseif ($block_image['id']): ?>
              <figure>
                <?php echo wp_get_attachment_image($block_image['id'], $block_image_size); ?>
              </figure>
            <?php endif; ?>

            <?php if ($block_formation_layout !== 'arte'): ?>
              <div class="desc">
                <?php if ($block_tag): ?>
                  <div class="tags">
                    <div class="tag"><?php echo $block_tag ?></div>
                  </div>
                <?php endif; ?>
                <div class="title">
                  <h2 class="uppercase"><?php echo get_the_title($block) ?></h2>
                  <?php if ($block_subtitle): ?>
                    <div class="subtitle"><?php echo $block_subtitle ?></div>
                  <?php endif; ?>
                </div>


                <?php if ($block_formation_layout !== 'juri'): ?>
                  <div class="info">
                  <?php if ($block_date): ?><span class="date"><i
                    class="icon-mdi-calendar"></i><?php echo $block_date ?>
                    </span><?php endif; ?>
                  <?php if ($activity_hour): ?><span class="hour"><i
                    class="icon-clock"></i><?php echo $activity_hour; ?>
                    </span><?php endif; ?>
                  <?php if ($block_place): ?><span class="place"><i
                    class="icon-pin-fill"></i><?php echo $block_place; ?>
                    </span><?php endif; ?>
                  <?php if ($block_workload): ?>
                    <span><i
                        class="icon-clock"></i><?php echo __('carga horária', 'up') ?>: <?php echo $block_workload; ?></span>
                  <?php endif; ?>
                  <?php if ($block_vacancies): ?>
                    <span><i
                        class="icon-user"></i><?php echo __('vagas', 'up') ?>: <?php echo $block_vacancies; ?></span>
                  <?php endif; ?>
                  <?php if ($block_age_range): ?>
                    <span><i
                        class="icon-user"></i><?php echo __('faixa etária', 'up') ?>: <?php echo $block_age_range; ?></span>
                  <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($block_formation_layout !== 'juri'): ?>
                  <p class="excerpt">
                    <?php echo get_the_excerpt($block) ?>
                  </p>
                <?php endif; ?>


                <?php if ($block_obs): ?>
                  <p><i><?php echo $block_obs ?></i></p>
                <?php endif; ?>

                <?php if ($block_formation_layout !== 'juri'): ?>
                  <div class="cta">
                    <?php if (!$block_readmore): ?>
                      <a class="btn-red with-decorator black" href="<?php echo get_the_permalink($block) ?>">
                        <?php echo __('Saiba mais', 'up') ?></a>
                    <?php endif; ?>
                    <?php if (!empty($block_subscription) && $block_subscription['presentation_subscription_enabled']): ?>
                      <a target="_blank"
                         class="btn-dark with-decorator red"
                         href="<?php echo $block_subscription['presentation_subscription_link'] ?>">
                        <?php echo !empty($block_subscription['presentation_subscription_label'])
                          ? $block_subscription['presentation_subscription_label']
                          : __('Inscreva-se', 'up'); ?>
                      </a>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php else: ?>
              <div class="desc">
                <p class="excerpt">
                  <?php echo  get_custom_excerpt($block->post_content, 80); ?>
                </p>
              </div>
              <div class="cta">
                <?php if (!$block_readmore): ?>
                  <a class="btn-red with-decorator black" href="<?php echo get_the_permalink($block) ?>">
                    <?php echo __('Saiba mais', 'up') ?></a>
                <?php endif; ?>
                <?php if (!empty($block_subscription) && $block_subscription['presentation_subscription_enabled']): ?>
                  <a target="_blank"
                     class="btn-dark with-decorator red"
                     href="<?php echo $block_subscription['presentation_subscription_link'] ?>">
                    <?php echo !empty($block_subscription['presentation_subscription_label'])
                      ? $block_subscription['presentation_subscription_label']
                      : __('Inscreva-se', 'up'); ?>
                  </a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach;
      } ?>
    </div>
    <?php
    if (!$is_preview) {
      echo '</div></div>';
    }
  }
}

add_action('acf/init', [Formation::class, 'register']);
