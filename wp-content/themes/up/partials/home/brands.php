<?php
$home_brands_img = get_field('up_home_brands', 'option');
if($home_brands_img): ?>
  <div class="partners " aria-hidden="true">
    <div class="container big">
      <div class="">
        <img
          src="<?php echo $home_brands_img['url'] ?>"
          alt="<?php echo $home_brands_img['alt'] ?>"
          width="<?php echo $home_brands_img['width'] ?>"
          height="<?php echo $home_brands_img['height'] ?>">
      </div>
    </div>
  </div>
<?php endif;
