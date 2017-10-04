<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_find_transitions";
$subdir = "audio_inpainting";
$title = "AI_FIND_TRANSITIONS - Find two optimal transitions";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_FIND_TRANSITIONS - Find two optimal transitions";
$content = '
<h1 class="title">AI_FIND_TRANSITIONS - Find two optimal transitions</h1>
<h2 class="subtitle" id="xxxdescription">Description</h2>

<dl class="docutils">
<dt>Inputs parameters:</dt>
<dd>G           : Graph of transitions
sthole      : Starting node for the hole
finhole     : End node for the hole
weight_disthole : Regularization parameter - distance to the hole
weight transition : Regularization parameter - quality of the transitions
weight_diffdist : Regularization parameter - change in length
nprop       : Number of propositions
exclude     : Nodes to exclude</dd>
<dt>Outputs parameters</dt>
<dd>transitions : Transitions
qvec        : Quality measurements of the transitions</dd>
</dl>
<p>This function searches for two ear-frendly transitions around the
hole. To do so, it solves an optimization problem.</p>
';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
