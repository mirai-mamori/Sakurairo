<?php
/**
 * The default section.
 * Inspired from https://github.com/justintadlock/trt-customizer-pro
 *
 * @package kirki-framework/module-sections
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Section_Types;

/**
 * Link Section.
 */
class Link extends \WP_Customize_Section {

	/**
	 * The section type.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var string
	 */
	public $type = 'kirki-link';

	/**
	 * Button Text
	 *
	 * @access public
	 * @since 1.0.0
	 * @var string
	 */
	public $button_text = '';

	/**
	 * Button URL.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var string
	 */
	public $button_url = '';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$json = parent::json();

		$json['button_text'] = $this->button_text;
		$json['button_url']  = $this->button_url;

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	protected function render_template() {
		?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
			<h3 class="accordion-section-title">
				{{ data.title }}
				<a href="{{ data.button_url }}" class="button alignright" target="_blank" rel="nofollow">{{ data.button_text }}</a>
			</h3>
		</li>
		<?php
	}
}
