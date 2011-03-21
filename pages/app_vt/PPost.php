<?php
//
// PPost.php
//
// Show one post
//
//

// Check if access is allowed

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

// Make sure to get the right post

if (isset ($_GET['id']) && is_numeric($_GET['id']) && $_GET['p'] == 'post') {
  $id = $_GET['id'];
  $queryPost = <<<EOD
SELECT post_id,
  post_content,
  post_title,
  post_date,
  user_id,
  user_name,
  COUNT(comment_id) AS count_comment
FROM {$tablePosts}
  INNER JOIN {$tableUsers}
    ON post_author = user_id
  LEFT JOIN {$tableComments}
    ON post_id = comment_post_id
WHERE post_id = {$id} GROUP BY post_id
EOD;
} else {
  header("Location: ?p=home");
  exit;
}

// Get categories

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
  if (empty ($categories)) {
    $categories = 'Ej taggat';
  } else {
    $categories = "Taggar: {$categories}";
  }


// Make the page

$resPost = $mysqli->query($queryPost);

$rowPost = $resPost->fetch_object();

  if (isset ($_SESSION['userid']) && ($_SESSION['userid'] == $rowPost->user_id)) {
    $edit = " | <a href='?p=edit&amp;id={$rowPost->post_id}'>Redigera</a> | <a href='?p=delete&amp;id={$rowPost->post_id}'>Ta bort</a>";
  } else {
    $edit = '';
  }
if ($rowPost->count_comment > 1 ) {
  $comments = "{$rowPost->count_comment} kommentarer";
} else if ($rowPost->count_comment == 1) {
  $comments = "1 kommentar";
} else {
  $comments = "Bli den första att skriva en kommentar";
}
$date = new DateTime($rowPost->post_date);
$postDate = $date->format('Y-m-d');
$htmlLeft = <<<EOD
    <div class="post">
      <h2><a href="?p=post&amp;id={$rowPost->post_id}">{$rowPost->post_title}</a></h2>
      <p class="small">Skrivet {$postDate} av {$rowPost->user_name}{$edit}<span class="alignright">{$comments}</span></p>
      <div class="content">
        {$rowPost->post_content}
      </div>
      <p class="small">{$categories}</p>
      <p>&nbsp;</p>
    </div>
EOD;

// Add anchor
  $htmlLeft .= "<a name='comments'></a>\n";
// Comments (if any)
if ($rowPost->count_comment > 0) {
  $queryComment = <<<EOD
  SELECT comment_author,
    comment_content,
    comment_title,
    comment_email,
    comment_date
  FROM {$tableComments}
  WHERE comment_post_id = {$id}
EOD;
  $resComment = $mysqli->query($queryComment);
  while ($rowComment = $resComment->fetch_object()) {
    $email = explode('@', $rowComment->comment_email);

    $htmlLeft .= <<<EOD
      <div class="comment">
        <p class="commentator">Kommentar av {$rowComment->comment_author} ({$email[0]}) den {$rowComment->comment_date}</p>
        <h3>{$rowComment->comment_title}</h3>
        <p>{$rowComment->comment_content}</p>
      </div>
EOD;
  }
}

// Comment form

$htmlLeft .= <<<EOD
<div class="commentform">
  <form action="?p=comment" method="post" onsubmit="return validateForm();">
    <p>
      <label for="title">Rubrik:<br /><input type="text" name="title" id="title" /></label><br />
      <label for="content">Kommentar:<br /><textarea name="content" id="content" rows="3" cols="40"></textarea></label><br />
      <label for="author">Namn:<br /><input type="text" name="author" id="author" /></label><br />
      <label for="email">E-postadress:<br /><input type="text" name="email" id="email" /></label><br />
      <input type="hidden" name="id" value="{$id}" />
      <input type="hidden" name="redirect" value="post&amp;id={$id}" />
      <input type="submit" value="Kommentera" name="submit" />
    </p>
  </form>
</div>
EOD;
// Page title

$title = $rowPost->post_title;

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');

$page = new CHTMLPage();

$page->printHTMLHeader($title, 'yes');
$page->printPageHeader();
$page->setLeftColumn($htmlLeft);
$page->setRightColumn($page->setSideBar());
$page->printPageBody();
$page->printPageFooter();


?>