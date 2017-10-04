<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
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
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span>[featuremat, param, patchmax] <span class="p">=</span><span class="w"> </span><span class="nf">ai_compute_features</span><span class="p">(</span>s,fs,param<span class="p">)</span><span class="w"></span>
<span class="c">%AI_COMPUTE_FEATURES compute a time-frequency features matrix</span>
<span class="c">%   Usage:  featuremat = ai_compute_features(s,fs)</span>
<span class="c">%           featuremat = ai_compute_features(s,fs,param)</span>
<span class="c">%           [featuremat, param] = ai_compute_features(...)</span>
<span class="c">%           [featuremat, param, patchmax] = ai_compute_features(...)</span>
<span class="c">%</span>
<span class="c">%   Input parameters:</span>
<span class="c">%       s           : audio signal (vector)</span>
<span class="c">%       fs          : sampling frequency</span>
<span class="c">%       param       : structure of optional parameters</span>
<span class="c">%   Output parameters:</span>
<span class="c">%       featuremat  : matrix of features</span>
<span class="c">%       param       : updated parameters</span>
<span class="c">%       patchmax    : vector of maximum values of the Gabor transform</span>
<span class="c">%</span>
<span class="c">%   param.featuretype can be set to an integer to change the type of</span>
<span class="c">%   features computed.</span>
<span class="c">%</span>
<span class="c">%    0: log spectrogram</span>
<span class="c">%    1: spectrogram</span>
<span class="c">%    2: reassigned spectrogram</span>
<span class="c">%    3: phase derivatives</span>
<span class="c">%    4: spectrogram + phase derivatives</span>
<span class="c">%    5: reassigned specectrogram + phase derivatives</span>
<span class="c">%    6: MFCC</span>
<span class="c">%</span>
<span class="c">%   param.verbose can be set to 1 to plot the feature matrix</span>
<span class="c">%</span>
<span class="c">%   This function requires the LTFAT toolbox to work.</span>
<span class="c">%</span>
<span class="c">%   References:</span>
<span class="c">%     N. Perraudin, N. Holighaus, P. Majdak, and P. Balazs. Audio inpainting</span>
<span class="c">%     with similarity graphs. arXiv preprint arXiv:1607.06667, 2016.</span>
<span class="c">%     </span>
<span class="c">%     </span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_compute_features.php</span>

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
 
 
<span class="c">% Authors: Nicki Hollighaus, Nathanael Perraudin</span>
<span class="c">% Date   : June 2016</span>
 
<span class="k">if</span> <span class="n">nargin</span><span class="o">&lt;</span><span class="mi">3</span>
    <span class="n">param</span> <span class="p">=</span> <span class="n">struct</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span> <span class="s">&#39;featuretype&#39;</span><span class="p">)</span>
    <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span> <span class="p">=</span> <span class="mi">4</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span> <span class="s">&#39;verbose&#39;</span><span class="p">)</span>
    <span class="n">param</span><span class="p">.</span><span class="n">verbose</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;win_length&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">win_length</span> <span class="p">=</span> <span class="mi">1024</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;win&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">win</span> <span class="p">=</span> <span class="s">&#39;itersine&#39;</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;M&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">M</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">win_length</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;a&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">a</span> <span class="p">=</span> <span class="mi">128</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;lambda&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">lambda</span> <span class="p">=</span> <span class="mi">3</span><span class="o">/</span><span class="mi">2</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;cepstralcoefs&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">cepstralcoefs</span> <span class="p">=</span> <span class="mi">25</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;dbrange&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">dbrange</span> <span class="p">=</span> <span class="mi">50</span> <span class="p">;</span> <span class="k">end</span>
 
 
<span class="c">% TODO: I think this is not good!</span>
<span class="c">% Make the signal a suitable length</span>
<span class="n">Loriginal_r</span> <span class="p">=</span> <span class="nb">length</span><span class="p">(</span><span class="n">s</span><span class="p">);</span>
<span class="n">L</span><span class="p">=</span><span class="n">dgtlength</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">win_length</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">M</span><span class="p">);</span>
<span class="n">s</span> <span class="p">=</span> <span class="n">s</span><span class="p">(</span> <span class="mi">1</span><span class="p">:</span> <span class="p">(</span><span class="n">Loriginal_r</span> <span class="o">-</span> <span class="nb">mod</span><span class="p">(</span><span class="n">Loriginal_r</span><span class="p">,</span><span class="n">L</span><span class="p">)));</span>
 
