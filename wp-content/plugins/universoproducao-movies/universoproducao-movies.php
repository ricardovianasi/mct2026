<?php
/*
Plugin Name: Universo Produção - Filmes
Plugin URI:
Description: This plugin imports movies from Universo Produção ADM System and provides help functions
Author: Ricardo Viana
Author URI: #
Author Email: ricardovianasi@gmail.com
Version: 1.0
License: GPLv2 or later
Text Domain:
*/

if (!function_exists('add_action')) {
  exit;
}

function UPMovies_add_management_page()
{
  add_management_page(
    "Universo Produção - Inportações",
    "UP - Inportações",
    "manage_options",
    basename(__FILE__), "UPMovies_management_page");
}

function UPMovies_conncet_DB()
{
  global $up_database;
  
  if (!$up_database) {
    $up_database = new wpdb('ricardo', 'ag51www', 'universoproducao_novo', '162.243.253.29');
  }
  
  return $up_database;
}

function UPCluster_conncet_DB()
{
  global $upcluster_database;
  
  if (!$upcluster_database) {
    $upcluster_database = new wpdb('doadmin', 'H3Dyfq33QuZX9FML', 'wp-cinebh2023-prod', 'db-mysql-nyc3-58545-do-user-1644160-0.b.db.ondigitalocean.com:25060');
  }
  
  return $upcluster_database;
}


function last_site_conncet_DB()
{
  global $lastsite_database;
  
  if (!$lastsite_database) {
    $lastsite_database = new wpdb('doadmin', 'H3Dyfq33QuZX9FML', 'wp-mct-2023', 'db-mysql-nyc3-58545-do-user-1644160-0.b.db.ondigitalocean.com:25060');
  }
  
  return $lastsite_database;
}

