<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
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
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span>[srec, G, transitions, info] <span class="p">=</span><span class="w"> </span><span class="nf">ai_audio_inpaint</span><span class="p">(</span>shole, fs, hole_interval, param<span class="p">)</span><span class="w"></span>
<span class="c">%AI_AUDIO_INPAINT In-paint an audio file</span>
<span class="c">%   Usage:  srec = ai_audio_inpaint(shole, fs, sthole, finhole);</span>
<span class="c">%           srec = ai_audio_inpaint(shole, fs, sthole, finhole, param);</span>
<span class="c">%           [srec, G] = ai_audio_inpaint(...);</span>
<span class="c">%           [srec, G, jumps] = ai_audio_inpaint(...);</span>
<span class="c">%           [srec, G, jumps, info] = ai_audio_inpaint(...);</span>
<span class="c">%   </span>
<span class="c">%   Input parameters:</span>
<span class="c">%       shole       : signal to be in-painted</span>
<span class="c">%       fs          : sampling frequency</span>
<span class="c">%       hole_inteval : start and end position of the hole (in seconds)</span>
<span class="c">%       param       : structure of optional parameters</span>
<span class="c">%   Output parameters:</span>
<span class="c">%       srec        : recomposed signal</span>
<span class="c">%       G           : graph used for the reconstruction</span>
<span class="c">%       jumps       : positions of the transitions on the graph</span>
<span class="c">%       info        : structure of additional information</span>
<span class="c">%   </span>
<span class="c">%   This function performs in-paint a hole in an audio file. It follows the</span>
<span class="c">%   following steps:</span>
<span class="c">%       </span>
<span class="c">%   1. It creates a graph of transitions of the audio file. See the function</span>
<span class="c">%      AI_TIME_AUDIO_GRAPH for more details.</span>
<span class="c">%   2. It searches for two optimal transitions.</span>
<span class="c">%   3. It reconstructs the signal using these two optimal transitions.</span>
<span class="c">%</span>
<span class="c">%   References:</span>
<span class="c">%     N. Perraudin, N. Holighaus, P. Majdak, and P. Balazs. Audio inpainting</span>
<span class="c">%     with similarity graphs. arXiv preprint arXiv:1607.06667, 2016.</span>
<span class="c">%     </span>
<span class="c">%     </span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_audio_inpaint.php</span>

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
 
<span class="n">timetot</span> <span class="p">=</span> <span class="n">tic</span><span class="p">;</span>
 
