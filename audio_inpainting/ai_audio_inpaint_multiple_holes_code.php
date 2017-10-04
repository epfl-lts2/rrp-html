<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
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
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span>srec <span class="p">=</span><span class="w"> </span><span class="nf">ai_audio_inpaint_multiple_holes</span><span class="p">(</span>shole, fs, hole_interval ,param<span class="p">)</span><span class="w"></span>
<span class="c">%AI_AUDIO_INPAINT_MULTIPLE_HOLES In-paint an audio file with multiples holes</span>
<span class="c">%   Usage:  srec = ai_audio_inpaint_multiple_holes(shole,hole_interval,fs,param)</span>
<span class="c">%   </span>
<span class="c">%   Input parameters:</span>
<span class="c">%       shole       : signal to be in-painted</span>
<span class="c">%       fs          : sampling frequency</span>
<span class="c">%       hole_interval: hole intervals (in seconds, k x 2 matrix) </span>
<span class="c">%       param       : structure of optional parameters</span>
<span class="c">%   Output parameters:</span>
<span class="c">%       srec        : recomposed signal</span>
<span class="c">%  </span>
<span class="c">%   This function inpaint multiples holes in an audio signal.</span>
<span class="c">%</span>
<span class="c">%   References:</span>
<span class="c">%     N. Perraudin, N. Holighaus, P. Majdak, and P. Balazs. Audio inpainting</span>
<span class="c">%     with similarity graphs. arXiv preprint arXiv:1607.06667, 2016.</span>
<span class="c">%     </span>
<span class="c">%     </span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_audio_inpaint_multiple_holes.php</span>

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

<span class="c">% Authors: Nathanael Perraudin, Nicki Hollighaus</span>
<span class="c">% Date   : June 2016</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">4</span>
    <span class="n">param</span> <span class="p">=</span> <span class="n">ai_conf</span><span class="p">();</span>
<span class="k">end</span>
 
