<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=1;
$name = "ai_time_audio_graph";
$subdir = "audio_inpainting";
$title = "AI_TIME_AUDIO_GRAPH - Create a graph of similarities from an audio signal";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_TIME_AUDIO_GRAPH - Create a graph of similarities from an audio signal";
$content = '
<h1 class="title">AI_TIME_AUDIO_GRAPH - Create a graph of similarities from an audio signal</h1>

<div class="section" id="usage">
<h2>Usage</h2>
<pre class="literal-block">
G = ai_time_audio_graph(s, fs)
G = ai_time_audio_graph(s, fs, param)
[G, Gfull] = ai_time_audio_graph(...)
[G, Gfull, param] = ai_time_audio_graph(...)
[G, Gfull, param, timing] = ai_time_audio_graph(...)
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
<td>signal</td></tr>
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
<kbd><span class="option"><var>G</var></span></kbd></td>
<td>graph of audio transitions</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>Gfull</var></span></kbd></td>
<td>a graph with more audio transitions</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>param</var></span></kbd></td>
<td>structure of optional parameters (updated)</td></tr>
<tr><td class="option-group">
<kbd><span class="option"><var>timing</var></span></kbd></td>
<td>timing (features computation, graph construction)</td></tr>
</tbody>
</table>
</div>
<div class="section" id="xxxdescription">
<h2>Description</h2>
<p>This function create a graph of similarity inside a song with the
following steps:</p>
<ol class="arabic simple">
<li>Downsampling of the song</li>
<li>Compute audio features from a Time-Frequency transform</li>
<li>Create the graph of nearest neighbors</li>
<li>Select only relevant connections</li>
</ol>
<p><em>param</em> is a structure of optional parameter containing the following
fields:</p>
<ul class="simple">
<li><em>param.fsmax</em> : maximum sampling frequency (default 12 000 Hz). The
algorithm will cut everything above this frequency before any other
operation.</li>
<li><em>param.win_length</em> : window length (default: 1024)</li>
<li><em>param.M</em> : number of frequency channels (default: <em>param.win_length</em>)</li>
<li><em>param.a</em> : shift in time (default: 128)</li>
<li><em>param.win</em> : window used (default: \'itersine\')</li>
<li><em>param.dbrange</em> : range of dB used in the spectrograph (default: 50)</li>
<li><em>param.use_flann</em> : use flann library for the computation of the
graph (default 1)</li>
<li><em>param.k</em>         : number of neighbors for the graph (default 10)</li>
<li><em>param.loc</em>       : locality parameter (default 0). How much being
far away from each other is important?</li>
<li><em>param.hsize</em>     : numbeer of time bin for one patch (default 4).</li>
<li><em>param.diagdist</em>     : size of the convolution kernel  (default 20).</li>
<li><em>param.threshold</em>    : threshold to cut small values (default 2).</li>
<li><em>param.featuretype</em>  : type of features (default 0), see the function
ai_compute_features for more details.</li>
</ul>
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
