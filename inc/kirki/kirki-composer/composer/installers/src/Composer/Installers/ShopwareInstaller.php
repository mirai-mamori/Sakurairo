<?php

namespace Composer\Installers;

/**
 * Plugin/theme installer for shopware
 * @author Benjamin Boit
 */
class ShopwareInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'backend-plugin'    => 'engine/Shopware/Plugins/Local/Backend/{$name}/',
        'core-plugin'       => 'engine/Shopware/Plugins/Local/Core/{$name}/',
        'frontend-plugin'   => 'engine/Shopware/Plugins/Local/Frontend/{$name}/',
        'theme'             => 'templates/{$name}/',
        'plugin'            => 'custom/plugins/{$name}/',
        'frontend-theme'    => 'themes/Frontend/{$name}/',
    );

    /**
     * Transforms the names
     */
    public function inflectPackageVars(array $vars): array
    {
        if ($vars['type'] === 'shopware-theme') {
            return $this->correctThemeName($vars);
        }

        return $this->correctPluginName($vars);
    }

    /**
     * Changes the name to a camelcased combination of vendor and name
     *
     * @param array<string, string> $vars
     * @return array<string, string>
     */
    private function correctPluginName(array $vars): array
    {
        $camelCasedName = preg_replace_callback('/(-[a-z])/', function ($matches) {
            return strtoupper($matches[0][1]);
        }, $vars['name']);

        if (null === $camelCasedName) {
            throw new \RuntimeException('Failed to run preg_replace_callback: '.preg_last_error());
        }

        $vars['name'] = ucfirst($vars['vendor']) . ucfirst($camelCasedName);

        return $vars;
    }

    /**
     * Changes the name to a underscore separated name
     *
     * @param array<string, string> $vars
     * @return array<string, string>
     */
    private function correctThemeName(array $vars): array
    {
        $vars['name'] = str_replace('-', '_', $vars['name']);

        return $vars;
    }
}