<span class="n">g</span> <span class="p">=</span> <span class="n">firwin</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">win</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">win_length</span><span class="p">);</span>
<span class="n">Gd</span> <span class="p">=</span> <span class="p">@(</span><span class="n">x</span><span class="p">)</span> <span class="n">dgtreal</span><span class="p">(</span><span class="n">x</span><span class="p">,</span><span class="n">g</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">M</span><span class="p">);</span>
 
<span class="n">F</span> <span class="p">=</span> <span class="n">frame</span><span class="p">(</span><span class="s">&#39;dgt&#39;</span><span class="p">,</span><span class="n">g</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">M</span><span class="p">);</span>
<span class="p">[</span><span class="o">~</span><span class="p">,</span><span class="n">B</span><span class="p">]</span> <span class="p">=</span> <span class="n">framebounds</span><span class="p">(</span><span class="n">F</span><span class="p">);</span>
 
 
<span class="n">gabor_transform</span> <span class="p">=</span> <span class="n">Gd</span><span class="p">(</span><span class="n">s</span><span class="p">)</span><span class="o">/</span><span class="nb">sqrt</span><span class="p">(</span><span class="n">B</span><span class="p">);</span>
<span class="n">mxGT</span> <span class="p">=</span> <span class="n">max</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">(:)));</span>
<span class="p">[</span><span class="n">vsize</span><span class="p">,</span> <span class="n">owidth</span><span class="p">]=</span> <span class="nb">size</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">);</span>     
<span class="c">%vsize = vsize/2+1;</span>
 
<span class="c">% Compute feature matrix</span>
 
<span class="k">switch</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">4</span><span class="p">,</span><span class="mi">5</span><span class="p">}</span>
        <span class="n">num_features</span> <span class="p">=</span> <span class="mi">2</span><span class="p">;</span>
    <span class="k">otherwise</span>
        <span class="n">num_features</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span> <span class="o">==</span> <span class="mi">6</span>
    <span class="n">vsizefull</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">cepstralcoefs</span><span class="o">+</span><span class="p">(</span><span class="n">num_features</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="n">vsize</span><span class="o">-</span><span class="mi">1</span><span class="p">;</span>
<span class="k">else</span>
    <span class="n">vsizefull</span> <span class="p">=</span> <span class="n">num_features</span><span class="o">*</span><span class="n">vsize</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="n">featuremat</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="n">vsizefull</span><span class="p">,</span><span class="n">owidth</span><span class="p">);</span>
 
<span class="k">switch</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">2</span><span class="p">,</span><span class="mi">3</span><span class="p">,</span><span class="mi">4</span><span class="p">,</span><span class="mi">5</span><span class="p">}</span> <span class="c">% Precompute phase derivatives</span>
        <span class="p">[</span><span class="n">tgrad</span><span class="p">,</span><span class="n">fgrad</span><span class="p">]</span> <span class="p">=</span> <span class="n">gabphasegrad</span><span class="p">(</span><span class="s">&#39;dgt&#39;</span><span class="p">,</span><span class="n">s</span><span class="p">,</span><span class="n">g</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">M</span><span class="p">);</span>
        <span class="n">tgrad</span> <span class="p">=</span> <span class="n">tgrad</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">floor</span><span class="p">(</span><span class="k">end</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="mi">1</span><span class="p">,:);</span>
        <span class="n">fgrad</span> <span class="p">=</span> <span class="n">fgrad</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">floor</span><span class="p">(</span><span class="k">end</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="mi">1</span><span class="p">,:);</span>
    <span class="k">otherwise</span>        
<span class="k">end</span>
 
<span class="n">patchmax</span> <span class="p">=</span> <span class="nb">ones</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="n">owidth</span><span class="p">);</span>
<span class="k">if</span> <span class="n">nargout</span> <span class="o">&gt;</span> <span class="mi">1</span>
    <span class="n">patchmax</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="mi">1</span><span class="p">,</span><span class="n">owidth</span><span class="p">);</span>
    <span class="k">for</span> <span class="n">kk</span> <span class="p">=</span> <span class="mi">1</span><span class="p">:</span><span class="n">owidth</span>
       <span class="n">patchmax</span><span class="p">(</span><span class="n">kk</span><span class="p">)</span> <span class="p">=</span> <span class="n">norm</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">(:,</span><span class="n">kk</span><span class="p">),</span><span class="n">Inf</span><span class="p">);</span>       
    <span class="k">end</span>
    <span class="n">patchmax</span><span class="p">(</span><span class="n">patchmax</span> <span class="o">&lt;</span> <span class="mf">10e-8</span><span class="p">)</span> <span class="p">=</span> <span class="n">Inf</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">switch</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">0</span><span class="p">,</span><span class="mi">4</span><span class="p">}</span> <span class="c">%log spectrogram feature</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="nb">abs</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">);</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="mi">20</span><span class="o">*</span><span class="nb">log10</span><span class="p">(</span><span class="n">temp</span><span class="p">);</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span> <span class="o">-</span> <span class="n">max</span><span class="p">(</span><span class="n">temp</span><span class="p">(:));</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span> <span class="o">+</span> <span class="n">param</span><span class="p">.</span><span class="n">dbrange</span><span class="p">;</span>
        <span class="n">temp</span><span class="p">(</span><span class="n">temp</span><span class="o">&lt;</span><span class="mi">0</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
        <span class="n">featuremat</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="p">,:)</span> <span class="p">=</span> <span class="n">temp</span><span class="o">/</span><span class="n">max</span><span class="p">(</span><span class="n">temp</span><span class="p">(:));</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">1</span><span class="p">}</span> <span class="c">% spectrogram feature</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="nb">abs</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">)</span><span class="o">.^</span><span class="mi">2</span><span class="p">;</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span><span class="o">./</span><span class="nb">repmat</span><span class="p">(</span><span class="n">patchmax</span><span class="o">.^</span><span class="mi">2</span><span class="p">,</span><span class="n">vsize</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
        <span class="n">featuremat</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="p">,:)</span> <span class="p">=</span> <span class="n">temp</span><span class="p">;</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">2</span><span class="p">,</span><span class="mi">5</span><span class="p">}</span> <span class="c">% reassigned spectrogram feature        </span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">gabreassign</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">)</span><span class="o">.^</span><span class="mi">2</span><span class="p">,</span><span class="n">tgrad</span><span class="p">,</span><span class="n">fgrad</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">);</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="mi">10</span><span class="o">*</span><span class="nb">log10</span><span class="p">(</span><span class="n">temp</span><span class="p">);</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span> <span class="o">-</span> <span class="n">max</span><span class="p">(</span><span class="n">temp</span><span class="p">(:));</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span> <span class="o">+</span> <span class="mf">1.5</span><span class="o">*</span><span class="n">param</span><span class="p">.</span><span class="n">dbrange</span><span class="p">;</span>
        <span class="n">temp</span><span class="p">(</span><span class="n">temp</span><span class="o">&lt;</span><span class="mi">0</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span> 
        <span class="c">%temp = temp./repmat(patchmax.^2,vsize,1);</span>
        <span class="n">featuremat</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="p">,:)</span> <span class="p">=</span> <span class="n">temp</span><span class="o">/</span><span class="n">max</span><span class="p">(</span><span class="n">temp</span><span class="p">(:));</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">6</span><span class="p">}</span>   <span class="c">% MFCC feature</span>
