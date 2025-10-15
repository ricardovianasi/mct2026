<?php
$up_home_introduction = get_field('up_home_introduction', 'option');
if (!empty($up_home_introduction['up_home_introduction_enabled'])):
  $up_home_introduction_title = $up_home_introduction['up_home_introduction_title'];
  $up_home_introduction_img = $up_home_introduction['up_home_introduction_img'];
  $up_home_introduction_text = $up_home_introduction['up_home_introduction_text'];
  $up_home_introduction_link = $up_home_introduction['up_home_introduction_link'];
  $up_home_introduction_link_text = $up_home_introduction['up_home_introduction_link_text'];

  ?>
  <div class="intro">
    <div class="container small">
      <?php if($up_home_introduction_img['ID']): ?>
        <div class="left">
          <?php echo wp_get_attachment_image($up_home_introduction_img['ID'], 'intro_gallery') ?>
        </div>
      <?php endif; ?>
      <div class="right">
        <div class="desc">
          <?php echo $up_home_introduction_text ?>
        </div>
        <?php if ($up_home_introduction_link): ?>
          <a href="<?php echo $up_home_introduction_link ?>"
             class="btn-link red"><?php echo $up_home_introduction_link_text ?? 'Leia a apresentação' ?>
            <i class="icon-arrow-right-2"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endif;
