<?php
// ----------------------------- 
//
// Class CHTMLPage
// Print predefined elements and output
//
// ------------------------------

class CHTMLPage {
  protected $meny;
  protected $css;
  protected $leftColumn;
  protected $rightColumn;
  protected $mainColumn;
// -----------------------------
//
// Constructor, destructor
	
  public function  __construct() {
   $this->css = $_SESSION['css'];
    $this->meny = unserialize(WS_MENU);
    $this->leftColumn = '';
    $this->rightColumn = '';
    $this->mainColumn = '';
  }

  public function  __destruct() {
    ;
  }

// ---------------------------
//
// Create HTML header
//

  public function printHTMLHeader($title, $js = 'no') {
    $site = WS_SITELINK;
    echo <<< EOD
<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv" lang="sv">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />
		<title>{$title}</title>
    <link rel="stylesheet" href="{$this->css}" type="text/css" />
    <link rel="alternate" type="application/rss+xml" title="{$title}" href="{$site}rss.php" />
EOD;
    if ($js == 'yes') {
      echo <<<EOD
       <script type='text/javascript' src='js/jquery.js'></script>
       <script type="text/javascript" src="js/validate.js"></script>
EOD;
    }
    echo "</head>";

  }

// -----------------------
//
// Create page header
//

  public function printPageHeader($header = WS_TITLE) {
    $htmlLoginMenu = $this->getLoginoutMenu();
    $meny = '';
    foreach ($this->meny as $text => $link) {
      $meny .= "<li><a href='{$link}'>{$text}</a></li>";
    }
    echo <<< EOD
<body>
{$htmlLoginMenu}
<div id='meny'>
    <h1>{$header}</h1>
    <ul>{$meny}</ul>
</div>
EOD;

  }

// --------------------------------
//
// Create page footer and terminate HTML
//

  public function printPageFooter($footer = WS_FOOTER) {
    echo <<< EOD
  <div id='footer'>
    <p>{$footer}</p>
  </div>
</body>
</html>
EOD;
  }

// ------------------------------
//
// Print page body
//

  public function printPageBody($aBody = '') {
    if (empty ($aBody)) {
      $colNr = 1;
      $colNr += empty ($this->leftColumn) ? 0 : 2;
      $colNr += empty ($this->rightColumn) ? 0 : 4;
      $colNr = ($colNr == 7) ? '' : $colNr;
      $html = $this->leftColumn;
      $html .= "<div class='mainCol{$colNr}'>{$this->mainColumn}</div>";
      $html .= $this->rightColumn;
      $html .= "<div class='clear'> </div>";
    } else {
      $html = <<<EOD
        <div class='mainCol'>
          <div class="roundtop">
            <div class="r1"></div>
            <div class="r2"></div>
            <div class="r3"></div>
            <div class="r4"></div>
          </div>
          <div class="container">
            {$aBody}
          </div>
            <div class="roundbottom">
            <div class="r4"></div>
            <div class="r3"></div>
            <div class="r2"></div>
            <div class="r1"></div>
          </div>
        </div>

EOD;
    }
    $errorMessage = $this->getErrorMessage();
    echo <<< EOD
  <div id='main'>
    {$errorMessage}
    {$html}
  </div>
EOD;
  }

  public function getLoginoutMenu() {
    $html = "<div class='login'>\n";
    if (empty ($_SESSION['username'])) {
      $html .= "  <div class='alignleft'><a href='?p=login'>Logga in</a></div>\n";
    } else {
      $html .= <<<EOD
      <div class='alignleft'>Du är inloggad som: <a href='?p=account'>{$_SESSION['username']}</a>
      | <a href='?p=new'>Nytt inlägg</a>
      | <a href='?p=logout'>Logga ut</a></div>
EOD;
    }
    $html .= "<div class='alignright'>" . $this->setCss() . "</div>";
    $html .= "</div>\n";
    return $html;
  }

  public function getErrorMessage() {
    $html = '';
    if (isset ($_SESSION['errorMessage'])) {
      $html = "<div class='error'>\n  <p>{$_SESSION['errorMessage']}</p>\n</div>\n";
      unset ($_SESSION['errorMessage']);
    }
    return $html;
  }

  public function setLeftColumn($leftSide) {
    $this->leftColumn = <<<EOD
      <div class='leftCol'>
        <div class="roundtop">
          <div class="r1"></div>
          <div class="r2"></div>
          <div class="r3"></div>
          <div class="r4"></div>
        </div>
        <div class="container">
          {$leftSide}
        </div>
        <div class="roundbottom">
          <div class="r4"></div>
          <div class="r3"></div>
          <div class="r2"></div>
          <div class="r1"></div>
        </div>
      </div>

EOD;
  }

  public function setRightColumn($rightColumn) {
    $this->rightColumn = <<<EOD
      <div class='rightCol'>
        {$rightColumn}
      </div>

EOD;
  }

  public function setMainColumn($mainColumn) {
    $this->mainColumn = $mainColumn;
  }

