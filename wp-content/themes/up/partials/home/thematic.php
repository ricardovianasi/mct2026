<?php
$home_thematic = get_field('up_home_thematic', 'option');
if(!empty($home_thematic['up_home_thematic_enabled'])) {
  $home_thematic_title = !empty($home_thematic['up_home_thematic_title'])
    ? $home_thematic['up_home_thematic_title']
    : __('TEMÁTICA', 'up');

  $home_thematic_items = $home_thematic['up_home_thematic_cat_items'];
  $home_thematic_item = $home_thematic_items[0] ?? [];

  if (!empty($home_thematic_item)):
    $home_thematic_item_title = $home_thematic_item['title'] ?? null;
    $home_thematic_item_img = $home_thematic_item['image'] ?? null;
    $home_thematic_item_description = $home_thematic_item['description'] ?? null;
    $home_thematic_item_label_do_link = $home_thematic_item['label_do_link'] ?? __('Saiba mais', 'up');
    $home_thematic_item_link = $home_thematic_item['link'] ?? null;

  ?>
    <div class="thematic">
      <div class="container medium flex-col">
        <h2 class="home-title uppercase"><?php echo $home_thematic_title ?></h2>
        <div class="thematic-wrapper">
          <div>
            <?php if (!empty($home_thematic_item_title)): ?>
              <h3><?php echo $home_thematic_item_title ?></h3>
            <?php endif ?>
            <?php if (!empty($home_thematic_item_title)): ?>
              <p>
                <?php echo $home_thematic_item_description ?>
              </p>
            <?php endif ?>
          </div>
          <?php if (isset($home_thematic_item_img['id'])): ?>
            <figure>
              <?php echo wp_get_attachment_image($home_thematic_item_img['id'], 'thematic'); ?>
            </figure>
          <?php endif?>
          <?php if ($home_thematic_item_link): ?>
            <div class="thematic-read-more">
              <a class="btn-yellow" href="<?php echo $home_thematic_item_link ?>">
                <?php echo $home_thematic_item_label_do_link ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php
  endif;
}
