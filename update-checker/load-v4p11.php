<?php
require dirname(__FILE__) . '/Puc/v4p11/Autoloader.php';
new Puc_v4p11_Autoloader();

require dirname(__FILE__) . '/Puc/v4p11/Factory.php';
require dirname(__FILE__) . '/Puc/v4/Factory.php';

//Register classes defined in this version with the factory.
foreach (
	array(
		'Plugin_UpdateChecker' => 'Puc_v4p11_Plugin_UpdateChecker',
		'Theme_UpdateChecker'  => 'Puc_v4p11_Theme_UpdateChecker',

		'Vcs_PluginUpdateChecker' => 'Puc_v4p11_Vcs_PluginUpdateChecker',
		'Vcs_ThemeUpdateChecker'  => 'Puc_v4p11_Vcs_ThemeUpdateChecker',

		'GitHubApi'    => 'Puc_v4p11_Vcs_GitHubApi',
		'BitBucketApi' => 'Puc_v4p11_Vcs_BitBucketApi',
		'GitLabApi'    => 'Puc_v4p11_Vcs_GitLabApi',
	)
	as $pucGeneralClass => $pucVersionedClass
) {
	Puc_v4_Factory::addVersion($pucGeneralClass, $pucVersionedClass, '4.11');
	//Also add it to the minor-version factory in case the major-version factory
	//was already defined by another, older version of the update checker.
	Puc_v4p11_Factory::addVersion($pucGeneralClass, $pucVersionedClass, '4.11');
}

