<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: wp_editor
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_wp_editor' ) ) {
  class CSF_Field_wp_editor extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'tinymce'       => true,
        'quicktags'     => true,
        'media_buttons' => true,
        'wpautop'       => false,
        'height'        => '',
      ) );

      $attributes = array(
        'rows'         => 10,
        'class'        => 'wp-editor-area',
        'autocomplete' => 'off',
      );

      $editor_height = ( ! empty( $args['height'] ) ) ? ' style="height:'. esc_attr( $args['height'] ) .';"' : '';

      $editor_settings  = array(
        'tinymce'       => $args['tinymce'],
        'quicktags'     => $args['quicktags'],
        'media_buttons' => $args['media_buttons'],
        'wpautop'       => $args['wpautop'],
      );

      echo $this->field_before();

      echo ( csf_wp_editor_api() ) ? '<div class="csf-wp-editor" data-editor-settings="'. esc_attr( json_encode( $editor_settings ) ) .'">' : '';

      echo '<textarea name="'. esc_attr( $this->field_name() ) .'"'. $this->field_attributes( $attributes ) . $editor_height .'>'. $this->value .'</textarea>';

      echo ( csf_wp_editor_api() ) ? '</div>' : '';

      echo $this->field_after();

    }

    public function enqueue() {

      if ( csf_wp_editor_api() && function_exists( 'wp_enqueue_editor' ) ) {

        wp_enqueue_editor();

        $this->setup_wp_editor_settings();

        add_action( 'print_default_editor_scripts', array( &$this, 'setup_wp_editor_media_buttons' ) );

      }

    }

    // Setup wp editor media buttons
    public function setup_wp_editor_media_buttons() {

      ob_start();
      echo '<div class="wp-media-buttons">';
      do_action( 'media_buttons' );
      echo '</div>';
      $media_buttons = ob_get_clean();

      echo '<script type="text/javascript">';
      echo 'var csf_media_buttons = '. json_encode( $media_buttons ) .';';
      echo '</script>';

    }

    // Setup wp editor settings
    public function setup_wp_editor_settings() {

      if ( csf_wp_editor_api() && class_exists( '_WP_Editors') ) {

        $defaults = apply_filters( 'csf_wp_editor', array(
          'tinymce' => array(
            'wp_skip_init' => true
          ),
        ) );

        $setup = _WP_Editors::parse_settings( 'csf_wp_editor', $defaults );

        _WP_Editors::editor_settings( 'csf_wp_editor', $setup );

      }

    }

  }
}
