<?php
global $post;

$activity_type = get_field('presentation_type');
$activity_subtitle = get_field('presentation_subtitle');
$activity_date = get_field('presentation_date');
$activity_start = get_field('presentation_start_date_time');
$activity_end = get_field('presentation_end_date_time');
$activity_subscription = get_field('presentation_subscription');
$activity_image = get_field('presentation_image');
$block_image_grid          = get_field( 'presentation_image_grid');
$activity_image_banner = get_field('hero_banner');

$activity_workload         = get_field( 'presentation_workload' );
$activity_vacancies        = get_field( 'presentation_vacancies' );
$activity_age_range        = get_field( 'presentation_age_range' );
$activity_tag = get_field('presentation_tag');
$activity_gallery = get_field( 'presentation_gallery' );

$activity_guest = get_field( 'presentation_guest' );
$guest_name = $activity_guest['name'] ?? null;
$guest_image = $activity_guest['image'] ?? null;
$guest_country = $activity_guest['country'] ?? null;
$guest_description = $activity_guest['description'] ?? null;
$guest_curriculo = $activity_guest['curriculo'] ?? null;

$playerObj = new UP_Player();
$player = $playerObj->player(get_the_ID());

$activity_terms           = get_the_terms($post->ID, 'cat_activities');
$activities_page_title    = join(', ', wp_list_pluck($activity_terms, 'name'));

$cat_place_terms = get_the_terms($post->ID, 'cat_place');
$block_place = join(', ', wp_list_pluck($cat_place_terms, 'name'));

$home_debate = get_field('up_home_debate', 'option');
$heading_title_debate = !empty($home_debate['up_home_debate_title'])
  ? $home_debate['up_home_debate_title']
  : __('DEBATES E RODAS DE CONVERSA', 'up');

$home_art = get_field('up_home_art', 'option');
$heading_title_art = !empty($home_art['up_home_art_title'])
  ? $home_art['up_home_art_title']
  : __('Arte', 'up');

$heading_title = $activity_type === 'debate' ? $heading_title_debate : $heading_title_art;

get_header(); ?>
<div class="main-container container container-medium flex-col">
  <div class="main-header">
    <div class="container container-medium flex-col">
      <div class="main-header-title">
        <span class="h1 uppercase"><?php echo $heading_title ?></span>
      </div>
    </div>
  </div>

  <div class="main-content">
    <div class="debate-single">
      <?php if ($block_image_grid): ?>
      <div class="expanded">
        <div class="container container-small justify-center">
          <div class="img-grid">
          <?php $cont=0; foreach ($block_image_grid as  $key => $img) {
            $cont++;
            if ($cont >= 9) {
              break;
            }
            echo wp_get_attachment_image($img['ID'], 'debate_grid');
          } ?>
        </div>
        </div>
      </div>
      <?php else: ?>
        <figure>
          <?php echo wp_get_attachment_image($activity_image_banner, 'hero_banner') ?>
        </figure>
      <?php endif; ?>
      <div class="container container-small content">
        <div class="desc">
          <div class="title">
            <?php if ($activity_tag): ?>
              <div class="tags">
                <?php render_tags($activity_tag); ?>
              </div>
            <?php endif; ?>
            <h1 class="uppercase"><?php the_title(); ?></h1>
          </div>
          <div class="info">
            <?php if ($activity_date): ?>
              <span><i class="icon-mdi-calendar"></i><?php echo $activity_date ?></span>
            <?php endif; ?>
            <?php if ($block_place): ?>
              <span><i class="icon-pin-fill"></i><?php echo $block_place ?></span>
            <?php endif; ?>
          </div>

          <?php the_content(); ?>


          <?php if ($player): ?>
            <div class="play">
              <h3><?php echo __('Assista', 'up')?></h3>
              <div class="play-wrapper">
                <?php echo $player ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
get_footer();
