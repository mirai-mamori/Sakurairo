<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: date
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_date' ) ) {
  class CSF_Field_date extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $default_settings = array(
        'dateFormat' => 'mm/dd/yy',
      );

      $settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : array();
      $settings = wp_parse_args( $settings, $default_settings );

      echo $this->field_before();

      if ( ! empty( $this->field['from_to'] ) ) {

        $args = wp_parse_args( $this->field, array(
          'text_from' => esc_html__( 'From', 'sakurairo_csf' ),
          'text_to'   => esc_html__( 'To', 'sakurairo_csf' ),
        ) );

        $value = wp_parse_args( $this->value, array(
          'from' => '',
          'to'   => '',
        ) );

        echo '<label class="csf--from">'. esc_attr( $args['text_from'] ) .' <input type="text" name="'. esc_attr( $this->field_name( '[from]' ) ) .'" value="'. esc_attr( $value['from'] ) .'"'. $this->field_attributes() .'/></label>';
        echo '<label class="csf--to">'. esc_attr( $args['text_to'] ) .' <input type="text" name="'. esc_attr( $this->field_name( '[to]' ) ) .'" value="'. esc_attr( $value['to'] ) .'"'. $this->field_attributes() .'/></label>';

      } else {

        echo '<input type="text" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .'/>';

      }

      echo '<div class="csf-date-settings" data-settings="'. esc_attr( json_encode( $settings ) ) .'"></div>';

      echo $this->field_after();

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
        wp_enqueue_script( 'jquery-ui-datepicker' );
      }

    }

  }
}
