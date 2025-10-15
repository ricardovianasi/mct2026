<?php
$bannerItems = get_posts([
  'post_type' => POST_TYPE_BANNER,
  'showposts' => 10,
  'suppress_filters' => 0
]);

if ($bannerItems): ?>
  <div class="banner">
    <div class="container">
      <div class="banner-slider">
        <div class="swiper">
          <div class="swiper-wrapper">
            <?php foreach ($bannerItems as $banner):
              $bannerTitle = $banner->post_title;
              $bannerImageDesktop = null;
              $bannerImageMobile = null;
              $bannerImageAlt = "";
              $bannerImageUrl = "";
              if (get_field('banner_image_mobile', $banner)) {
                $bannerImageMobile = wp_get_attachment_image(get_field('banner_image_mobile', $banner)['id'], 'banner_mobile_size');
              }
              if (get_field('banner_image_desktop', $banner)) {
                $bannerImageDesktop = wp_get_attachment_image(get_field('banner_image_desktop', $banner)['id'], 'banner_desktop_size');
              }
              $bannerUrl = get_field('banner_url', $banner); ?>
              <div class="swiper-slide banner-slide">
                <?php if ($bannerImageMobile): ?>
                  <div class="mobile">
                    <?php echo $bannerUrl ? "<a aria-label='$bannerTitle' href='$bannerUrl'>$bannerImageMobile</a>" : $bannerImageMobile ?>
                  </div>
                  <div class="desktop">
                    <?php echo $bannerUrl ? "<a aria-label='$bannerTitle' href='$bannerUrl'>$bannerImageDesktop</a>" : $bannerImageDesktop ?>
                  </div>
                <?php else: ?>
                  <?php echo $bannerUrl ? "<a aria-label='$bannerTitle' href='$bannerUrl'>$bannerImageDesktop</a>" : $bannerImageDesktop ?>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php if (count($bannerItems) > 1): ?>
        <div class="slider-controls red">
          <<button class="slider-button-prev">
            <i class="icon-arrow-left-2"></i>
          </button>
          <div class="slider-pagination"></div>
          <button class="slider-button-next">
            <i class="icon-arrow-right-2"></i>
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif;
