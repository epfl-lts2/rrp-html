<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
$name = "ai_find_transitions";
$subdir = "audio_inpainting";
$title = "AI_FIND_TRANSITIONS - Find two optimal transitions";
$seealso = array(
);
$demos = array(
);
$keywords = "AI_FIND_TRANSITIONS - Find two optimal transitions";
$content = '
<h1 class="title">AI_FIND_TRANSITIONS - Find two optimal transitions</h1>
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span>[transitions, qvec] <span class="p">=</span><span class="w"> </span><span class="nf">ai_find_transitions</span><span class="p">(</span>G,sthole,finhole,weight_disthole, weight_transition, weight_diffdist,nprop, exclude<span class="p">)</span><span class="w"></span>
<span class="c">%AI_FIND_TRANSITIONS Find two optimal transitions</span>
<span class="c">%   </span>
<span class="c">%   Inputs parameters:</span>
<span class="c">%       G           : Graph of transitions</span>
<span class="c">%       sthole      : Starting node for the hole</span>
<span class="c">%       finhole     : End node for the hole</span>
<span class="c">%       weight_disthole : Regularization parameter - distance to the hole</span>
<span class="c">%       weight transition : Regularization parameter - quality of the transitions</span>
<span class="c">%       weight_diffdist : Regularization parameter - change in length</span>
<span class="c">%       nprop       : Number of propositions</span>
<span class="c">%       exclude     : Nodes to exclude </span>
<span class="c">%   Outputs parameters</span>
<span class="c">%       transitions : Transitions</span>
<span class="c">%       qvec        : Quality measurements of the transitions</span>
<span class="c">%</span>
<span class="c">%   This function searches for two ear-frendly transitions around the</span>
<span class="c">%   hole. To do so, it solves an optimization problem.</span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_find_transitions.php</span>

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
    <span class="n">weight_disthole</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">5</span>
    <span class="n">weight_transition</span> <span class="p">=</span> <span class="mi">100</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">6</span>
    <span class="n">weight_diffdist</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">7</span>
    <span class="n">nprop</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">8</span>
    <span class="n">exclude</span> <span class="p">=</span> <span class="p">[];</span>
<span class="k">end</span>
 
<span class="c">% Find starting and finishing positions</span>
<span class="n">indices</span> <span class="p">=</span> <span class="nb">find</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">d</span><span class="p">);</span>
 
<span class="c">% All possible starting positions</span>
<span class="n">ind_bfin</span> <span class="p">=</span> <span class="n">indices</span><span class="p">(</span><span class="n">indices</span><span class="o">&lt;</span><span class="n">sthole</span><span class="p">);</span>
<span class="c">% All possible finishing positions</span>
<span class="n">ind_afin</span> <span class="p">=</span> <span class="n">indices</span><span class="p">(</span><span class="n">indices</span><span class="o">&gt;</span><span class="n">finhole</span><span class="p">);</span>
 
<span class="c">% We perform two jumps</span>
<span class="p">[</span><span class="n">fin1</span><span class="p">,</span><span class="n">jump1</span><span class="p">,</span><span class="n">w1</span><span class="p">]</span> <span class="p">=</span> <span class="nb">find</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">W</span><span class="p">(:,</span><span class="n">ind_bfin</span><span class="p">));</span>
<span class="n">start1</span> <span class="p">=</span> <span class="n">ind_bfin</span><span class="p">(</span><span class="n">jump1</span><span class="p">);</span> <span class="c">% we need a reordering!</span>
 
<span class="c">% find all possible jumps for the finishing nodes</span>
<span class="p">[</span><span class="n">fin2</span><span class="p">,</span><span class="n">jump2</span><span class="p">,</span><span class="n">w2</span><span class="p">]</span> <span class="p">=</span> <span class="nb">find</span><span class="p">(</span><span class="n">G</span><span class="p">.</span><span class="n">W</span><span class="p">(:,</span><span class="n">ind_afin</span><span class="p">));</span>
<span class="n">start2</span> <span class="p">=</span> <span class="n">ind_afin</span><span class="p">(</span><span class="n">jump2</span><span class="p">);</span>
 
