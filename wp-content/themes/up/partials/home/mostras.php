<?php
$up_home_mostra = get_field('up_home_mostra', 'option');
if (!empty($up_home_mostra['up_home_mostra_enabled'])):
  $up_home_mostra_title = $up_home_mostra['up_home_mostra_title'] ?? __('MOSTRAS', 'up');
  $up_home_mostra_items = $up_home_mostra['up_home_mostra_items'] ?? [];
  $movie = Movie::getInstance();
  $movie->setType('list');
  ?>

  <div class="category">
    <div class="container container-big flex-col">
      <h2 class="home-title"><?php echo $up_home_mostra_title ?></h2>
      <div class="category-wrapper">
        <div class="category-names">
          <div class="swiper">
            <div class="swiper-wrapper">
              <?php foreach ($up_home_mostra_items as $mostra):
                $mostra_title = $mostra['title'] ?? '';
                ?>
                <div class="swiper-slide category-name">
                  <a href="javascript:;"><?php echo $mostra_title ?></a></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="category-items">
          <div class="swiper">
              <div class="swiper-wrapper">
                <?php foreach ($up_home_mostra_items as $mostra):
                  $mostra_title = $mostra['title'] ?? '';
                  $mostra_description = $mostra['description'] ?? '';
                  $mostra_link = $mostra['link'] ?? '';
                  $mostra_movies = $mostra['movies'] ?? [];
                  ?>
                  <div class="swiper-slide category-item">
                    <div class="flex">
                      <div class="category-desc">
                        <?php echo $mostra_description ?>
                        <div class="category-item-readmore">
                          <?php if ($mostra_link): ?>
                            <a class="btn-dark with-decorator red" href="<?php echo $mostra_link ?>">
                              <?php echo __('Ver Filmes', 'up') ?></a>
                          <?php endif; ?>
                        </div>
                      </div>
                      <div class="category-movies">
                        <div class="movie movie-list">
                          <div class="movie-items">
                            <?php foreach ($mostra_movies as $m) {
                              echo $movie::single($m);
                            } ?>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <div class="slider-navigation flex gap-4">
              <button class="slider-button-prev">
                <i class="icon-arrow-left-2"></i>
              </button>
              <button class="slider-button-next">
                <i class="icon-arrow-right-2"></i>
              </button>
            </div>
        </div>
      </div>
    </div>
  </div>

<?php endif;
