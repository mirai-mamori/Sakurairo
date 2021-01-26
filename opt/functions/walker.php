<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Custom Walker for Nav Menu Edit
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Walker_Nav_Menu_Edit' ) && class_exists( 'Walker_Nav_Menu_Edit' ) ) {
  class CSF_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

      $html = '';

      parent::start_el( $html, $item, $depth, $args, $id );

      ob_start();
      do_action( 'wp_nav_menu_item_custom_fields', $item->ID, $item, $depth, $args );
      $custom_fields = ob_get_clean();

      $output .= preg_replace( '/(?=<(fieldset|p)[^>]+class="[^"]*field-move)/', $custom_fields, $html );

    }

  }
}