<span class="c">% Check if there is one empty set</span>
<span class="k">if</span> <span class="o">~</span><span class="nb">numel</span><span class="p">(</span><span class="n">w1</span><span class="p">)</span> 
    <span class="nb">disp</span><span class="p">(</span><span class="s">&#39;No path found! No connections before the hole.&#39;</span><span class="p">)</span>
    <span class="n">transitions</span><span class="p">{</span><span class="mi">1</span><span class="p">}</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">);</span>
    <span class="n">qvec</span> <span class="p">=</span> <span class="p">[];</span>
    <span class="k">return</span>
<span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="nb">numel</span><span class="p">(</span><span class="n">w2</span><span class="p">)</span> 
    <span class="nb">disp</span><span class="p">(</span><span class="s">&#39;No path found! No connections after the hole.&#39;</span><span class="p">)</span>
    <span class="n">transitions</span><span class="p">{</span><span class="mi">1</span><span class="p">}</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">);</span>
    <span class="n">qvec</span> <span class="p">=</span> <span class="p">[];</span>
    <span class="k">return</span>
<span class="k">end</span>
 
 
 
<span class="c">% Let us now take care of the 3 penalizations</span>
<span class="c">% 1) The distance to the hole</span>
<span class="c">%    It is stored inside pmatrix.</span>
<span class="n">p1</span> <span class="p">=</span> <span class="n">sthole</span> <span class="o">-</span> <span class="n">ind_bfin</span><span class="p">;</span>
<span class="n">p2</span> <span class="p">=</span> <span class="n">ind_afin</span> <span class="o">-</span> <span class="n">finhole</span> <span class="p">;</span>
<span class="c">%pmatrix = repmat(p1(start1),1,length(start2))&#39; + repmat(p2(start2),1,length(start1));</span>
<span class="n">pmatrix</span> <span class="p">=</span> <span class="nb">repmat</span><span class="p">(</span><span class="n">p1</span><span class="p">(</span><span class="n">jump1</span><span class="p">),</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">jump2</span><span class="p">))</span><span class="o">&#39;</span> <span class="o">+</span> <span class="nb">repmat</span><span class="p">(</span><span class="n">p2</span><span class="p">(</span><span class="n">jump2</span><span class="p">),</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">jump1</span><span class="p">));</span>
 
<span class="c">% 2) The weights of the edges</span>
<span class="c">%    It is stored inside p2matrix.</span>
 
<span class="c">% We would like a relative penalization, so we start by normalizing the</span>
<span class="c">% weights</span>
<span class="n">w1</span> <span class="p">=</span> <span class="n">w1</span><span class="o">/</span><span class="n">max</span><span class="p">(</span><span class="n">w1</span><span class="p">);</span>
<span class="n">w2</span> <span class="p">=</span> <span class="n">w2</span><span class="o">/</span><span class="n">max</span><span class="p">(</span><span class="n">w2</span><span class="p">);</span>
<span class="n">p2matrix</span> <span class="p">=</span> <span class="nb">repmat</span><span class="p">(</span><span class="mf">1.</span><span class="o">/</span><span class="n">w1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">w2</span><span class="p">))</span><span class="o">&#39;</span> <span class="o">+</span> <span class="nb">repmat</span><span class="p">(</span><span class="mf">1.</span><span class="o">/</span><span class="n">w2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">w1</span><span class="p">));</span>
 
<span class="c">% 3) The difference of the length of the two pieces</span>
<span class="c">%    It is stored inside d</span>
 
