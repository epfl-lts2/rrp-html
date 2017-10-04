<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_compute_features";
$subdir = "audio_inpainting";
$title = "AI_COMPUTE_FEATURES - compute a time-frequency features matrix";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_COMPUTE_FEATURES - compute a time-frequency features matrix";
$content = '
<h1 class="title">AI_COMPUTE_FEATURES - compute a time-frequency features matrix</h1>

<div class="section" id="usage">
<h2>Usage</h2>
<pre class="literal-block">
featuremat = ai_compute_features(s,fs)
featuremat = ai_compute_features(s,fs,param)
[featuremat, param] = ai_compute_features(...)
[featuremat, param, patchmax] = ai_compute_features(...)
</pre>
</div>
<div class="section" id="input-parameters">
<h2>Input parameters</h2>
<table class="docutils option-list" frame="void" rules="none">
<col class="option" />
<col class="description" />
<tbody valign="top">
<tr><td class="option-group">
<kbd><span class="option"><var>s</var></span></kbd></td>
<td>audio signal (vector)</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>fs</var></span></kbd></td>
<td>sampling frequency</td></tr>
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
<kbd><span class="option"><var>featuremat</var></span></kbd></td>
<td>matrix of features</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>param</var></span></kbd></td>
<td>updated parameters</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>patchmax</var></span></kbd></td>
<td>vector of maximum values of the Gabor transform</td></tr>
</tbody>
</table>
</div>
<div class="section" id="xxxdescription">
<h2>Description</h2>
<p><em>param.featuretype</em> can be set to an integer to change the type of
features computed.</p>
<ul class="simple">
<li>0: log spectrogram</li>
<li>1: spectrogram</li>
<li>2: reassigned spectrogram</li>
<li>3: phase derivatives</li>
<li>4: spectrogram + phase derivatives</li>
<li>5: reassigned specectrogram + phase derivatives</li>
<li>6: MFCC</li>
</ul>
<p><em>param.verbose</em> can be set to <em>1</em> to plot the feature matrix</p>
<p>This function requires the LTFAT toolbox to work.</p>
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
