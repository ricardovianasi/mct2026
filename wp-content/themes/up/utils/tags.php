<?php
function render_tags($tag) {
  $tags = explode(';', $tag);

  foreach ($tags as $t) {
    echo '<div class="tag pink">'.trim($t).'</div>';
  }
}
