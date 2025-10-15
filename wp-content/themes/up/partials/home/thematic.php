<?php
$home_thematic = get_field('up_home_thematic', 'option');
if(!empty($home_thematic['up_home_thematic_enabled'])) {
  $home_thematic_title = !empty($home_thematic['up_home_thematic_title'])
    ? $home_thematic['up_home_thematic_title']
    : __('TEMÁTICA', 'up');

  $home_thematic_items = $home_thematic['up_home_thematic_cat_items'];
  $home_thematic_item = $home_thematic_items[0] ?? [];

  if (!empty($home_thematic_item)):
    $home_thematic_item_img = $home_thematic_item['image'] ?? null;
    $home_thematic_item_description = $home_thematic_item['description'] ?? null;
    $home_thematic_item_descricao_coluna_direita = $home_thematic_item['descricao_coluna_direita'] ?? null;
    $home_thematic_item_label_do_link = $home_thematic_item['label_do_link'] ?? __('Saiba mais', 'up');
    $home_thematic_item_link = $home_thematic_item['link'] ?? null;

  ?>
    <div class="thematic">
      <div class="id">
        <svg width="1920" height="996" viewBox="0 0 1920 996" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M938.222 27.218C684.222 -14.782 -8.5 62.718 -123 140.218V1035.62L2007 1022.22L2007 -18C1677.5 100.718 1327.22 91.5409 938.222 27.218Z" fill="#D63949"/>
        </svg>

      </div>
      <div class="container container-big flex-col">
        <div class="thematic-wrapper">
          <div>
            <h2 class="uppercase tracking-[6.4px]"><?php echo $home_thematic_title ?></h2>
            <?php if ($home_thematic_item_description): ?>
              <p><?php echo $home_thematic_item_description ?></p>
            <?php endif; ?>
          </div>
          <div class="middle">
<!--            --><?php //if (isset($home_thematic_item_img['id'])) {
//              echo wp_get_attachment_image($home_thematic_item_img['id'], 'thematic');
//            } ?>
          </div>
          <div class="last">
            <?php if ($home_thematic_item_descricao_coluna_direita): ?>
              <p><?php echo $home_thematic_item_descricao_coluna_direita ?></p>
            <?php endif; ?>
            <?php if ($home_thematic_item_link): ?>
              <a class="btn-white with-decorator black" href="<?php echo $home_thematic_item_link ?>">
                <?php echo $home_thematic_item_label_do_link ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php
  endif;
}
