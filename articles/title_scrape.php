<?php
require("../includes/simple_html_dom.php");

$url = $_GET["nurl"];

$html = file_get_html($url);
$title = $html -> find('title', 0);
//$image = $html -> find('img', 0);

echo $title -> plaintext;
//echo $image->src;
?>