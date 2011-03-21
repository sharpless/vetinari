<?php
//
// PAllPosts.php
//
// Show all posts
// 
//
//

if (!isset ($indexVisited)) {
  die('Access not allowed');
}

//
// Create a new database object, we are using the MySQLi-extension.
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


// Page title

$title = WS_TITLE;

// Stylesheet

$css = isset ($_SESSION['css']) ? $_SESSION['css'] : WS_CSS;

//
// Create queries
//
$queryPosts = <<<EOD
SELECT post_id,
  post_content,
  post_title,
  post_date,
  user_id,
  user_name,
  (SELECT COUNT(comment_post_id)) AS count_comment
FROM {$tablePosts}
  INNER JOIN {$tableUsers}
    ON post_author = user_id
  LEFT JOIN {$tableComments}
    ON post_id = comment_post_id
GROUP BY post_id
ORDER BY post_date DESC
EOD;

// Main content

$resPosts = $mysqli->query($queryPosts);
if (!$resPosts->num_rows) {
  header("Location: " . WS_SITELINK . "?p=install");
  exit;
}
$htmlLeft = "";
while ($rowPosts = $resPosts->fetch_object()) {
  $queryCategories = <<<EOD
SELECT category_name
FROM {$tableCategories}
LEFT JOIN {$tablePostCategories}
ON category_id = pc_category_id
WHERE pc_post_id = {$rowPosts->post_id}
EOD;
  $resCategories = $mysqli->query($queryCategories);
  $categories = '';
  while ($rowCategories = $resCategories->fetch_object()) {
    $categories .= "{$rowCategories->category_name}, ";
  }
  $categories = rtrim($categories, ', ');
  if (empty ($categories)) {
    $categories = 'Ej taggat';
  } else {
    $categories = "Taggar: {$categories}";
  }
  if (isset ($_SESSION['userid']) && ($_SESSION['userid'] == $rowPosts->user_id)) {
    $edit = " | <a href='?p=edit&amp;id={$rowPosts->post_id}'>Redigera</a> | <a href='?p=delete&amp;id={$rowPosts->post_id}'>Ta bort</a>";
  } else {
    $edit = '';
  }
  if ($rowPosts->count_comment > 1 ) {
    $comments = "<a href='?p=post&amp;id={$rowPosts->post_id}#comments'>{$rowPosts->count_comment} kommentarer</a>";
  } else if ($rowPosts->count_comment == 1) {
    $comments = "<a href='?p=post&amp;id={$rowPosts->post_id}#comments'>1 kommentar</a>";
  } else {
    $comments = "<a href='?p=post&amp;id={$rowPosts->post_id}#comments'>inga kommentarer</a>";
  }
  $date = new DateTime($rowPosts->post_date);
  $postDate = $date->format('Y-m-d');
  $htmlLeft .= <<<EOD
    <div class="post">
      <h2><a href="?p=post&amp;id={$rowPosts->post_id}">{$rowPosts->post_title}</a></h2>
      <p class="small"><span class="alignleft">Skrivet {$postDate} av {$rowPosts->user_name}{$edit}</span> <span class="alignright">Det finns {$comments}</span></p>
      <div class="content clear">
        {$rowPosts->post_content}
      </div>
      <p class="small">{$categories}</p>
      <p>&nbsp;</p>
    </div>
EOD;
}

// Sidebar
// First the user part

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');

$page = new CHTMLPage();
$page->setLeftColumn($htmlLeft);
$page->setRightColumn($page->setSideBar());
$page->printHTMLHeader($title);
$page->printPageHeader();
$page->printPageBody();
$page->printPageFooter();


?>