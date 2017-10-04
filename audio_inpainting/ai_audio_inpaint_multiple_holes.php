<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_audio_inpaint_multiple_holes";
$subdir = "audio_inpainting";
$title = "AI_AUDIO_INPAINT_MULTIPLE_HOLES - In-paint an audio file with multiples holes";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_AUDIO_INPAINT_MULTIPLE_HOLES - In-paint an audio file with multiples holes";
$content = '
<h1 class="title">AI_AUDIO_INPAINT_MULTIPLE_HOLES - In-paint an audio file with multiples holes</h1>

<div class="section" id="usage">
<h2>Usage</h2>
<pre class="literal-block">
srec = ai_audio_inpaint_multiple_holes(shole,hole_interval,fs,param)
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
<kbd><span class="option"><var>hole_interval</var></span></kbd></td>
<td>hole intervals (in seconds, k x 2 matrix)</td></tr>
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
</tbody>
</table>
</div>
<div class="section" id="xxxdescription">
<h2>Description</h2>
<p>This function inpaint multiples holes in an audio signal.</p>
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
