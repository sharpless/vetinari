<?php
// ---------------------------
//
// config.php
// 
// Includes some constants and variables required for the current app
//
//

// ----------------------
// Database-config
// Contains DB_USER, DB_PASSWORD, DB_DATABASE and DB_HOST
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'database');                          // <-- mysql db name
define('DB_HOST', 'db.example.com');  // <-- mysql server host


//
// The following supports having many databases in one database by using table/view prefix.
//
define('DB_PREFIX',     'vt_');        // Prefix to use infront of tablename and views

// -------------------------------------------------------------------------------------------
//
// Settings for this website (WS), used as default values in CHTMPLPage.php
//
define('WS_TITLE', 'Vetinari');    // The H1 label of this site.
define('WS_CSS', 'stylesheet/classic.css');            // Default stylesheet of the site.
define('WS_FOOTER', 'Fredrik Larsson, 2009-10');    // Footer at the end of the page.
define('WS_SITELINK', 'http://vetinari.example.com/');
//
// Define the menu-array, slight workaround using serialize.
//
$meny = array (
        'Hem' => '?p=home',
        'Install' => '?p=install',
        'XHTML' => 'http://validator.w3.org/check/referer',
        'CSS' => 'http://jigsaw.w3.org/css-validator/check/referer');
define('WS_MENU', serialize($meny));
// ------------------------------------------------------------------------
//
// Settings for the template (TP) structure, where are everything?
//

// Classes, functions, code
define('TP_SOURCEPATH',   dirname(__FILE__) . '/src/');

// Pagecontrollers and modules
define('TP_PAGESPATH',    dirname(__FILE__) . '/pages/');


?>