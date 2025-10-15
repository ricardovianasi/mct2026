<?php
/**
 * Template Name: Página de Programação
 */

$prog_args = array(
  'post_type' => 'prog',
  'showposts' => '-1',
  'suppress_filters' => 0,
  'meta_query' => array(
    array(
      'key' => 'prog_start',
      'compare' => 'EXIST',
      'type' => 'DATETIME'
    ),
    array(
      'relation' => 'or',
      array(
        'key' => 'prog_hide',
        'compare' => 'NOT EXISTS',
      ),
      array(
        'relation' => 'and',
        array(
          'key' => 'prog_hide',
          'compare' => 'EXIST',
        ),
        array(
          'key' => 'prog_hide',
          'compare' => '!=',
          'value' => '1',
        ),
      ),
    ),
  ),
  'order' => 'ASC',
  'orderby' => 'meta_value',
  'meta_key' => 'prog_start',
  'meta_type' => 'DATETIME',
);

$cat_filter = !empty($_GET['categoria']) ? esc_attr($_GET['categoria']) : "";
if ($cat_filter) {
  $prog_args['tax_query'][] = [
    'taxonomy' => 'cat_activities',
    'field' => 'slug',
    'terms' => $cat_filter,
  ];
}

$local_filter = !empty($_GET['local']) ? esc_attr($_GET['local']) : "";
if ($local_filter) {
  $prog_args['tax_query'][] = [
    'taxonomy' => 'cat_place',
    'field' => 'slug',
    'terms' => $local_filter,
  ];
}

$day_filter = !empty($_GET['dia']) ? esc_attr($_GET['dia']) : "";
if ($day_filter) {
  $day_filter_from = "$day_filter 00:00:00";
  $day_filter_to = "$day_filter 23:59:59";

  $prog_args['meta_query'][] = array(
    'key' => 'prog_start',
    'compare' => '>=',
    'value' => $day_filter_from,
    'type' => 'DATETIME',
  );
  $prog_args['meta_query'][] = array(
    'key' => 'prog_start',
    'compare' => '<=',
    'value' => $day_filter_to,
    'type' => 'DATETIME',
  );
}

$prog_per_days = [];
$prog_all_items = get_posts($prog_args);
foreach ($prog_all_items as $prog) {
  $start = get_field('prog_start', $prog);
  $start_date_time = \DateTime::createFromFormat('d/m/Y H:i:s', $start);
  $prog_per_days[$start_date_time->format('Ymd')]['items'][] = $prog;
  $prog_per_days[$start_date_time->format('Ymd')]['start_date_time'] = $start_date_time;
}
ksort($prog_per_days);

