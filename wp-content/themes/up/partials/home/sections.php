<?php
$home_sections = get_field('up_home_section', 'option');
foreach ($home_sections as $key => $section) {
  $titulo = $section['titulo'];
  $descricao = $section['descricao'];
  $imagem_ou_galeria = $section['imagem_ou_galeria'] ?? [];
  $label_da_link = $section['label_da_link'] ?? 'Saiba mais';
  $link_para_pagina_interna = $section['link_para_pagina_interna'];
  ?>
  <div class="section section-<?php echo $key + 1; ?>">
    <div class="container container-big section-content">
      <div class="section-wrapper">
        <div class="desc">
          <h2><?php echo $titulo ?></h2>
          <p><?php echo $descricao ?></p>
        </div>

        <?php if(count($imagem_ou_galeria) > 1): ?>
          <div class="section-slider">
            <div class="swiper">
              <div class="swiper-wrapper">
                <?php foreach ($imagem_ou_galeria as $img): ?>
                  <div class="swiper-slide">
                    <?php echo wp_get_attachment_image($img['ID'], 'intro_gallery') ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php elseif (count($imagem_ou_galeria) === 1): ?>
          <figure><?php echo wp_get_attachment_image($imagem_ou_galeria[0]['ID'], 'intro_gallery') ?></figure>
        <?php endif; ?>
      </div>
      <div class="section-controls slider-controls">
        <?php if ($link_para_pagina_interna): ?>
          <a class="btn-blue with-decorator white" href="<?php echo $link_para_pagina_interna ?>">
            <?php echo __($label_da_link) ?></a>
        <?php endif; ?>
        <?php if (count($imagem_ou_galeria) > 1): ?>
          <div class="slider-pagination black"></div>
          <div class="slider-navigation red">
            <button class="slider-button-prev">
              <i class="icon-arrow-left-2"></i>
            </button>
            <button class="slider-button-next">
              <i class="icon-arrow-right-2"></i>
            </button>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
  <?php
}
