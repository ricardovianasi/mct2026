<?php
$home_honor = get_field('up_home_honor', 'option');
if (!empty($home_honor['up_home_honor_enabled'])):
  $home_honor_items = $home_honor['up_home_honor_items'] ?? [];
  $up_home_honor_title = $home_honor['up_home_honor_title'] ?? __('Mostra Homenagem', 'up');

  if (!empty($home_honor_items)):
    ?>
    <div class="tribute">
      <div class="id">
        <svg width="1920" height="713" viewBox="0 0 1920 713" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2174 548.079C1971.5 567.745 1543.5 537.179 1451.5 257.579C1336.5 -91.9214 1979.5 -49.9414 1898.5 276.059C1827.74 560.863 1414.5 361.079 1184.5 504.579C954.5 648.079 859.5 438.579 580 619.579C356.4 764.379 81.5 679.912 -28 619.579" stroke="#2E375B" stroke-width="25"/>
        </svg>
      </div>
      <div class="container container-big flex-col">
        <div class="tribute-wrapper">
          <h2 class="home-title"><?php echo $up_home_honor_title ?></h2>
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php foreach ($home_honor_items as $home_honor_item):
                $titulo = $home_honor_item['titulo'] ?? '';
                $subtitulo = $home_honor_item['subtitulo'] ?? '';
                $descricao = $home_honor_item['descricao'] ?? '';
                $imagem = $home_honor_item['imagem'] ?? null;
                $link = $home_honor_item['link'] ?? null;
                $label = $home_honor_item['label'] ?? 'Saiba Mais';
                ?>
                <div class="swiper-slide">
                  <div class="tribute-item">
                    <div class="tribute-content">
                      <div class="tribute-content-wrapper">
                        <?php if ($titulo): ?>
                          <h2><?php echo $titulo ?></h2>
                        <?php endif; ?>
                        <?php if ($subtitulo): ?>
                          <h3><?php echo $subtitulo ?></h3>
                        <?php endif; ?>
                        <?php if ($descricao): ?>
                          <p><?php echo $descricao ?></p>
                        <?php endif; ?>
                        <?php if ($link): ?>
                          <a class="btn-dark with-decorator red" href="<?php echo $link ?>"><?php echo $label ?></a>
                        <?php endif; ?>
                      </div>
                    </div>
                    <?php if (!empty($imagem['ID'])): ?>
                      <figure class="thematic-figure">
                        <?php echo wp_get_attachment_image($imagem['ID'], 'thematic') ?>
                      </figure>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php if (count($home_honor_items) > 1): ?>
            <div class="slider-controls">
              <div class="slider-navigation">
                <button class="slider-button-prev">
                  <i class="icon-arrow-left-2"></i>
                </button>
                <button class="slider-button-next">
                  <i class="icon-arrow-right-2"></i>
                </button>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php
  endif;
endif;
