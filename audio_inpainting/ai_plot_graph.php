<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_plot_graph";
$subdir = "audio_inpainting";
$title = "AI_PLOT_GRAPH - Plot the transitions graph";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_PLOT_GRAPH - Plot the transitions graph";
$content = '
<h1 class="title">AI_PLOT_GRAPH - Plot the transitions graph</h1>

<div class="section" id="usage">
<h2>Usage</h2>
<pre class="literal-block">
ai_plot_graph(G, transition, sthole, finhole, fs);
ai_plot_graph(G, transition, sthole, finhole, fs, param);
</pre>
</div>
<div class="section" id="input-parameters">
<h2>Input parameters</h2>
<table class="docutils option-list" frame="void" rules="none">
<col class="option" />
<col class="description" />
<tbody valign="top">
<tr><td class="option-group">
<kbd><span class="option"><var>G</var></span></kbd></td>
<td>graph of transitions</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>transitions</var></span></kbd></td>
<td>transitions to be highlighted</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>hole_interval</var></span></kbd></td>
<td>start and end position of the hole (in seconds)</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>param</var></span></kbd></td>
<td>optional structure of parameters;</td></tr>
</tbody>
</table>
</div>
<div class="section" id="xxxdescription">
<h2>Description</h2>
<p>This function plots the graph of transitions and highlights the to
selected transitions with arrows.</p>
</div>
';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
