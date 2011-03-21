<?php
// ---------------------------
//
// Edit a post
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
$tableCategories = DB_PREFIX . 'categories';
$tablePostCategories = DB_PREFIX . 'post_categories';

// Handle $_GET

if ($_GET['p'] == 'edit' && isset ($_GET['id'])) {
  $id = $_GET['id'];
  $query = "SELECT post_title, post_content FROM {$tablePosts} WHERE post_id = {$id} AND post_author = {$_SESSION['userid']}";
  $res = $mysqli->query($query);
  if ($res->num_rows === 1) {
    $row = $res->fetch_object();
    $title = $row->post_title;
    $content = $row->post_content;
    $queryCategories = <<<EOD
SELECT category_name
FROM {$tableCategories}
LEFT JOIN {$tablePostCategories}
ON category_id = pc_category_id
WHERE pc_post_id = {$id}
EOD;
  $resCategories = $mysqli->query($queryCategories);
  $categories = '';
  while ($rowCategories = $resCategories->fetch_object()) {
    $categories .= "{$rowCategories->category_name}, ";
  }
  $categories = rtrim($categories, ', ');

  }
} else {
  header('Location: ?p=home');
  exit;
}


$content = htmlspecialchars($content);
$html = <<<EOD
  <form action="?p=editp" method="post" onsubmit="return validateForm();">
    <p><label for="title">Rubrik<br /><input type="text" value="{$title}" name="title" id="title" /></label></p>
    <p><label for="content">Text<br /><textarea rows="8" cols="80" name="content" id="content">{$content}</textarea></label></p>
    <p><label for="categories">Taggar<br /><input type="text" name="categories" id="categories" value="{$categories}" /></p>
    <p><input type="hidden" value="{$id}" name="id" /></p>
    <p><input type="hidden" value="post&amp;id=" name="redirect" />
    <input type="submit" value="Uppdatera" name="submit" /></p>
  </form>
EOD;

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');


$page = new CHTMLPage();

$page->printHTMLHeader("Uppdatera post");
$page->printPageHeader();
$page->printPageBody($html);
$page->printPageFooter();
?>
