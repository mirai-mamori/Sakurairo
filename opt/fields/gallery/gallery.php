<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: gallery
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_gallery' ) ) {
  class CSF_Field_gallery extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'add_title'   => esc_html__( 'Add Gallery', 'csf' ),
        'edit_title'  => esc_html__( 'Edit Gallery', 'csf' ),
        'clear_title' => esc_html__( 'Clear', 'csf' ),
      ) );

      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo $this->field_before();

      echo '<ul>';
      if ( ! empty( $this->value ) ) {

        $values = explode( ',', $this->value );

        foreach ( $values as $id ) {
          $attachment = wp_get_attachment_image_src( $id, 'thumbnail' );
          echo '<li><img src="'. esc_url( $attachment[0] ) .'" /></li>';
        }

      }
      echo '</ul>';

      echo '<a href="#" class="button button-primary csf-button">'. $args['add_title'] .'</a>';
      echo '<a href="#" class="button csf-edit-gallery'. esc_attr( $hidden ) .'">'. $args['edit_title'] .'</a>';
      echo '<a href="#" class="button csf-warning-primary csf-clear-gallery'. esc_attr( $hidden ) .'">'. $args['clear_title'] .'</a>';
      echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .'/>';

      echo $this->field_after();

    }

  }
}
