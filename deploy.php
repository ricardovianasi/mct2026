<?php

namespace Deployer;

require 'recipe/common.php';

// Config

// Project name
set('application', 'mct2026');

set('repository', 'git@github.com:ricardovianasi/mct2026.git');

// [Optional] Allocate tty for git clone. Default value is false.
//set('git_tty', true);
set('ssh_type', 'native');
set('ssh_multiplexing', true);

// Shared files/dirs between deploys
set('shared_dirs', ['wp-content/uploads', 'wp-content/ewww', 'wp-content/cache', 'wp-content/w3tc-config', 'wp-content/languages']);
set('writable_dirs', ['wp-content/uploads', 'wp-content/ewww', 'wp-content/cache', 'wp-content/w3tc-config', 'wp-content/languages']);

set('http_user', 'www-data');
set('writable_chmod_mode', '0755');
set('writable_mode', 'chown');

// Assets Dir
set('assets_dir', 'wp-content/themes/up/assets');
set('rsync_src', __DIR__ . '/wp-content/themes/up/assets/dist');
set('rsync_dest', '{{release_path}}/wp-content/themes/up/assets/');

set('prod_files', ['wp-config.php.prod', '.htaccess.prod']);

// Hosts

//host('167.172.252.128')
//  ->set('remote_user', 'root')
//  ->set('identity_file', '~/.ssh/id_rsa')
//  ->set('deploy_path', '/var/www/html/{{application}}');

host('159.203.111.152')
  ->set('remote_user', 'root')
  ->set('identity_file', '~/.ssh/id_rsa')
  ->set('deploy_path', '/var/www/html/{{application}}');

host('167.172.17.179')
  ->set('remote_user', 'root')
  ->set('identity_file', '~/.ssh/id_rsa')
  ->set('deploy_path', '/var/www/html/{{application}}');

// Hooks
//Task to change shared filenames.
task('up:profiles', function () {

  foreach (get('prod_files') as $file) {
    if (strpos($file, '.prod')) {
      // Rename
      run("mv {{release_path}}/$file {{release_path}}/" . str_replace('.prod', '', $file));
    }
  }
});

task('up:compile_assets', function () {
  $assetsDir = __DIR__ . '/' . trim(get('assets_dir'), '/');

  // Check if shared dir does not exist.
  if (!testLocally("[ -d $assetsDir ]")) {
    throw new Exception("Assets dir '$assetsDir' doesn't exist.");
  }

  runLocally("cd $assetsDir && npm rum build");
  runLocally("cd " . __DIR__);
});

task('up:rsync', function () {
  upload(get('rsync_src'), get('rsync_dest'));
});

// Tasks
desc('Deploy your project');
task('deploy', [
  'deploy:prepare',
  'up:profiles',
  'up:compile_assets',
  'up:rsync',
  'deploy:publish',
]);


after('deploy:failed', 'deploy:unlock');
