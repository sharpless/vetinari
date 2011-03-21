<?php
// ---------------------------
//
// Page to check credentials
//
// Author: Fredrik Larsson
// Created: 2010
//
// ------------------------

// ------------------------
// ---------------------------------------------------------------------
//
// Destroy the current session (logout user), if it exists, review the manual
// http://se.php.net/manual/en/function.session-destroy.php
//

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time()-42000, '/');
}

// Finally, destroy the session.
session_destroy();

session_start(); // Must call it since we destroyed it above.
session_regenerate_id(); // To avoid problems

$username = empty ($_POST['username']) ? '' : $_POST['username'];
$password = empty ($_POST['password']) ? '' : $_POST['password'];

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

$query = <<<EOD
SELECT user_id, user_login
FROM {$tableUsers}
WHERE user_login = ?
AND user_password = md5(?);
EOD;
$stmt = $mysqli->stmt_init();
if ($stmt->prepare($query)) {
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($user_id, $user_login);
  if ($stmt->num_rows === 1) {
    $stmt->fetch();
    $_SESSION['userid'] = $user_id;
    $_SESSION['username'] = $user_login;
  } else {
    $_SESSION['errorMessage']    = "Inloggningen misslyckades";
    $_POST['redirect']           = 'login';
  }
} else {
  echo "Fel :" . $mysqli->error;
  exit;
}
$stmt->free_result();
$stmt->close();

$redirect = empty ($_POST['redirect']) ? 'home' : $_POST['redirect'];

header('Location: ' . WS_SITELINK . "?p={$redirect}");

?>
