<?php
// -----------------------
//
// PRss.php
// Create a RSS-feed
//
//

require_once './config.php';
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

$query = "SELECT post_id, post_title, post_content FROM {$tablePosts} ORDER BY post_date DESC LIMIT 10";

$res = $mysqli->query($query);
$site = WS_SITELINK;
$title = WS_TITLE;
$xml = <<<EOD
<?xml version="1.0"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>{$title}</title>
    <link>{$site}</link>
    <atom:link href="{$site}rss.php" rel="self" type="application/rss+xml" />
    <description>Senaste inläggen på {$title}</description>

EOD;
while ($row = $res->fetch_object()) {
  $content = htmlspecialchars($row->post_content);
  $xml .= <<<EOD
    <item>
      <title>{$row->post_title}</title>
      <link>{$site}?p=post&amp;id={$row->post_id}</link>
      <description>{$content}</description>
      <guid>{$site}?p=post&amp;id={$row->post_id}</guid>
    </item>

EOD;
}
$xml .= <<<EOD
  </channel>
</rss>
EOD;

header("Content-Type: text/xml;charset=utf-8");
echo $xml;
?>
