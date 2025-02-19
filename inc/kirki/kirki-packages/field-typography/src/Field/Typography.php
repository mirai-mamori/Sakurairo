<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-typography
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\Util\Helper;
use Kirki\Field;
use Kirki\GoogleFonts;
use Kirki\Module\Webfonts\Fonts;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Typography extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-typography';

	/**
	 * Has the glogal fonts var been added already?
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var bool
	 */
	private static $fonts_var_added = false;

	/**
	 * Has the preview related var been added already?
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var bool
	 */
	private static $preview_var_added = false;

	/**
	 * An array of typography controls.
	 *
	 * This is used by the typography script for any custom logic
	 * that has to be applied to typography controls.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private static $typography_controls = [];

	/**
	 * An array of standard font variants.
	 *
	 * @access private
	 * @since 1.0.1
	 *
	 * @var array
	 */
	private static $std_variants;

	/**
	 * An array of complete font variants.
	 *
	 * @access private
	 * @since 1.0.1
	 *
	 * @var array
	 */
	private static $complete_variants;

	/**
	 * An array of complete font variant labels.
	 *
	 * @access private
	 * @since 1.0.2
	 *
	 * @var array
	 */
	private static $complete_variant_labels = [];

	/**
	 * Extra logic for the field.
	 *
	 * Adds all sub-fields.
	 *
	 * @access public
	 * @param array $args The arguments of the field.
	 */
	public function init( $args = [] ) {

		self::$typography_controls[] = $args['settings'];

		self::$std_variants = [
			[
				'value' => 'regular',
				'label' => __( 'Regular', 'kirki' ),
			],
			[
				'value' => 'italic',
				'label' => __( 'Italic', 'kirki' ),
			],
			[
				'value' => '700',
				'label' => __( '700', 'kirki' ),
			],
			[
				'value' => '700italic',
				'label' => __( '700 Italic', 'kirki' ),
			],
		];

		self::$complete_variants = [
			[
				'value' => 'regular',
				'label' => __( 'Regular', 'kirki' ),
			],
			[
				'value' => 'italic',
				'label' => __( 'Italic', 'kirki' ),
			],
			[
				'value' => '100',
				'label' => __( '100', 'kirki' ),
			],
			[
				'value' => '100italic',
				'label' => __( '100 Italic', 'kirki' ),
			],
			[
				'value' => '200',
				'label' => __( '200', 'kirki' ),
			],
			[
				'value' => '200italic',
				'label' => __( '200 Italic', 'kirki' ),
			],
			[
				'value' => '300',
				'label' => __( '300', 'kirki' ),
			],
			[
				'value' => '300italic',
				'label' => __( '300 Italic', 'kirki' ),
			],
			[
				'value' => '500',
				'label' => __( '500', 'kirki' ),
			],
			[
				'value' => '500italic',
				'label' => __( '500 Italic', 'kirki' ),
			],
			[
				'value' => '600',
				'label' => __( '600', 'kirki' ),
			],
			[
				'value' => '600italic',
				'label' => __( '600 Italic', 'kirki' ),
			],
			[
				'value' => '700',
				'label' => __( '700', 'kirki' ),
			],
			[
				'value' => '700italic',
				'label' => __( '700 Italic', 'kirki' ),
			],
			[
				'value' => '800',
				'label' => __( '800', 'kirki' ),
			],
			[
				'value' => '800italic',
				'label' => __( '800 Italic', 'kirki' ),
			],
			[
				'value' => '900',
				'label' => __( '900', 'kirki' ),
			],
			[
				'value' => '900italic',
				'label' => __( '900 Italic', 'kirki' ),
			],
		];

		foreach ( self::$complete_variants as $variants ) {
			self::$complete_variant_labels[ $variants['value'] ] = $variants['label'];
		}

		$this->add_sub_fields( $args );

		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_control_scripts' ] );
		add_action( 'customize_preview_init', [ $this, 'enqueue_preview_scripts' ] );
		add_filter( 'kirki_output_control_classnames', [ $this, 'output_control_classnames' ] );
	}

	/**
	 * Add sub-fields.
	 *
	 * @access private
	 * @since 1.0
	 * @param array $args The field arguments.
	 * @return void
	 */
	private function add_sub_fields( $args ) {

		$args['kirki_config'] = isset( $args['kirki_config'] ) ? $args['kirki_config'] : 'global';

		$defaults = isset( $args['default'] ) ? $args['default'] : [];

		/**
		 * Add a hidden field, the label & description.
		 */
		new \Kirki\Field\Generic(
			wp_parse_args(
				[
					'sanitize_callback' => isset( $args['sanitize_callback'] ) ? $args['sanitize_callback'] : [ __CLASS__, 'sanitize' ],
					'wrapper_opts'      => [
						'gap' => 'small',
					],
					'input_attrs'       => '',
					'choices'           => [
						'type'        => 'hidden',
						'parent_type' => 'kirki-typography',
					],
				],
				$args
			)
		);

		$args['parent_setting'] = $args['settings'];
		$args['output']         = [];
		$args['wrapper_attrs']  = [
			'data-kirki-parent-control-type' => 'kirki-typography',
		];

		if ( isset( $args['transport'] ) && 'auto' === $args['transport'] ) {
			$args['transport'] = 'postMessage';
		}

		/**
		 * Add font-family selection controls.
		 * These include font-family and variant.
		 * They are grouped here because all they are required.
		 * in order to get the right googlefont variant.
		 */
		if ( isset( $args['default']['font-family'] ) ) {

			$args['wrapper_attrs']['kirki-typography-subcontrol-type'] = 'font-family';

			/**
			 * Add font-family control.
			 */
			new \Kirki\Field\ReactSelect(
				wp_parse_args(
					[
						'label'       => esc_html__( 'Font Family', 'kirki' ),
						'description' => '',
						'settings'    => $args['settings'] . '[font-family]',
						'default'     => isset( $args['default']['font-family'] ) ? $args['default']['font-family'] : '',
						'input_attrs' => $this->filter_preferred_choice_setting( 'input_attrs', 'font-family', $args ),
						'choices'     => [], // The choices will be populated later inside `get_font_family_choices` function in this file.
						'css_vars'    => [],
						'output'      => [],
					],
					$args
				)
			);

			/**
			 * Add font variant.
			 */
			$font_variant = isset( $args['default']['variant'] ) ? $args['default']['variant'] : 'regular';

			if ( isset( $args['default']['font-weight'] ) ) {
				$font_variant = 400 === $args['default']['font-weight'] || '400' === $args['default']['font-weight'] ? 'regular' : $args['default']['font-weight'];
			}

			$args['wrapper_attrs']['kirki-typography-subcontrol-type'] = 'font-variant';

			new \Kirki\Field\ReactSelect(
				wp_parse_args(
					[
						'label'       => esc_html__( 'Font Variant', 'kirki' ),
						'description' => '',
						'settings'    => $args['settings'] . '[variant]',
						'default'     => $font_variant,
						'input_attrs' => $this->filter_preferred_choice_setting( 'input_attrs', 'variant', $args ),
						'choices'     => [], // The choices will be populated later inside `get_variant_choices` function in this file.
						'css_vars'    => [],
						'output'      => [],
					],
					$args
				)
			);

		}

		$font_size_field_specified = isset( $defaults['font-size'] );
		$color_field_specified     = isset( $defaults['color'] );

		if ( $font_size_field_specified || $color_field_specified ) {
			$group = [
				'font-size' => [
					'type'         => 'dimension',
					'label'        => esc_html__( 'Font Size', 'kirki' ),
					'is_specified' => $font_size_field_specified,
				],
				'color'     => [
					'type'         => 'react-colorful',
					'label'        => esc_html__( 'Font Color', 'kirki' ),
					'is_specified' => $color_field_specified,
					'choices'      => [
						'alpha'       => true,
						'label_style' => 'top',
					],
				],
			];

			$this->generate_controls_group( $group, $args );
		}

		$text_align_field_specified     = isset( $defaults['text-align'] );
		$text_transform_field_specified = isset( $defaults['text-transform'] );

		if ( $text_align_field_specified || $text_transform_field_specified ) {
			$group = [
				'text-align'     => [
					'type'         => 'react-select',
					'label'        => esc_html__( 'Text Align', 'kirki' ),
					'is_specified' => $text_align_field_specified,
					'choices'      => [
						'initial' => esc_html__( 'Initial', 'kirki' ),
						'left'    => esc_html__( 'Left', 'kirki' ),
						'center'  => esc_html__( 'Center', 'kirki' ),
						'right'   => esc_html__( 'Right', 'kirki' ),
						'justify' => esc_html__( 'Justify', 'kirki' ),
					],
				],
				'text-transform' => [
					'type'         => 'react-select',
					'label'        => esc_html__( 'Text Transform', 'kirki' ),
					'is_specified' => $text_transform_field_specified,
					'choices'      => [
						'none'       => esc_html__( 'None', 'kirki' ),
						'capitalize' => esc_html__( 'Capitalize', 'kirki' ),
						'uppercase'  => esc_html__( 'Uppercase', 'kirki' ),
						'lowercase'  => esc_html__( 'Lowercase', 'kirki' ),
					],
				],
			];

			$this->generate_controls_group( $group, $args );
		}

		$text_decoration_field_specified = isset( $defaults['text-decoration'] );

		if ( $text_decoration_field_specified ) {
			$group = [
				'text-decoration' => [
					'type'         => 'react-select',
					'label'        => esc_html__( 'Text Decoration', 'kirki' ),
					'is_specified' => $text_decoration_field_specified,
					'choices'      => [
						'none'         => esc_html__( 'None', 'kirki' ),
						'underline'    => esc_html__( 'Underline', 'kirki' ),
						'line-through' => esc_html__( 'Line Through', 'kirki' ),
						'overline'     => esc_html__( 'Overline', 'kirki' ),
						'solid'        => esc_html__( 'Solid', 'kirki' ),
						'wavy'         => esc_html__( 'Wavy', 'kirki' ),
					],
				],
			];

			$this->generate_controls_group( $group, $args );
		}

		$line_height_field_specified    = isset( $defaults['line-height'] );
		$letter_spacing_field_specified = isset( $defaults['letter-spacing'] );

		if ( $line_height_field_specified || $letter_spacing_field_specified ) {
			$group = [
				'line-height'    => [
					'type'         => 'dimension',
					'label'        => esc_html__( 'Line Height', 'kirki' ),
					'is_specified' => $line_height_field_specified,
				],
				'letter-spacing' => [
					'type'         => 'dimension',
					'label'        => esc_html__( 'Letter Spacing', 'kirki' ),
					'is_specified' => $letter_spacing_field_specified,
				],
			];

			$this->generate_controls_group( $group, $args );
		}

		$margin_top_field_specified    = isset( $defaults['margin-top'] );
		$margin_bottom_field_specified = isset( $defaults['margin-bottom'] );

		if ( $margin_top_field_specified || $margin_bottom_field_specified ) {
			$group = [
				'margin-top'    => [
					'type'         => 'dimension',
					'label'        => esc_html__( 'Margin Top', 'kirki' ),
					'is_specified' => $margin_top_field_specified,
				],
				'margin-bottom' => [
					'type'         => 'dimension',
					'label'        => esc_html__( 'Margin Bottom', 'kirki' ),
					'is_specified' => $margin_bottom_field_specified,
				],
			];

			$this->generate_controls_group( $group, $args );
		}

	}

	/**
	 * Generate controls group.
	 *
	 * @param array $group The group data.
	 * @param array $args The field args.
	 */
	public function generate_controls_group( $group, $args ) {

		$total_specified = 0;
		$field_width     = 100;

		foreach ( $group as $css_prop => $control ) {
			if ( $control['is_specified'] ) {
				$total_specified++;
			}
		}

		if ( $total_specified > 1 ) {
			$field_width = floor( 100 / $total_specified );
		}

		$group_count = 0;

		foreach ( $group as $css_prop => $control ) {
			if ( $control['is_specified'] ) {
				$group_count++;

				$group_classname  = 'kirki-group-item';
				$group_classname .= 1 === $group_count ? ' kirki-group-start' : ( $group_count === $total_specified ? ' kirki-group-end' : '' );

				$control_class = str_ireplace( '-', ' ', $control['type'] );
				$control_class = ucwords( $control_class );
				$control_class = str_replace( ' ', '', $control_class );
				$control_class = '\\Kirki\\Field\\' . $control_class;

				new $control_class(
					wp_parse_args(
						[
							'label'         => isset( $control['label'] ) ? $control['label'] : '',
							'description'   => isset( $control['description'] ) ? $control['description'] : '',
							'settings'      => $args['settings'] . '[' . $css_prop . ']',
							'default'       => $args['default'][ $css_prop ],
							'wrapper_attrs' => wp_parse_args(
								[
									'data-kirki-typography-css-prop' => $css_prop,
									'kirki-typography-subcontrol-type' => $css_prop,
									'class' => '{default_class} ' . $group_classname . ' kirki-w' . $field_width,
								],
								$args['wrapper_attrs']
							),
							'input_attrs'   => $this->filter_preferred_choice_setting( 'input_attrs', $css_prop, $args ),
							'choices'       => ( isset( $control['choices'] ) ? $control['choices'] : [] ),
							'css_vars'      => [],
							'output'        => [],
						],
						$args
					)
				);

			}
		}

	}

	/**
	 * Sanitizes typography controls
	 *
	 * @static
	 * @since 1.0
	 * @param array $value The value.
	 * @return array
	 */
	public static function sanitize( $value ) {

		if ( ! is_array( $value ) ) {
			return [];
		}

		foreach ( $value as $key => $val ) {
			switch ( $key ) {
				case 'font-family':
					$value['font-family'] = sanitize_text_field( $val );
					break;

				case 'variant':
					// Use 'regular' instead of 400 for font-variant.
					$value['variant'] = ( 400 === $val || '400' === $val ) ? 'regular' : $val;

					// Get font-weight from variant.
					$value['font-weight'] = filter_var( $value['variant'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					$value['font-weight'] = ( 'regular' === $value['variant'] || 'italic' === $value['variant'] ) ? '400' : (string) absint( $value['font-weight'] );

					// Get font-style from variant.
					if ( ! isset( $value['font-style'] ) ) {
						$value['font-style'] = ( false === strpos( $value['variant'], 'italic' ) ) ? 'normal' : 'italic';
					}

					break;

				case 'text-align':
					if ( ! in_array( $val, [ '', 'inherit', 'left', 'center', 'right', 'justify' ], true ) ) {
						$value['text-align'] = '';
					}

					break;

				case 'text-transform':
					if ( ! in_array( $val, [ '', 'none', 'capitalize', 'uppercase', 'lowercase', 'initial', 'inherit' ], true ) ) {
						$value['text-transform'] = '';
					}

					break;

				case 'text-decoration':
					if ( ! in_array( $val, [ '', 'none', 'underline', 'overline', 'line-through', 'solid', 'wavy', 'initial', 'inherit' ], true ) ) {
						$value['text-transform'] = '';
					}

					break;

				case 'color':
					$value['color'] = '' === $value['color'] ? '' : \Kirki\Field\ReactColorful::sanitize( $value['color'] );
					break;

				default:
					$value[ $key ] = sanitize_text_field( $value[ $key ] );
			}
		}

		return $value;

	}

	/**
	 * Enqueue scripts & styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_style( 'kirki-control-typography', \Kirki\URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], '1.0' );

		wp_enqueue_script( 'kirki-control-typography', \Kirki\URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [], '1.0', true );

		wp_localize_script( 'kirki-control-typography', 'kirkiTypographyControls', self::$typography_controls );

		$args = $this->args;

		$variants = [];

		// Add custom variants (for custom fonts) to the $variants.
		if ( isset( $args['choices'] ) && isset( $args['choices']['fonts'] ) && isset( $args['choices']['fonts']['families'] ) ) {

			// If $args['choices']['fonts']['families'] exists, then loop it.
			foreach ( $args['choices']['fonts']['families'] as $font_family_key => $font_family_value ) {

				// Then loop the $font_family_value['children].
				foreach ( $font_family_value['children'] as $font_family ) {

					// Then check if $font_family['id'] exists in variants argument.
					if ( isset( $args['choices']['fonts']['variants'] ) && isset( $args['choices']['fonts']['variants'][ $font_family['id'] ] ) ) {

						// Create new array if $variants[ $font_family['id'] ] doesn't exist.
						if ( ! isset( $variants[ $font_family['id'] ] ) ) {
							$variants[ $font_family['id'] ] = [];
						}

						// The $custom_variant here can be something like "400italic" or "italic".
						foreach ( $args['choices']['fonts']['variants'][ $font_family['id'] ] as $custom_variant ) {

							// Check if $custom_variant exists in self::$complete_variant_labels.
							if ( isset( self::$complete_variant_labels[ $custom_variant ] ) ) {

								// If it exists, assign it to $variants[ $font_family['id'] ], so that they will be available in JS object.
								array_push(
									$variants[ $font_family['id'] ],
									[
										'value' => $custom_variant,
										'label' => self::$complete_variant_labels[ $custom_variant ],
									]
								);

							} // End of isset(self::$complete_variant_labels[$font_family['id']]) if.
						} // End of $args['choices']['fonts']['variants'][ $font_family['id'] foreach.
					}
				} // End of $font_family_value['children'] foreach.
			} // End of $args['choices']['fonts']['families'] foreach.
		} // End of $args['choices']['fonts']['families'] if.

		if ( ! isset( $args['choices']['fonts'] ) || ! isset( $args['choices']['fonts']['standard'] ) ) {
			$standard_fonts = Fonts::get_standard_fonts();

			foreach ( $standard_fonts as $font ) {
				if ( isset( $font['variants'] ) ) {

					// Create new array if $variants[ $font['stack'] ] doesn't exist.
					if ( ! isset( $variants[ $font['stack'] ] ) ) {
						$variants[ $font['stack'] ] = [];
					}

					// The $std_variant here can be something like "400italic" or "italic".
					foreach ( $font['variants'] as $std_variant ) {

						// Check if $std_variant exists in self::$complete_variant_labels.
						if ( isset( self::$complete_variant_labels[ $std_variant ] ) ) {

							// If it exists, assign it to $variants[ $font['stack'] ], so that they will be available in JS object.
							array_push(
								$variants[ $font['stack'] ],
								[
									'value' => $std_variant,
									'label' => self::$complete_variant_labels[ $std_variant ],
								]
							);

						} // End of isset(self::$complete_variant_labels[$font_family['id']]) if.
					} // End of $args['choices']['fonts']['variants'][ $font_family['id'] foreach.
				}
			}
		} elseif ( is_array( $args['choices']['fonts']['standard'] ) ) {
			foreach ( $args['choices']['fonts']['standard'] as $key => $val ) {
				$key = ( is_int( $key ) ) ? $val : $key;

				if ( isset( $val['variants'] ) ) {

					// Create new array if $variants[ $font['stack'] ] doesn't exist.
					if ( ! isset( $variants[ $key ] ) ) {
						$variants[ $key ] = [];
					}

					// The $std_variant here can be something like "400italic" or "italic".
					foreach ( $val['variants'] as $std_variant ) {

						// Check if $std_variant exists in self::$complete_variant_labels.
						if ( isset( self::$complete_variant_labels[ $std_variant ] ) ) {

							// If it exists, assign it to $variants[ $font['stack'] ], so that they will be available in JS object.
							array_push(
								$variants[ $key ],
								[
									'value' => $std_variant,
									'label' => self::$complete_variant_labels[ $std_variant ],
								]
							);

						} // End of isset(self::$complete_variant_labels[$font_family['id']]) if.
					} // End of $args['choices']['fonts']['variants'][ $font_family['id'] foreach.
				}
			}
		}

		// Scripts inside this block will only be executed once.
		if ( ! self::$fonts_var_added ) {
			wp_localize_script(
				'kirki-control-typography',
				'kirkiFontVariants',
				[
					'standard' => self::$std_variants,
					'complete' => self::$complete_variants,
				]
			);

			$google = new GoogleFonts();

			wp_localize_script( 'kirki-control-typography', 'kirkiGoogleFonts', $google->get_array() );
			wp_add_inline_script( 'kirki-control-typography', 'var kirkiCustomVariants = {};', 'before' );

			self::$fonts_var_added = true;
		}

		// This custom variants will be available for each typography control.
		$custom_variant_key   = str_ireplace( ']', '', $args['settings'] );
		$custom_variant_key   = str_ireplace( '[', '_', $custom_variant_key );
		$custom_variant_value = wp_json_encode( Helper::prepare_php_array_for_js( $variants ) );

		wp_add_inline_script(
			'kirki-control-typography',
			'kirkiCustomVariants["' . $custom_variant_key . '"] = ' . $custom_variant_value . ';',
			$variants
		);

	}

	/**
	 * Enqueue scripts for customize_preview_init.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue_preview_scripts() {

		wp_enqueue_script( 'kirki-preview-typography', \Kirki\URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/preview.js' ), [ 'wp-hooks' ], '1.0', true );

		if ( ! self::$preview_var_added ) {
			$google = new GoogleFonts();

			wp_localize_script(
				'kirki-preview-typography',
				'kirkiGoogleFontNames',
				$google->get_google_font_names()
			);

			self::$preview_var_added = true;
		}

	}

	/**
	 * Prefer control specific value over field value
	 *
	 * @access public
	 * @since 4.0
	 * @param $setting
	 * @param $choice
	 * @param $args
	 *
	 * @return string
	 */
	public function filter_preferred_choice_setting( $setting, $choice, $args ) {

		// Fail early.
		if ( ! isset( $args[ $setting ] ) ) {
			return '';
		}

		// If a specific field for the choice is set
		if ( isset( $args[ $setting ][ $choice ] ) ) {
			return $args[ $setting ][ $choice ];
		}

		// Unset input_attrs of all other choices.
		foreach ( $args['choices'] as $id => $set ) {
			if ( $id !== $choice && isset( $args[ $setting ][ $id ] ) ) {
				unset( $args[ $setting ][ $id ] );
			} elseif ( ! isset( $args[ $setting ][ $id ] ) ) {
				$args[ $setting ] = '';
			}
		}

		return $args[ $setting ];

	}

	/**
	 * Populate the font family choices.
	 *
	 * It's separated from the `add_sub_field` function to prevent a bug
	 * when hooking a function into `kirki_fonts_standard_fonts` hook after registering the field.
	 *
	 * When a function is hooked to `kirki_fonts_standard_fonts` before registering the field, it will work.
	 * But if it's hooked after field registration, then the function won't be available.
	 *
	 * @access private
	 * @since 1.0.1
	 *
	 * @return array
	 */
	private function get_font_family_choices() {

		$args = $this->args;

		// Figure out how to sort the fonts.
		$sorting   = 'alpha';
		$max_fonts = 9999;
		$google    = new GoogleFonts();

		if ( isset( $args['choices'] ) && isset( $args['choices']['fonts'] ) && isset( $args['choices']['fonts']['google'] ) && ! empty( $args['choices']['fonts']['google'] ) ) {
			if ( in_array( $args['choices']['fonts']['google'][0], [ 'alpha', 'popularity', 'trending' ], true ) ) {
				$sorting = $args['choices']['fonts']['google'][0];

				if ( isset( $args['choices']['fonts']['google'][1] ) && is_int( $args['choices']['fonts']['google'][1] ) ) {
					$max_fonts = (int) $args['choices']['fonts']['google'][1];
				}

				$g_fonts = $google->get_google_fonts_by_args(
					[
						'sort'  => $sorting,
						'count' => $max_fonts,
					]
				);
			} else {
				$g_fonts = $args['choices']['fonts']['google'];
			}
		} else {
			$g_fonts = $google->get_google_fonts_by_args(
				[
					'sort'  => $sorting,
					'count' => $max_fonts,
				]
			);
		}

		$std_fonts = [];

		if ( ! isset( $args['choices']['fonts'] ) || ! isset( $args['choices']['fonts']['standard'] ) ) {
			$standard_fonts = Fonts::get_standard_fonts();

			foreach ( $standard_fonts as $font ) {
				$std_fonts[ $font['stack'] ] = $font['label'];
			}
		} elseif ( is_array( $args['choices']['fonts']['standard'] ) ) {
			foreach ( $args['choices']['fonts']['standard'] as $key => $val ) {
				$key               = ( \is_int( $key ) ) ? $val : $key;
				$std_fonts[ $key ] = $val;
			}
		}

		$choices = [];

		$choices['default'] = [
			esc_html__( 'Defaults', 'kirki' ),
			[
				'' => esc_html__( 'Default', 'kirki' ),
			],
		];

		if ( isset( $args['choices'] ) && isset( $args['choices']['fonts'] ) && isset( $args['choices']['fonts']['families'] ) ) {
			// Implementing the custom font families.
			foreach ( $args['choices']['fonts']['families'] as $font_family_key => $font_family_value ) {
				if ( ! isset( $choices[ $font_family_key ] ) ) {
					$choices[ $font_family_key ] = [];
				}

				$family_opts = [];

				foreach ( $font_family_value['children'] as $font_family ) {
					$family_opts[ $font_family['id'] ] = $font_family['text'];
				}

				$choices[ $font_family_key ] = [
					$font_family_value['text'],
					$family_opts,
				];
			}
		}

		$choices['standard'] = [
			esc_html__( 'Standard Fonts', 'kirki' ),
			$std_fonts,
		];

		$choices['google'] = [
			esc_html__( 'Google Fonts', 'kirki' ),
			array_combine( array_values( $g_fonts ), array_values( $g_fonts ) ),
		];

		if ( empty( $choices['standard'][1] ) ) {
			$choices = array_combine( array_values( $g_fonts ), array_values( $g_fonts ) );
		} elseif ( empty( $choices['google'][1] ) ) {
			$choices = $std_fonts;
		}

		return $choices;

	}

	/**
	 * Get custom variant choices (for custom fonts).
	 *
	 * It's separated from the `add_sub_field` function to prevent a bug
	 * when hooking a function into `kirki_fonts_standard_fonts` hook after registering the field.
	 *
	 * When a function is hooked to `kirki_fonts_standard_fonts` before registering the field, it will work.
	 * But if it's hooked after field registration, then the function won't be available.
	 *
	 * @access private
	 * @since 1.0.2
	 *
	 * @return array
	 */
	private function get_variant_choices() {

		$args = $this->args;

		$choices = self::$std_variants;

		// Implementing the custom variants for custom fonts.
		if ( isset( $args['choices'] ) && isset( $args['choices']['fonts'] ) && isset( $args['choices']['fonts']['families'] ) ) {

			$choices = [];

			// If $args['choices']['fonts']['families'] exists, then loop it.
			foreach ( $args['choices']['fonts']['families'] as $font_family_key => $font_family_value ) {

				// Then loop the $font_family_value['children].
				foreach ( $font_family_value['children'] as $font_family ) {

					// Then check if $font_family['id'] exists in $args['choices']['fonts']['variants'].
					if ( isset( $args['choices']['fonts']['variants'] ) && isset( $args['choices']['fonts']['variants'][ $font_family['id'] ] ) ) {

						// The $custom_variant here can be something like "400italic" or "italic".
						foreach ( $args['choices']['fonts']['variants'][ $font_family['id'] ] as $custom_variant ) {

							// Check if $custom_variant exists in self::$complete_variant_labels.
							if ( isset( self::$complete_variant_labels[ $custom_variant ] ) ) {

								// If it exists, assign it to $choices.
								array_push(
									$choices,
									[
										'value' => $custom_variant,
										'label' => self::$complete_variant_labels[ $custom_variant ],
									]
								);

							} // End of isset(self::$complete_variant_labels[$font_family['id']]) if.
						} // End of $args['choices']['fonts']['variants'][ $font_family['id'] foreach.
					}
				} // End of $font_family_value['children'] foreach.
			} // End of $args['choices']['fonts']['families'] foreach.
		} // End of $args['choices']['fonts']['families'] if.

		return $choices;

	}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] . '[font-family]' ) {
			$args            = parent::filter_control_args( $args, $wp_customize );
			$args['choices'] = $this->get_font_family_choices();
		}

		if ( $args['settings'] === $this->args['settings'] . '[variant]' ) {
			$args            = parent::filter_control_args( $args, $wp_customize );
			$args['choices'] = $this->get_variant_choices();
		}

		return $args;

	}

	/**
	 * Adds a custom output class for typography fields.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $classnames The array of classnames.
	 * @return array
	 */
	public function output_control_classnames( $classnames ) {

		$classnames['kirki-typography'] = '\Kirki\Field\CSS\Typography';
		return $classnames;

	}

	/**
	 * Override parent method. No need to register any setting.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return void
	 */
	public function add_setting( $wp_customize ) {}

	/**
	 * Override the parent method. No need for a control.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return void
	 */
	public function add_control( $wp_customize ) {}

}
