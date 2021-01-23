<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Fields Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Fields' ) ) {
  abstract class CSF_Fields extends CSF_Abstract {

    public function __construct( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {
      $this->field  = $field;
      $this->value  = $value;
      $this->unique = $unique;
      $this->where  = $where;
      $this->parent = $parent;
    }

    public function field_name( $nested_name = '' ) {

      $field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
      $unique_id  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $field_id .']' : $field_id;
      $field_name = ( ! empty( $this->field['name'] ) ) ? $this->field['name'] : $unique_id;
      $tag_prefix = ( ! empty( $this->field['tag_prefix'] ) ) ? $this->field['tag_prefix'] : '';

      if ( ! empty( $tag_prefix ) ) {
        $nested_name = str_replace( '[', '['. $tag_prefix, $nested_name );
      }

      return $field_name . $nested_name;

    }

    public function field_attributes( $custom_atts = array() ) {

      $field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
      $attributes = ( ! empty( $this->field['attributes'] ) ) ? $this->field['attributes'] : array();

      if ( ! empty( $field_id ) && empty( $attributes['data-depend-id'] ) ) {
        $attributes['data-depend-id'] = $field_id;
      }

      if ( ! empty( $this->field['placeholder'] ) ) {
        $attributes['placeholder'] = $this->field['placeholder'];
      }

      $attributes = wp_parse_args( $attributes, $custom_atts );

      $atts = '';

      if ( ! empty( $attributes ) ) {
        foreach ( $attributes as $key => $value ) {
          if ( $value === 'only-key' ) {
            $atts .= ' '. esc_attr( $key );
          } else {
            $atts .= ' '. esc_attr( $key ) . '="'. esc_attr( $value ) .'"';
          }
        }
      }

      return $atts;

    }

    public function field_before() {
      return ( ! empty( $this->field['before'] ) ) ? '<div class="csf-before-text">'. $this->field['before'] .'</div>' : '';
    }

    public function field_after() {

      $output  = ( ! empty( $this->field['after'] ) ) ? '<div class="csf-after-text">'. $this->field['after'] .'</div>' : '';
      $output .= ( ! empty( $this->field['desc'] ) ) ? '<div class="clear"></div><div class="csf-desc-text">'. $this->field['desc'] .'</div>' : '';
      $output .= ( ! empty( $this->field['help'] ) ) ? '<div class="csf-help"><span class="csf-help-text">'. $this->field['help'] .'</span><i class="fas fa-question-circle"></i></div>' : '';
      $output .= ( ! empty( $this->field['_error'] ) ) ? '<div class="csf-error-text">'. $this->field['_error'] .'</div>' : '';

      return $output;

    }

    public static function field_data( $type = '', $term = false, $query_args = array() ) {

      $options = array();
      $array_search = false;

      // sanitize type name
      if ( in_array( $type, array( 'page', 'pages' ) ) ) {
        $option = 'page';
      } else if ( in_array( $type, array( 'post', 'posts' ) ) ) {
        $option = 'post';
      } else if ( in_array( $type, array( 'category', 'categories' ) ) ) {
        $option = 'category';
      } else if ( in_array( $type, array( 'tag', 'tags' ) ) ) {
        $option = 'post_tag';
      } else if ( in_array( $type, array( 'menu', 'menus' ) ) ) {
        $option = 'nav_menu';
      } else {
        $option  = '';
      }

      // switch type
      switch( $type ) {

        case 'page':
        case 'pages':
        case 'post':
        case 'posts':

          // term query required for ajax select
          if ( ! empty( $term ) ) {

            $query             = new WP_Query( wp_parse_args( $query_args, array(
              's'              => $term,
              'post_type'      => $option,
              'post_status'    => 'publish',
              'posts_per_page' => 25,
            ) ) );

          } else {

            $query          = new WP_Query( wp_parse_args( $query_args, array(
              'post_type'   => $option,
              'post_status' => 'publish',
            ) ) );

          }

          if ( ! is_wp_error( $query ) && ! empty( $query->posts ) ) {
            foreach ( $query->posts as $item ) {
              $options[$item->ID] = $item->post_title;
            }
          }

        break;

        case 'category':
        case 'categories':
        case 'tag':
        case 'tags':
        case 'menu':
        case 'menus':

          if ( ! empty( $term ) ) {

            $query         = new WP_Term_Query( wp_parse_args( $query_args, array(
              'search'     => $term,
              'taxonomy'   => $option,
              'hide_empty' => false,
              'number'     => 25,
            ) ) );

          } else {

            $query         = new WP_Term_Query( wp_parse_args( $query_args, array(
              'taxonomy'   => $option,
              'hide_empty' => false,
            ) ) );

          }

          if ( ! is_wp_error( $query ) && ! empty( $query->terms ) ) {
            foreach ( $query->terms as $item ) {
              $options[$item->term_id] = $item->name;
            }
          }

        break;

        case 'user':
        case 'users':

          if ( ! empty( $term ) ) {

            $query      = new WP_User_Query( array(
              'search'  => '*'. $term .'*',
              'number'  => 25,
              'orderby' => 'title',
              'order'   => 'ASC',
              'fields'  => array( 'display_name', 'ID' )
            ) );

          } else {

            $query = new WP_User_Query( array( 'fields' => array( 'display_name', 'ID' ) ) );

          }

          if ( ! is_wp_error( $query ) && ! empty( $query->get_results() ) ) {
            foreach ( $query->get_results() as $item ) {
              $options[$item->ID] = $item->display_name;
            }
          }

        break;

        case 'sidebar':
        case 'sidebars':

          global $wp_registered_sidebars;

          if ( ! empty( $wp_registered_sidebars ) ) {
            foreach ( $wp_registered_sidebars as $sidebar ) {
              $options[$sidebar['id']] = $sidebar['name'];
            }
          }

          $array_search = true;

        break;

        case 'role':
        case 'roles':

          global $wp_roles;

          if ( ! empty( $wp_roles ) ) {
            if ( ! empty( $wp_roles->roles ) ) {
              foreach ( $wp_roles->roles as $role_key => $role_value ) {
                $options[$role_key] = $role_value['name'];
              }
            }
          }

          $array_search = true;

        break;

        case 'post_type':
        case 'post_types':

          $post_types = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );

          if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
            foreach ( $post_types as $post_type ) {
              $options[$post_type->name] = $post_type->labels->name;
            }
          }

          $array_search = true;

        break;

        case 'location':
        case 'locations':

          $nav_menus = get_registered_nav_menus();

          if ( ! is_wp_error( $nav_menus ) && ! empty( $nav_menus ) ) {
            foreach ( $nav_menus as $nav_menu_key => $nav_menu_name ) {
              $options[$nav_menu_key] = $nav_menu_name;
            }
          }

          $array_search = true;

        break;

        default:

          if ( is_callable( $type ) ) {
            if ( ! empty( $term ) ) {
              $options = call_user_func( $type, $query_args );
            } else {
              $options = call_user_func( $type, $term, $query_args );
            }
          }

        break;

      }

      // Array search by "term"
      if ( ! empty( $term ) && ! empty( $options ) && ! empty( $array_search ) ) {
        $options = preg_grep( '/'. $term .'/i', $options );
      }

      // Make multidimensional array for ajax search
      if ( ! empty( $term ) && ! empty( $options ) ) {
        $arr = array();
        foreach ( $options as $option_key => $option_value ) {
          $arr[] = array( 'value' => $option_key, 'text' => $option_value );
        }
        $options = $arr;
      }

      return $options;

    }

    public function field_wp_query_data_title( $type, $values ) {

      $options = array();

      if ( ! empty( $values ) && is_array( $values ) ) {

        foreach ( $values as $value ) {

          $options[$value] = ucfirst( $value );

          switch( $type ) {

            case 'post':
            case 'posts':
            case 'page':
            case 'pages':

              $title = get_the_title( $value );

              if ( ! is_wp_error( $title ) && ! empty( $title ) ) {
                $options[$value] = $title;
              }

            break;

            case 'category':
            case 'categories':
            case 'tag':
            case 'tags':
            case 'menu':
            case 'menus':

              $term = get_term( $value );

              if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
                $options[$value] = $term->name;
              }

            break;

            case 'user':
            case 'users':

              $user = get_user_by( 'id', $value );

              if ( ! is_wp_error( $user ) && ! empty( $user ) ) {
                $options[$value] = $user->display_name;
              }

            break;

            case 'sidebar':
            case 'sidebars':

              global $wp_registered_sidebars;

              if ( ! empty( $wp_registered_sidebars[$value] ) ) {
                $options[$value] = $wp_registered_sidebars[$value]['name'];
              }

            break;

            case 'role':
            case 'roles':

              global $wp_roles;

              if ( ! empty( $wp_roles ) && ! empty( $wp_roles->roles ) && ! empty( $wp_roles->roles[$value] ) ) {
                $options[$value] = $wp_roles->roles[$value]['name'];
              }

            break;

            case 'post_type':
            case 'post_types':

              $post_types = get_post_types( array( 'show_in_nav_menus' => true ) );

              if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) && ! empty( $post_types[$value] ) ) {
                $options[$value] = ucfirst( $value );
              }

            break;

            case 'location':
            case 'locations':

              $nav_menus = get_registered_nav_menus();

              if ( ! is_wp_error( $nav_menus ) && ! empty( $nav_menus ) && ! empty( $nav_menus[$value] ) ) {
                $options[$value] = $nav_menus[$value];
              }

            break;

            default:

              if ( is_callable( $type .'_title' ) ) {
                $options[$value] = call_user_func( $type .'_title', $value );
              }

            break;

          }

        }

      }

      return $options;

    }

  }
}