  public function setCss() {
    $site = WS_SITELINK;
    $classic = '';
    $modern = '';
    $id = isset ($_GET['id']) ? "&amp;id={$_GET['id']}" : '';
    if ($_SESSION['css'] == 'stylesheet/classic.css') {
      $classic = " selected='selected' ";
    } else {
      $modern = " selected='selected' ";
    }
      $html = <<<EOD
  <form action='?p=cssp' method='post'>
    <div>
      Stilmall:
      <select name='css'>
        <option value='classic'{$classic}>Klassisk</option>
        <option value='modern' {$modern}>Modern</option>
      </select>
      <input type='submit' name='submit' value='OK' />
      <input type='hidden' name='redirect' value='{$_SESSION['page']}{$id}' />
    </div>
  </form>
EOD;
    return $html;
  }
  public function setSideBar() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $mysqli->set_charset('latin1');
    if (mysqli_connect_error()) {
      echo "Connect failed: ".mysqli_connect_error()."<br>";
      exit();
    }
$tablePosts = DB_PREFIX . 'posts';
$tableUsers = DB_PREFIX . 'users';
$tableComments = DB_PREFIX . 'comments';
$tableCategories = DB_PREFIX . 'categories';
$tablePostCategories = DB_PREFIX . 'post_categories';

$queryUsers = <<<EOD
SELECT user_id,
  user_name,
  user_occupation
FROM {$tableUsers}
ORDER BY user_name ASC
EOD;

$queryTitles = <<<EOD
SELECT post_id,
  post_title
FROM {$tablePosts}
ORDER BY post_date DESC
LIMIT 10
EOD;

$queryCategories = <<< EOD
SELECT category_name,
  COUNT(pc_id) AS count
FROM {$tableCategories}
LEFT JOIN {$tablePostCategories}
  ON category_id = pc_category_id
GROUP BY category_id
EOD;

$html = <<<EOD
  <div class='users'>
    <div class="roundtop">
      <div class="r1"></div>
      <div class="r2"></div>
      <div class="r3"></div>
      <div class="r4"></div>
    </div>
    <div class="container">
      <h3>Bloggare</h3>
EOD;
$resUsers = $mysqli->query($queryUsers) or die ($mysqli->error);

while ($rowUsers = $resUsers->fetch_object()) {
  $html .= <<<EOD
    <p>{$rowUsers->user_name}<br />
      <span class="occupation">{$rowUsers->user_occupation}</span></p>
EOD;
}
$html .= <<<EOD
    </div>
    <div class="roundbottom">
      <div class="r4"></div>
      <div class="r3"></div>
      <div class="r2"></div>
      <div class="r1"></div>
    </div>
  </div>
EOD;

// Then the post part

$resTitles = $mysqli->query($queryTitles);
$html .= <<<EOD
  <div class='titles'>
    <div class="roundtop">
      <div class="r1"></div>
      <div class="r2"></div>
      <div class="r3"></div>
      <div class="r4"></div>
    </div>
    <div class="container">
      <h3>Senaste posterna</h3>
      <p>
EOD;
while ($rowTitles = $resTitles->fetch_object()) {
  $html .= "        <a href='?p=post&amp;id={$rowTitles->post_id}'>{$rowTitles->post_title}</a><br />\n";
}
$html .= <<<EOD
      </p>
    </div>
    <div class="roundbottom">
      <div class="r4"></div>
      <div class="r3"></div>
      <div class="r2"></div>
      <div class="r1"></div>
    </div>
  </div>
EOD;

require_once TP_SOURCEPATH . 'CStatistics.php';
$stats = new CStatistics();
$tenDays = $stats->getPostCountPeriod('10 days');
$lastMonth = $stats->getPostCountPeriod('1 month');
$lastYear = $stats->getPostCountPeriod('1 year');
$html .= <<<EOD
  <div class='statistics'>
    <div class="roundtop">
      <div class="r1"></div>
      <div class="r2"></div>
      <div class="r3"></div>
      <div class="r4"></div>
    </div>
    <div class="container">
      <h3>Statistik</h3>
      <p>Antal poster senaste:</p>
      <ul>
        <li>tio dagarna: {$tenDays} poster</li>
        <li>månaden: {$lastMonth} poster</li>
        <li>året: {$lastYear} poster</li>
      </ul>
    </div>
    <div class="roundbottom">
      <div class="r4"></div>
      <div class="r3"></div>
      <div class="r2"></div>
      <div class="r1"></div>
    </div>
  </div>
EOD;

$resCategories = $mysqli->query($queryCategories);
$html .= <<<EOD
  <div class='categories'>
    <div class="roundtop">
      <div class="r1"></div>
      <div class="r2"></div>
      <div class="r3"></div>
      <div class="r4"></div>
    </div>
    <div class="container">
      <h3>Taggar</h3>
      <ul>
EOD;
while ($rowCategories = $resCategories->fetch_object()) {
  $html .= "<li>{$rowCategories->category_name}: {$rowCategories->count} poster</li>\n";
}
$html .= <<<EOD
      </ul>
    </div>
    <div class="roundbottom">
      <div class="r4"></div>
      <div class="r3"></div>
      <div class="r2"></div>
      <div class="r1"></div>
    </div>
  </div>
EOD;


  return $html;
  }
}
?>
