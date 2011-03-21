<?php
// ---------------------------
//
// Show user info
//
// Author: Fredrik Larsson
// Created: 2010
//
// ------------------------

// ------------------------
// Check if access allowed
//


if (!$indexVisited || !isset ($_SESSION['userid'])) {
  header("Location: " . WS_SITELINK);
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

// ------------------------
// Prepare query, execute and create HTML
//
$tableUser = DB_PREFIX . 'User';
$tableGroup	= DB_PREFIX . 'Group';
$tableGroupMember = DB_PREFIX . 'GroupMember';


$query = <<<EOD
SELECT
  idUser,
  accountUser,
  emailUser
FROM {$tableUser}
WHERE idUser = {$_SESSION['userid']};

EOD;

$query .= <<<EOD
SELECT
  idGroup,
  nameGroup
FROM {$tableGroupMember} AS GM
INNER JOIN {$tableGroup} AS G
ON GM.GroupMember_idGroup = G.idGroup
WHERE GM.GroupMember_idUser = {$_SESSION['userid']}
EOD;

$mysqli->multi_query($query);

$resUser = $mysqli->store_result();
$rowUser = $resUser->fetch_object();
$html = <<<EOD
  <form>
    <label for="idUser">Användarid:<input type="text" readonly="readonly" value="{$rowUser->idUser}" id="idUser" /></label><br />
    <label for="accountUser">Användarnamn:<input type="text" readonly="readonly" value="{$rowUser->accountUser}" id="accountUser" /></label><br />
    <label for="emailUser">E-postadress:<input type="text" readonly="readonly" value="{$rowUser->emailUser}" id="emailUser" /></label><br />
EOD;
$resUser->free();
$mysqli->next_result();
$resGroup = $mysqli->store_result();
$i = 0;
while ($rowGroup = $resGroup->fetch_object()) {
  $i++;
  if ($resGroup->num_rows == 1) $i = '';
  $html .= <<<EOD
    <label for="idGroup{$i}">Grupp {$i}:<input type="text" readonly="readonly" value="{$rowGroup->idGroup}" id="idGroup{$i}" /></label><br />
    <label for="nameGroup{$i}">Gruppbeskrivning {$i}:<input type="text" readonly="readonly" value="{$rowGroup->nameGroup}" id="nameGroup{$i}" /></label><br />
EOD;
}
$html .= "</form>\n";

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');

$stylesheet = 'stylesheet.css';

$page = new CHTMLPage($stylesheet);

$page->printHTMLHeader("Kontoinfo");
$page->printPageHeader();
$page->printPageBody($html);
$page->printPageFooter();
?>
