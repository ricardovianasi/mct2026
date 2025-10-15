<div class="main-container container flex-col">
  <?php get_template_part('partials/heading', '', $args); ?>
    <div class="main-content">
      <div class="container container-small content flex-col">
        <?php
        if(!empty($args['content-before'])) {
          echo $args['content-before'];
        }

        the_content();

        if(!empty($args['content-after'])) {
          echo $args['content-after'];
        }
        ?>
      </div>
    </div>
</div>
<?php
