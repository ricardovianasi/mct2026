<?php
/* Custom menu */
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

  function start_lvl(&$output, $depth = 0, $args = null ) {
    $indent = str_repeat("\t", $depth);
    //$output .= "\n$indent<ul class=\"sub-menu\">\n";

    // Change sub-menu to dropdown menu
    $output .= "\n$indent<ul class=\"sub-menu\">\n";
  }
  function start_el ( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    // Most of this code is copied from original Walker_Nav_Menu
    global $wp_query, $wpdb;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

    $class_names = $value = '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = ' class="' . esc_attr( $class_names ) . '"';

    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

    global $wpdb;
    $has_children = $wpdb->get_var("SELECT COUNT(meta_id)
                            FROM ".$wpdb->prefix."postmeta
                            WHERE meta_key='_menu_item_menu_item_parent'
                            AND meta_value='".$item->ID."'");

    $output .= $indent . '<li' . $id . $value . $class_names .'>';

    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';


    // Check if menu item is in main menu
    if ( $depth == 0 && $has_children > 0  ) {
      // These lines adds your custom class and attribute
      $attributes .= ' class="dropdown-toggle" href="javascript:;"';
      $attributes .= ' data-toggle="dropdown"';

    } else {
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url ) .'"' : '';
    }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
//    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    $item_output .= '<span data-text="'.$item->title.'">'.apply_filters( 'the_title', $item->title, $item->ID ).'</span>';

    // Add the caret if menu level is 0
    if ( $depth == 0 && $has_children > 0  ) {
      $item_output .= ' <span class="submenu-link"><b class="icon-chevron-down"></b></span>';
    }

    $item_output .= '</a>';

    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}