<span class="c">%        error(&#39;This will be implemented later on&#39;);</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">mfcc_from_dgtreal</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">,</span><span class="n">fs</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">M</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">cepstralcoefs</span><span class="p">);</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="nb">abs</span><span class="p">(</span><span class="n">temp</span><span class="p">(</span><span class="mi">2</span><span class="p">:</span><span class="k">end</span><span class="p">,:));</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span><span class="o">/</span><span class="n">max</span><span class="p">(</span><span class="n">temp</span><span class="p">(:));</span>
        <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span><span class="o">./</span><span class="nb">repmat</span><span class="p">(</span><span class="n">patchmax</span><span class="o">.^</span><span class="mi">2</span><span class="p">,</span><span class="n">param</span><span class="p">.</span><span class="n">cepstralcoefs</span><span class="p">,</span><span class="mi">1</span><span class="p">);</span>
        <span class="n">featuremat</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">param</span><span class="p">.</span><span class="n">cepstralcoefs</span><span class="o">-</span><span class="mi">1</span><span class="p">,:)</span> <span class="p">=</span> <span class="n">temp</span><span class="p">;</span>
    <span class="k">otherwise</span>        
<span class="k">end</span>
 
<span class="k">switch</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span>
    <span class="k">case</span> <span class="p">{</span><span class="mi">3</span><span class="p">,</span><span class="mi">4</span><span class="p">,</span><span class="mi">5</span><span class="p">}</span> <span class="c">% phase derivative feature</span>
       <span class="n">temp</span> <span class="p">=</span> <span class="n">tgrad</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="p">,:);</span>
       <span class="n">temp1</span> <span class="p">=</span> <span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">gabor_transform</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="p">,:))</span><span class="o">&gt;</span> <span class="mi">2</span><span class="o">*</span><span class="n">mxGT</span><span class="o">*</span><span class="mf">10e-3</span><span class="p">);</span>
       <span class="n">temp</span> <span class="p">=</span> <span class="n">temp1</span><span class="o">.*</span><span class="n">temp</span><span class="p">;</span>
       
       <span class="n">clear</span> <span class="n">temp1</span><span class="p">;</span> 
       
       <span class="n">kernel</span> <span class="p">=</span> <span class="n">fir2long</span><span class="p">(</span><span class="n">firwin</span><span class="p">(</span><span class="s">&#39;hann&#39;</span><span class="p">,</span><span class="mi">8</span><span class="p">),</span><span class="nb">size</span><span class="p">(</span><span class="n">temp</span><span class="p">,</span><span class="mi">2</span><span class="p">));</span>
       <span class="n">temp</span> <span class="p">=</span> <span class="n">pconv</span><span class="p">(</span><span class="n">temp</span><span class="p">.</span><span class="o">&#39;</span><span class="p">,</span><span class="n">kernel</span><span class="p">).</span><span class="o">&#39;</span><span class="p">;</span>
<span class="c">%        for kk = 1:size(temp,1)</span>
<span class="c">%           temp(kk,:) = pconv(temp(kk,:),kernel);</span>
<span class="c">%        end</span>
       <span class="n">mx</span> <span class="p">=</span> <span class="n">max</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">temp</span><span class="p">(:)));</span>
       <span class="n">temp</span> <span class="p">=</span> <span class="n">temp</span><span class="o">/</span><span class="n">mx</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">lambda</span><span class="p">;</span>
       <span class="n">featuremat</span><span class="p">((</span><span class="n">num_features</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="n">vsize</span><span class="o">+</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="p">),:)</span> <span class="p">=</span> <span class="n">temp</span><span class="p">;</span>
    <span class="k">otherwise</span>        
<span class="k">end</span>
 
<span class="c">% else</span>
<span class="c">%     % Old method</span>
<span class="c">%     </span>
<span class="c">% </span>
<span class="c">%     </span>
<span class="c">%     g = firwin(param.win,param.win_length);</span>
<span class="c">%     Gd = @(x) dgtreal(x,g,param.a,param.M);</span>
<span class="c">%     F = frame(&#39;dgt&#39;,g,param.a,param.M);</span>
<span class="c">%     [~,B] = framebounds(F);</span>
<span class="c">%     gabor_transform = Gd(s)/sqrt(B);</span>
<span class="c">%     [vsize, owidth]= size(gabor_transform);     </span>
<span class="c">% </span>
<span class="c">%      patchmax = ones(1,owidth);</span>
<span class="c">%     if nargout &gt; 1</span>
<span class="c">%         patchmax = zeros(1,owidth);</span>
<span class="c">%         for kk = 1:owidth</span>
<span class="c">%            patchmax(kk) = norm(gabor_transform(:,kk),Inf);       </span>
<span class="c">%         end</span>
<span class="c">%         patchmax(patchmax &lt; 10e-8) = Inf;</span>
<span class="c">%     end</span>
<span class="c">%     spec = abs(gabor_transform).^2;</span>
<span class="c">%     transpec = 20*log10(spec);</span>
<span class="c">%     transpec = transpec - max(transpec(:));</span>
<span class="c">%     transpec = transpec + param.dbrange;</span>
<span class="c">%     transpec(transpec&lt;0) = 0;</span>
<span class="c">%     featuremat = transpec/max(transpec(:));</span>
<span class="c">% end</span>
 
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span><span class="o">&gt;</span><span class="mi">1</span>
    <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span> <span class="o">==</span> <span class="mi">4</span><span class="p">;</span>
        <span class="n">figure</span><span class="p">(</span><span class="mi">101</span><span class="p">)</span>
        <span class="n">subplot</span><span class="p">(</span><span class="mi">211</span><span class="p">)</span>
        <span class="n">time</span> <span class="p">=</span> <span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">round</span><span class="p">(</span><span class="n">vsize</span><span class="o">/</span><span class="mi">4</span><span class="p">))</span><span class="o">/</span><span class="nb">round</span><span class="p">(</span><span class="n">vsize</span><span class="o">/</span><span class="mi">4</span><span class="p">)</span><span class="o">*</span><span class="nb">length</span><span class="p">(</span><span class="n">s</span><span class="p">)</span><span class="o">/</span><span class="n">fs</span><span class="p">;</span>
        <span class="n">frequency</span> <span class="p">=</span> <span class="p">(</span><span class="mi">0</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">featuremat</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">/</span><span class="nb">size</span><span class="p">(</span><span class="n">featuremat</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="n">fs</span><span class="o">/</span><span class="mi">2</span><span class="p">;</span>
        <span class="n">imagesc</span><span class="p">(</span><span class="n">time</span><span class="p">,</span><span class="n">frequency</span><span class="p">,</span><span class="nb">abs</span><span class="p">(</span><span class="n">featuremat</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="nb">round</span><span class="p">(</span><span class="n">vsize</span><span class="o">/</span><span class="mi">4</span><span class="p">),:)))</span>
        <span class="n">axis</span><span class="p">(</span><span class="s">&#39;xy&#39;</span><span class="p">);</span>
        <span class="n">xlabel</span><span class="p">(</span><span class="s">&#39;Time (s)&#39;</span><span class="p">)</span>
        <span class="n">ylabel</span><span class="p">(</span><span class="s">&#39;Frequency (Hz)&#39;</span><span class="p">)</span>
        <span class="n">colorbar</span><span class="p">;</span>
        <span class="n">title</span><span class="p">(</span><span class="s">&#39;Normalized log-spectrogram&#39;</span><span class="p">)</span>        
        <span class="n">subplot</span><span class="p">(</span><span class="mi">212</span><span class="p">)</span>
        <span class="n">imagesc</span><span class="p">(</span><span class="n">time</span><span class="p">,</span><span class="n">frequency</span><span class="p">,</span><span class="n">featuremat</span><span class="p">(</span><span class="n">vsize</span><span class="o">+</span><span class="mi">1</span><span class="p">:</span><span class="n">vsize</span><span class="o">+</span><span class="nb">round</span><span class="p">(</span><span class="n">vsize</span><span class="o">/</span><span class="mi">4</span><span class="p">),:))</span>
        <span class="n">axis</span><span class="p">(</span><span class="s">&#39;xy&#39;</span><span class="p">);</span>
        <span class="n">xlabel</span><span class="p">(</span><span class="s">&#39;Time (s)&#39;</span><span class="p">)</span>
        <span class="n">ylabel</span><span class="p">(</span><span class="s">&#39;Frequency (Hz)&#39;</span><span class="p">)</span>
        <span class="n">colorbar</span><span class="p">;</span>
        <span class="n">title</span><span class="p">(</span><span class="s">&#39;Phase derivative&#39;</span><span class="p">)</span>   
    <span class="k">else</span>
        <span class="n">figure</span><span class="p">(</span><span class="mi">101</span><span class="p">)</span>
       <span class="n">imagesc</span><span class="p">(</span><span class="nb">abs</span><span class="p">(</span><span class="n">featuremat</span><span class="p">))</span>
        <span class="n">axis</span><span class="p">(</span><span class="s">&#39;xy&#39;</span><span class="p">);</span>
        <span class="n">xlabel</span><span class="p">(</span><span class="s">&#39;Patch number&#39;</span><span class="p">)</span>
        <span class="n">ylabel</span><span class="p">(</span><span class="s">&#39;Frequency feature number&#39;</span><span class="p">)</span>
        <span class="n">colorbar</span><span class="p">;</span>
        <span class="n">title</span><span class="p">(</span><span class="s">&#39;Feature space&#39;</span><span class="p">)</span>
    <span class="k">end</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="n">nargout</span> <span class="o">&gt;</span> <span class="mi">1</span>
    <span class="c">% Why to we do this?</span>
    <span class="n">patchmax</span><span class="p">(</span><span class="n">patchmax</span> <span class="o">&gt;</span> <span class="mf">10e10</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">end</span>
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
