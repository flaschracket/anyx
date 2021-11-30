<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'anyx',
    'version' => '5.0.0',
    'path' => 'application/modules/Anyx',
    'title' => 'Anyx interface',
    'description' => 'the interface that help other resources to integrate with a social engine',
    'author' => '2RAD',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
	    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    	),
    'directories' => 
    array (
      0 => 'application/modules/Anyx',
    	),
    'files' => 
    array (
      0 => 'application/languages/en/anyx.csv',
    	),
  ),
// Routes --------------------------------------------------------------------
  'routes' => array(
    'anyx_general' => array(
      'route' => 'anyx/:action/*',
      'defaults' => array(
        'module' => 'anyx',
        'controller' => 'index',
        'action' => 'index',
			),
		          ),
),
);

 ?>