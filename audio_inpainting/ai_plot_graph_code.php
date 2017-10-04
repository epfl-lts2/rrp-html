<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
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
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span><span class="nf">ai_plot_graph</span><span class="p">(</span>G,transitions,hole_interval,param<span class="p">)</span><span class="w"></span>
<span class="c">%AI_PLOT_GRAPH Plot the transitions graph</span>
<span class="c">%   Usage: ai_plot_graph(G, transition, sthole, finhole, fs);</span>
<span class="c">%          ai_plot_graph(G, transition, sthole, finhole, fs, param);</span>
<span class="c">%   </span>
<span class="c">%   Input parameters:</span>
<span class="c">%       G           : graph of transitions</span>
<span class="c">%       transitions : transitions to be highlighted</span>
<span class="c">%       hole_interval : start and end position of the hole (in seconds)</span>
<span class="c">%       param       : optional structure of parameters;</span>
<span class="c">%   </span>
<span class="c">%   This function plots the graph of transitions and highlights the to</span>
<span class="c">%   selected transitions with arrows.</span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_plot_graph.php</span>

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
    <span class="n">param</span> <span class="p">=</span> <span class="n">struct</span><span class="p">;</span>
<span class="k">end</span>
<span class="n">time</span> <span class="p">=</span> <span class="n">G</span><span class="p">.</span><span class="n">time</span><span class="p">;</span>
<span class="n">fs</span> <span class="p">=</span> <span class="n">G</span><span class="p">.</span><span class="n">fs</span><span class="p">;</span>
<span class="k">if</span> <span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;subgraph&#39;</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">,</span><span class="s">&#39;time_in&#39;</span><span class="p">)</span>
    <span class="n">sthole</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_in</span><span class="p">;</span> <span class="c">% start of the hole</span>
    <span class="n">finhole</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_fin</span><span class="p">;</span> <span class="c">% final of the hole    </span>
<span class="k">else</span>
    <span class="n">sthole</span> <span class="p">=</span> <span class="n">hole_interval</span><span class="p">(</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="n">fs</span><span class="o">*</span><span class="n">G</span><span class="p">.</span><span class="n">ratio</span><span class="p">;</span> <span class="c">% start of the hole</span>
    <span class="n">finhole</span> <span class="p">=</span> <span class="n">hole_interval</span><span class="p">(</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="n">fs</span><span class="o">*</span><span class="n">G</span><span class="p">.</span><span class="n">ratio</span><span class="p">;</span> <span class="c">% final of the hole</span>
<span class="k">end</span>
<span class="n">sig</span> <span class="p">=</span> <span class="nb">ones</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">N</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
<span class="n">sig</span><span class="p">(</span><span class="n">time</span><span class="o">&gt;</span><span class="n">sthole</span> <span class="o">&amp;</span> <span class="n">time</span> <span class="o">&lt;</span> <span class="n">finhole</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="k">if</span> <span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;subgraph&#39;</span><span class="p">)</span>
    <span class="n">sig</span><span class="p">(</span><span class="n">time</span><span class="o">&lt;</span><span class="n">sthole</span> <span class="o">&amp;</span> <span class="n">time</span> <span class="o">&gt;</span> <span class="p">(</span><span class="n">sthole</span> <span class="o">-</span> <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_range</span> <span class="o">*</span> <span class="n">fs</span> <span class="o">*</span> <span class="n">G</span><span class="p">.</span><span class="n">ratio</span><span class="p">))</span> <span class="p">=</span> <span class="mi">2</span><span class="p">;</span>
    <span class="n">sig</span><span class="p">(</span><span class="n">time</span><span class="o">&gt;</span><span class="n">finhole</span> <span class="o">&amp;</span> <span class="n">time</span> <span class="o">&lt;</span> <span class="p">(</span><span class="n">finhole</span> <span class="o">+</span> <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_range</span> <span class="o">*</span> <span class="n">fs</span> <span class="o">*</span> <span class="n">G</span><span class="p">.</span><span class="n">ratio</span><span class="p">))</span> <span class="p">=</span> <span class="mi">2</span><span class="p">;</span>
<span class="k">end</span>


<span class="n">gsp_plot_signal</span><span class="p">(</span><span class="n">G</span><span class="p">,</span><span class="n">sig</span><span class="p">);</span>

<span class="n">hold</span> <span class="n">on</span>
<span class="c">%     plot([G.coords(jumps(1,1),1);G.coords(jumps(1,2),1)&#39;],...</span>
<span class="c">%         [G.coords(jumps(1,1),2);G.coords(jumps(1,2),2)&#39;],...</span>
<span class="c">%         G.plotting.edge_style, &#39;LineWidth&#39;,2,...</span>
<span class="c">%         &#39;Color&#39;,&#39;k&#39;);</span>
<span class="c">% </span>
<span class="c">%     plot([G.coords(jumps(2,1),1);G.coords(jumps(2,2),1)&#39;],...</span>
<span class="c">%         [G.coords(jumps(2,1),2);G.coords(jumps(2,2),2)&#39;],...</span>
<span class="c">%         G.plotting.edge_style, &#39;LineWidth&#39;,2,...</span>
<span class="c">%         &#39;Color&#39;,&#39;k&#39;);</span>
<span class="n">quiver</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">1</span><span class="p">),</span><span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">),</span><span class="c">...</span>
    <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">),</span><span class="mi">1</span><span class="p">)</span> <span class="o">-</span> <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">1</span><span class="p">),</span><span class="c">...</span>
    <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">),</span><span class="mi">2</span><span class="p">)</span> <span class="o">-</span> <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">),</span><span class="c">...</span>
    <span class="n">G</span><span class="p">.</span><span class="n">plotting</span><span class="p">.</span><span class="n">edge_style</span><span class="p">,</span> <span class="s">&#39;LineWidth&#39;</span><span class="p">,</span><span class="mi">3</span><span class="p">,</span><span class="c">...</span>
    <span class="s">&#39;Color&#39;</span><span class="p">,</span><span class="s">&#39;k&#39;</span><span class="p">,</span><span class="s">&#39;Autoscale&#39;</span><span class="p">,</span><span class="s">&#39;off&#39;</span><span class="p">,</span><span class="s">&#39;MaxHeadSize&#39;</span><span class="p">,</span><span class="mf">0.5</span><span class="p">);</span>
<span class="n">quiver</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">1</span><span class="p">),</span><span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">),</span><span class="c">...</span>
    <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">),</span><span class="mi">1</span><span class="p">)</span> <span class="o">-</span> <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">1</span><span class="p">),</span><span class="c">...</span>
    <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">),</span><span class="mi">2</span><span class="p">)</span> <span class="o">-</span> <span class="n">G</span><span class="p">.</span><span class="n">coords</span><span class="p">(</span><span class="n">transitions</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">),</span><span class="c">...</span>
    <span class="n">G</span><span class="p">.</span><span class="n">plotting</span><span class="p">.</span><span class="n">edge_style</span><span class="p">,</span> <span class="s">&#39;LineWidth&#39;</span><span class="p">,</span><span class="mi">3</span><span class="p">,</span><span class="c">...</span>
    <span class="s">&#39;Color&#39;</span><span class="p">,</span><span class="s">&#39;k&#39;</span><span class="p">,</span><span class="s">&#39;Autoscale&#39;</span><span class="p">,</span><span class="s">&#39;off&#39;</span><span class="p">,</span><span class="s">&#39;MaxHeadSize&#39;</span><span class="p">,</span><span class="mf">0.5</span><span class="p">);</span>
<span class="n">colorbar</span> <span class="n">off</span>

<span class="k">end</span>
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
