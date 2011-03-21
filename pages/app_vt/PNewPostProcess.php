<?php
//
// PNewPostProcess.php
//
// Process data from PNewPost.php
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
if(!(empty ($_POST['title']) || empty ($_POST['content']))) {
  foreach ($_POST as $key => $value) {
    $$key = $value;
  }
  $_POST = array ();
  $id = NULL;
  $query = "INSERT INTO {$tablePosts}( post_title, post_content, post_author, post_date, post_modified_date)  VALUES (?, ?, {$_SESSION['userid']}, NOW(), NOW())";
  $stmt = $mysqli->stmt_init();
  if ($stmt->prepare($query)) {
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    $stmt->close();
    $queryid = "SELECT MAX(post_id) AS p_id FROM {$tablePosts} WHERE post_author = {$_SESSION['userid']}";
    $resid = $mysqli->query($queryid);
    $rowid = $resid->fetch_object();
    $id = $rowid->p_id;
    if (!empty($categories)) {
      $categories = explode(',', $categories);
      $i = 0;
      while (!empty ($categories[$i])) {
        $categories[$i] = trim($categories[$i]);
        $i++;
      }
      $catQuery = "INSERT IGNORE INTO {$tableCategories} (category_name) VALUES";
      $catIdQuery = "SELECT category_id FROM {$tableCategories} WHERE";
      foreach ($categories as $category) {
        $category = $mysqli->real_escape_string($category);
        $catQuery .= "('{$category}'), ";
        $catIdQuery .= " category_name = '{$category}' OR";
      }
      $catQuery = rtrim($catQuery, ', ');
      $catIdQuery = rtrim($catIdQuery, ' OR');
      $mysqli->query($catQuery) or die($mysqli->error);
      $catIdRes = $mysqli->query($catIdQuery) or die($mysqli->error);
      if ($catIdRes->num_rows > 0) {
        $catPostCatQuery = "INSERT INTO {$tablePostCategories} (pc_post_id, pc_category_id) VALUES ";
        while ($catIdRow = $catIdRes->fetch_object()) {
          $catPostCatQuery .= "({$id}, {$catIdRow->category_id}), ";
        }
        $catPostCatQuery = rtrim($catPostCatQuery, ', ');
        $mysqli->query($catPostCatQuery);
      }
    }
  }
}
$redirect = empty($redirect) ? 'home' : $redirect;
header("Location: ". WS_SITELINK . "?p={$redirect}{$id}");

?>
