<?php

add_filter( 'acf/fields/wysiwyg/toolbars' , 'wysiwyg_toolbars'  );
function wysiwyg_toolbars($toolbars) {
  // Add a new toolbar called "Very Simple"
  // - this toolbar has only 1 row of buttons
  $toolbars['Very Simple' ] = array();
  $toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline' );

  // return $toolbars - IMPORTANT!
  return $toolbars;
}

if(function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'page_title' 	=> 'Configurações Gerais do Site',
    'menu_title'	=> 'Configurações Gerais',
    'menu_slug' 	=> 'up-general-settings',
    'capability'	=> 'edit_posts',
    'redirect'		=> false,
    'position'    => '10.1'
  ));

  acf_add_options_sub_page(array(
    'page_title' 	=> 'Página Inicial',
    'menu_title'	=> 'Página Inicial',
    'parent_slug'	=> 'up-general-settings',
    'menu_slug' 	=> 'up-general-settings-homepage',
  ));
}
