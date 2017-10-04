<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=0;
$name = "Contents";
$subdir = "audio_inpainting";
$title = "RRP - Audio in-painting";
$seealso = array();
$demos = array();
$keywords = "RRP - Audio in-painting";
$content = '
<h1 class="title">RRP - Audio in-painting</h1>

<p>This package requires the LTFAT and the GSPBox toolbox to be executed.</p>
<div class="section" id="abstract">
<h2>Abstract</h2>
<p>In this contribution, we present a method to compensate for long
duration data gaps in audio signals, in particular music. To achieve
this task, a similarity graph is constructed, based on a short-time
Fourier analysis of reliable signal segments, e.g. the uncorrupted
remainder of the music piece, and the temporal regions adjacent to the
unreliable section of the signal. A suitable candidate segment is then
selected through an optimization scheme and smoothly inserted into the
gap.</p>
<ul class="simple">
<li>Paper   : Audio inpainting with similarity graphs</li>
<li>Authors : Nathanael Perraudin, Nicki Holighaus, Piotr Majdak, Peter Balazs</li>
<li>Date    : June 2016</li>
<li>ArXiv   : <a class="reference external" href="http://arxiv.org/abs/1607.06667">http://arxiv.org/abs/1607.06667</a></li>
<li>Online demo : <a class="reference external" href="https://lts2.epfl.ch/web-audio-inpainting/">https://lts2.epfl.ch/web-audio-inpainting/</a></li>
</ul>
<p>In order to run this script you need to install the LTFAT and the
GSPBox toolboxes. You can download them at:</p>
<ul class="simple">
<li><a class="reference external" href="http://ltfat.github.io/">http://ltfat.github.io/</a></li>
<li><a class="reference external" href="https://lts2.epfl.ch/gsp/">https://lts2.epfl.ch/gsp/</a></li>
</ul>
</div>
<div class="section" id="contents">
<h2>Contents</h2>
<p>A demo is availlable throught the <em>file ai_demo_inpaint</em>. The function
<em>ai_conf</em> contains the default parameters.</p>
</div>
<div class="section" id="audio-inpainting-functions">
<h2>Audio inpainting functions</h2>
<ul class="simple">
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_audio_inpaint.php">ai_audio_inpaint</a></span> - Inpaint an audio signal</li>
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_time_audio_graph.php">ai_time_audio_graph</a></span> - Create a graph of similarities from an audio signal</li>
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_audio_inpaint_multiple_holes.php">ai_audio_inpaint_multiple_holes</a></span> - Inpaint an audio signal with multiple holes</li>
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_plot_graph.php">ai_plot_graph</a></span> - Plot the transitions graph</li>
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_start.php">ai_start</a></span> - Start the audio inpainting box</li>
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_compute_features.php">ai_compute_features</a></span> - Compute a time-frequency features matrix</li>
<li><span class="linkrole"><a href="https://lts2.epfl.ch/rrp/audio_inpainting/ai_find_transitions.php">ai_find_transitions</a></span> - Find two optimal transitions</li>
</ul>
<p>For help, bug reports, suggestions etc. please send email to
nathanael (dot) perraudin (at) epfl (dot) ch</p>
</div>
';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
