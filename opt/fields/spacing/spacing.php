<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: spacing
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_spacing' ) ) {
  class CSF_Field_spacing extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'top_icon'           => '<i class="fas fa-long-arrow-alt-up"></i>',
        'right_icon'         => '<i class="fas fa-long-arrow-alt-right"></i>',
        'bottom_icon'        => '<i class="fas fa-long-arrow-alt-down"></i>',
        'left_icon'          => '<i class="fas fa-long-arrow-alt-left"></i>',
        'all_icon'           => '<i class="fas fa-arrows-alt"></i>',
        'top_placeholder'    => esc_html__( 'top', 'csf' ),
        'right_placeholder'  => esc_html__( 'right', 'csf' ),
        'bottom_placeholder' => esc_html__( 'bottom', 'csf' ),
        'left_placeholder'   => esc_html__( 'left', 'csf' ),
        'all_placeholder'    => esc_html__( 'all', 'csf' ),
        'top'                => true,
        'left'               => true,
        'bottom'             => true,
        'right'              => true,
        'unit'               => true,
        'show_units'         => true,
        'all'                => false,
        'units'              => array( 'px', '%', 'em' )
      ) );

      $default_values = array(
        'top'    => '',
        'right'  => '',
        'bottom' => '',
        'left'   => '',
        'all'    => '',
        'unit'   => 'px',
      );

      $value   = wp_parse_args( $this->value, $default_values );
      $unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
      $is_unit = ( ! empty( $unit ) ) ? ' csf--is-unit' : '';

      echo $this->field_before();

      echo '<div class="csf--inputs">';

      if ( ! empty( $args['all'] ) ) {

        $placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="'. esc_attr( $args['all_placeholder'] ) .'"' : '';

        echo '<div class="csf--input">';
        echo ( ! empty( $args['all_icon'] ) ) ? '<span class="csf--label csf--icon">'. $args['all_icon'] .'</span>' : '';
        echo '<input type="number" name="'. esc_attr( $this->field_name( '[all]' ) ) .'" value="'. esc_attr( $value['all'] ) .'"'. $placeholder .' class="csf-input-number'. esc_attr( $is_unit ) .'" step="any" />';
        echo ( $unit ) ? '<span class="csf--label csf--unit">'. esc_attr( $args['units'][0] ) .'</span>' : '';
        echo '</div>';

      } else {

        $properties = array();

        foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
          if ( ! empty( $args[$prop] ) ) {
            $properties[] = $prop;
          }
        }

        $properties = ( $properties === array( 'right', 'left' ) ) ? array_reverse( $properties ) : $properties;

        foreach ( $properties as $property ) {

          $placeholder = ( ! empty( $args[$property.'_placeholder'] ) ) ? ' placeholder="'. esc_attr( $args[$property.'_placeholder'] ) .'"' : '';

          echo '<div class="csf--input">';
          echo ( ! empty( $args[$property.'_icon'] ) ) ? '<span class="csf--label csf--icon">'. $args[$property.'_icon'] .'</span>' : '';
          echo '<input type="number" name="'. esc_attr( $this->field_name( '['. $property .']' ) ) .'" value="'. esc_attr( $value[$property] ) .'"'. $placeholder .' class="csf-input-number'. esc_attr( $is_unit ) .'" step="any" />';
          echo ( $unit ) ? '<span class="csf--label csf--unit">'. esc_attr( $args['units'][0] ) .'</span>' : '';
          echo '</div>';

        }

      }

      if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
        echo '<div class="csf--input">';
        echo '<select name="'. esc_attr( $this->field_name( '[unit]' ) ) .'">';
        foreach ( $args['units'] as $unit ) {
          $selected = ( $value['unit'] === $unit ) ? ' selected' : '';
          echo '<option value="'. esc_attr( $unit ) .'"'. esc_attr( $selected ) .'>'. esc_attr( $unit ) .'</option>';
        }
        echo '</select>';
        echo '</div>';
      }

      echo '</div>';

      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';

      $mode = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'padding';
      $mode = ( $mode === 'relative' || $mode === 'absolute' || $mode === 'none' ) ? '' : $mode;
      $mode = ( ! empty( $mode ) ) ? $mode .'-' : '';

      if ( ! empty( $this->field['all'] ) && isset( $this->value['all'] ) && $this->value['all'] !== '' ) {

        $output  = $element .'{';
        $output .= $mode .'top:'.    $this->value['all'] . $unit . $important .';';
        $output .= $mode .'right:'.  $this->value['all'] . $unit . $important .';';
        $output .= $mode .'bottom:'. $this->value['all'] . $unit . $important .';';
        $output .= $mode .'left:'.   $this->value['all'] . $unit . $important .';';
        $output .= '}';

      } else {

        $top     = ( isset( $this->value['top']    ) && $this->value['top']    !== '' ) ?  $mode .'top:'.    $this->value['top']    . $unit . $important .';' : '';
        $right   = ( isset( $this->value['right']  ) && $this->value['right']  !== '' ) ?  $mode .'right:'.  $this->value['right']  . $unit . $important .';' : '';
        $bottom  = ( isset( $this->value['bottom'] ) && $this->value['bottom'] !== '' ) ?  $mode .'bottom:'. $this->value['bottom'] . $unit . $important .';' : '';
        $left    = ( isset( $this->value['left']   ) && $this->value['left']   !== '' ) ?  $mode .'left:'.   $this->value['left']   . $unit . $important .';' : '';

        if ( $top !== '' || $right !== '' || $bottom !== '' || $left !== '' ) {
          $output = $element .'{'. $top . $right . $bottom . $left .'}';
        }

      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
