<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_audio_inpaint";
$subdir = "audio_inpainting";
$title = "AI_AUDIO_INPAINT - In-paint an audio file";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_AUDIO_INPAINT - In-paint an audio file";
$content = '
<h1 class="title">AI_AUDIO_INPAINT - In-paint an audio file</h1>

<div class="section" id="usage">
<h2>Usage</h2>
<pre class="literal-block">
srec = ai_audio_inpaint(shole, fs, sthole, finhole);
srec = ai_audio_inpaint(shole, fs, sthole, finhole, param);
[srec, G] = ai_audio_inpaint(...);
[srec, G, jumps] = ai_audio_inpaint(...);
[srec, G, jumps, info] = ai_audio_inpaint(...);
</pre>
</div>
<div class="section" id="input-parameters">
<h2>Input parameters</h2>
<table class="docutils option-list" frame="void" rules="none">
<col class="option" />
<col class="description" />
<tbody valign="top">
<tr><td class="option-group">
<kbd><span class="option"><var>shole</var></span></kbd></td>
<td>signal to be in-painted</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>fs</var></span></kbd></td>
<td>sampling frequency</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>hole_inteval</var></span></kbd></td>
<td>start and end position of the hole (in seconds)</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>param</var></span></kbd></td>
<td>structure of optional parameters</td></tr>
</tbody>
</table>
</div>
<div class="section" id="output-parameters">
<h2>Output parameters</h2>
<table class="docutils option-list" frame="void" rules="none">
<col class="option" />
<col class="description" />
<tbody valign="top">
<tr><td class="option-group">
<kbd><span class="option"><var>srec</var></span></kbd></td>
<td>recomposed signal</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>G</var></span></kbd></td>
<td>graph used for the reconstruction</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>jumps</var></span></kbd></td>
<td>positions of the transitions on the graph</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>info</var></span></kbd></td>
<td>structure of additional information</td></tr>
</tbody>
</table>
</div>
<div class="section" id="xxxdescription">
<h2>Description</h2>
<p>This function performs in-paint a hole in an audio file. It follows the
following steps:</p>
<ol class="arabic simple">
<li>It creates a graph of transitions of the audio file. See the function
<span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_time_audio_graph.php">ai_time_audio_graph</a></span> for more details.</li>
<li>It searches for two optimal transitions.</li>
<li>It reconstructs the signal using these two optimal transitions.</li>
</ol>
</div>
<H2>References:</H2>



<p><a name="perraudin2016audio"></a>

N.&nbsp;Perraudin, N.&nbsp;Holighaus, P.&nbsp;Majdak, and P.&nbsp;Balazs.
 Audio inpainting with similarity graphs.
 <em>arXiv preprint arXiv:1607.06667</em>, 2016.

</p>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
