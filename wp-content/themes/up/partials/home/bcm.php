<?php
$up_home_bcm = get_field('up_home_bcm', 'option');
$up_home_bcm_enabled = !empty($up_home_bcm['up_home_bcm_enabled']) && $up_home_bcm['up_home_bcm_enabled'];
$up_home_bcm_text = !empty($up_home_bcm['up_home_bcm_text']) ? $up_home_bcm['up_home_bcm_text'] : "";
$up_home_bcm_text_right = !empty($up_home_bcm['up_home_bcm_text_right']) ? $up_home_bcm['up_home_bcm_text'] : "";
$up_home_bcm_date = !empty($up_home_bcm['up_home_bcm_featured']) ? $up_home_bcm['up_home_bcm_featured'] : "";

if( $up_home_bcm_enabled ):
?>
	<div class="bcm">
    <div class="bcm-wrapper">
      <div class="container container-big">
        <div class="left">
          <img alt="Brasil CineMundi - 16º International Coproduction Meeting" class="bcm-logo" src="<?php bloginfo('template_directory'); ?>/assets/dist/images/bcm.svg">
          <?php if ($up_home_bcm_text): ?>
            <div class="desc">
              <?php echo $up_home_bcm_text ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="right">
          <?php if ($up_home_bcm_text_right): ?>
            <div class="desc">
              <?php echo $up_home_bcm_text_right ?>
            </div>
          <?php endif; ?>
          <?php if ($up_home_bcm_date): ?>
            <div class="date">
              <p><?php echo $up_home_bcm_date ?></p>
            </div>
          <?php endif; ?>
          <a class="link" target="_blank" href="https://brasilcinemundi.com.br/"><?php echo __('ACESSE O SITE')?> <i class="icon-long-arrow-right"></i></a>
        </div>
      </div>
    </div>
	</div>
<?php endif;
