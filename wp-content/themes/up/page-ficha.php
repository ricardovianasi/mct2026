<?php
/**
 * Template Name: Página Ficha Técnica
 */

get_header();


?>
<div class="main-container container flex-col">
  <?php get_template_part('partials/heading', '', $args); ?>
  <div class="main-content">
    <div class="container container-big flex-col">
      <?php
      $fichas = get_posts([
        'post_type' => 'ficha',
        'showposts' => -1,
      ]);

      if ($fichas) { ?>
      <div class="info-ficha">
        <?php $count = 3; $last = 'blue'; foreach ($fichas as $ficha):
          $ficha_funcao = get_field('ficha_funcao', $ficha);
          $ficha_image = get_field('ficha_img', $ficha);
          ?>
          <div>
            <?php if ($ficha_image['ID']): ?>
              <figure><?php echo wp_get_attachment_image($ficha_image['ID'], 'news') ?></figure>
            <?php endif; ?>
            <div>
              <p><strong><?php echo get_the_title($ficha) ?></strong></p>
              <p><?php echo $ficha_funcao ?></p>
            </div>
          </div>
        <?php endforeach; ?>
        
      </div>
      <?php }
      the_content();
      ?>
    </div>
  </div>
</div>>
<?php get_footer();
