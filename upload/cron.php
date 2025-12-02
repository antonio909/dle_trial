<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group
-----------------------------------------------------
 https://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2025 SoftNews Media Group
=====================================================
 This code is protected by copyright
=====================================================
 File: cron.php
-----------------------------------------------------
 Use: Cron operations
=====================================================
*/

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
To support the launch operations for the cron you need set a value 1 for the variable $allow_cron
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

$allow_cron = 0;

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Specify the number of backup files database for save on the server
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

$max_count_files = 5;

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Don't edit the code which follows bellow.
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

if ($allow_cron) {

  define('DATALIFEENGINE', true);
  define('AUTOMODE', true);
  define('LOGGED_IN', true);

  define('ROOT_DIR', dirname(__FILE__));
  define('ENGINE_DIR', ROOT_DIR . '/engine');

  require_once(ENGINE_DIR . '/classes/plugins.class.php');
  require_once(DLEPlugins::Check(ENGINE_DIR . '/inc/include/functions.inc.php'));
  include_once(DLEPlugins::Check(ROOT_DIR . '/language/' . $config['langs'] . '/website.lng'));

  date_default_timezone_set($config['date_adjust']);

  $cronmode = false;

  if (isset($_REQUEST['cronmode']) AND $_REQUEST['cronmode']) {
    $cronmode = $_REQUEST['cronmode'];
  } elseif (isset($_SERVER['argc']) && !empty($_SERVER['argc']) && $_SERVER['argc'] > 1) {
    $cronmode = $_SERVER['argv'][1];
  }

  $_REQUEST = array();
  $_POST = array();
  $_GET = array();
  $_REQUEST['user_hash'] = 1;
  $dle_login_hash = 1;

  if ($cronmode == "sitemap") {

    $_POST['action'] = "create";
    $member_id = array();
    $user_group = array();
    $member_id['user_group'] = 1;
    $user_group[$member_id['user_group']]['admin_googlemap'] = 1;

    $cat_info = get_vars("category");

    if (!is_array($cat_info)) {

      $cat_info = array();

      $db->query("SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC");

      while ($row = $db->get_row()) {
        if (!$row['active']) {
          continue;
        }

        $cat_info[$row['id']] = array();

        foreach ($row as $key => $value) {
          $cat_info[$row['id']][$key] = stripslashes($value);
        }
      }
      set_vars("category", $cat_info);
      $db->free();
    }

    include_once(DLEPlugins::Check(ROOT_DIR . '/engine/inc/googlemap.php'));

    die("done");
  } elseif ($cronmode == "optimize") {

    $arr = array();
    $tables = "";

    $db->query("SHOW TABLES");
    while ($row = $db->get_array()) {
      if (substr($row[0], 0, strlen(PREFIX)) == PREFIX) {
        $tables .= ", `" . $db->safesql($row[0]) . "`";
      }
    }
    $db->free();

    $tables = substr($tables, 1);
    $query = "OPTIMIZE TABLE ";
    $query .= $tables;

    $db->query($query);
    die("done");
  } elseif ($cronmode == "antivirus") {

    include_once(DLEPlugins::);
  }
}
