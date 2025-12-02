<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group
----------------------------------------------------
 https://dle-news.ru/
----------------------------------------------------
 Copyright (c) 2004-2025 SoftNews Media Group
=====================================================
 This code is protected by copyright
=====================================================
 File: install.php
-----------------------------------------------------
 Use: Script installation
=====================================================
*/

error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('html_errors', '0');

session_start();

header("Content-type: text/html; charset=utf-8");

define('DATALIFEENGINE', true);
define('ROOT_DIR', dirname(__FILE__));
define('ENGINE_DIR', ROOT_DIR . '/engine');

require_once(ENGINE_DIR . '/inc/include/functions.inc.php');

$is_loged_in = false;
$selected_language = 'Russian';
$PHP_MIN_VERSION = '8.0';

$_REQUEST['action'] = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

$url = explode(basename($_SERVER['PHP_SELF']), $_SERVER['PHP_SELF']);
$url = reset($url);
$_IP = get_ip();

if (isSSL()) $url = "https://" . $_SERVER['HTTP_HOST'] . $url;
else $url = "http://" . $_SERVER['HTTP_HOST'] . $url;

if (isset($_POST['selected_language'])) {

  $_POST['selected_language'] = totranslit($_POST['selected_language'], false, false);

  if ($_POST['selected_language'] and @is_dir(ROOT_DIR . '/language/' . $_POST['selected_language'])) {

    $selected_language = $_POST['selected_language'];
    set_cookie("selected_language", $selected_language, 365);
  }
  
} elseif (isset($_COOKIE['selected_language'])) {

  $_COOKIE['selected_language'] = totranslit($_COOKIE['selected_language'], false, false);

  if ($_COOKIE['selected_language'] != "" and @is_dir(ROOT_DIR . '/language/' . $_COOKIE['selected_language'])) {
    $selected_language = $_COOKIE['selected_language'];
  }
  
}

include_once(ROOT_DIR . '/language/' . $selected_language . '/adminpanel.lng');
include_once(ROOT_DIR . '/language/' . $selected_language . '/install.lng');

if ($lang['direction'] == 'rtl') $rtl_prefix = '_rtl'; else $rtl_prefix = '';

$skin_header = <<<HTML
<!doctype html>
<html lang="{$lang['language_code']}" dir="{$lang['direction']}">
<head>
  <meta charset="utf-8">
  <title>{$lang['install_1']}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="apple-mobile-web-app-capable", content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <link type="text/css" rel="stylesheet" media="screen" href="public/fonts/fontawesome/styles.min.css">
  <link type="text/css" rel="stylesheet" media="screen" href="public/adminpanel//stylesheets/application{$rtl_prefix}.css">
  <script src="public/js/jquery3.js"></script>
  <script src="public/js/jqueryui.js"></script>
  <script src="public/adminpanel/javascripts/application.js"></script>
</head>
<body class="no-theme">
<script>
  var dle_act_lang    = [];
  var cal_language    = '{$lang['language_code']}';
  var filedefaulttext = '';
  var filebtntext     = '';
</script>
<style>
.installbox {
  width: 95%;
  max-width: 950px;
  margin-left: auto;
  margin-right: auto;
}
@media (min-width: 769px) {
  .installpanel {
    display: table-cell;
    vertical-align: middle;
  }
  @media (min-height: 600px) {
    .installbox {
      margin-top: -100px;
    }
  }
}
</style>
<div class="navbar navbar-inverse bg-primary-700 mb-20">
  <div class="navbar-header">
    <a class="navbar-brand" href="install.php">{$lang['install_1']}</a>
  </div>
</div>
<div class="page-container">
  <div class="installpanel">
    <div class="installbox">
<!-- Main area -->
HTML;

$skin_footer = <<<HTML