$contentBefore = "";
ob_start(); ?>
  <div class="prog">
    <div class="prog-filter">
      <form action="">
        <div class="row cat">
          <label for="prog_filter_type"><?php echo __('Filtrar por local', 'up') ?>:</label>
          <select name="local" id="prog_filter_type" name="local" onchange="this.form.submit(); ">
            <option value=""><?php echo __('Todos', 'up') ?></option>
            <?php
            /*$categories = get_terms([
              'taxonomy' => 'cat_activities',
              'hide_empty' => true,
            ]);*/
            $categories = get_terms_by_post_type(['cat_place'], ['prog']);
            foreach ($categories as $cat): ?>
              <option <?php echo $local_filter == $cat->slug ? 'selected' : ''; ?>
                value="<?php echo $cat->slug ?>"><?php echo $cat->name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row buttons">
          <label for="prog_filter_type"><?php echo __('Filtre por dia', 'up') ?>:</label>
          <div class="days">
            <button value="" class="btn btn-primary <?php echo !$day_filter ? 'active' : '' ?> ">
              <span><?php echo __('todos', 'up') ?></span></button>
            <?php
            $prog_event_init = \DateTime::createFromFormat('Y-m-d', '2025-09-23');
            $prog_event_end = \DateTime::createFromFormat('Y-m-d', '2025-09-28');
            while ($prog_event_init <= $prog_event_end) {
              echo sprintf(
                "<button value='%s' name='dia' class='btn %s' type='submit'><span>%s</span></button>",
                $prog_event_init->format('Y-m-d'),
                $day_filter == $prog_event_init->format('Y-m-d') ? 'active' : '',
                str_replace(['setembro', 'outubro', 'janeiro', 'fevereiro', 'junho'], ['set', 'out', 'jan', 'fev', 'JUN'], date_i18n('d F', $prog_event_init->getTimestamp()))
              );
              $prog_event_init->add(new \DateInterval('P1D'));
            } ?>
          </div>
        </div>
      </form>
    </div>
    <div class="prog-items">
      <?php
      if (!empty($prog_per_days)) {
        foreach ($prog_per_days as $day => $prog_items) {
          $current_date = $day;
          echo '<div class="prog-items-wrapper"><div class="container container-medium">';
          echo '<div class="prog-day"><span>' . date_i18n('d \d\e F', $prog_items['start_date_time']->getTimestamp()) . '</span></div>';
          echo '<div class="prog-items-list">';
          foreach ($prog_items['items'] as $prog) :
            $prog_img = get_field('prog_img', $prog);
            $prog_start = get_field('prog_start', $prog);
            $prog_end = get_field('prog_end', $prog);
            $prog_date = get_field('prog_date', $prog);
            $prog_type = get_field('prog_type', $prog);
            $prog_activity = get_field('prog_activity', $prog);
            $prog_label = get_field('prog_label', $prog);
            $prog_link = get_field('prog_link', $prog);
            $prog_online = get_field('prog_online', $prog);
            $prog_obs = get_field('prog_obs', $prog);

            $prog_place = '';
            $prog_place_terms = get_the_terms($prog->ID, 'cat_place');
            $prog_place = $prog_place_terms ? join(', ', wp_list_pluck($prog_place_terms, 'name')) : false;

            $prog_real_link = $prog_link;
            if (!$prog_real_link && $prog_activity) {
              $prog_real_link = get_the_permalink($prog_activity);
            }

            $prog_real_date = $prog_date;
            if (!$prog_real_date) {
              $prog_start_date_time_obj = \DateTime::createFromFormat('d/m/Y H:i:s', $prog_start);
              $prog_real_date = date_i18n('d/m \| l \| H:i', $prog_start_date_time_obj->getTimestamp());
              $prog_real_date = str_replace('-feira', '', $prog_real_date);
            }

            $prog_real_label = $prog_label;
            if (!$prog_real_label) {
              $prog_real_label = $prog_type->name;
            } ?>
            <div class="prog-item">
              <a href="<?php echo $prog_real_link ?>">
                <figure>
                  <?php if (!empty($prog_img['ID'])) {
                    echo wp_get_attachment_image($prog_img['ID'], 'prog');
                  } ?>
                </figure>
                <div class="wrapper">
                  <div class="label">
                    <span class="tag"><?php echo $prog_real_label ?></span>
                    <?php if ($prog_online): ?>
                      <span class="tag"><?php echo __('Assista on-line', 'up') ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="wrapper-bgc">
                    <h3><?php echo get_the_title($prog) ?></h3>
                    <?php if ($prog_obs): ?>
                      <p class="obs"><i><?php echo $prog_obs ?></i></p>
                    <?php endif; ?>
                    <span class="date"><i class="icon-mdi-calendar mr-3"></i><?php echo $prog_real_date ?></span>
                    <span class="where">
                      <?php if ($prog_place): ?>
                        <i class="icon-pin-fill mr-3"></i><?php echo $prog_place; ?>
                      <?php endif; ?>
                    </span>
                    <?php if ($prog_real_link): ?>
                      <span class="btn-red with-decorator white"><?php echo __('Saiba Mais', 'up') ?></span>
                    <?php endif; ?>
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach;
          echo '</div>';
          echo '</div>';
          echo '</div>';

          $movieHigh = 'dia-' . $prog_items['start_date_time']->format('d');
          $movieHighTerm = get_term_by('slug', $movieHigh, 'cat_highlight');
          if ($movieHighTerm) {
            $movieInstance = Movie::newInstance();
            $movieInstance->setType('carousel');
            $movieInstance->setDestaque($movieHighTerm->term_id);
            $high_movies = $movieInstance::renderBlock($movieHigh);
            if ($high_movies) {
              echo '<h2 class="prog-movie">ASSISTA ONLINE</h2>' . $high_movies;
            }
          }
        }
      } else {
        echo '<h2>' . __('Nada por aqui :( Por favor, tente novamente.', 'up') . '</h2>';
      }
      ?>
    </div>
  </div>
<?php $contentAfter = ob_get_clean();

get_header(); ?>
  <div class="main-container container container-medium flex-col">
    <?php get_template_part('partials/heading', '', $args); ?>

    <div class="main-content">
      <div class="container container-medium flex-col">
        <div>
          <?php
          the_content();
          echo $contentAfter;
          ?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer();
