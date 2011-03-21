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
//
//
if(!(empty ($_POST['css']))) {
  $css = ($_POST['css'] == 'classic') ? 'classic' : 'modern';
  $_SESSION['css'] = "stylesheet/{$css}.css";
  $redirect = $_POST['redirect'];
}

$redirect = empty($redirect) ? 'home' : $redirect;
header("Location: ". WS_SITELINK . "?p={$redirect}");
?>
