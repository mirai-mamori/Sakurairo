<?php
/**
 * Adds class aliases for backwards compatibility.
 *
 * @package   Kirki
 * @category  Core
 * @author    Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     0.1
 */

namespace Kirki\Compatibility;

/**
 * Adds class aliases for backwards compatibility.
 *
 * @since 0.1
 */
class Aliases {

	/**
	 * An array of class aliases.
	 *
	 * @access private
	 * @since 0.1
	 * @var array
	 */
	private $aliases = [
		'generic'    => [
			[ 'Kirki\Compatibility\Kirki', 'Kirki' ],
			[ 'Kirki\Compatibility\Config', 'Kirki_Config' ],
			[ 'Kirki\Compatibility\Control', 'Kirki_Control' ],
			[ 'Kirki\Compatibility\Field', 'Kirki_Field' ],
			[ 'Kirki\Util\Helper', 'Kirki_Helper' ],
			[ 'Kirki\Compatibility\Init', 'Kirki_Init' ],
			[ 'Kirki\L10n', 'Kirki_L10n' ],
			[ 'Kirki\Compatibility\Modules', 'Kirki_Modules' ],
			[ 'Kirki\Compatibility\Sanitize_Values', 'Kirki_Sanitize_Values' ],
			[ 'Kirki\Compatibility\Section', 'Kirki_Section' ],
			[ 'Kirki\Compatibility\Values', 'Kirki_Values' ],
			[ 'Kirki\Util\Util', 'Kirki_Util' ],
			[ 'Kirki\Compatibility\Framework', 'Kirki_Toolkit' ],
			[ 'Kirki\Module\CSS', 'Kirki_Modules_CSS' ],
			[ 'Kirki\Module\CSS\Output', 'Kirki_Output' ],
			[ 'Kirki\Module\CSS\Generator', 'Kirki_Modules_CSS_Generator' ],
			[ 'Kirki\Module\CSS\Property', 'Kirki_Output_Property' ],
			[ 'Kirki\Module\CSS\Property\Font_Family', 'Kirki_Output_Property_Font_Family' ],
			[ 'Kirki\Module\Preset', 'Kirki_Modules_Preset' ],
			[ 'Kirki\Module\Tooltips', 'Kirki_Modules_Tooltips' ],
			[ 'Kirki\Module\Webfonts', 'Kirki_Modules_Webfonts' ],
			[ 'Kirki\Module\Webfonts\Google', 'Kirki_Fonts_Google' ],
			[ 'Kirki\Module\Webfonts\Fonts', 'Kirki_Fonts' ],
			[ 'Kirki\Module\Webfonts\Embed', 'Kirki_Modules_Webfonts_Embed' ],
			[ 'Kirki\Module\Webfonts\Async', 'Kirki_Modules_Webfonts_Async' ],
			[ 'Kirki\Module\Field_Dependencies', 'Kirki_Modules_Field_Dependencies' ],
			[ 'Kirki\Module\Editor_Styles', 'Kirki_Modules_Gutenberg' ],
			[ 'Kirki\Module\Selective_Refresh', 'Kirki_Modules_Selective_Refresh' ],
			[ 'Kirki\Module\Postmessage', 'Kirki_Modules_Postmessage' ],
			[ 'Kirki\Field\Background', 'Kirki_Field_Background' ],
			[ 'Kirki\Field\CSS\Background', 'Kirki_Output_Field_Background' ],
			[ 'Kirki\Field\Checkbox', 'Kirki_Field_Checkbox' ],
			[ 'Kirki\Field\Checkbox_Switch', 'Kirki_Field_Switch' ],
			[ 'Kirki\Field\Checkbox_Switch', 'Kirki\Field\Switch' ], // Preventing typo.
			[ 'Kirki\Field\Checkbox_Toggle', 'Kirki_Field_Toggle' ],
			[ 'Kirki\Field\Checkbox_Toggle', 'Kirki\Field\Toggle' ], // Preventing typo.
			[ 'Kirki\Field\Code', 'Kirki_Field_Code' ],
			[ 'Kirki\Field\Color', 'Kirki_Field_Color' ],
			[ 'Kirki\Field\Color', 'Kirki_Field_Color_Alpha' ],
			[ 'Kirki\Field\Color_Palette', 'Kirki_Field_Color_Palette' ],
			[ 'Kirki\Field\Custom', 'Kirki_Field_Custom' ],
			[ 'Kirki\Field\Dashicons', 'Kirki_Field_Dashicons' ],
			[ 'Kirki\Field\Date', 'Kirki_Field_Date' ],
			[ 'Kirki\Field\Dimension', 'Kirki_Field_Dimension' ],
			[ 'Kirki\Field\Dimensions', 'Kirki_Field_Dimensions' ],
			[ 'Kirki\Field\CSS\Dimensions', 'Kirki_Output_Field_Dimensions' ],
			[ 'Kirki\Field\Dimensions', 'Kirki_Field_Spacing' ],
			[ 'Kirki\Field\Dimensions', 'Kirki\Field\Spacing' ],
			[ 'Kirki\Field\Editor', 'Kirki_Field_Editor' ],
			[ 'Kirki\Field\FontAwesome', 'Kirki_Field_FontAwesome' ],
			[ 'Kirki\Field\Generic', 'Kirki_Field_Kirki_Generic' ],
			[ 'Kirki\Field\Generic', 'Kirki_Field_Generic' ],
			[ 'Kirki\Field\Text', 'Kirki_Field_Text' ],
			[ 'Kirki\Field\Textarea', 'Kirki_Field_Textarea' ],
			[ 'Kirki\Field\URL', 'Kirki_Field_URL' ],
			[ 'Kirki\Field\URL', 'Kirki_Field_Link' ],
			[ 'Kirki\Field\URL', 'Kirki\Field\Link' ],
			[ 'Kirki\Field\Image', 'Kirki_Field_Image' ],
			[ 'Kirki\Field\CSS\Image', 'Kirki_Output_Field_Image' ],
			[ 'Kirki\Field\Multicheck', 'Kirki_Field_Multicheck' ],
			[ 'Kirki\Field\Multicolor', 'Kirki_Field_Multicolor' ],
			[ 'Kirki\Field\CSS\Multicolor', 'Kirki_Output_Field_Multicolor' ],
			[ 'Kirki\Field\Number', 'Kirki_Field_Number' ],
			[ 'Kirki\Field\Palette', 'Kirki_Field_Palette' ],
			[ 'Kirki\Field\Repeater', 'Kirki_Field_Repeater' ],
			[ 'Kirki\Field\Dropdown_Pages', 'Kirki_Field_Dropdown_Pages' ],
			[ 'Kirki\Field\Preset', 'Kirki_Field_Preset' ],
			[ 'Kirki\Field\Select', 'Kirki_Field_Select' ],
			[ 'Kirki\Field\Slider', 'Kirki_Field_Slider' ],
			[ 'Kirki\Field\Sortable', 'Kirki_Field_Sortable' ],
			[ 'Kirki\Field\Typography', 'Kirki_Field_Typography' ],
			[ 'Kirki\Field\CSS\Typography', 'Kirki_Output_Field_Typography' ],
			[ 'Kirki\Field\Upload', 'Kirki_Field_Upload' ],
		],
		'customizer' => [
			[ 'Kirki\Control\Base', 'Kirki_Control_Base' ],
			[ 'Kirki\Control\Base', 'Kirki_Customize_Control' ],
			[ 'Kirki\Control\Checkbox', 'Kirki_Control_Checkbox' ],
			[ 'Kirki\Control\Checkbox_Switch', 'Kirki_Control_Switch' ],
			[ 'Kirki\Control\Checkbox_Toggle', 'Kirki_Control_Toggle' ],
			[ 'WP_Customize_Code_Editor_Control', 'Kirki_Control_Code' ],
			[ 'Kirki\Control\Color', 'Kirki_Control_Color' ],
			[ 'Kirki\Control\Color_Palette', 'Kirki_Control_Color_Palette' ],
			[ 'WP_Customize_Cropped_Image_Control', 'Kirki_Control_Cropped_Image' ],
			[ 'Kirki\Control\Custom', 'Kirki_Control_Custom' ],
			[ 'Kirki\Control\Dashicons', 'Kirki_Control_Dashicons' ],
			[ 'Kirki\Control\Date', 'Kirki_Control_Date' ],
			[ 'Kirki\Control\Dimension', 'Kirki_Control_Dimension' ],
			[ 'Kirki\Control\Editor', 'Kirki_Control_Editor' ],
			[ 'Kirki\Control\Generic', 'Kirki_Control_Generic' ],
			[ 'Kirki\Control\Image', 'Kirki_Control_Image' ],
			[ 'Kirki\Control\Multicheck', 'Kirki_Control_Multicheck' ],
			[ 'Kirki\Control\Generic', 'Kirki_Control_Number' ],
			[ 'Kirki\Control\Palette', 'Kirki_Control_Palette' ],
			[ 'Kirki\Control\Radio', 'Kirki_Control_Radio' ],
			[ 'Kirki\Control\Radio_Buttonset', 'Kirki_Control_Radio_Buttonset' ],
			[ 'Kirki\Control\Radio_Image', 'Kirki_Control_Radio_Image' ],
			[ 'Kirki\Control\Radio_Image', 'Kirki_Controls_Radio_Image_Control' ],
			[ 'Kirki\Control\Repeater', 'Kirki_Control_Repeater' ],
			[ 'Kirki\Control\Select', 'Kirki_Control_Select' ],
			[ 'Kirki\Control\Slider', 'Kirki_Control_Slider' ],
			[ 'Kirki\Control\Sortable', 'Kirki_Control_Sortable' ],
			[ 'Kirki\Control\Upload', 'Kirki_Control_Upload' ],
			[ 'Kirki\Settings\Repeater', 'Kirki\Settings\Repeater_Setting' ],
			[ 'Kirki\Settings\Repeater', 'Kirki_Settings_Repeater_Setting' ],
			[ 'WP_Customize_Section', 'Kirki_Sections_Default_Section' ],
			[ 'Kirki\Section_Types\Expanded', 'Kirki_Sections_Expanded_Section' ],
			[ 'Kirki\Section_Types\Nested', 'Kirki_Sections_Nested_Section' ],
			[ 'Kirki\Section_Types\Link', 'Kirki_Sections_Link_Section' ],
			[ 'Kirki\Panel_Types\Nested', 'Kirki_Panels_Nested_Panel' ],
		],
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {
		$this->add_aliases();
		add_action( 'customize_register', [ $this, 'add_customizer_aliases' ] );
	}

	/**
	 * Adds object aliases.
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function add_aliases() {
		foreach ( $this->aliases['generic'] as $item ) {
			if ( class_exists( $item[0] ) ) {
				class_alias( $item[0], $item[1] );
			}
		}
	}

	/**
	 * Adds object aliases for classes that get instantiated on customize_register.
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function add_customizer_aliases() {
		foreach ( $this->aliases['customizer'] as $item ) {
			if ( class_exists( $item[0] ) ) {
				class_alias( $item[0], $item[1] );
			}
		}
	}
}
