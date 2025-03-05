<?php
/**
 * Helper methods
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license     https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Util;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A simple object containing static methods.
 */
class Helper {

	/**
	 * Recursive replace in arrays.
	 *
	 * @static
	 * @access public
	 * @param array $array The first array.
	 * @param array $array1 The second array.
	 * @return mixed
	 */
	public static function array_replace_recursive( $array, $array1 ) {
		if ( function_exists( 'array_replace_recursive' ) ) {
			return array_replace_recursive( $array, $array1 );
		}

		/**
		 * Handle the arguments, merge one by one.
		 *
		 * In PHP 7 func_get_args() changed the way it behaves but this doesn't mean anything in this case
		 * since this method is only used when the array_replace_recursive() function doesn't exist
		 * and that was introduced in PHP v5.3.
		 *
		 * Once WordPress-Core raises its minimum requirements we'll be able to remove this fallback completely.
		 */
		$args  = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue
		$array = $args[0];
		if ( ! is_array( $array ) ) {
			return $array;
		}
		$count = count( $args );
		for ( $i = 1; $i < $count; $i++ ) {
			if ( is_array( $args[ $i ] ) ) {
				$array = self::recurse( $array, $args[ $i ] );
			}
		}
		return $array;
	}

	/**
	 * Helper method to be used from the array_replace_recursive method.
	 *
	 * @static
	 * @access public
	 * @param array $array The first array.
	 * @param array $array1 The second array.
	 * @return array
	 */
	public static function recurse( $array, $array1 ) {
		foreach ( $array1 as $key => $value ) {

			// Create new key in $array, if it is empty or not an array.
			if ( ! isset( $array[ $key ] ) || ( isset( $array[ $key ] ) && ! is_array( $array[ $key ] ) ) ) {
				$array[ $key ] = [];
			}

			// Overwrite the value in the base array.
			if ( is_array( $value ) ) {
				$value = self::recurse( $array[ $key ], $value );
			}
			$array[ $key ] = $value;
		}
		return $array;
	}

	/**
	 * Initialize the WP_Filesystem.
	 *
	 * @static
	 * @access public
	 * @return object WP_Filesystem
	 */
	public static function init_filesystem() {
		$credentials = [];

		if ( ! defined( 'FS_METHOD' ) ) {
			define( 'FS_METHOD', 'direct' );
		}

		$method = defined( 'FS_METHOD' ) ? FS_METHOD : false;

		if ( 'ftpext' === $method ) {
			// If defined, set it to that, Else, set to NULL.
			$credentials['hostname'] = defined( 'FTP_HOST' ) ? preg_replace( '|\w+://|', '', FTP_HOST ) : null;
			$credentials['username'] = defined( 'FTP_USER' ) ? FTP_USER : null;
			$credentials['password'] = defined( 'FTP_PASS' ) ? FTP_PASS : null;

			// Set FTP port.
			if ( strpos( $credentials['hostname'], ':' ) && null !== $credentials['hostname'] ) {
				list( $credentials['hostname'], $credentials['port'] ) = explode( ':', $credentials['hostname'], 2 );
				if ( ! is_numeric( $credentials['port'] ) ) {
					unset( $credentials['port'] );
				}
			} else {
				unset( $credentials['port'] );
			}

			// Set connection type.
			if ( ( defined( 'FTP_SSL' ) && FTP_SSL ) && 'ftpext' === $method ) {
				$credentials['connection_type'] = 'ftps';
			} elseif ( ! array_filter( $credentials ) ) {
				$credentials['connection_type'] = null;
			} else {
				$credentials['connection_type'] = 'ftp';
			}
		}

		// The WordPress filesystem.
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
			WP_Filesystem( $credentials );
		}