<span class="c">% The length of the first part</span>
<span class="n">d1</span> <span class="p">=</span> <span class="nb">repmat</span><span class="p">(</span><span class="n">fin2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">fin1</span><span class="p">))</span> <span class="o">-</span> <span class="nb">repmat</span><span class="p">(</span><span class="n">fin1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">fin2</span><span class="p">))</span><span class="o">&#39;</span><span class="p">;</span>
<span class="n">d1</span><span class="p">(</span><span class="n">d1</span><span class="o">&lt;</span><span class="mi">0</span><span class="p">)</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">;</span> <span class="c">% Remove all negative lengths</span>
<span class="c">% The length of the second part</span>
<span class="n">d2</span> <span class="p">=</span> <span class="nb">repmat</span><span class="p">(</span><span class="n">start2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">start1</span><span class="p">))</span> <span class="o">-</span> <span class="nb">repmat</span><span class="p">(</span><span class="n">start1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">start2</span><span class="p">))</span><span class="o">&#39;</span><span class="p">;</span>
<span class="k">if</span> <span class="n">sum</span><span class="p">(</span><span class="n">sum</span><span class="p">(</span><span class="n">d2</span><span class="o">&lt;</span><span class="mi">0</span><span class="p">))</span>
    <span class="n">error</span><span class="p">(</span><span class="s">&#39;Something is not normal!!!!&#39;</span><span class="p">)</span>
<span class="k">end</span>
<span class="n">d</span> <span class="p">=</span> <span class="nb">abs</span><span class="p">(</span><span class="n">d1</span><span class="o">-</span><span class="n">d2</span><span class="p">);</span> <span class="c">% Shall we square this term?</span>
 
<span class="c">% remove fin1&lt;sthole and fin2&gt;finhole</span>
<span class="c">% We do not want the hole to be in the middle of the recovery part</span>
<span class="n">d</span><span class="p">((</span><span class="nb">repmat</span><span class="p">(</span><span class="n">fin1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">fin2</span><span class="p">))</span><span class="o">&lt;</span><span class="p">=</span><span class="n">finhole</span><span class="p">)</span><span class="o">&#39;</span> <span class="o">&amp;</span> <span class="p">(</span><span class="nb">repmat</span><span class="p">(</span><span class="n">fin2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">fin1</span><span class="p">))</span><span class="o">&gt;</span><span class="p">=</span><span class="n">sthole</span><span class="p">))</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">;</span>
 
