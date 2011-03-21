<?php
//
// PHome.php
//
// Introductory page for the Rate My Teacher application
// Text borrowed from Mikael Roos
//
//

// Page title

$title = 'Cascading stylesheets';

//
// Page specific code
//

$html = <<<EOD
<h2>CSS</h2>
<p>Här får du en möjlighet att leka med lite olika stylesheets.<br />
<a href="?p=minwidth">Minsta bredd</a> &lt;&gt; <a href="?p=centered">Centrerat</a> &lt;&gt;
<a href="?p=2col">Två fasta kolumner</a> &lt;&gt; <a href="?p=3col">Tre fasta kolumner</a> &lt;&gt;
<a href="?p=13col">Dynamiska kolumner, 1-3 st</a></p>
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