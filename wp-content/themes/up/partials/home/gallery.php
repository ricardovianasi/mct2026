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
    <div class="id">
      <svg width="1920" height="218" viewBox="0 0 1920 218" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2023.29 93.3632C1757.29 -31.0078 1715.1 198.504 1457.5 207.259C1135.5 218.204 1158 34.6324 919.041 125.791C680.082 216.95 536.5 13.6082 355.5 10.1259C174.5 6.64346 45.041 76.0473 -40 141.711" stroke="#D05C3B" stroke-width="20"/>
      </svg>
    </div>
    <div class="container container-big">
      <h2 class="uppercase home-title"><?php echo __('Galeria', 'up'); ?></h2>
    </div>
    <div class="gallery-slider">
      <div class="swiper">
        <div class="swiper-wrapper">
          <?php foreach ($gallery_items as $key => $img): ?>
            <div class="swiper-slide">
              <a data-fancybox="gallery" href="<?php echo wp_get_attachment_image_url($img['ID'], 'full') ?>">
                <?php echo wp_get_attachment_image($img['ID'], 'gallery') ?></a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="container container-big">
      <div class="galley-controls slider-controls">
        <div class="slider-navigation red">
          <button class="slider-button-prev">
            <i class="icon-arrow-left-2"></i>
          </button>
          <button class="slider-button-next">
            <i class="icon-arrow-right-2"></i>
          </button>
        </div>
        <div class="slider-pagination black"></div>
        <a class="btn-red with-decorator black" target="_blank" href="https://www.flickr.com/photos/universoproducao">
          <?php echo __('Visite nosso Flickr', 'up')?></a>
      </div>
    </div>
  </div>
<?php endif;