<span class="c">% Remove the solutions that are overlapping with the excluded set</span>
<span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">exclude</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span>
    <span class="n">d</span><span class="p">((</span><span class="nb">repmat</span><span class="p">(</span><span class="n">fin1</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">fin2</span><span class="p">))</span><span class="o">&lt;</span><span class="p">=</span><span class="n">exclude</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span> <span class="p">)</span><span class="o">&#39;</span> <span class="o">&amp;</span> <span class="p">(</span><span class="nb">repmat</span><span class="p">(</span><span class="n">fin2</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="nb">length</span><span class="p">(</span><span class="n">fin1</span><span class="p">))</span><span class="o">&gt;</span><span class="p">=</span><span class="n">exclude</span><span class="p">(</span><span class="n">ii</span><span class="p">,</span><span class="mi">1</span><span class="p">)))</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="c">% Objective function</span>
<span class="n">obj</span> <span class="p">=</span> <span class="n">weight_diffdist</span><span class="o">*</span><span class="n">d</span> <span class="o">+</span> <span class="n">weight_disthole</span> <span class="o">*</span> <span class="n">pmatrix</span> <span class="o">+</span> <span class="n">weight_transition</span> <span class="o">*</span> <span class="n">p2matrix</span><span class="p">;</span>
 
<span class="c">% Find the minimums</span>
<span class="n">transitions</span> <span class="p">=</span> <span class="n">cell</span><span class="p">(</span><span class="n">nprop</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
<span class="n">qvec</span> <span class="p">=</span> <span class="n">cell</span><span class="p">(</span><span class="n">nprop</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
<span class="n">qvec</span><span class="p">{:}</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">6</span><span class="p">);</span>
<span class="k">for</span> <span class="n">ii</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="n">nprop</span>
    <span class="p">[</span><span class="n">m1</span><span class="p">,</span><span class="n">i1</span><span class="p">]</span> <span class="p">=</span> <span class="n">min</span><span class="p">(</span><span class="n">obj</span><span class="p">);</span>
    <span class="p">[</span><span class="n">testv</span><span class="p">,</span><span class="n">i2</span><span class="p">]</span> <span class="p">=</span> <span class="n">min</span><span class="p">(</span><span class="n">m1</span><span class="p">);</span>
    
    <span class="n">qvec</span><span class="p">{</span><span class="n">ii</span><span class="p">}</span> <span class="p">=</span> <span class="p">[</span><span class="n">testv</span><span class="p">,</span> <span class="c">...</span>
        <span class="n">weight_transition</span> <span class="o">*</span> <span class="n">p2matrix</span><span class="p">(</span><span class="n">i1</span><span class="p">(</span><span class="n">i2</span><span class="p">),</span><span class="n">i2</span><span class="p">),</span> <span class="c">...</span>
        <span class="n">weight_diffdist</span> <span class="o">*</span> <span class="n">d</span><span class="p">(</span><span class="n">i1</span><span class="p">(</span><span class="n">i2</span><span class="p">),</span><span class="n">i2</span><span class="p">),</span> <span class="c">...</span>
        <span class="n">weight_disthole</span> <span class="o">*</span> <span class="n">pmatrix</span><span class="p">(</span><span class="n">i1</span><span class="p">(</span><span class="n">i2</span><span class="p">),</span><span class="n">i2</span><span class="p">),</span> <span class="c">...</span>
        <span class="n">weight_transition</span><span class="o">./</span><span class="n">w1</span><span class="p">(</span><span class="n">i2</span><span class="p">),</span> <span class="c">...</span>
        <span class="n">weight_transition</span><span class="o">./</span><span class="n">w2</span><span class="p">(</span><span class="n">i1</span><span class="p">(</span><span class="n">i2</span><span class="p">))];</span>
    <span class="k">if</span> <span class="nb">isnan</span><span class="p">(</span><span class="n">testv</span><span class="p">)</span>
        <span class="k">if</span> <span class="n">ii</span><span class="o">==</span><span class="mi">1</span>
            <span class="nb">disp</span><span class="p">(</span><span class="s">&#39;Warning: The algorithm is not able to find a solution&#39;</span><span class="p">)</span>
            <span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">);</span>
            <span class="n">qvec</span> <span class="p">=</span> <span class="p">{</span><span class="nb">nan</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="mi">6</span><span class="p">)};</span>
        <span class="k">else</span>
            <span class="n">transitions</span> <span class="p">=</span> <span class="n">transitions</span><span class="p">(</span><span class="mi">1</span><span class="p">:(</span><span class="n">ii</span><span class="o">-</span><span class="mi">1</span><span class="p">));</span>
            <span class="n">qvec</span> <span class="p">=</span> <span class="n">qvec</span><span class="p">(</span><span class="mi">1</span><span class="p">:(</span><span class="n">ii</span><span class="o">-</span><span class="mi">1</span><span class="p">));</span>
            <span class="nb">disp</span><span class="p">(</span><span class="s">&#39;Warning: The algorithm is not able to find so many solutions&#39;</span><span class="p">)</span>           
        <span class="k">end</span>
        <span class="k">break</span>
    <span class="k">else</span>
 
    <span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">1</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="n">start1</span><span class="p">(</span><span class="n">i2</span><span class="p">);</span>
    <span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">1</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span> <span class="p">=</span> <span class="n">fin1</span><span class="p">(</span><span class="n">i2</span><span class="p">);</span>
    <span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">2</span><span class="p">,</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="n">fin2</span><span class="p">(</span><span class="n">i1</span><span class="p">(</span><span class="n">i2</span><span class="p">));</span>
    <span class="n">transitions</span><span class="p">{</span><span class="n">ii</span><span class="p">}(</span><span class="mi">2</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span> <span class="p">=</span> <span class="n">start2</span><span class="p">(</span><span class="n">i1</span><span class="p">(</span><span class="n">i2</span><span class="p">));</span>
    
    <span class="c">% Remove the solution</span>
    <span class="n">obj</span><span class="p">(</span><span class="n">i1</span><span class="p">,:)</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">;</span>
    <span class="n">obj</span><span class="p">(:,</span><span class="n">i2</span><span class="p">)</span> <span class="p">=</span> <span class="nb">nan</span><span class="p">;</span>
    <span class="k">end</span>
<span class="k">end</span>
 
<span class="k">end</span>
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
