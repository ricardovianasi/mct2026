<?php
$heading_title = get_the_title();
if (!empty($args['heading_title'])) {
  $heading_title = $args['heading_title'];
}

$hero_banner = false;
if (!empty($args['hero-banner'])) {
  $hero_banner = $args['hero-banner'];
} else {
  $hero_banner_field = get_field('hero_banner');
  $hero_banner = wp_get_attachment_image_url($hero_banner_field, 'hero_banner');
}

$playerObj = new UP_Player();
$player = $playerObj->player(get_the_ID(), $hero_banner);

$showBreadcrumbsBefore = !($player || $hero_banner);

?>
<?php if ($showBreadcrumbsBefore && function_exists('bcn_display')): ?>
  <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
    <?php bcn_display(); ?>
  </div>
<?php endif; ?>
  <div class="main-header">
    <?php
    if ($hero_banner): ?>
      <div class="main-header-bg">
        <figure><img src="<?php echo $hero_banner ?>" alt="<?php echo $heading_title ?>"></figure>
        <div class="main-header-title">
          <h1 class="uppercase"><?php echo $heading_title ?></h1>
        </div>
      </div>
    <?php else: ?>
      <div class="main-header-title">
        <h1 class="uppercase h2"><?php echo $heading_title ?></h1>
      </div>
    <?php endif; ?>
  </div>
<?php if (!$showBreadcrumbsBefore && function_exists('bcn_display')): ?>
  <div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
    <?php bcn_display(); ?>
  </div>
<?php endif;
