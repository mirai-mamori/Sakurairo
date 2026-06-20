<?php
/**
 * Manage fonts downloading.
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license     https://opensource.org/licenses/MIT
 * @since       3.1.0
 */

namespace Kirki\Module\Webfonts;

/**
 * Fonts-downloading manager.
 *
 * @since 3.1.0
 */
class Downloader {

	/**
	 * Get styles from URL.
	 *
	 * @access public
	 * @since 3.1.0
	 * @param string $url The URL.
	 * @return string
	 */
	public function get_styles( $url ) {
		$css = $this->get_cached_url_contents( $url );
		return $this->get_local_font_styles( $css );
	}

	/**
	 * Get styles with fonts downloaded locally.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @param string $css The styles.
	 * @return string
	 */
	protected function get_local_font_styles( $css ) {
		$files = $this->get_local_files_from_css( $css );

		// Convert paths to URLs.
		foreach ( $files as $remote => $local ) {
			$files[ $remote ] = str_replace( WP_CONTENT_DIR, content_url(), $local );
		}

		return str_replace(
			array_keys( $files ),
			array_values( $files ),
			$css
		);
	}

	/**
	 * Download files mentioned in our CSS locally.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @param string $css The CSS we want to parse.
	 * @return array      Returns an array of remote URLs and their local counterparts.
	 */
	protected function get_local_files_from_css( $css ) {
		$font_files = $this->get_files_from_css( $css );
		$stored     = get_option( 'kirki_downloaded_font_files', array() );
		$change     = false; // If in the end this is true, we need to update the cache option.

		// If the fonts folder don't exist, create it.
		if ( ! file_exists( WP_CONTENT_DIR . '/fonts' ) ) {
			$this->get_filesystem()->mkdir( WP_CONTENT_DIR . '/fonts', FS_CHMOD_DIR );
		}

		foreach ( $font_files as $font_family => $files ) {

			// The folder path for this font-family.
			$folder_path = WP_CONTENT_DIR . '/fonts/' . $font_family;

			// If the folder doesn't exist, create it.
			if ( ! file_exists( $folder_path ) ) {
				$this->get_filesystem()->mkdir( $folder_path, FS_CHMOD_DIR );
			}

			foreach ( $files as $url ) {

				// Get the filename.
				$filename  = basename( wp_parse_url( $url, PHP_URL_PATH ) );
				$font_path = $folder_path . '/' . $filename;

				if ( file_exists( $font_path ) ) {

					// Skip if already cached.
					if ( isset( $stored[ $url ] ) ) {
						continue;
					}

					$stored[ $url ] = $font_path;
					$change         = true;
				}

				if ( ! function_exists( 'download_url' ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				}

				// Download file to temporary location.
				$tmp_path = download_url( $url );

				// Make sure there were no errors.
				if ( is_wp_error( $tmp_path ) ) {
					continue;
				}

				// Move temp file to final destination.
				$success = $this->get_filesystem()->move( $tmp_path, $font_path, true );
				if ( $success ) {
					$stored[ $url ] = $font_path;
					$change         = true;
				}
			}
		}

		if ( $change ) {
			update_option( 'kirki_downloaded_font_files', $stored );
		}

		return $stored;
	}

	/**
	 * Get cached url contents.
	 * If a cache doesn't already exist, get the URL contents from remote
	 * and cache the result.
	 *
	 * @access public
	 * @since 3.1.0
	 * @param string $url        The URL we want to get the contents from.
	 * @param string $user_agent The user-agent to use for our request.
	 * @return string            Returns the remote URL contents.
	 */
	public function get_cached_url_contents( $url = '', $user_agent = null ) {

		// Try to retrieved cached response from the gfonts API.
		$contents         = false;
		$cached_responses = get_transient( 'kirki_remote_url_contents' );
		$cached_responses = ( $cached_responses && is_array( $cached_responses ) ) ? $cached_responses : array();
		if ( isset( $cached_responses[ md5( $url . $user_agent ) ] ) ) {
			return $cached_responses[ md5( $url . $user_agent ) ];
		}

		// Get the contents from remote.
		$contents = $this->get_url_contents( $url, $user_agent );

		// If we got the contents successfully, store them in a transient.
		// We're using a transient and not an option because fonts get updated
		// so we want to be able to get the latest version weekly.
		if ( $contents ) {
			$cached_responses[ md5( $url . $user_agent ) ] = $contents;
			set_transient( 'kirki_remote_url_contents', $cached_responses, WEEK_IN_SECONDS );
		}

		return $contents;
	}

	/**
	 * Get remote file contents.
	 *
	 * @access public
	 * @since 3.1.0
	 * @param string $url        The URL we want to get the contents from.
	 * @param string $user_agent The user-agent to use for our request.
	 * @return string            Returns the remote URL contents.
	 */
	public function get_url_contents( $url = '', $user_agent = null ) {

		if ( ! $user_agent ) {

			/**
			 * The user-agent we want to use.
			 *
			 * For woff2 format, use'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0'.
			 * The default user-agent is the only one compatible with woff (not woff2)
			 * which also supports unicode ranges.
			 */
			$user_agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0';

		}

		// Get the response.
		$response = wp_remote_get( $url, array( 'user-agent' => $user_agent ) );

		// Early exit if there was an error.
		if ( is_wp_error( $response ) ) {
			return;
		}

		// Get the CSS from our response.
		$contents = wp_remote_retrieve_body( $response );

		// Early exit if there was an error.
		if ( is_wp_error( $contents ) ) {
			return;
		}

		return $contents;
	}

	/**
	 * Get font files from the CSS.
	 *
	 * @access public
	 * @since 3.1.0
	 * @param string $css The CSS we want to parse.
	 * @return array      Returns an array of font-families and the font-files used.
	 */
	public function get_files_from_css( $css ) {

		$font_faces = explode( '@font-face', $css );

		$result = array();

		// Loop all our font-face declarations.
		foreach ( $font_faces as $font_face ) {

			// Make sure we only process styles inside this declaration.
			$style = explode( '}', $font_face )[0];

			// Sanity check.
			if ( false === strpos( $style, 'font-family' ) ) {
				continue;
			}

			// Get an array of our font-families.
			preg_match_all( '/font-family.*?\;/', $style, $matched_font_families );

			// Get an array of our font-files.
			preg_match_all( '/url\(.*?\)/i', $style, $matched_font_files );

			// Get the font-family name.
			$font_family = 'unknown';
			if ( isset( $matched_font_families[0] ) && isset( $matched_font_families[0][0] ) ) {
				$font_family = rtrim( ltrim( $matched_font_families[0][0], 'font-family:' ), ';' );
				$font_family = trim( str_replace( array( "'", ';' ), '', $font_family ) );
				$font_family = sanitize_key( strtolower( str_replace( ' ', '-', $font_family ) ) );
			}

			// Make sure the font-family is set in our array.
			if ( ! isset( $result[ $font_family ] ) ) {
				$result[ $font_family ] = array();
			}

			// Get files for this font-family and add them to the array.

			foreach ( $matched_font_files as $match ) {

				// Sanity check.
				if ( ! isset( $match[0] ) ) {
					continue;
				}

				// Add the file URL.
				$result[ $font_family ][] = rtrim( ltrim( $match[0], 'url(' ), ')' );
			}

			// Make sure we have unique items.
			// We're using array_flip here instead of array_unique for improved performance.
			$result[ $font_family ] = array_flip( array_flip( $result[ $font_family ] ) );
		}
		return $result;
	}

	/**
	 * Get the filesystem.
	 *
	 * @access protected
	 * @since 3.1.0
	 * @return WP_Filesystem
	 */
	protected function get_filesystem() {
		global $wp_filesystem;
		if ( ! $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			}
			WP_Filesystem();
		}
		return $wp_filesystem;
	}
}
