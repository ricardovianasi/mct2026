<?php
$up_home_gallery  = get_field('up_home_gallery', 'option');
$gallery_items = !empty($up_home_gallery['up_home_gallery_images']) ? $up_home_gallery['up_home_gallery_images'] : [];
if($gallery_items):
  $gallery_slides = "";
  foreach ($gallery_items as $key => $img) {
    $gallery_slides .= '<div class="swiper-slide">'.wp_get_attachment_image($img['ID'], 'gallery').'</div>';
  }
  ?>

  <div class="gallery">
    <div class="container big">
      <h2 class="uppercase home-title"><?php echo __('Galeria', 'up'); ?></h2>
    </div>

    <div class="gallery-container">
      <div class="gallery-wrapper">
        <div class="gallery-items">
          <?php foreach ($gallery_items as $key => $img): ?>
            <div class="gallery-item">
              <a data-fancybox="gallery" href="<?php echo wp_get_attachment_image_url($img['ID'], 'full') ?>">
                <?php echo wp_get_attachment_image($img['ID'], 'gallery') ?></a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <a class="btn-red with-decorator black" target="_blank" href="https://www.flickr.com/photos/universoproducao">
      <?php echo __('Visite nosso Flickr', 'up')?></a>

  </div>
<?php endif;
