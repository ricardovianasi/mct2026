<?php
/**
 * Template Name: Página de exposição
 */

$expoContentAfter = "";

$exposure_internal = get_posts([
  'post_type' => 'timeline',
  'suppress_filters' => 0,
  'showposts' => -1,
]);

ob_start();
foreach ($exposure_internal as $expo):
  $expo_theme = get_field('timeline_slogan', $expo);
  $expo_gallery = get_field('timeline_gallery', $expo);
  ?>

  <div class="exposition-year">
    <span><?php echo get_the_title($expo) ?></span>
  </div>

  <div class="exposition-desc">
    <span class="exposition-theme"><?php echo $expo_theme ?></span>
  </div>

  <?php
  $items = [];
  foreach ($expo_gallery as $img) {
    $items[] = '
      <div class="col">
        <a href="'.$img['url'].'" data-fancybox="expo" data-caption="">
          '.wp_get_attachment_image($img['ID'], 'expo').'
        </a>
      </div>';
  }
  if($items): ?>
    <div class="exposition-grid">
      <div class="row">
        <div class="col-50"><?php echo $items[0]; ?></div>
        <div class="col-50">
          <?php
          echo implode('', array_slice($items, 1, 4, true));
          ?>
        </div>
      </div>
      <div class="row">
        <?php echo implode('', array_slice($items, 5, 5, true)); ?>
      </div>
    </div>
  <?php
  endif;
endforeach;
$expoContentAfter = ob_get_clean();

get_header();
get_template_part('partials/content', '', [
  'content-after' => $expoContentAfter
]);
get_footer();
