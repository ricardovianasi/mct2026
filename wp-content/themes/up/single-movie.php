<?php
global $post;
$senha_de_protecao = get_field('senha_de_protecao', $post);
if (!empty($senha_de_protecao)) {

  $movieLogin = !empty($_POST['movie_login']) ? $_POST['movie_login'] : "";
  $moviePass = !empty($_POST['movie_password']) ? $_POST['movie_password'] : "";

  if ($movieLogin !== 'mostratiradentes' || $moviePass !== $senha_de_protecao) {
    ?>
    <form action="" method="post">
      <h3>Acesso restrito!</h3>
      <p>Preencha os dados abaixo para continuar:</p>
      <label for="">Login <br>
        <input type="text" name="movie_login">
      </label> <br> <br>
      <label for="">Senha <br>
        <input type="password" name="movie_password">
      </label> <br> <br>
      <button type="submit">Acessar</button>
    </form>
    <?php
    //header('WWW-Authenticate: Basic realm="Acesso restrito"');
    //header('HTTP/1.0 401 Unauthorized');
    exit;
  }
}

$movie_titulo_original = get_field('titulo_original');
$movie_id = get_field('movie_id');
$movie_dir = get_field('movie_dir');
$movie_resume = get_field('movie_resume');
$movie_info = get_field('movie_info');
$movie_img_cover = get_field('movie_img_cover');
$movie_img_banner = get_field('hero_banner');
$movie_classification = get_field('movie_classification');
$movie_title_english = get_field('movie_title_english');
$trailer = get_field('trailer');
$genero = get_field('genero');
$cor = get_field('cor');
$duracao = get_field('movie_duration');
$estado = get_field('estado_de_producao');
$ano = get_field('ano_de_finalizacao');
$classificacao = get_field('movie_classification');
$direcao = get_field('direcao');
$producao = get_field('producao');
$roteiro = get_field('roteiro');
$montagem = get_field('montagem');
$fotografia = get_field('fotografia');
$direcaoArte = get_field('direcao_de_arte');
$som = get_field('som');
$empresaProdutora = get_field('empresa_produtora');
$empresasCoProdutoras = get_field('empresas_co_produtoras');
$distribuidora = get_field('distribuidora');
$elenco = get_field('elenco');
$descricao_direcao = get_field('descricao_direcao');
$formato = get_field('formato');
$informacoes_adicionais = get_field('informacoes_adicionais');

$heading_title = "<h1>" . get_the_title() . "</h1>";
$heading_title .= "<strong>" . implode(', ', [$estado, $duracao . ' MIN']) . "</strong>";

$movie_img = "";
if ($movie_img_banner) {
  $movie_img = wp_get_attachment_image_url($movie_img_banner, 'hero_banner');
} elseif ($movie_img_cover) {
  $movie_img = wp_get_attachment_image_url($movie_img_cover['id'], 'hero_banner');
}

// Programation
$prog_items = get_posts([
  'post_type' => 'prog',
  'meta_query' => [
    [
      'key' => 'prog_movie_session',
      'value' => '"' . get_the_ID() . '"',
      'compare' => 'like'
    ]
  ],
  'showposts' => '-1',
  'order' => 'ASC',
  'orderby' => 'meta_value',
  'meta_key' => 'prog_start',
  'meta_type' => 'DATETIME',
]);

$prog_per_days = [];
foreach ($prog_items as $prog) {
  $start = get_field('prog_start', $prog);
  $start_date_time = DateTime::createFromFormat('d/m/Y H:i:s', $start);
  $prog_per_days[$start_date_time->format('Ymd')]['items'][] = $prog;
  $prog_per_days[$start_date_time->format('Ymd')]['start_date_time'] = $start_date_time;
}
ksort($prog_per_days);

get_header();

$player_show_start_date_time = get_field('player_show_start_date_time');
if ($player_show_start_date_time) {
  $player_show_start_date_time = DateTime::createFromFormat('d/m/Y H:i:s', $player_show_start_date_time);
}

$player_show_end_date_time = get_field('player_show_end_date_time');
if ($player_show_end_date_time) {
  $player_show_end_date_time = DateTime::createFromFormat('d/m/Y H:i:s', $player_show_end_date_time);
}

