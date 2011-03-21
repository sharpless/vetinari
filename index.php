<?php
// ---------------------------------
//
// index.php
//
// Frontpage controller
//
// Author: Fredrik Larsson
//

// Include file with common content

require_once './config.php';

// Start session

session_start();

// Stylesheet

$_SESSION['css'] = (isset ($_SESSION['css'])) ? $_SESSION['css'] : WS_CSS;

// Set page
$indexVisited = TRUE;

$_SESSION['page'] = empty ($_GET['p']) ? 'home' : $_GET['p'];

// Load requested page

switch ($_SESSION['page']) {
  case 'post':
    include_once TP_PAGESPATH . 'app_vt/PPost.php';
    break;
  case 'author':
    include_once TP_PAGESPATH . 'app_vt/PAuthor.php';
    break;
  case 'comment':
    include_once TP_PAGESPATH . 'app_vt/PCommentProcess.php';
    break;
  case 'login':
    include_once TP_PAGESPATH . 'login/PLogin.php';
    break;
  case 'loginp' :
    include_once TP_PAGESPATH . 'login/PLoginProcess.php';
    break;
  case 'logout':
    include_once TP_PAGESPATH . 'login/PLogoutProcess.php';
    break;
  case 'install':
    include_once TP_PAGESPATH . 'install/PRestore.php';
    break;
  case 'edit':
    include_once TP_PAGESPATH . 'app_vt/PEditPost.php';
    break;
  case 'editp':
    include_once TP_PAGESPATH . 'app_vt/PEditPostProcess.php';
    break;
  case 'new':
    include_once TP_PAGESPATH . 'app_vt/PNewPost.php';
    break;
  case 'newp':
    include_once TP_PAGESPATH . 'app_vt/PNewPostProcess.php';
    break;
  case 'cssp':
    include_once TP_PAGESPATH . 'app_vt/PCssProcess.php';
    break;
  case 'delete':
    include_once TP_PAGESPATH . 'app_vt/PDeletePostProcess.php';
    break;
  default:
  case 'home':
    include_once TP_PAGESPATH . 'app_vt/PAllPosts.php';
    $_SESSION['page'] = 'home';
    break;
}

?>
