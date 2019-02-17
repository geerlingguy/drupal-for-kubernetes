<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Drupal site-specific configuration file.
 */

// Config sync directory.
$config_directories['sync'] = '../config/sync';

// Hash salt.
$settings['hash_salt'] = '$DRUPAL_HASH_SALT';

// Disallow access to update.php by anonymous users.
$settings['update_free_access'] = FALSE;

// Other helpful settings.
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['entity_update_batch_size'] = 50;
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

// Database connection.
$databases['default']['default'] = [
  'database' => getenv('DRUPAL_DATABASE_NAME'),
  'username' => getenv('DRUPAL_DATABASE_USERNAME'),
  'password' => getenv('DRUPAL_DATABASE_PASSWORD'),
  'prefix' => '',
  'host' => getenv('DRUPAL_DATABASE_HOST'),
  'port' => getenv('DRUPAL_DATABASE_PORT'),
  'namespace' => 'Drupal\Core\Database\Driver\mysql',
  'driver' => 'mysql',
];
