<?php
//
// PLogin.php
//
// Log in
//
//
if (empty($_SESSION['username'])) {

// Page title

$title = 'Logga in';

//
// Page specific code
//

$html = <<<EOD
<div class="roundtop">
  <div class="r1"></div>
  <div class="r2"></div>
  <div class="r3"></div>
  <div class="r4"></div>
</div>
<div class="container">
  <h2>Logga in</h2>
  <p>Ange användarnamn och lösenord.</p>
  <fieldset>
    <legend>Logga in</legend>
    <form action="?p=loginp" method="post">
      <div>
        <label for="username">Användarnamn: <input type="text" name="username" id="username" /></label>
      <label for="password">Lösenord: <input type="password" name="password" id="password" /></label>
      <input type="submit" value="Logga in" /> <button onclick="history.go(-1)">Avbryt</button>
    </div>
  </form>
</fieldset>
<p>&nbsp;</p>
</div>
<div class="roundbottom">
      <div class="r4"></div>
      <div class="r3"></div>
      <div class="r2"></div>
      <div class="r1"></div>
    </div>
EOD;
} else {
  $title = 'Logga in?';
  $html = <<<EOD
  <h2>Logga in?</h2>
  <p>Du är redan inloggad. Vill du logga in som någon annan? <a href="?p=logout">Logga ut</a> först!</p>
EOD;
}

//
// Create and print out the resulting page
//
require_once(TP_SOURCEPATH . 'CHTMLPage.php');

$page = new CHTMLPage();

$page->printHTMLHeader($title);
$page->printPageHeader();
$page->printPageBody($html);
$page->printPageFooter();


?>