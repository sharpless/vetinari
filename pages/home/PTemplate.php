<?php
//
// PHome.php
//
// Introductory page for the Rate My Teacher application
// Text borrowed from Mikael Roos
//
//

// Page title

$title = 'Mall';

//
// Page specific code
//

$html = <<<EOD
<h2>Mallsidan</h2>
<h3>Mallsida</h3>
<p>Wall of text</p>
EOD;


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