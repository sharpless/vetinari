<?php
// ---------------------------
//
// Page to insert comments into database
//
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


// ------------------------
//
//
$mailtest = empty($_POST['email']) ? '' : strstr($_POST['email'], '@');
if(!(empty ($_POST['title']) || empty ($_POST['content']) || empty ($_POST['author']) || empty ($mailtest))) {
  foreach ($_POST as $key => $value) {
    $$key = $value;
  }
  $_POST = array ();
  $query = "INSERT INTO {$tableComments}(comment_post_id, comment_title, comment_content, comment_author, comment_email, comment_date)  VALUES (?, ?, ?, ?, ?, NOW())";
  $stmt = $mysqli->stmt_init();
  if ($stmt->prepare($query)) {
    $stmt->bind_param("issss", $id, htmlentities($title), nl2br(htmlentities($content)), htmlentities($author), htmlentities($email));
    $stmt->execute();
    $stmt->close();
  }
}
$redirect = empty($_POST['redirect']) ? 'home' : $_POST['redirect'];
header("Location: ". WS_SITELINK . "?p={$redirect}");
?>