$available = "";
if ($player_show_start_date_time && $player_show_end_date_time) {
  $available = $player_show_start_date_time->format('d/m \à\s H:i\h');
  $available .= " até ";
  $available .= $player_show_end_date_time->format('d/m \à\s H:i\h');
}

$playerObj = new UP_Player();

$tags = [];

if (!empty($genero)) {
  $tags[] = $genero;
}

if (!empty($cor)) {
  $tags[] = $cor;
}

if (!empty($formato)) {
  $tags[] = $formato;
}

if (!empty($ano)) {
  $tags[] = $ano;
}

$availableSection = "";
if ($available) {
  $availableSection = '<div class="info online">
                <div class="info-item">
                  <p class="title">
                    <strong>Assista online</strong>
                  </p>
                  <div>
                    <p>
                      <i class="icon-mdi-calendar"></i>
                      Sinal Disponível: ' . $available . '
                    </p>
                  </div>
                </div>
              </div>';
}

?>
  <div class="main-container container container-1216 flex-col">
    <?php
    get_template_part('partials/heading', '', [
      'heading_title' => $heading_title,
      'hero-banner' => $movie_img
    ]);
    ?>
    <div class="main-content">
      <div class="container container-small content flex-col !px-0">
        <div class="movie-details">
          <div class="content">
            <div class="section">
              <?php if ($movie_titulo_original): ?>
                <h3><?php echo $movie_titulo_original ?></h3>
              <?php endif; ?>

              <?php if (!empty($tags)): ?>
                <p class="tags">
                  <?php foreach ($tags as $tag): ?>
                    <span class="tag black"><?php echo $tag ?></span>
                  <?php endforeach; ?>
                </p>
              <?php endif; ?>

              <h3><?php echo __('Sinopse', 'up') ?></h3>
              <?php the_content(); ?>
              <?php if (!empty($direcao)): ?>
                <p><strong><?php echo __('Direção', 'up') ?>:</strong> <?php echo $direcao ?></p>
              <?php endif; ?>
              <?php if (!empty($classificacao)): ?>
                <p><strong><?php echo __('Classificação', 'up') ?>:</strong> <?php echo $classificacao ?></p>
              <?php endif; ?>
            </div>

            <?php if (!empty($descricao_direcao)): ?>
              <div class="section">
                <h3><?php echo __('Direção', 'up') ?></h3>
                <?php foreach ($descricao_direcao as $dir):
                  $dir_nome = $dir['nome'];
                  $dir_desc = $dir['descricao'];
                  $dir_img = $dir['imagem'];
                  ?>
                  <div class="direction">
                    <?php if (!empty($dir_img['ID'])): ?>
                      <figure><?php echo wp_get_attachment_image($dir_img['ID'], 'movie_dir') ?></figure>
                    <?php endif; ?>
                    <div>
                      <p><strong><?php echo $dir_nome ?></strong></p>
                      <?php echo $dir_desc ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if ($playerObj->isPlayerOpen(get_the_ID())): ?>
              <div class="section prog flex flex-col">
                <h3><?php echo __('Assista', 'up') ?></h3>

                <?php if ($available) {
                  echo $availableSection;
                } ?>

                <div class="main-player expanded">
                  <div class="container">
                    <?php echo $playerObj->player(get_the_ID()) ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php if (!empty($prog_per_days) || ($available && !$playerObj->isPlayerOpen(get_the_ID()))):
              $up_general_prog = get_field('up_general_prog', 'option');
              ?>
              <div class="section prog flex flex-col">
                <h3><?php echo __('Programação', 'up') ?></h3>

                <?php if ($available && !$playerObj->isPlayerOpen(get_the_ID())) {
                  echo $availableSection;
                } ?>

                <?php if (!empty($prog_per_days)): ?>
                  <div class="info">
                    <?php foreach ($prog_per_days as $day => $prog_items) {
                      $current_date = $day;
                      foreach ($prog_items['items'] as $prog) :
                        $prog_start = get_field('prog_start', $prog);
                        $prog_date = get_field('prog_date', $prog);
                        $prog_label = get_field('prog_label', $prog);
                        $prog_type = get_field('prog_type', $prog);

                        $prog_place = get_the_terms($prog->ID, 'cat_place');
                        $prog_place = $prog_place ? join(', ', wp_list_pluck($prog_place, 'name')) : '';

                        $prog_real_date = $prog_date;
                        if (!$prog_real_date) {
                          $prog_start_date_time_obj = DateTime::createFromFormat('d/m/Y H:i:s', $prog_start);
                          $prog_real_date = date_i18n('d/m \| l \| H:i', $prog_start_date_time_obj->getTimestamp());
                          $prog_real_date = str_replace('-feira', '', $prog_real_date);
                        }

                        $prog_real_label = $prog_label;
                        if (!$prog_real_label) {
                          $prog_real_label = $prog_type->name;
                        } ?>
                        <div class="info-item">
                          <p class="title"><strong><?php echo $prog_real_label ?></strong></p>
                          <div>
                            <p><i class="icon-mdi-calendar"></i><?php echo $prog_real_date ?></p>
                            <p><i class="icon-pin-fill"></i><?php echo $prog_place ?></p>
                          </div>
                        </div>
                      <?php endforeach;
                    } ?>
                  </div>
                <?php endif; ?>

                <?php if ($up_general_prog): ?>
                  <a href="<?php echo $up_general_prog ?>" class="btn btn-red">
                    <?php echo __('Ver Programação Completa', 'up') ?>
                  </a>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($trailer) && preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $trailer, $trailer_match)) {
              $trailer_id = $trailer_match[1];
              if ($trailer_id): ?>
                <div class="section trailer">
                  <h3>Trailer</h3>
                  <div class="trailer-wrapper">
                    <iframe id="ytplayer" type="text/html" width="100%" height="480"
                            src="https://www.youtube.com/embed/<?php echo $trailer_id ?>?autoplay=1&origin=https://mostratiradentes.com.br"
                            frameborder="0"></iframe>
                  </div>
                </div>
              <?php endif;
            } ?>

            <div class="section credits">
              <?php if (!empty($movie_img_cover['ID'])): ?>
                <figure class="poster">
                  <?php echo wp_get_attachment_image($movie_img_cover['ID'], 'movie_poster'); ?>
                </figure>
              <?php endif; ?>

              <div>
                <h3>Créditos</h3>
                <div class="credits-wrapper">
                  <?php if (!empty($producao)): ?><p><strong><?php echo __('Produção', 'up') ?>
                    :</strong> <?php echo $producao ?></p><?php endif; ?>
                  <?php if (!empty($roteiro)): ?><p><strong><?php echo __('Roteiro', 'up') ?>
                    :</strong> <?php echo $roteiro ?></p><?php endif; ?>
                  <?php if (!empty($montagem)): ?><p><strong><?php echo __('Montagem', 'up') ?>
                    :</strong> <?php echo $montagem ?></p><?php endif; ?>
                  <?php if (!empty($fotografia)): ?><p><strong><?php echo __('Fotografia', 'up') ?>
                    :</strong> <?php echo $fotografia ?></p><?php endif; ?>
                  <?php if (!empty($direcaoArte)): ?><p><strong><?php echo __('Direção de Arte', 'up') ?>
                    :</strong> <?php echo $direcaoArte ?></p><?php endif; ?>
                  <?php if (!empty($som)): ?><p><strong><?php echo __('Som', 'up') ?>:</strong> <?php echo $som ?>
                    </p><?php endif; ?>
                  <?php if (!empty($empresaProdutora)): ?><p><strong><?php echo __('Empresa produtora', 'up') ?>
                    :</strong> <?php echo $empresaProdutora ?></p><?php endif; ?>
                  <?php if (!empty($empresasCoProdutoras)): ?><p>
                    <strong><?php echo __('Empresa(s) coprodutora(s)', 'up') ?>
                      :</strong> <?php echo $empresasCoProdutoras ?></p><?php endif; ?>
                  <?php if (!empty($distribuidora)): ?><p><strong><?php echo __('Distribuidora', 'up') ?>
                    :</strong> <?php echo $distribuidora ?></p><?php endif; ?>
                  <?php if (!empty($informacoes_adicionais)) {
                    echo $informacoes_adicionais;
                  } ?>
                  <?php if (!empty($elenco)): ?><p><strong><?php echo __('Elenco', 'up') ?>
                    :</strong> <?php echo $elenco ?></p><?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
get_footer();
