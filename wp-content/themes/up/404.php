<?php get_header(); ?>
  <div class="main-container container flex-col">
    <div class="main-content">
      <div class="container container-small content flex-col">
        <section class="error-404 not-found">
          <h1>Página não encontrada</h1>
          <p>Desculpe, a página que você procura não existe ou foi removida.</p>
          <a class="btn-red mt-8" href="<?php echo home_url(); ?>">Voltar para a página inicial</a>
        </section>
      </div>
    </div>
  </div>
<?php get_footer();
