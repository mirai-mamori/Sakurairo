<?php
/**
 * The Kirki Customizer class.
 *
 * @package kirki
 * @since 5.0.0
 */

namespace Kirki;

use Kirki\L10n;
use Kirki\Compatibility\Modules;
use Kirki\Compatibility\Framework;
use Kirki\Compatibility\Kirki;

/**
 * The Customizer class.
 */
class Customizer
{

    /**
     * Init the customizer.
     *
     * @return void
     */
    public static function init()
    {

        if (!defined('KIRKI_PLUGIN_FILE')) {
            define('KIRKI_PLUGIN_FILE', dirname(__DIR__) . '/kirki.php');
        }

        require_once __DIR__ . '/lib/class-aricolor.php';
        require_once __DIR__ . '/lib/class-kirki-color.php';
        require_once dirname(__DIR__) . '/vendor/autoload.php';
        require_once __DIR__ . '/bootstrap.php';

        if (!defined('KIRKI_VERSION')) {
            define('KIRKI_VERSION', '5.2.3');
        }

        if (!defined('KIRKI_PLUGIN_DIR')) {
            define('KIRKI_PLUGIN_DIR', dirname(__DIR__));
        }

        if (!defined('KIRKI_PLUGIN_URL')) {
            define('KIRKI_PLUGIN_URL', URL::get_from_path(dirname(__DIR__)));
        }

        // Start Kirki.
        global $kirki;
        $kirki = Framework::get_instance();

        // Instantiate the modules.
        $kirki->modules = new Modules();

        // Instantiate classes.
        new Kirki();
        new L10n('kirki', dirname(__DIR__) . '/languages');

        // Add an empty config for global fields.
        Kirki::add_config('');

        // Load custom config if exists.
        $custom_config_path = dirname(__DIR__) . '/custom-config.php';
        if (file_exists($custom_config_path)) {
            require_once $custom_config_path;
        }

        // Add upgrade notifications.
        require_once __DIR__ . '/lib/upgrade-notifications.php';

        // Load packages.
        require_once __DIR__ . '/packages/index.php';

        // Handle tests.
        if (defined('KIRKI_TEST') && true === constant('KIRKI_TEST') && file_exists(__DIR__ . '/example.php')) {
            include_once __DIR__ . '/example.php';
        }

        // Enqueue common assets.
        add_action('customize_controls_enqueue_scripts', [__CLASS__, 'enqueue_assets'], 5);
        add_action('customize_preview_init', [__CLASS__, 'enqueue_assets'], 5);
    }

    /**
     * Enqueue common assets.
     *
     * @param array $deps The script dependencies.
     * @return void
     */
    public static function enqueue_assets($deps = [])
    {
        // Preview frame is NOT admin, Pane frame IS admin.
        $is_preview_frame = is_customize_preview() && !is_admin();

        if (empty($deps)) {
            $deps = ['jquery', 'wp-element'];
            if ($is_preview_frame) {
                $deps[] = 'customize-preview';
            } else {
                $deps[] = 'customize-controls';
            }
        }

        $file = $is_preview_frame ? 'preview' : 'controls';

        wp_enqueue_style('kirki-customizer', URL::get_from_path(dirname(__DIR__) . "/assets/customizer/{$file}.min.css"), [], KIRKI_VERSION);
        wp_enqueue_script('kirki-customizer', URL::get_from_path(dirname(__DIR__) . "/assets/customizer/{$file}.min.js"), $deps, KIRKI_VERSION, true);
    }
}
