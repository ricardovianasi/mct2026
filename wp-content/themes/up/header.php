<?php
global $post;

$body_classes = [];
if (is_home()) {
  $body_classes[] = 'home';
}

if ($post && $post->post_type) {
  $body_classes[] = $post->post_type . '-post-type';
}

$up_general_header_cta = get_field('up_general_header_cta', 'option');
$up_general_header_cta_label = $up_general_header_cta['label'] ?? false;
$up_general_header_cta_url = $up_general_header_cta['url'] ?? false;

?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <?php wp_head(); ?>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-10028533-3"></script>
  <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
          dataLayer.push(arguments);
      }

      gtag('js', new Date());

      gtag('config', 'UA-10028533-3');
  </script>

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/assets/dist/styles/vendor.css">
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/assets/dist/styles/main.css?v=50">
</head>
<body class="<?php echo implode(' ', $body_classes) ?>">
<header class="header">
  <div class="header-top">
    <div class="container big">
      <div>
<!--        <p>-->
<!--          <strong>Ministério da Cultura e Governo de Minas Gerais</strong> apresentam:-->
<!--        </p>-->
      </div>
      <div><h1><a href="/" title="29ª Mostra de Cinema de Tiradentes"><span class="icon-mct-2026"></span></a></h1></div>
      <div>
        <p>
          <strong>23 - 31 JAN 2026</strong> <br>
          <strong>PROGRAMAÇÃO GRATUITA</strong>
        </p>
      </div>
    </div>
  </div>
  <button class="menu-toggle hamburger-menu">
      <span class="hamburger">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
      </span>
  </button>
  <div class="header-nav">
    <div class="container big">
      <button class="menu-toggle hamburger-menu">
        <span class="hamburger">
          <span class="line"></span>
          <span class="line"></span>
          <span class="line"></span>
        </span>
      </button>
      <div class="hamburger" id="menubtn" aria-label="Clique para abrir o menu" role="button">
        <span class="wrapper">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </span>
      </div>
      <nav>
        <?php if (has_nav_menu('main-menu')) {
          wp_nav_menu([
            'theme_location' => 'main-menu',
            'menu' => '',
            'container' => FALSE,
            'container_class' => 'text-neutral-100',
            'container_id' => '',
            'menu_class' => 'mainmenu',
            'menu_id' => 'mainmenu',
            'echo' => true,
            'fallback_cb' => 'wp_page_menu',
            'before' => '',
            'after' => '',
            'link_before' => '<span>',
            'link_after' => '</span>',
            'depth' => 3,
            'walker' => new Custom_Walker_Nav_Menu
          ]);
        } ?>
      </nav>
    </div>
  </div>
</header>
<main class="main">
