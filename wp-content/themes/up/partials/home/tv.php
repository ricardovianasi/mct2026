<?php
$up_home_tv = get_field('up_home_tv', 'option');

$homeTvItems = [];

if ($up_home_tv && !empty($up_home_tv['up_home_tv_enabled'])) {
  $homeTvItems = get_posts([
    'post_type' => 'tv',
    'numberposts' => 3,
    'suppress_filters' => 0
  ]);

  $homeTvTitle = !empty($up_home_tv['up_home_tv_title']) ? $up_home_tv['up_home_tv_title'] : _('TV MOSTRA', 'up');
  $homeBtnLabel = !empty($up_home_tv['up_home_tv_btn']) ? $up_home_tv['up_home_tv_btn'] : _('Acompanhe nosso canal no YouTube', 'up');
}

$homeTvItemsRendered = [];
foreach ($homeTvItems as $tv) {
  $tvLink = get_field('tv_link', $tv);
  $tvCover = get_field('tv_cover', $tv);

  $homeTvItemsRendered[] = '
    <div class="tv-item">
      <a rel="noopener" target="_blank" href="' . $tvLink . '" data-fancybox>
        <figure class="tv-figure">
        ' . wp_get_attachment_image($tvCover['ID'], 'tv') . '
        <span class="circle-play"><i class="icon-play1"></i></span>
        </figure>
        <span class="content">
          <span class="tv-title">' . get_the_title($tv) . '</span>
          <span class="btn-short-cta">
            <i class="icon-arrow-right"></i>
          </span>
        </span>
      </a>
    </div>';
}

if ($homeTvItemsRendered): ?>
  <div class="tv">
    <h2 class="home-title">
      <?php echo $homeTvTitle; ?></h2>
    <div class="tv-wrapper flex">
      <div class="left"><?php
        echo $homeTvItemsRendered[0];
        unset($homeTvItemsRendered[0]);
        ?></div>
      <div class="right">
        <?php echo implode('', $homeTvItemsRendered); ?>
        <a class="btn-white with-decorator red" rel="noopener" target="_blank"
           href="http://www.youtube.com/user/universoproducao?sub_confirmation=1">
          <?php echo $homeBtnLabel ?></a>
      </div>
      <div class="id">
        <svg
          width="1920"
          height="396"
          viewBox="0 0 1920 396"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g clip-path="url(#clip0_4165_103)">
            <path
              d="M-93 183.505C-69.6667 128.171 41.4 19.7047 299 28.5047C621 39.5047 613.5 180.005 920.5 320.505C1227.5 461.005 1394 213.005 1575 209.505C1756 206.005 2029.5 431.005 2048 520.005"
              stroke="#E32F46"
              stroke-width="20"
            />
          </g>
          <defs>
            <clipPath id="clip0_4165_103">
              <rect width="1920" height="396" fill="white" />
            </clipPath>
          </defs>
        </svg>
      </div>
    </div>
  </div>
<?php endif;
