<?php
//
// PEditPostProcess.php
//
// Process data from EditPost.php
// Author: Fredrik Larsson
// Created: 2010
//
// ------------------------

// ------------------------
// Create database connection
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
// ------------------------
//
//
if(!(empty ($_GET['id']) || empty ($_SESSION['userid']))) {
  $id = $_GET['id'];
  $queryPost = "DELETE FROM {$tablePosts} WHERE post_id = {$id} AND post_author = {$_SESSION['userid']}";
  $queryComment = "DELETE FROM {$tableComments} WHERE comment_post_id = {$id}";
  $queryCategories = "DELETE FROM {$tablePostCategories} WHERE pc_post_id = {$id}";
  $mysqli->query($queryCategories) or die($mysqli->error);
  $mysqli->query($queryComment) or die($mysqli->error);
  $mysqli->query($queryPost) or die($mysqli->error);



  }

header("Location: ". WS_SITELINK);
?>