function UPMovies_get_option($opt, $movide_id)
{
  
  global $up_database;
  
  $opt = $up_database->get_var('SELECT OPT.name
        FROM movie_option OPT
        INNER JOIN movie_has_options HAS_OPT ON HAS_OPT.movie_option_id = OPT.id
        INNER JOIN movie MOV ON MOV.id = HAS_OPT.movie_id
        WHERE OPT.`type` = "' . $opt . '" AND MOV.id =' . $movide_id);
  
  return $opt;
  
}

function UPMovies_get_movie_image($movie_id)
{
  global $up_database;
  
  if (!$up_database) UPMovies_conncet_DB();
  
  $image_filename = $up_database->get_var('SELECT M.src FROM movie_media M WHERE M.movie_id = ' . $movie_id);
  if (empty($image_filename)) {
    return "";
  }
  
  $f = str_split(substr($image_filename, 0, 4));
  $f = implode('/', $f) . '/';
  
  return 'http://universoproducao.com.br/repository/' . $f . $image_filename;
  
}

function UPMovies_management_page()
{
  
  if (isset($_POST['UP_movie_import_submit']) && !check_admin_referer('UP_movie_submit', 'UP_movie_nonce')) {
    echo '<div id="message" class="error fade">
			<p>
			<strong>ERRO - Por favor tente novamente. </strong>
			</p>
			</div>';
  } elseif (isset($_POST['UP_movie_import_submit'])) {
    
    UPMovies_conncet_DB();
    /**
     * @global  $up_database
     * @var wpdb
     */
    global $up_database;
    
    if (!$up_database->check_connection()) {
      echo '<div id="message" class="error fade">
			<p>
			    <strong>ERRO - Não foi possível conectar no banco de dados. </strong>
			</p>
			</div>';
    }
    
    
    if ($_POST['UP_movie_delete_all']) {
      
      echo "<p> <hr> Apagando os filmes existentes </p> ";
      
      $old_movies = get_posts(array(
        'post_type' => 'movie',
        'numberposts' => -1
      ));
      
      foreach ($old_movies as $old) {
        wp_delete_post($old->ID, true);
      }
      
      echo "<p> <hr> Todos os filmes foram apagados </p> ";
    }
    
    
    //Selecionar os filmes
    $movieFile = fopen(WP_CONTENT_DIR . '/csv/movie.csv', 'r');
    $cont = 0;
    while (($movie = fgetcsv($movieFile, 1000, ",")) !== false) {
      
      echo "<p> <hr>  " . $cont . "  Importando filme "  . $titulo . " </p> ";
      echo "<pre>";
      print_r($movie);
      echo "</pre>";
      
      [
        , // Carimbo de data/hora
        $mostra,
        $titulo,
        $direcao,
        $genero,
        $cor,
        $duracao,
        $estado,
        $ano,
        $roteiro,
        $producao,
        $montagem,
        $fotografia,
        $direcaoArte,
        $som,
        $empresaProdutora,
        $empresasCoProdutoras,
        $distribuidora,
        $elenco,
        $sinopse,
        $classificacao,// Classificação indicativa:
        $trailer,
        , // longa / curta
        , // email
        , //o filme contou com recursos
        , // Imagem de divulgação do filme para catálogo:
        , // Imagem horizontal do filme para site:
        , // Pôster / imagem vertical do filme para site:
        , // Autorização de exibição
        $miniBioDirecao,
        , // Foto de divulgação da direção:
      ] = $movie;
      
      
      $cont++;
      if ($cont == 1) {
        continue;
      }
      
      $new_post_data = array(
        'post_type' => 'movie',
        'post_title' => $titulo,
        'post_content' => '<p>'.$sinopse.'</p>',
        'post_status' => 'draft',
        'post_author' => 1
      );
      
      // Verifica se o filme já existe.
      $up_movie = get_posts([
        'post_status' => 'any',
        'post_type' => 'movie',
        'title' => $titulo
      ]);

      if($up_movie) {
	      $wp_movie = current($up_movie);
	      echo  "<p>Filme já existe - ID: ".$wp_movie->ID."</p>";
        continue;
      } else {
        $wp_movie = wp_insert_post($new_post_data, true);

        echo  "<p>Filme inserido - ID: ".$wp_movie."</p>";
      }

      if (!$wp_movie || $wp_movie instanceof WP_Error) {
        echo "<p style='color: red'> ERRO! Filme não importado. <br>  " . $wp_movie->get_error_messages() . "  </p> ";
      }
      
      // Mostra
      if (!empty($mostra)) {

        echo  "<p>Inserindo Mostra: ".$mostra."</p>";

        $mostra_slug = sanitize_title($mostra);
        $term = term_exists($mostra_slug, 'cat_mostra');
        if (!$term) {
          $term = wp_insert_term($mostra, 'cat_mostra', [
            'slug' => $mostra_slug,
          ]);
        }

        var_dump($term);

        if (!empty($term['term_id'])) {
          wp_set_object_terms($wp_movie, $mostra_slug, 'cat_mostra');
        }
      }

      // Titulo em Português
      update_field('titulo_em_portugues', $tituloEmPortugues, $wp_movie);

      //Duração
      update_field('movie_duration', $duracao, $wp_movie);
      
      // Classificação
      update_field('movie_classification', $classificacao, $wp_movie);
      
      // Trailer
      update_field('trailer', $trailer, $wp_movie);
      
      // Genero
      update_field('genero', $genero, $wp_movie);
      
      // Genero
      update_field('cor', $cor, $wp_movie);
      
      // Estado de Produção
      update_field('estado_de_producao', $estado, $wp_movie);
      
      // Producao
      update_field('producao', $producao, $wp_movie);
      
      // Roteiro
      update_field('roteiro', $roteiro, $wp_movie);
      
      // Montagem
      update_field('montagem', $montagem, $wp_movie);
      
      // Fotografia
      update_field('fotografia', $fotografia, $wp_movie);
      
      // Direção de Arte
      update_field('direcao_de_arte', $direcaoArte, $wp_movie);
      
      // Som
      update_field('som', $som, $wp_movie);
      
      // Empresa Produtora
      update_field('empresa_produtora', $empresaProdutora, $wp_movie);
      
      // Empresas Co Produtoras
      update_field('empresas_co_produtoras', $empresasCoProdutoras, $wp_movie);
      
      // Distribuidora
      update_field('distribuidora', $distribuidora, $wp_movie);
      
      // Elenco
      update_field('elenco', $elenco, $wp_movie);
      
      // ano_de_finalizacao
      update_field('ano_de_finalizacao', $ano, $wp_movie);
      
      // Direcao
      update_field('direcao', $direcao, $wp_movie);
      
      // Mini Bio
      update_field('descricao_direcao', [
        [
          'nome' => $direcao,
          'descricao' => '<p>'.$miniBioDirecao.'</p>'
        ]
      ], $wp_movie);
      
      // Formato
      //update_field('formato', $formato, $wp_movie);
      
      echo "<p> Filme importado com sucesso  </p> ";
    }
    fclose($movieFile);
  }
  
  if (isset($_POST['UP_preview_ed_import_submit']) && !check_admin_referer('UP_preview_ed_submit', 'UP_preview_ed_nonce')) {
    echo '<div id="message" class="error fade">
			<p>
			<strong>ERRO - Por favor tente novamente. </strong>
			</p>
			</div>';
  }
  elseif (isset($_POST['UP_preview_ed_import_submit'])) {
    
    UPMovies_conncet_DB();
    /**
     * @global  $up_database
     * @var wpdb
     */
    global $up_database;
    
    if (!$up_database->check_connection()) {
      echo '<div id="message" class="error fade">
			<p>
			    <strong>ERRO - Não foi possível conectar no banco de dados. </strong>
			</p>
			</div>';
    }
    
    $type = $_POST['UP_preview_ed_type'];
    
    //Selecionar as edições
    $editions = $up_database->get_results("SELECT ED.*
            FROM event ED
            WHERE ED.`type` = '$type' ORDER BY ED.edition ASC;", ARRAY_A);
    
    if (!$editions) {
      echo '<div id="message" class="error fade">
			<p>
			    <strong>ERRO - Não foi possível localizar os filmes. </strong>
			</p>
			</div>';
    }
    
    $cont = 0;
    foreach ($editions as $ed) {
      $cont++;
      echo "<p> <hr>  " . $cont . "  Importando edição " . $ed['id'] . " | " . $ed['full_name'] . " </p> ";
      
      $new_post_data = array(
        'post_type' => 'edition',
        'post_title' => $ed['full_name'],
        'post_content' => $ed['description'],
        'post_status' => 'publish',
        'post_author' => 1
      );
      
      $wp_ed = wp_insert_post($new_post_data, true);
      
      if (!$wp_ed || $wp_ed instanceof WP_Error) {
        echo "<p style='color: #ff0000'> ERRO! Edição não importado. <br>  " . $wp_ed->get_error_messages() . "  </p> ";
      } else {
        //id
        update_field('edicao', (int)$ed['edition'], $wp_ed);
        echo "<p> Edição importada com sucesso  </p> ";
      }
    }
  }
  
  if (isset($_POST['UP_timeline_import_submit']) && !check_admin_referer('UP_timeline_submit', 'UP_timeline_nonce')) {
    echo '<div id="message" class="error fade">
			<p>
			<strong>ERRO - Por favor tente novamente. </strong>
			</p>
			</div>';
  } elseif (isset($_POST['UP_timeline_import_submit'])) {
    
    last_site_conncet_DB();
    
    /**
     * @global  $up_database
     * @var wpdb
     */
    global $lastsite_database;
    
    if (!$lastsite_database->check_connection()) {
      echo '<div id="message" class="error fade">
			<p>
			    <strong>ERRO - Não foi possível conectar no banco de dados. </strong>
			</p>
			</div>';
    }
    
    
    //Selecionar as edições
    $timeline = $lastsite_database->get_results(
        "SELECT * FROM wp_posts P where P.post_type = 'timeline'",
        ARRAY_A);
    
    if (!$timeline) {
      echo '<div id="message" class="error fade">
			<p>
			    <strong>ERRO - Não foi possível localizar os filmes. </strong>
			</p>
			</div>';
    }
    
    $cont = 0;
    foreach ($timeline as $ti) {
      $cont++;
      echo "<p> <hr>  " . $cont . "  Importando edição " . $ti['post_title'] . " </p> ";
      
      $new_post_data = array(
        'post_type' => 'timeline',
        'post_title' => $ti['post_title'],
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1
      );
  
      // Verifica se o filme já existe.
      $exists = get_posts([
        'post_status' => 'any',
        'post_type' => 'timeline',
        'meta_key' => 'timeline_year',
        'meta_value' => $ti['post_title']
      ]);
  
      if($exists) {
        $wp_timeline = current($exists);
      } else {
        $wp_timeline = wp_insert_post($new_post_data, true);
        update_field('timeline_year', $ti['post_title'], $wp_timeline);
      }
      
      if (!$wp_timeline || $wp_timeline instanceof WP_Error) {
        echo "<p style='color: #ff0000'> ERRO! Edição não importado. <br>  " . $wp_timeline->get_error_messages() . "  </p> ";
      } else {
        //Expo theme field
        $theme = $lastsite_database->get_var(
            "SELECT P.meta_value FROM wp_postmeta P where P.meta_key = 'timeline_slogan' and P.post_id = " . $ti['ID']);
        update_field('timeline_slogan', $theme, $wp_timeline);
        
        // Expo img
        $meta_img = get_field('timeline_image', $wp_timeline);
        
        var_dump($meta_img);
        
        if(!$meta_img) {
          $img_attach_id = $lastsite_database->get_var("(SELECT PM2.meta_value from wp_postmeta PM2 where PM2.meta_key = 'timeline_image' and PM2.post_id = ".$ti['ID'].")");
          
          $img = $lastsite_database->get_var(
            "select
              PM.meta_value
          from
              wp_postmeta PM
          where
              PM.post_id = $img_attach_id
              AND PM.meta_key = '_wp_attached_file';");
          
          $img_post_date = $lastsite_database->get_var( "SELECT P.post_modified from wp_posts P where P.ID = " . $img_attach_id );
          
          var_dump($img_post_date);
          
          $img_date = DateTime::createFromFormat('Y-m-d H:i:s', $img_post_date);
          $img_timestamp = $img_date->format('dHis');
          
          $img_end =  array_slice(explode('/', $img), -1)[0];
          $img_path_url = str_replace($img_end, $img_timestamp . '/' . $img_end, $img);
          
          $img_url = 'http://209.97.145.188/2023/wp-content/uploads/' . $img;
          var_dump($img_url);
          
          $media = media_sideload_image($img_url, $wp_timeline->ID, '', 'id');
          if ($media) {
            update_field( 'timeline_image', $media, $wp_timeline );
          }
        }
        
        // Gallery
        $meta_gallery = get_field('timeline_gallery', $wp_timeline);
        if(!$meta_gallery) {
          $gallery = $lastsite_database->get_var(
            "SELECT P.meta_value FROM wp_postmeta P where P.meta_key = 'timeline_gallery' and P.post_id = " . $ti['ID']);
          
          $unserialized = unserialize($gallery);
          $gallery_items = [];
          foreach ($unserialized as $attach_id) {
            $img = $lastsite_database->get_var(
              "select
              PM.meta_value
          from
              wp_postmeta PM
          where
              PM.post_id = $attach_id
              AND PM.meta_key = '_wp_attached_file';");
            
            var_dump($attach_id);
            
            $attach_post_date = $lastsite_database->get_var( "SELECT P.post_date from wp_posts P where P.ID = $attach_id" );
            $attach_date = DateTime::createFromFormat('Y-m-d H:i:s', $attach_post_date);
            $attach_timestamp = $attach_date->format('dHis');
            $attach_path_url = $attach_date->format('Y') . '/' . $attach_date->format('m');
            $attach_path_url = str_replace($attach_path_url, $attach_path_url . '/' . $attach_timestamp, $img);
            
            $img_end =  array_slice(explode('/', $img), -1)[0];
            $img_path_url = str_replace($img_end, $attach_timestamp . '/' . $img_end, $img);
            
            
            $img_url = 'http://209.97.145.188/2023/wp-content/uploads/' . $img;
            var_dump($img_url);
            $media = media_sideload_image($img_url, $wp_timeline->ID, '', 'id');
            if($media) {
              $gallery_items[] = $media;
            }
          }
          update_field( 'timeline_gallery', $gallery_items, $wp_timeline );
        }
        
        
        echo "<p> Edição importada com sucesso  </p> ";
      }

    }
  }
  
  ?>
  

  <div class="wrap">
    <h2>Importar Filmes</h2>
    <form method="post" action="tools.php?page=<?php echo basename(__FILE__); ?>">
      <?php wp_nonce_field('UP_movie_submit', 'UP_movie_nonce'); ?>

      <p>
        <strong>ATENÇÃO: A importação irá sobrescrever todas as alterações realizadas na base de filmes</strong>
      </p>

      <h3 style="margin-bottom:5px; margin-top: 30px"> Opções de importação </h3>

      <table class="form-table">
        <tr>
          <td><p style="line-height:20px;">
              <input name="UP_movie_delete_all" type="checkbox" id="UP_movie_delete_all" value="content"/>
              <label for="UP_movie_delete_all">
                <strong>
                  APAGAR TODOS OS FILMES
                </strong>
                - ******************** Todos os filmes existentes serão permanentemente excluídos ********************
              </label>
              <br/>
            </p></td>
        </tr>
      </table>

      <p>
        <input class="button-primary" name="UP_movie_import_submit" value="Importar filmes agora" type="submit"/>
      </p>
    </form>
  </div>

  <br>
  <br>
  <hr>
  <br>

  <div class="wrap">
    <h2>Importar edições antigas</h2>
    <form method="post" action="tools.php?page=<?php echo basename(__FILE__); ?>">
      <?php wp_nonce_field('UP_preview_ed_submit', 'UP_preview_ed_nonce'); ?>
      <h3 style="margin-bottom:5px; margin-top: 30px"> Opções de importação </h3>
      <table class="form-table">
        <tr>
          <td>
            <p style="line-height:20px;">
              <label for="UP_preview_ed_type">
                <strong>
                  Tipo do evento
                </strong>
              </label><br/>
              <select name="UP_preview_ed_type" required id="UP_preview_ed_type">
                <option value="">Selecione uma opção</option>
                <option value="mostratiradentes">Mostra Tiradentes</option>
                <option value="cineop">CineOP</option>
                <option value="cinebh">CineBH</option>
              </select>

            </p>
          </td>
        </tr>
      </table>
      <p>
        <input class="button-primary" name="UP_preview_ed_import_submit" value="Importar edições agora" type="submit"/>
      </p>
    </form>
  </div>

  <br>
  <br>
  <hr>
  <br>
  
  <div class="wrap">
    <h2>Importar Exposição</h2>
    <form method="post" action="tools.php?page=<?php echo basename(__FILE__); ?>">
      <?php wp_nonce_field('UP_timeline_submit', 'UP_timeline_nonce'); ?>
      <p>
        <input class="button-primary" name="UP_timeline_import_submit" value="Importar linha do tempo agora" type="submit"/>
      </p>
    </form>
  </div>
  
  <?php
}

add_action('admin_menu', 'UPMovies_add_management_page');
