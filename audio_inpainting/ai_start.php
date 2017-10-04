<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_start";
$subdir = "audio_inpainting";
$title = "AI_START - Start the audio in-painting box";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_START - Start the audio in-painting box";
$content = '
<h1 class="title">AI_START - Start the audio in-painting box</h1>

<div class="section" id="usage">
<h2>Usage</h2>
<pre class="literal-block">
ai_start();
</pre>
</div>
<div class="section" id="xxxdescription">
<h2>Description</h2>
<p>This function add the necessary path for the audio in-painting.</p>
</div>
';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
