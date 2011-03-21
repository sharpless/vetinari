<?php
// ---------------------------
//
// Write a new post
//
// Author: Fredrik Larsson
// Created: 2010
//
// ------------------------

// ------------------------
// Check if access allowed
//


if (!$indexVisited) {
  die('Access not allowed');
}
if(!isset ($_SESSION['userid'])) {
  header("Location: ?p=home");
}

// -------------------------
// Setup db conn
//
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$mysqli->set_charset('utf8');
if (mysqli_connect_error()) {
  echo "Connect failed: ".mysqli_connect_error()."<br>";
  exit();
}

// Set tablenames

$tablePosts = DB_PREFIX . 'posts';
$tableUsers = DB_PREFIX . 'users';
$tableComments = DB_PREFIX . 'comments';


//Create the form

$html = <<<EOD
  <form action="?p=newp" method="post" onsubmit="return validateForm();">
    <p><label for="title">Rubrik<br /><input type="text" name="title" id="title" /></label></p>
    <p><label for="content">Text<br /><textarea rows="8" cols="80" name="content" id="content"></textarea></label></p>
    <p><label for="categories">Taggar<br /><input type="text" name="categories" id="categories" /></p>
    <p><input type="hidden" value="post&amp;id=" name="redirect" />
    <input type="submit" value="Publicera" name="submit" /></p>
  </form>
EOD;

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');


$page = new CHTMLPage();

$page->printHTMLHeader("Nytt inlägg");
$page->printPageHeader();
$page->printPageBody($html);
$page->printPageFooter();
?>