<span class="n">srec</span> <span class="p">=</span> <span class="n">shole</span><span class="p">;</span>
<span class="n">hole_interval_fin</span> <span class="p">=</span> <span class="n">hole_interval</span><span class="p">;</span>
<span class="n">ns_old</span> <span class="p">=</span> <span class="nb">size</span><span class="p">(</span><span class="n">srec</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
<span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">hole_interval</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span>
<span class="c">%     sthole = hole_interval_fin(ii,1); % start of the hole</span>
<span class="c">%     finhole = hole_interval_fin(ii,2); % final of the hole</span>
    <span class="n">param</span><span class="p">.</span><span class="n">exclude</span> <span class="p">=</span> <span class="n">hole_interval_fin</span><span class="p">((</span><span class="n">ii</span><span class="o">+</span><span class="mi">1</span><span class="p">):</span><span class="k">end</span><span class="p">,:);</span>
    <span class="n">srecs</span> <span class="p">=</span> <span class="n">ai_audio_inpaint</span><span class="p">(</span><span class="n">srec</span><span class="p">,</span> <span class="n">fs</span><span class="p">,</span> <span class="n">hole_interval_fin</span><span class="p">(</span><span class="n">ii</span><span class="p">,:),</span> <span class="n">param</span><span class="p">);</span>
    <span class="n">srec</span> <span class="p">=</span> <span class="n">srecs</span><span class="p">{</span><span class="mi">1</span><span class="p">};</span>
 
    <span class="c">%% Adjust hole positions</span>
    <span class="p">[</span><span class="n">hole_interval_fin</span><span class="p">,</span> <span class="n">ns_old</span> <span class="p">]</span> <span class="p">=</span> <span class="n">ai_adjust_hole_position</span><span class="p">(</span><span class="n">hole_interval_fin</span><span class="p">,</span><span class="nb">size</span><span class="p">(</span><span class="n">srec</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="n">ns_old</span><span class="p">,</span><span class="n">hole_interval_fin</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="mi">2</span><span class="p">),</span><span class="n">fs</span><span class="p">);</span>
    
    <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
        <span class="c">%% Plot some stuff</span>
        <span class="n">plot_tmp</span><span class="p">(</span><span class="n">shole</span><span class="p">,</span><span class="n">srec</span><span class="p">,</span><span class="n">fs</span><span class="p">,</span> <span class="n">hole_interval</span><span class="p">,</span> <span class="n">hole_interval_fin</span><span class="p">)</span>
    <span class="k">end</span>
<span class="k">end</span>
 
 
<span class="k">end</span>
<span class="k"> </span>
<span class="k">function</span><span class="w"> </span>[hole_interval, ns_old ] <span class="p">=</span><span class="w"> </span><span class="nf">ai_adjust_hole_position</span><span class="p">(</span>hole_interval,ns,ns_old,finhole,fs<span class="p">)</span><span class="w"></span>
<span class="w"> </span>
<span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">hole_interval</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span>
    <span class="k">if</span> <span class="n">hole_interval</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span><span class="o">&gt;</span> <span class="n">finhole</span>
        <span class="n">hole_interval</span><span class="p">(</span><span class="n">ii</span><span class="p">,:)</span> <span class="p">=</span> <span class="n">hole_interval</span><span class="p">(</span><span class="n">ii</span><span class="p">,:)</span> <span class="o">+</span> <span class="p">(</span><span class="n">ns</span> <span class="o">-</span> <span class="n">ns_old</span><span class="p">)</span><span class="o">/</span><span class="n">fs</span><span class="p">;</span>
    <span class="k">end</span>
<span class="k">end</span>
<span class="n">ns_old</span> <span class="p">=</span> <span class="n">ns</span><span class="p">;</span>
 
<span class="k">end</span>
<span class="k"> </span>
<span class="k"> </span>
<span class="k">function</span><span class="w"> </span><span class="nf">plot_tmp</span><span class="p">(</span>shole,srecs,fs, hole_interval, hole_interval_fin<span class="p">)</span><span class="w"></span>
<span class="w"> </span>
<span class="w">    </span><span class="n">time0</span> <span class="p">=</span> <span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">shole</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="mf">1.5</span><span class="p">)</span><span class="o">/</span><span class="n">fs</span><span class="p">;</span>
    
    <span class="n">figure</span><span class="p">(</span><span class="mi">100</span><span class="p">)</span>
    <span class="n">subplot</span><span class="p">(</span><span class="mi">211</span><span class="p">)</span>
    <span class="n">hold</span> <span class="n">off</span>
    <span class="n">sholet</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="nb">numel</span><span class="p">(</span><span class="n">time0</span><span class="p">),</span><span class="nb">size</span><span class="p">(</span><span class="n">shole</span><span class="p">,</span><span class="mi">2</span><span class="p">));</span>
    <span class="n">sholet</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">numel</span><span class="p">(</span><span class="n">shole</span><span class="p">))</span> <span class="p">=</span> <span class="n">shole</span><span class="p">;</span>
    <span class="n">plot</span><span class="p">(</span><span class="n">time0</span><span class="p">,</span><span class="n">sholet</span><span class="p">);</span>
    <span class="n">axis</span> <span class="n">tight</span>
    <span class="c">% Plot intervals</span>
    <span class="n">hold</span> <span class="n">on</span>
    <span class="n">plot</span><span class="p">(</span><span class="n">time0</span><span class="p">,</span><span class="n">interval2signal</span><span class="p">(</span><span class="n">hole_interval</span><span class="p">,</span><span class="n">time0</span><span class="p">),</span><span class="s">&#39;k-&#39;</span><span class="p">);</span>
    
    <span class="n">subplot</span><span class="p">(</span><span class="mi">212</span><span class="p">)</span>
    <span class="n">hold</span> <span class="n">off</span>
    <span class="n">srecst</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="nb">numel</span><span class="p">(</span><span class="n">time0</span><span class="p">),</span><span class="nb">size</span><span class="p">(</span><span class="n">srecs</span><span class="p">,</span><span class="mi">2</span><span class="p">));</span>
    <span class="n">srecst</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">numel</span><span class="p">(</span><span class="n">srecs</span><span class="p">))</span> <span class="p">=</span> <span class="n">srecs</span><span class="p">;</span> 
    <span class="n">plot</span><span class="p">(</span><span class="n">time0</span><span class="p">,</span><span class="n">srecst</span><span class="p">);</span>
    <span class="n">axis</span> <span class="n">tight</span>
    <span class="n">hold</span> <span class="n">on</span>
    <span class="n">plot</span><span class="p">(</span><span class="n">time0</span><span class="p">,</span><span class="n">interval2signal</span><span class="p">(</span><span class="n">hole_interval_fin</span><span class="p">,</span><span class="n">time0</span><span class="p">),</span><span class="s">&#39;k-&#39;</span><span class="p">);</span>
    
    <span class="n">drawnow</span>
 
<span class="k">end</span>
<span class="k"> </span>
<span class="k"> </span>
<span class="k">function</span><span class="w"> </span>[s ] <span class="p">=</span><span class="w"> </span><span class="nf">interval2signal</span><span class="p">(</span>hole_interval,time0<span class="p">)</span><span class="w"></span>
<span class="w"> </span>
<span class="n">nel</span> <span class="p">=</span> <span class="nb">size</span><span class="p">(</span><span class="n">hole_interval</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
<span class="n">s</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="n">nel</span><span class="p">,</span><span class="nb">numel</span><span class="p">(</span><span class="n">time0</span><span class="p">));</span>
 
<span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="n">nel</span>
    <span class="n">s</span><span class="p">(</span><span class="n">ii</span><span class="p">,:)</span> <span class="p">=</span> <span class="n">double</span><span class="p">(</span><span class="n">time0</span> <span class="o">&gt;</span> <span class="n">hole_interval</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span> <span class="o">&amp;</span> <span class="n">time0</span> <span class="o">&lt;</span> <span class="n">hole_interval</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="mi">2</span><span class="p">));</span>
<span class="k">end</span>
 
<span class="k">end</span>
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
