<?php
$home_honor = get_field('up_home_honor', 'option');
if (!empty($home_honor['up_home_honor_enabled'])):
  $home_honor_items = $home_honor['up_home_honor_items'] ?? [];
  $up_home_honor_title = $home_honor['up_home_honor_title'] ?? __('Mostra Homenagem', 'up');

  if (!empty($home_honor_items)):
    ?>
    <div class="tribute">
      <div class="container">
        <h2 class="home-title"><?php echo $up_home_honor_title ?></h2>
          <?php foreach ($home_honor_items as $home_honor_item):
            $titulo = $home_honor_item['titulo'] ?? '';
            $subtitulo = $home_honor_item['subtitulo'] ?? '';
            $descricao = $home_honor_item['descricao'] ?? '';
            $imagem1 = $home_honor_item['imagem'] ?? null;
            $imagem2 = $home_honor_item['imagem_2'] ?? null;
            $imagem3 = $home_honor_item['imagem_3'] ?? null;
            $link = $home_honor_item['link'] ?? null;
            $label = $home_honor_item['label'] ?? 'Saiba Mais';
            ?>
            <div class="tribute-wrapper">
              <div class="left">
                <figure class="gota">
                  <?php if ($imagem1): ?>
                    <?php echo wp_get_attachment_image($imagem1['ID'], 'honor_1') ?>
                  <?php else: ?>
                    <img src="<?php bloginfo('template_directory'); ?>/assets/dist/images/gota.png" alt="gota">
                  <?php endif; ?>
                </figure>
                <figure class="gota">
                  <?php if ($imagem2): ?>
                    <?php echo wp_get_attachment_image($imagem2['ID'], 'honor_2') ?>
                  <?php else: ?>
                    <img src="<?php bloginfo('template_directory'); ?>/assets/dist/images/gota2.png" alt="gota">
                  <?php endif; ?>
                </figure>
              </div>
              <div class="right">
                <figure>
                  <?php if ($imagem3): ?>
                    <?php echo wp_get_attachment_image($imagem3['ID'], 'honor_2') ?>
                  <?php else: ?>
                    <img src="<?php bloginfo('template_directory'); ?>/assets/dist/images/gota2.png" alt="gota">
                  <?php endif; ?>
                </figure>
                <div class="tribute-desc gota">
                  <?php if ($titulo): ?>
                    <h3><?php echo $titulo ?></h3>
                  <?php endif; ?>
                  <?php if ($subtitulo): ?>
                    <p><strong><?php echo $subtitulo ?></strong></p>
                  <?php endif; ?>
                  <?php if ($descricao): ?>
                    <p><?php echo $descricao ?></p>
                  <?php endif; ?>
                  <?php if ($link): ?>
                    <a class="btn-red" href="<?php echo $link ?>"><?php echo $label ?></a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>

      </div>
    </div>
  <?php
  endif;
endif;
