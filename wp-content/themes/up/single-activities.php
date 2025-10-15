<?php
global $post;
$activity_type = get_field('presentation_type');
switch ($activity_type) {
  case 'oficina':
    get_template_part('partials/workshop');
    break;
  case 'debate':
  default:
    get_template_part('partials/debate');
    break;
}
