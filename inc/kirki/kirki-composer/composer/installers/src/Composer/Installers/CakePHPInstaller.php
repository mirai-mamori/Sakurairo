<?php

namespace Composer\Installers;

use Composer\DependencyResolver\Pool;
use Composer\Semver\Constraint\Constraint;

class CakePHPInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array(
        'plugin' => 'Plugin/{$name}/',
    );

    /**
     * Format package name to CamelCase
     */
    public function inflectPackageVars(array $vars): array
    {
        if ($this->matchesCakeVersion('>=', '3.0.0')) {
            return $vars;
        }

        $nameParts = explode('/', $vars['name']);
        foreach ($nameParts as &$value) {
            $value = strtolower($this->pregReplace('/(?<=\\w)([A-Z])/', '_\\1', $value));
            $value = str_replace(array('-', '_'), ' ', $value);
            $value = str_replace(' ', '', ucwords($value));
        }
        $vars['name'] = implode('/', $nameParts);

        return $vars;
    }

    /**
     * Change the default plugin location when cakephp >= 3.0
     */
    public function getLocations(string $frameworkType): array
    {
        if ($this->matchesCakeVersion('>=', '3.0.0')) {
            $this->locations['plugin'] =  $this->composer->getConfig()->get('vendor-dir') . '/{$vendor}/{$name}/';
        }
        return $this->locations;
    }

    /**
     * Check if CakePHP version matches against a version
     *
     * @phpstan-param Constraint::STR_OP_* $matcher
     */
    protected function matchesCakeVersion(string $matcher, string $version): bool
    {
        $repositoryManager = $this->composer->getRepositoryManager();
        /** @phpstan-ignore-next-line */
        if (!$repositoryManager) {
            return false;
        }

        $repos = $repositoryManager->getLocalRepository();
        /** @phpstan-ignore-next-line */
        if (!$repos) {
            return false;
        }

        return $repos->findPackage('cakephp/cakephp', new Constraint($matcher, $version)) !== null;
    }
}
