<?php
// ---------------------------
//
// Show users
//
// Author: Fredrik Larsson
// Created: 2010
//
// ------------------------

// ------------------------
// Check if access allowed
//

if (!isset ($indexVisited) || !isset ($_SESSION['userid']) || (array_search('adm', $_SESSION['groupid']) === FALSE)) {
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

$html = <<<EOD
<table>
  <tr>
    <th>Id</th>
    <th>Kontonamn</th>
    <th>Användarnamn</th>
    <th>Gruppid</th>
    <th>Gruppbeskrivning</th>
  </tr>
EOD;

$query = <<<EOD
SELECT
  idUser,
  accountUser,
  emailUser
FROM {$tableUser}
EOD;

$res = $mysqli->query($query);

while ($row = $res->fetch_object()) {
  $grpQuery = <<<EOD
  SELECT
    idGroup,
    nameGroup
  FROM {$tableGroupMember} AS GM
  INNER JOIN {$tableGroup} AS G
  ON GM.GroupMember_idGroup = G.idGroup
  WHERE GM.GroupMember_idUser = {$row->idUser}
EOD;

  $grpRes = $mysqli->query($grpQuery);
  while ($grpRow = $grpRes->fetch_object()) {
    $grpId[] = $grpRow->idGroup;
    $grpName[] = $grpRow->nameGroup;
  }
  $grpRes->close();
  
  $grpIdStr = implode(",<br />\n", $grpId);
  $grpNameStr = implode(",<br />\n", $grpName);
  $grpId = array ();
  $grpName = array ();
  
  $html .= <<<EOD
 <tr>
   <td>{$row->idUser}</td>
   <td>{$row->accountUser}</td>
   <td>{$row->emailUser}</td>
   <td>{$grpIdStr}</td>
   <td>{$grpNameStr}</td>
 </tr>
EOD;
}
$html .= "</table>";

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');

$stylesheet = 'stylesheet.css';

$page = new CHTMLPage($stylesheet);

$page->printHTMLHeader("Användare");
$page->printPageHeader();
$page->printPageBody($html);
$page->printPageFooter();

?>
