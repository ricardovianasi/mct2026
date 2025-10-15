<?php
get_header(); ?>
  <div class="main-container container flex-col">
    <?php
    get_template_part('partials/heading', '', [
      'heading_title' => 'Notícia'
    ]); ?>
    <div class="main-content">
      <div class="container container-small content flex-col">
        <div class="main-header-subtitle">
          <h2 class="h4">
            <?php the_title(); ?>
          </h2>
          <span>Publicado em <?php echo PostDate::format(get_the_ID(), PostDate::MEDIUM); ?></span>
        </div>

        <?php
        if (!empty($args['content-before'])) {
          echo $args['content-before'];
        }

        echo the_content();

        if (!empty($args['content-after'])) {
          echo $args['content-after'];
        }
        ?>
      </div>
    </div>
  </div>
<?php
get_footer();