<span class="k">if</span> <span class="n">hole_interval</span><span class="p">(</span><span class="mi">1</span><span class="p">)</span><span class="o">&lt;</span><span class="mi">3</span>
    <span class="n">error</span><span class="p">(</span><span class="s">&#39;The hole cannot be less than 3 seconds after the beginning of the audio file&#39;</span><span class="p">);</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">hole_interval</span><span class="p">(</span><span class="mi">2</span><span class="p">)</span> <span class="o">&gt;</span> <span class="nb">length</span><span class="p">(</span><span class="n">shole</span><span class="p">)</span><span class="o">/</span><span class="n">fs</span> <span class="o">-</span> <span class="mi">3</span> 
    <span class="n">error</span><span class="p">(</span><span class="s">&#39;The hole cannot be more than 3 seconds before the end of the audio file&#39;</span><span class="p">);</span>
<span class="k">end</span>
 
 
 
<span class="n">sthole</span> <span class="p">=</span> <span class="n">hole_interval</span><span class="p">(</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="n">fs</span><span class="p">;</span> <span class="c">% start of the hole</span>
<span class="n">finhole</span> <span class="p">=</span> <span class="n">hole_interval</span><span class="p">(</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="n">fs</span><span class="p">;</span> <span class="c">% final of the hole</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">4</span>
    <span class="n">param</span> <span class="p">=</span> <span class="n">ai_conf</span><span class="p">();</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;ns&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">ns</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;exclude&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">exclude</span> <span class="p">=</span> <span class="p">[]</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;threshold&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">threshold</span> <span class="p">=</span> <span class="mi">2</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;verbose&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span> <span class="p">=</span> <span class="mi">1</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;keepsignal&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">keepsignal</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;keeplength&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">keeplength</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;finetune&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">finetune</span> <span class="p">=</span> <span class="s">&#39;wave&#39;</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;melt&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">melt</span> <span class="p">=</span> <span class="mi">2</span><span class="p">;</span> <span class="k">end</span>
 
<span class="n">qvecfin</span> <span class="p">=</span> <span class="n">cell</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">ns</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
 
<span class="c">% Use only the subgraphs</span>
<span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_in</span> <span class="p">=</span> <span class="n">sthole</span><span class="p">;</span>
<span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_fin</span> <span class="p">=</span> <span class="n">finhole</span><span class="p">;</span>
 
 
<span class="n">no_solution_found</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
<span class="n">Ntry</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
<span class="k">while</span> <span class="n">no_solution_found</span>
    
    <span class="c">%% 1) build the graph</span>
    <span class="p">[</span><span class="n">G</span><span class="p">,</span><span class="n">Gfull</span><span class="p">,</span><span class="n">param</span><span class="p">,</span><span class="n">timing</span><span class="p">]</span>  <span class="p">=</span> <span class="n">ai_time_audio_graph</span><span class="p">(</span><span class="n">shole</span><span class="p">,</span><span class="n">fs</span><span class="p">,</span><span class="n">param</span><span class="p">);</span>
    <span class="n">time</span> <span class="p">=</span> <span class="n">G</span><span class="p">.</span><span class="n">time</span><span class="p">;</span>
    <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
        <span class="nb">disp</span><span class="p">(</span><span class="s">&#39;Find optimal transitions&#39;</span><span class="p">);</span>
    <span class="k">end</span>
    
    <span class="c">%% 2) find the transitions</span>
    <span class="c">% a) convert from time to node</span>
    <span class="n">timetransition</span> <span class="p">=</span> <span class="n">tic</span><span class="p">;</span>
    <span class="p">[</span><span class="o">~</span><span class="p">,</span><span class="n">ind1</span><span class="p">]</span> <span class="p">=</span> <span class="n">min</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">time</span><span class="o">-</span><span class="n">sthole</span><span class="p">));</span>
    <span class="p">[</span><span class="o">~</span><span class="p">,</span><span class="n">ind2</span><span class="p">]</span> <span class="p">=</span> <span class="n">min</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">time</span><span class="o">-</span><span class="n">finhole</span><span class="p">));</span>
 
    <span class="c">% If some part of the signal needs to be excluded</span>
    <span class="n">exclude</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="nb">size</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">exclude</span><span class="p">));</span>
    <span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">exclude</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span>
        <span class="k">for</span> <span class="n">jj</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">exclude</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span>
            <span class="p">[</span><span class="o">~</span><span class="p">,</span><span class="n">exclude</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="n">jj</span><span class="p">)]</span> <span class="p">=</span> <span class="n">min</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">time</span><span class="o">-</span><span class="n">param</span><span class="p">.</span><span class="n">exclude</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="n">jj</span><span class="p">)));</span>
        <span class="k">end</span>
    <span class="k">end</span>
 
    <span class="c">% Search for the optimal transition(s)</span>
    <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">keeplength</span>
        <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">keepsignal</span>
            <span class="p">[</span><span class="n">transitions</span><span class="p">,</span> <span class="n">qvec</span><span class="p">]</span> <span class="p">=</span> <span class="n">ai_find_transitions</span><span class="p">(</span><span class="n">Gfull</span><span class="p">,</span><span class="n">ind1</span><span class="p">,</span><span class="n">ind2</span><span class="p">,</span><span class="mi">10</span><span class="p">,</span><span class="mi">100</span><span class="p">,</span><span class="mi">10</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">ns</span><span class="p">,</span><span class="n">exclude</span> <span class="p">);</span>        
        <span class="k">else</span>
            <span class="p">[</span><span class="n">transitions</span><span class="p">,</span> <span class="n">qvec</span><span class="p">]</span> <span class="p">=</span> <span class="n">ai_find_transitions</span><span class="p">(</span><span class="n">G</span><span class="p">,</span><span class="n">ind1</span><span class="p">,</span><span class="n">ind2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">100</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">ns</span><span class="p">,</span> <span class="n">exclude</span> <span class="p">);</span> 
        <span class="k">end</span>
    <span class="k">else</span>
        <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">keepsignal</span>
            <span class="p">[</span><span class="n">transitions</span><span class="p">,</span> <span class="n">qvec</span><span class="p">]</span> <span class="p">=</span> <span class="n">ai_find_transitions</span><span class="p">(</span><span class="n">Gfull</span><span class="p">,</span><span class="n">ind1</span><span class="p">,</span><span class="n">ind2</span><span class="p">,</span><span class="mi">100</span><span class="p">,</span><span class="mi">100</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">ns</span><span class="p">,</span><span class="n">exclude</span><span class="p">);</span>        
        <span class="k">else</span>
            <span class="p">[</span><span class="n">transitions</span><span class="p">,</span> <span class="n">qvec</span><span class="p">]</span> <span class="p">=</span> <span class="n">ai_find_transitions</span><span class="p">(</span><span class="n">G</span><span class="p">,</span><span class="n">ind1</span><span class="p">,</span><span class="n">ind2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="mi">100</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">ns</span><span class="p">,</span><span class="n">exclude</span> <span class="p">);</span> 
        <span class="k">end</span>
    <span class="k">end</span>
    
   <span class="k">if</span> <span class="n">Ntry</span> <span class="o">==</span> <span class="mi">1</span>
        <span class="n">nh</span> <span class="p">=</span> <span class="n">std</span><span class="p">(</span><span class="n">shole</span><span class="p">(:));</span>
        <span class="k">for</span> <span class="n">ii</span><span class="p">=</span><span class="mi">1</span><span class="p">:</span><span class="nb">numel</span><span class="p">(</span><span class="n">qvec</span><span class="p">)</span>
            <span class="n">qvecfin</span><span class="p">{</span><span class="n">ii</span><span class="p">}</span> <span class="p">=</span> <span class="p">[</span><span class="n">qvec</span><span class="p">{</span><span class="n">ii</span><span class="p">},</span> <span class="nb">sqrt</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">sigma</span><span class="p">),</span> <span class="n">nh</span><span class="p">,</span> <span class="nb">sqrt</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">sigma</span><span class="p">)</span><span class="o">/</span><span class="n">nh</span><span class="p">,</span> <span class="n">norm</span><span class="p">(</span><span class="n">Gfull</span><span class="p">.</span><span class="n">W</span><span class="p">,</span><span class="s">&#39;fro&#39;</span><span class="p">),</span><span class="n">norm</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">W</span><span class="p">,</span><span class="s">&#39;fro&#39;</span><span class="p">),</span><span class="n">G</span><span class="p">.</span><span class="n">Ne</span> <span class="p">];</span> <span class="c">%#ok&lt;AGROW&gt;</span>
        <span class="k">end</span>
   <span class="k">end</span>
    
    <span class="k">if</span> <span class="nb">isnan</span><span class="p">(</span><span class="n">sum</span><span class="p">(</span><span class="n">transitions</span><span class="p">{</span><span class="mi">1</span><span class="p">}(:)))</span>
        <span class="n">param</span><span class="p">.</span><span class="n">threshold</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">threshold</span><span class="o">/</span><span class="mi">2</span><span class="p">;</span>
        <span class="n">Ntry</span> <span class="p">=</span> <span class="n">Ntry</span> <span class="o">+</span> <span class="mi">1</span><span class="p">;</span>
    <span class="k">else</span>
        <span class="n">no_solution_found</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
    <span class="k">end</span>
    <span class="n">timing_transition</span> <span class="p">=</span> <span class="n">toc</span><span class="p">(</span><span class="n">timetransition</span><span class="p">);</span>
    
    <span class="k">if</span> <span class="n">Ntry</span> <span class="o">&gt;</span> <span class="mi">5</span>
        <span class="n">error</span><span class="p">(</span><span class="s">&#39;The algorithm did not find any solutions&#39;</span><span class="p">)</span>
    <span class="k">end</span>
<span class="k">end</span>
 
 
<span class="c">% 3) Reconstruct the signal    </span>
<span class="n">time_reconstruction</span> <span class="p">=</span> <span class="n">tic</span><span class="p">;</span>
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
    <span class="nb">disp</span><span class="p">(</span><span class="s">&#39;Reconstruct the signal&#39;</span><span class="p">);</span>
<span class="k">end</span>
 
<span class="c">%% Compute some numbers (to be updated)</span>
<span class="k">try</span>
    <span class="n">rel_diff</span> <span class="p">=</span> <span class="p">@(</span><span class="n">x</span><span class="p">,</span><span class="n">y</span><span class="p">)</span> <span class="n">norm</span><span class="p">(</span><span class="n">x</span><span class="p">(:)</span><span class="o">-</span><span class="n">y</span><span class="p">(:))</span>^<span class="mi">2</span> <span class="o">/</span> <span class="p">(</span> <span class="n">norm</span><span class="p">(</span><span class="n">x</span><span class="p">(:))</span> <span class="o">*</span> <span class="n">norm</span><span class="p">(</span><span class="n">y</span><span class="p">(:))</span> <span class="p">);</span>
 
    <span class="n">features</span> <span class="p">=</span> <span class="n">G</span><span class="p">.</span><span class="n">features</span><span class="p">;</span>
    <span class="n">dd</span> <span class="p">=</span> <span class="mi">40</span><span class="p">;</span>
    <span class="n">convk</span> <span class="p">=</span> <span class="n">tripuls</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">,</span><span class="mi">2</span><span class="o">*</span><span class="n">dd</span><span class="o">+</span><span class="mi">1</span><span class="p">);</span> <span class="c">% kernel</span>
    <span class="n">convk1</span> <span class="p">=</span> <span class="n">convk</span><span class="p">;</span>
    <span class="n">convk2</span> <span class="p">=</span> <span class="n">convk</span><span class="p">;</span>
    <span class="n">convk1</span><span class="p">((</span><span class="n">dd</span><span class="o">+</span><span class="mi">1</span><span class="p">):</span><span class="k">end</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span> 
    <span class="n">convk2</span><span class="p">(</span><span class="mi">1</span><span class="p">:(</span><span class="n">dd</span><span class="o">-</span><span class="mi">1</span><span class="p">))</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
 
    <span class="n">mnf</span> <span class="p">=</span> <span class="n">norm</span><span class="p">(</span><span class="n">features</span><span class="p">,</span><span class="s">&#39;fro&#39;</span><span class="p">)</span><span class="o">/</span><span class="nb">sqrt</span><span class="p">(</span><span class="nb">size</span><span class="p">(</span><span class="n">features</span><span class="p">,</span><span class="mi">2</span><span class="p">))</span><span class="o">*</span><span class="n">norm</span><span class="p">(</span><span class="n">convk1</span><span class="p">);</span>
    <span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="nb">length</span><span class="p">(</span><span class="n">qvecfin</span><span class="p">)</span>
        <span class="n">qvecfin</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="k">end</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="n">rel_diff</span><span class="p">(</span> <span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk1</span><span class="p">),</span> <span class="c">...</span>
                        <span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk1</span><span class="p">)</span> <span class="p">);</span>
        <span class="n">qvecfin</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="k">end</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="n">rel_diff</span><span class="p">(</span> <span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk2</span><span class="p">),</span> <span class="c">...</span>
                        <span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk2</span><span class="p">)</span> <span class="p">);</span>
        <span class="n">qvecfin</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="k">end</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="p">(</span> <span class="n">norm</span><span class="p">(</span><span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk1</span><span class="p">),</span><span class="s">&#39;fro&#39;</span><span class="p">)</span> <span class="o">*</span> <span class="c">...</span>
                      <span class="n">norm</span><span class="p">(</span><span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk1</span><span class="p">),</span><span class="s">&#39;fro&#39;</span><span class="p">)</span> <span class="o">/</span> <span class="c">...</span>
                      <span class="n">mnf</span>^<span class="mi">2</span> <span class="p">)</span>^<span class="p">(</span><span class="o">-</span><span class="mi">1</span><span class="p">);</span>
        <span class="n">qvecfin</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="k">end</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="p">(</span> <span class="n">norm</span><span class="p">(</span><span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk2</span><span class="p">),</span><span class="s">&#39;fro&#39;</span><span class="p">)</span> <span class="o">*</span> <span class="c">...</span>
                      <span class="n">norm</span><span class="p">(</span><span class="n">features</span><span class="p">(:,</span><span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="o">-</span><span class="n">dd</span><span class="p">:</span><span class="n">dd</span><span class="p">))</span> <span class="o">*</span> <span class="nb">diag</span><span class="p">(</span><span class="n">convk2</span><span class="p">),</span><span class="s">&#39;fro&#39;</span><span class="p">)</span> <span class="o">/</span> <span class="c">...</span>
                      <span class="n">mnf</span>^<span class="mi">2</span> <span class="p">)</span>^<span class="p">(</span><span class="o">-</span><span class="mi">1</span><span class="p">);</span>
    <span class="k">end</span>
<span class="k">end</span>
<span class="c">%%</span>
 
<span class="n">srec</span> <span class="p">=</span> <span class="n">ai_reconstruct_signal</span><span class="p">(</span><span class="n">G</span><span class="p">,</span> <span class="n">shole</span><span class="p">,</span> <span class="n">transitions</span><span class="p">,</span> <span class="n">param</span><span class="p">);</span>
 
<span class="n">timing_reconstruction</span> <span class="p">=</span> <span class="n">toc</span><span class="p">(</span><span class="n">time_reconstruction</span><span class="p">);</span>
 
<span class="n">timing_tot</span> <span class="p">=</span> <span class="n">toc</span><span class="p">(</span><span class="n">timetot</span><span class="p">);</span>
 
<span class="n">timing</span> <span class="p">=</span> <span class="p">[</span><span class="n">timing</span><span class="p">,</span> <span class="n">timing_transition</span><span class="p">,</span> <span class="n">timing_reconstruction</span><span class="p">,</span> <span class="n">timing_tot</span><span class="p">];</span>
 
<span class="k">if</span> <span class="n">nargout</span><span class="o">&gt;</span><span class="mi">3</span>
    <span class="n">info</span><span class="p">.</span><span class="n">Gfull</span> <span class="p">=</span> <span class="n">Gfull</span><span class="p">;</span>
    <span class="n">info</span><span class="p">.</span><span class="n">qvec</span> <span class="p">=</span> <span class="n">qvecfin</span><span class="p">;</span>
    <span class="n">info</span><span class="p">.</span><span class="n">timing</span> <span class="p">=</span> <span class="n">timing</span><span class="p">;</span>
    <span class="n">info</span><span class="p">.</span><span class="n">param</span> <span class="p">=</span> <span class="n">param</span><span class="p">;</span>
<span class="k">end</span> 
 
 
<span class="k">end</span>
 
 
 
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
