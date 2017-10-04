<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
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
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span>[  ] <span class="p">=</span><span class="w"> </span><span class="nf">ai_start</span><span class="p">(</span>  <span class="p">)</span><span class="w"></span>
<span class="c">%AI_START Start the audio in-painting box</span>
<span class="c">%   Usage: ai_start();</span>
<span class="c">%   </span>
<span class="c">%   This function add the necessary path for the audio in-painting.</span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_start.php</span>

<span class="c">% Copyright (C) 2012-2013 Nathanael Perraudin.</span>
<span class="c">% This file is part of RRP version 0.2</span>
<span class="c">%</span>
<span class="c">% This program is free software: you can redistribute it and/or modify</span>
<span class="c">% it under the terms of the GNU General Public License as published by</span>
<span class="c">% the Free Software Foundation, either version 3 of the License, or</span>
<span class="c">% (at your option) any later version.</span>
<span class="c">%</span>
<span class="c">% This program is distributed in the hope that it will be useful,</span>
<span class="c">% but WITHOUT ANY WARRANTY; without even the implied warranty of</span>
<span class="c">% MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the</span>
<span class="c">% GNU General Public License for more details.</span>
<span class="c">%</span>
<span class="c">% You should have received a copy of the GNU General Public License</span>
<span class="c">% along with this program.  If not, see &lt;http://www.gnu.org/licenses/&gt;.</span>

<span class="n">ltfatstart</span><span class="p">;</span>
<span class="n">gsp_start</span><span class="p">;</span>

<span class="n">path</span> <span class="p">=</span> <span class="n">fileparts</span><span class="p">(</span><span class="n">mfilename</span><span class="p">(</span><span class="s">&#39;fullpath&#39;</span><span class="p">));</span>
<span class="n">addpath</span><span class="p">(</span><span class="n">genpath</span><span class="p">(</span><span class="n">path</span><span class="p">));</span>

<span class="k">end</span>
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