		return $wp_filesystem;
	}

	/**
	 * Returns the attachment object.
	 *
	 * @static
	 * @access public
	 * @see https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 * @param string $url URL to the image.
	 * @return int|string Numeric ID of the attachement.
	 */
	public static function get_image_id( $url ) {
		global $wpdb;
		if ( empty( $url ) ) {
			return 0;
		}

		$attachment = wp_cache_get( 'kirki_image_id_' . md5( $url ), null );
		if ( false === $attachment ) {
			$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid = %s;", $url ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			wp_cache_add( 'kirki_image_id_' . md5( $url ), $attachment, null );
		}

		if ( ! empty( $attachment ) ) {
			return $attachment[0];
		}
		return 0;
	}

	/**
	 * Returns an array of the attachment's properties.
	 *
	 * @param string $url URL to the image.
	 * @return array
	 */
	public static function get_image_from_url( $url ) {
		$image_id = self::get_image_id( $url );
		$image    = wp_get_attachment_image_src( $image_id, 'full' );

		return [
			'url'       => $image[0],
			'width'     => $image[1],
			'height'    => $image[2],
			'thumbnail' => $image[3],
		];
	}

	/**
	 * Get an array of posts.
	 *
	 * @static
	 * @access public
	 * @param array $args Define arguments for the get_posts function.
	 * @return array
	 */
	public static function get_posts( $args ) {
		if ( is_string( $args ) ) {
			$args = add_query_arg(
				[
					'suppress_filters' => false,
				]
			);
		} elseif ( is_array( $args ) && ! isset( $args['suppress_filters'] ) ) {
			$args['suppress_filters'] = false;
		}

		// Get the posts.
		// TODO: WordPress.VIP.RestrictedFunctions.get_posts_get_posts.
		$posts = get_posts( $args );

		// Properly format the array.
		$items = [];
		foreach ( $posts as $post ) {
			$items[ $post->ID ] = $post->post_title;
		}
		wp_reset_postdata();

		return $items;
	}

	/**
	 * Get an array of publicly-querable taxonomies.
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_taxonomies() {
		$items = [];

		// Get the taxonomies.
		$taxonomies = get_taxonomies(
			[
				'public' => true,
			]
		);

		// Build the array.
		foreach ( $taxonomies as $taxonomy ) {
			$id           = $taxonomy;
			$taxonomy     = get_taxonomy( $taxonomy );
			$items[ $id ] = $taxonomy->labels->name;
		}

		return $items;
	}

	/**
	 * Get an array of publicly-querable post-types.
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_post_types() {
		$items = [];

		// Get the post types.
		$post_types = get_post_types(
			[
				'public' => true,
			],
			'objects'
		);

		// Build the array.
		foreach ( $post_types as $post_type ) {
			$items[ $post_type->name ] = $post_type->labels->name;
		}

		return $items;
	}

	/**
	 * Get an array of terms from a taxonomy.
	 *
	 * @static
	 * @access public
	 * @param string|array $taxonomies See https://developer.wordpress.org/reference/functions/get_terms/ for details.
	 * @return array
	 */
	public static function get_terms( $taxonomies ) {
		$items = [];

		// Get the post types.
		$terms = get_terms( $taxonomies );

		// Build the array.
		foreach ( $terms as $term ) {
			$items[ $term->term_id ] = $term->name;
		}

		return $items;
	}

	/**
	 * Returns an array of navigation menus.
	 *
	 * @access public
	 * @param string $value_field The value to be stored in options. Accepted values: id|slug.
	 * @return array
	 */
	public static function get_nav_menus( $value_field = 'id' ) {
		$choices   = [];
		$nav_menus = wp_get_nav_menus();

		foreach ( $nav_menus as $term ) {
			$choices[ 'slug' === $value_field ? $term->slug : $term->term_id ] = $term->name;
		}

		return $choices;
	}

	/**
	 * Gets an array of material-design colors.
	 *
	 * @static
	 * @access public
	 * @param string $context Allows us to get subsets of the palette.
	 * @return array
	 */
	public static function get_material_design_colors( $context = 'primary' ) {
		return \Kirki\Util\MaterialColors::get_colors( $context );
	}

	/**
	 * Get an array of all available dashicons.
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_dashicons() {
		if ( class_exists( '\Kirki\Util\Dashicons' ) ) {
			return \Kirki\Util\Dashicons::get_icons();
		}
		return [];
	}

	/**
	 * Compares the 2 values given the condition
	 *
	 * @param mixed  $value1   The 1st value in the comparison.
	 * @param mixed  $value2   The 2nd value in the comparison.
	 * @param string $operator The operator we'll use for the comparison.
	 * @return boolean whether The comparison has succeded (true) or failed (false).
	 */
	public static function compare_values( $value1, $value2, $operator ) {
		if ( '===' === $operator ) {
			return $value1 === $value2;
		}
		if ( '!==' === $operator ) {
			return $value1 !== $value2;
		}
		if ( ( '!=' === $operator || 'not equal' === $operator ) ) {
			return $value1 != $value2; // phpcs:ignore WordPress.PHP.StrictComparisons
		}
		if ( ( '>=' === $operator || 'greater or equal' === $operator || 'equal or greater' === $operator ) ) {
			return $value2 >= $value1;
		}
		if ( ( '<=' === $operator || 'smaller or equal' === $operator || 'equal or smaller' === $operator ) ) {
			return $value2 <= $value1;
		}
		if ( ( '>' === $operator || 'greater' === $operator ) ) {
			return $value2 > $value1;
		}
		if ( ( '<' === $operator || 'smaller' === $operator ) ) {
			return $value2 < $value1;
		}
		if ( 'contains' === $operator || 'in' === $operator ) {
			if ( is_array( $value1 ) && is_array( $value2 ) ) {
				foreach ( $value2 as $val ) {
					if ( in_array( $val, $value1 ) ) { // phpcs:ignore WordPress.PHP.StrictInArray
						return true;
					}
				}
				return false;
			}
			if ( is_array( $value1 ) && ! is_array( $value2 ) ) {
				return in_array( $value2, $value1 ); // phpcs:ignore WordPress.PHP.StrictInArray
			}
			if ( is_array( $value2 ) && ! is_array( $value1 ) ) {
				return in_array( $value1, $value2 ); // phpcs:ignore WordPress.PHP.StrictInArray
			}
			return ( false !== strrpos( $value1, $value2 ) || false !== strpos( $value2, $value1 ) );
		}
		if ( 'does not contain' === $operator || 'not in' === $operator ) {
			return ! self::compare_values( $value1, $value2, $operator );
		}
		return $value1 == $value2; // phpcs:ignore WordPress.PHP.StrictComparisons
	}

	/**
	 * Prepare PHP array to be used as JS object.
	 *
	 * @see See https://developer.wordpress.org/reference/classes/wp_scripts/localize/
	 *
	 * @param array $values The data which can be either a single or multi-dimensional array.
	 * @return array
	 */
	public static function prepare_php_array_for_js( $values ) {

		foreach ( $values as $key => $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}

			$values[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}

		return $values;

	}
}
