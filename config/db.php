<?php

/**
 * Database Configuration for Production
 * Create db-local.php for Development
 */

if (file_exists(__DIR__ . '/db-local.php')) {
	return require(__DIR__ . '/db-local.php');
}
return [
	'db' => [
		'class' => 'yii\db\Connection',
		'dsn' => 'mysql:host=localhost;port=3306;dbname=app_bin2',
		'username' => 'admin',
		'password' => 'password',
		'charset' => 'utf8',
	]
];
