<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$path_include="../include/";
include($path_include."main.php");
$doctype=2;
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
<h2>Program code:</h2>
<div class="highlight"><pre><span class="k">function</span><span class="w"> </span>[G, Gfull,param, timing] <span class="p">=</span><span class="w"> </span><span class="nf">ai_time_audio_graph</span><span class="p">(</span>s,fs,param<span class="p">)</span><span class="w"></span>
<span class="c">%AI_TIME_AUDIO_GRAPH Create a graph of similarities from an audio signal</span>
<span class="c">%   Usage: G = ai_time_audio_graph(s, fs)</span>
<span class="c">%          G = ai_time_audio_graph(s, fs, param)</span>
<span class="c">%          [G, Gfull] = ai_time_audio_graph(...)</span>
<span class="c">%          [G, Gfull, param] = ai_time_audio_graph(...)</span>
<span class="c">%          [G, Gfull, param, timing] = ai_time_audio_graph(...)</span>
<span class="c">%</span>
<span class="c">%   Input parameters:</span>
<span class="c">%       s           : signal</span>
<span class="c">%       fs          : sampling frequency</span>
<span class="c">%       param       : structure of optional parameters</span>
<span class="c">%   Output parameters:</span>
<span class="c">%       G           : graph of audio transitions</span>
<span class="c">%       Gfull       : a graph with more audio transitions</span>
<span class="c">%       param       : structure of optional parameters (updated)</span>
<span class="c">%       timing      : timing (features computation, graph construction)</span>
<span class="c">%</span>
<span class="c">%   This function create a graph of similarity inside a song with the</span>
<span class="c">%   following steps:</span>
<span class="c">%</span>
<span class="c">%   1. Downsampling of the song </span>
<span class="c">%   2. Compute audio features from a Time-Frequency transform</span>
<span class="c">%   3. Create the graph of nearest neighbors</span>
<span class="c">%   4. Select only relevant connections</span>
<span class="c">%</span>
<span class="c">%   param is a structure of optional parameter containing the following</span>
<span class="c">%   fields:</span>
<span class="c">%</span>
<span class="c">%    param.fsmax : maximum sampling frequency (default 12 000 Hz). The</span>
<span class="c">%     algorithm will cut everything above this frequency before any other</span>
<span class="c">%     operation.</span>
<span class="c">%</span>
<span class="c">%    param.win_length : window length (default: 1024)</span>
<span class="c">%    param.M : number of frequency channels (default: param.win_length*)</span>
<span class="c">%    param.a : shift in time (default: 128)</span>
<span class="c">%    param.win : window used (default: &#39;itersine&#39;)</span>
<span class="c">%</span>
<span class="c">%    param.dbrange : range of dB used in the spectrograph (default: 50)</span>
<span class="c">%</span>
<span class="c">%    param.use_flann : use flann library for the computation of the</span>
<span class="c">%     graph (default 1)</span>
<span class="c">%    param.k         : number of neighbors for the graph (default 10)</span>
<span class="c">%    param.loc       : locality parameter (default 0). How much being</span>
<span class="c">%     far away from each other is important?</span>
<span class="c">%</span>
<span class="c">%    param.hsize     : numbeer of time bin for one patch (default 4).</span>
<span class="c">%</span>
<span class="c">%    param.diagdist     : size of the convolution kernel  (default 20).</span>
<span class="c">%    param.threshold    : threshold to cut small values (default 2).</span>
<span class="c">%    param.featuretype  : type of features (default 0), see the function</span>
<span class="c">%     ai_compute_features for more details.</span>
<span class="c">%</span>
<span class="c">%   References:</span>
<span class="c">%     N. Perraudin, N. Holighaus, P. Majdak, and P. Balazs. Audio inpainting</span>
<span class="c">%     with similarity graphs. arXiv preprint arXiv:1607.06667, 2016.</span>
<span class="c">%     </span>
<span class="c">%     </span>
<span class="c">%</span>
<span class="c">%</span>
<span class="c">%   Url: https://lts2.epfl.ch/rrp/audio_inpainting/ai_time_audio_graph.php</span>

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
 
 
<span class="c">% 0) Handling optional parameters</span>
<span class="k">if</span> <span class="n">nargin</span> <span class="o">&lt;</span> <span class="mi">3</span>
    <span class="n">param</span> <span class="p">=</span> <span class="n">struct</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;fsmax&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">fsmax</span> <span class="p">=</span> <span class="mi">12000</span><span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;verbose&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span> <span class="k">end</span>
 
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;use_flann&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">use_flann</span> <span class="p">=</span> <span class="mi">1</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;k&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">k</span> <span class="p">=</span> <span class="mi">40</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;graph_type&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">graph_type</span> <span class="p">=</span> <span class="s">&#39;knn&#39;</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;loc&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">loc</span> <span class="p">=</span> <span class="mi">0</span> <span class="p">;</span> <span class="k">end</span>
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;hsize&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">hsize</span> <span class="p">=</span> <span class="mi">0</span> <span class="p">;</span> <span class="k">end</span>
 
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;diagdist&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">diagdist</span> <span class="p">=</span> <span class="mi">40</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;threshold&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">threshold</span> <span class="p">=</span> <span class="mi">2</span> <span class="p">;</span> <span class="k">end</span>
<span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;featuretype&#39;</span><span class="p">),</span> <span class="n">param</span><span class="p">.</span><span class="n">featuretype</span> <span class="p">=</span> <span class="mi">4</span> <span class="p">;</span> <span class="k">end</span>
 
<span class="k">if</span> <span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">,</span><span class="s">&#39;subgraph&#39;</span><span class="p">)</span>
    
    <span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">,</span><span class="s">&#39;time_in&#39;</span><span class="p">)</span>
        <span class="n">fprintf</span><span class="p">(</span><span class="s">&#39;Missing starting time. Use the full graph \n&#39;</span><span class="p">);</span>
        <span class="n">use_subgraph</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
    <span class="k">elseif</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">,</span><span class="s">&#39;time_fin&#39;</span><span class="p">)</span>
        <span class="n">fprintf</span><span class="p">(</span><span class="s">&#39;Missing final time. Use the full graph \n&#39;</span><span class="p">);</span>
        <span class="n">use_subgraph</span> <span class="p">=</span> <span class="n">o</span><span class="p">;</span>
    <span class="k">else</span>
        <span class="k">if</span> <span class="o">~</span><span class="n">isfield</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">,</span><span class="s">&#39;time_range&#39;</span><span class="p">)</span>
            <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_range</span> <span class="p">=</span> <span class="mi">5</span><span class="p">;</span>
        <span class="k">end</span>
    <span class="n">use_subgraph</span> <span class="p">=</span> <span class="mi">1</span><span class="p">;</span>
    <span class="k">end</span>
    
<span class="k">else</span>
    <span class="n">use_subgraph</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="k">end</span>
 
 
<span class="c">%% STEP 1</span>
<span class="c">% Downsampling , we do not really care about high frequencies + making</span>
<span class="c">% the sound mono (we can use stereo sound later on)</span>
<span class="k">if</span> <span class="nb">size</span><span class="p">(</span><span class="n">s</span><span class="p">,</span><span class="mi">2</span><span class="p">)</span><span class="o">&gt;</span><span class="mi">1</span>
    <span class="n">s</span> <span class="p">=</span> <span class="p">(</span><span class="n">s</span><span class="p">(:,</span><span class="mi">1</span><span class="p">)</span><span class="o">+</span><span class="n">s</span><span class="p">(:,</span><span class="mi">2</span><span class="p">))</span><span class="o">/</span><span class="mi">2</span><span class="p">;</span>
<span class="k">end</span>
 
<span class="c">% % Original size of the signal</span>
<span class="c">% Noriginal = length(s);</span>
 
<span class="c">% Downsample if necessary </span>
<span class="k">if</span> <span class="n">fs</span> <span class="o">&gt;</span> <span class="n">param</span><span class="p">.</span><span class="n">fsmax</span>
    <span class="n">ratio</span> <span class="p">=</span> <span class="nb">ceil</span><span class="p">(</span><span class="n">fs</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">fsmax</span><span class="p">);</span>
    <span class="n">s</span> <span class="p">=</span> <span class="n">resample</span><span class="p">(</span><span class="n">s</span><span class="p">,</span><span class="mi">1</span><span class="p">,</span><span class="n">ratio</span><span class="p">);</span>
    <span class="n">fs</span> <span class="p">=</span> <span class="n">fs</span><span class="o">/</span><span class="n">ratio</span><span class="p">;</span>
<span class="k">end</span>
 
 
<span class="c">%% STEP 2</span>
<span class="c">% 2) Create audio features</span>
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
    <span class="n">fprintf</span><span class="p">(</span><span class="s">&#39;Compute local audio features\n&#39;</span><span class="p">);</span>
<span class="k">end</span>
<span class="c">% featuremat = sim_features(s,fs,param);</span>
<span class="c">% </span>
<span class="c">% %% Create the feature patches</span>
<span class="c">% </span>
<span class="c">% 1) Compute useful quantities</span>
 
 
<span class="c">% if nargout &gt; 3</span>
<span class="c">%     [patches,patchmax] = sim_features(s,fs,param);</span>
<span class="c">% else </span>
<span class="c">%     patches = sim_features(s,fs,param);</span>
<span class="c">% end</span>
<span class="n">tfeatures</span> <span class="p">=</span> <span class="n">tic</span><span class="p">;</span>
 
<span class="p">[</span><span class="n">featuremat</span><span class="p">,</span> <span class="n">param</span><span class="p">,</span> <span class="n">patchmax</span><span class="p">]</span> <span class="p">=</span> <span class="n">ai_compute_features</span><span class="p">(</span><span class="n">s</span><span class="p">,</span><span class="n">fs</span><span class="p">,</span><span class="n">param</span><span class="p">);</span>
 
<span class="n">hsize</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">hsize</span><span class="p">;</span>
<span class="n">marg</span> <span class="p">=</span> <span class="nb">floor</span><span class="p">(</span><span class="n">hsize</span> <span class="o">/</span> <span class="mi">2</span><span class="p">);</span>  <span class="c">% left - right margin</span>
 
<span class="k">if</span> <span class="n">hsize</span>
    <span class="p">[</span><span class="n">vsize</span><span class="p">,</span><span class="n">owidth</span><span class="p">]</span> <span class="p">=</span> <span class="nb">size</span><span class="p">(</span><span class="n">featuremat</span><span class="p">);</span>
    <span class="n">dim</span> <span class="p">=</span> <span class="n">hsize</span><span class="o">*</span><span class="n">vsize</span><span class="p">;</span>
 
    <span class="c">% 2) Initialize variables    </span>
    <span class="n">patches</span> <span class="p">=</span> <span class="nb">zeros</span><span class="p">(</span><span class="n">dim</span><span class="o">+</span><span class="mi">1</span><span class="p">,</span><span class="n">owidth</span><span class="o">-</span><span class="mi">2</span><span class="o">*</span><span class="n">marg</span><span class="p">);</span>
 
    <span class="k">for</span> <span class="n">jj</span> <span class="p">=</span> <span class="mi">1</span> <span class="p">:</span> <span class="n">owidth</span><span class="o">-</span><span class="mi">2</span><span class="o">*</span><span class="n">marg</span>    
        <span class="n">patches</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">,</span><span class="n">jj</span><span class="p">)</span> <span class="p">=</span> <span class="nb">reshape</span><span class="p">(</span><span class="c">...</span>
            <span class="n">featuremat</span><span class="p">(:,</span> <span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">hsize</span><span class="p">)</span><span class="o">+</span><span class="p">(</span><span class="n">jj</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span> <span class="p">),[],</span><span class="mi">1</span><span class="p">);</span>
        <span class="c">% for  distance of the coordinates</span>
        <span class="n">patches</span><span class="p">(</span><span class="k">end</span><span class="p">,</span><span class="n">jj</span><span class="p">)</span> <span class="p">=</span> <span class="n">jj</span><span class="o">*</span><span class="n">param</span><span class="p">.</span><span class="n">loc</span><span class="p">;</span>
    <span class="k">end</span>
<span class="k">else</span>
    <span class="n">patches</span> <span class="p">=</span> <span class="n">featuremat</span><span class="p">;</span>
<span class="k">end</span>
<span class="n">timing_features</span> <span class="p">=</span> <span class="n">toc</span><span class="p">(</span><span class="n">tfeatures</span><span class="p">);</span>
 
<span class="c">%% Step 3</span>
<span class="c">% Create the time variable</span>
<span class="n">N</span> <span class="p">=</span> <span class="nb">size</span><span class="p">(</span><span class="n">patches</span><span class="p">,</span><span class="mi">2</span><span class="p">);</span>
<span class="c">% Check this !!</span>
<span class="n">time</span> <span class="p">=</span> <span class="p">((</span><span class="n">marg</span><span class="p">):(</span><span class="n">marg</span><span class="o">+</span><span class="n">N</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span> <span class="p">)</span> <span class="o">*</span> <span class="n">param</span><span class="p">.</span><span class="n">a</span> <span class="o">*</span> <span class="n">ratio</span><span class="p">;</span>
 
<span class="c">%% STEP 4</span>
<span class="n">tgraph</span> <span class="p">=</span> <span class="n">tic</span><span class="p">;</span>
<span class="c">% Create the graph of nearest neighbors</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">use_flann</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">use_flann</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">type</span> <span class="p">=</span> <span class="s">&#39;knn&#39;</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">k</span> <span class="p">=</span> <span class="n">max</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">k</span><span class="p">,</span><span class="nb">floor</span><span class="p">(</span><span class="n">N</span><span class="o">/</span><span class="mi">1000</span><span class="p">));</span> <span class="c">% Patch graph minimum number of connections (KNN).</span>
 
<span class="n">gparam</span><span class="p">.</span><span class="n">center</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">rescale</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">resize</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">symetrize_type</span> <span class="p">=</span> <span class="s">&#39;full&#39;</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">type</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">graph_type</span><span class="p">;</span>
<span class="n">gparam</span><span class="p">.</span><span class="n">target_degree</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">k</span><span class="p">;</span>
 
<span class="k">if</span> <span class="n">use_subgraph</span>
    <span class="n">t_ini</span> <span class="p">=</span> <span class="n">max</span><span class="p">(</span><span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_in</span><span class="o">/</span><span class="n">ratio</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">a</span> <span class="o">-</span> <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_range</span><span class="o">*</span><span class="n">fs</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">a</span>  <span class="p">),</span><span class="mi">1</span><span class="p">);</span>
    <span class="n">t_inf</span> <span class="p">=</span> <span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_in</span><span class="o">/</span><span class="n">ratio</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">a</span> <span class="p">);</span>
    <span class="n">t_outf</span> <span class="p">=</span> <span class="n">min</span><span class="p">(</span><span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_fin</span><span class="o">/</span><span class="n">ratio</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">a</span> <span class="o">+</span> <span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_range</span><span class="o">*</span><span class="n">fs</span><span class="o">/</span> <span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">),</span><span class="n">N</span><span class="p">);</span>
    <span class="n">t_outi</span> <span class="p">=</span> <span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">subgraph</span><span class="p">.</span><span class="n">time_fin</span><span class="o">/</span><span class="n">ratio</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">);</span>
    <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
        <span class="n">fprintf</span><span class="p">(</span><span class="s">&#39;Compute graph with %d vertices\n&#39;</span> <span class="p">,</span> <span class="nb">size</span><span class="p">(</span><span class="n">patches</span><span class="p">,</span><span class="mi">2</span><span class="p">));</span>
    <span class="k">end</span>
    <span class="n">Gd</span> <span class="p">=</span> <span class="n">gsp_nn_part_graph</span><span class="p">(</span><span class="n">patches</span><span class="o">&#39;</span><span class="p">,[</span><span class="mi">1</span><span class="p">:</span><span class="n">t_inf</span><span class="p">,</span><span class="n">t_outi</span><span class="p">:</span><span class="nb">size</span><span class="p">(</span><span class="n">patches</span><span class="p">,</span><span class="mi">2</span><span class="p">)],[</span><span class="n">t_ini</span><span class="p">:</span><span class="n">t_inf</span><span class="p">,</span><span class="n">t_outi</span><span class="p">:</span><span class="n">t_outf</span><span class="p">],</span><span class="n">gparam</span><span class="p">);</span>
<span class="k">else</span>
    <span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
        <span class="n">fprintf</span><span class="p">(</span><span class="s">&#39;Compute graph with %d vertices\n&#39;</span> <span class="p">,</span> <span class="nb">size</span><span class="p">(</span><span class="n">patches</span><span class="p">,</span><span class="mi">2</span><span class="p">));</span>
    <span class="k">end</span>
    <span class="n">Gd</span> <span class="p">=</span> <span class="n">gsp_nn_graph</span><span class="p">(</span><span class="n">patches</span><span class="o">&#39;</span><span class="p">,</span> <span class="n">gparam</span><span class="p">);</span>    
<span class="k">end</span>
    <span class="c">% Update plotting parameters</span>
    <span class="n">x</span> <span class="p">=</span> <span class="nb">sin</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">)</span><span class="o">/</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="o">*</span><span class="nb">pi</span><span class="o">*</span><span class="mf">0.95</span><span class="p">);</span>    
    <span class="n">y</span> <span class="p">=</span> <span class="nb">cos</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">)</span><span class="o">/</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="o">*</span><span class="nb">pi</span><span class="o">*</span><span class="mf">0.95</span><span class="p">);</span>
    <span class="n">Gd</span><span class="p">.</span><span class="n">coords</span> <span class="p">=</span> <span class="p">[</span><span class="n">x</span><span class="p">;</span><span class="n">y</span><span class="p">]</span><span class="o">&#39;</span><span class="p">;</span>
    <span class="n">Gd</span><span class="p">.</span><span class="n">plotting</span><span class="p">.</span><span class="n">limits</span> <span class="p">=</span> <span class="p">[</span><span class="o">-</span><span class="mf">1.1</span> <span class="mf">1.1</span> <span class="o">-</span><span class="mf">1.1</span> <span class="mf">1.1</span><span class="p">];</span>
    <span class="n">Gd</span> <span class="p">=</span> <span class="n">gsp_graph_default_parameters</span><span class="p">(</span><span class="n">Gd</span><span class="p">);</span>
 
<span class="c">%% STEP 5</span>
<span class="c">%  Select only relevant connections. To do so we create another graph G2 !</span>
 
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span>
    <span class="n">fprintf</span><span class="p">(</span><span class="s">&#39;Select only good connections\n&#39;</span><span class="p">);</span>
<span class="k">end</span>
<span class="c">% 1) remove the diagonal</span>
<span class="n">W</span> <span class="p">=</span> <span class="n">Gd</span><span class="p">.</span><span class="n">W</span><span class="p">;</span>
<span class="c">% W( logical( 1-(triu(ones(Gd.N),param.diagdist) + tril(ones(Gd.N),-param.diagdist))) ) = 0;</span>
<span class="c">% W( logical(spdiags(ones(Gd.N,param.diagdist*2+1), -param.diagdist:param.diagdist, Gd.N, Gd.N)) ) = 0;</span>
<span class="n">W</span><span class="p">(</span> <span class="n">logical</span><span class="p">(</span><span class="n">spdiags</span><span class="p">(</span><span class="nb">ones</span><span class="p">(</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">,</span><span class="mi">2</span><span class="o">*</span><span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">diagdist</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span><span class="o">+</span><span class="mi">1</span><span class="p">),</span> <span class="c">...</span>
    <span class="o">-</span><span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">diagdist</span><span class="o">/</span><span class="mi">2</span><span class="p">):</span><span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">diagdist</span><span class="o">/</span><span class="mi">2</span><span class="p">),</span> <span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">,</span> <span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">))</span> <span class="p">)</span><span class="c">...</span>
    <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
 
    
    
<span class="c">% 2) We want to have multiple connections in time to assert a good</span>
<span class="c">% edge. So we compute a convolution with a diagonal kernel. This will</span>
<span class="c">% diffuse the energy of isolate connections and keep strong multiple</span>
<span class="c">% connections. </span>
<span class="n">dd</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">diagdist</span><span class="p">;</span>
<span class="n">convk</span> <span class="p">=</span> <span class="nb">diag</span><span class="p">(</span><span class="n">tripuls</span><span class="p">(</span><span class="o">-</span><span class="nb">round</span><span class="p">(</span><span class="n">dd</span><span class="p">)</span><span class="o">/</span><span class="mi">2</span><span class="p">:</span><span class="nb">round</span><span class="p">(</span><span class="n">dd</span><span class="p">)</span><span class="o">/</span><span class="mi">2</span><span class="p">,</span><span class="nb">round</span><span class="p">(</span><span class="n">dd</span><span class="p">)</span><span class="o">+</span><span class="mi">1</span><span class="p">));</span> <span class="c">% kernel</span>
 
<span class="k">if</span> <span class="n">use_subgraph</span>
     <span class="n">Wconv</span> <span class="p">=</span> <span class="n">sconv2</span><span class="p">(</span><span class="n">W</span><span class="p">,</span><span class="n">convk</span><span class="p">,</span><span class="s">&#39;same&#39;</span><span class="p">);</span>
<span class="c">%     Wconv = zeros(size(W));</span>
<span class="c">%     Wconv(:,[t_ini:t_inf,t_outi:t_outf]) = conv2(full(W(:,[t_ini:t_inf,t_outi:t_outf])),convk,&#39;same&#39;);</span>
<span class="k">else</span>
    <span class="c">%Wconv = conv2(full(W),convk,&#39;same&#39;);</span>
    <span class="n">Wconv</span> <span class="p">=</span> <span class="n">sconv2</span><span class="p">(</span><span class="n">W</span><span class="p">,</span><span class="n">convk</span><span class="p">,</span><span class="s">&#39;same&#39;</span><span class="p">);</span>
 
<span class="k">end</span>
 
<span class="c">% Remove small elements</span>
<span class="p">[</span><span class="n">row</span><span class="p">,</span><span class="n">col</span><span class="p">,</span><span class="n">v</span><span class="p">]</span> <span class="p">=</span> <span class="nb">find</span><span class="p">(</span><span class="n">Wconv</span><span class="p">);</span>
<span class="n">row</span> <span class="p">=</span> <span class="n">row</span><span class="p">(</span><span class="n">v</span><span class="o">&gt;</span><span class="n">param</span><span class="p">.</span><span class="n">threshold</span><span class="p">);</span>
<span class="n">col</span> <span class="p">=</span> <span class="n">col</span><span class="p">(</span><span class="n">v</span><span class="o">&gt;</span><span class="n">param</span><span class="p">.</span><span class="n">threshold</span><span class="p">);</span>
<span class="n">v</span> <span class="p">=</span> <span class="n">v</span><span class="p">(</span><span class="n">v</span><span class="o">&gt;</span><span class="n">param</span><span class="p">.</span><span class="n">threshold</span><span class="p">);</span>
<span class="n">Wconv</span> <span class="p">=</span> <span class="n">sparse</span><span class="p">(</span><span class="n">row</span><span class="p">,</span><span class="n">col</span><span class="p">,</span><span class="n">v</span><span class="p">,</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">,</span><span class="n">Gd</span><span class="p">.</span><span class="n">N</span><span class="p">);</span>
 
<span class="c">% figure(1); imagesc(log(Wconv))</span>
 
<span class="c">% Remove the starting and final connections. They are wrong because the</span>
<span class="c">% signal is not periodic</span>
<span class="n">Nr</span> <span class="p">=</span> <span class="nb">round</span><span class="p">(</span><span class="n">param</span><span class="p">.</span><span class="n">win_length</span><span class="o">/</span><span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">);</span>
<span class="n">Wconv</span><span class="p">(</span><span class="mi">1</span><span class="p">:</span><span class="n">Nr</span><span class="p">,:)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(:,</span><span class="mi">1</span><span class="p">:</span><span class="n">Nr</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">((</span><span class="k">end</span><span class="o">-</span><span class="n">Nr</span><span class="p">):</span><span class="k">end</span><span class="p">,:)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(:,(</span><span class="k">end</span><span class="o">-</span><span class="n">Nr</span><span class="p">):</span><span class="k">end</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
 
 
<span class="k">if</span> <span class="n">nargout</span><span class="o">&gt;</span><span class="mi">1</span>
    <span class="c">% Add new parameter to the graph structure</span>
    <span class="n">Gfull</span> <span class="p">=</span> <span class="n">Gd</span><span class="p">;</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">W</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">;</span>
    <span class="n">Gfull</span> <span class="p">=</span> <span class="n">gsp_graph_default_parameters</span><span class="p">(</span><span class="n">Gfull</span><span class="p">);</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">time</span> <span class="p">=</span> <span class="n">time</span><span class="p">;</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">ratio</span> <span class="p">=</span> <span class="n">ratio</span><span class="p">;</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">patchmax</span> <span class="p">=</span> <span class="n">patchmax</span><span class="p">;</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">fs</span> <span class="p">=</span> <span class="n">fs</span><span class="p">;</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">a</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">;</span>
    <span class="n">Gfull</span><span class="p">.</span><span class="n">features</span> <span class="p">=</span> <span class="n">featuremat</span><span class="p">;</span>
 
<span class="k">end</span>
 
 
 
 
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span><span class="o">&gt;</span><span class="mi">1</span>
    <span class="n">figure</span><span class="p">(</span><span class="mi">102</span><span class="p">)</span>
    <span class="n">imagesc</span><span class="p">(</span><span class="n">W</span><span class="p">)</span>
    <span class="n">colorbar</span>
    <span class="n">title</span><span class="p">(</span><span class="s">&#39;Nearest Neighboor weight matrix&#39;</span><span class="p">)</span>
    <span class="n">figure</span><span class="p">(</span><span class="mi">103</span><span class="p">)</span>
    <span class="n">imagesc</span><span class="p">(</span><span class="n">Wconv</span><span class="p">)</span>
    <span class="n">colorbar</span>
   <span class="c">% colormap(flipud(gray))</span>
    <span class="n">title</span><span class="p">(</span><span class="s">&#39;Weight matrix after convolution&#39;</span><span class="p">)</span>
<span class="k">end</span>
 
 
<span class="c">% 3) Compute local maxima for this</span>
<span class="n">C1</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">));</span>
<span class="n">C2</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">1</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">2</span><span class="p">),</span><span class="mi">3</span><span class="p">:(</span><span class="k">end</span><span class="p">));</span>
<span class="n">C3</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">3</span><span class="p">:(</span><span class="k">end</span><span class="p">),</span><span class="mi">1</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">2</span><span class="p">));</span>
<span class="n">C4</span> <span class="p">=</span> <span class="p">((</span><span class="n">C1</span><span class="o">-</span><span class="n">C2</span><span class="p">)</span><span class="o">&gt;</span><span class="mi">0</span><span class="p">)</span> <span class="o">&amp;</span> <span class="p">((</span><span class="n">C1</span><span class="o">-</span><span class="n">C3</span><span class="p">)</span><span class="o">&gt;</span><span class="mi">0</span><span class="p">);</span>
 
<span class="n">Wconv</span><span class="p">(</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">))</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">))</span><span class="o">.*</span> <span class="n">C4</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(</span><span class="mi">1</span><span class="p">,:)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(:,</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(</span><span class="k">end</span><span class="p">,:)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(:,</span><span class="k">end</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
 
 
 
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span><span class="o">&gt;</span><span class="mi">1</span>
    <span class="n">figure</span><span class="p">(</span><span class="mi">104</span><span class="p">)</span>
    <span class="n">imagesc</span><span class="p">(</span><span class="n">Wconv</span><span class="p">)</span>
    <span class="n">colorbar</span>
    <span class="n">title</span><span class="p">(</span><span class="s">&#39;Nearest Neighboor weight after first maxima selection&#39;</span><span class="p">)</span>
<span class="k">end</span>
 
 
<span class="n">C1</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">));</span>
<span class="n">C2</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">1</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">2</span><span class="p">),</span><span class="mi">1</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">2</span><span class="p">));</span>
<span class="n">C3</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">3</span><span class="p">:(</span><span class="k">end</span><span class="p">),</span><span class="mi">3</span><span class="p">:(</span><span class="k">end</span><span class="p">));</span>
<span class="n">C4</span> <span class="p">=</span> <span class="p">((</span><span class="n">C1</span><span class="o">-</span><span class="n">C2</span><span class="p">)</span><span class="o">&gt;</span><span class="mi">0</span><span class="p">)</span> <span class="o">&amp;</span> <span class="p">((</span><span class="n">C1</span><span class="o">-</span><span class="n">C3</span><span class="p">)</span><span class="o">&gt;</span><span class="mi">0</span><span class="p">);</span>
 
<span class="n">Wconv</span><span class="p">(</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">))</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">(</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">),</span><span class="mi">2</span><span class="p">:(</span><span class="k">end</span><span class="o">-</span><span class="mi">1</span><span class="p">))</span><span class="o">.*</span> <span class="n">C4</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(</span><span class="mi">1</span><span class="p">,:)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(:,</span><span class="mi">1</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(</span><span class="k">end</span><span class="p">,:)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
<span class="n">Wconv</span><span class="p">(:,</span><span class="k">end</span><span class="p">)</span> <span class="p">=</span> <span class="mi">0</span><span class="p">;</span>
 
<span class="k">if</span> <span class="n">param</span><span class="p">.</span><span class="n">verbose</span><span class="o">&gt;</span><span class="mi">1</span>
    <span class="n">figure</span><span class="p">(</span><span class="mi">105</span><span class="p">)</span>
    <span class="n">imagesc</span><span class="p">(</span><span class="n">Wconv</span><span class="p">)</span>
    <span class="n">colorbar</span>
    <span class="n">title</span><span class="p">(</span><span class="s">&#39;Nearest Neighboor weight after second maxima selection&#39;</span><span class="p">)</span>
<span class="k">end</span>
<span class="c">% 4) Create the new graph</span>
<span class="n">G</span> <span class="p">=</span> <span class="n">Gd</span><span class="p">;</span>
<span class="n">G</span><span class="p">.</span><span class="n">W</span> <span class="p">=</span> <span class="n">Wconv</span><span class="p">;</span>
<span class="n">G</span> <span class="p">=</span> <span class="n">gsp_graph_default_parameters</span><span class="p">(</span><span class="n">G</span><span class="p">);</span>
<span class="n">clear</span> <span class="n">Gd</span>
 
<span class="c">% Add new parameter to the graph structure</span>
<span class="n">G</span><span class="p">.</span><span class="n">time</span> <span class="p">=</span> <span class="n">time</span><span class="p">;</span>
<span class="n">G</span><span class="p">.</span><span class="n">ratio</span> <span class="p">=</span> <span class="n">ratio</span><span class="p">;</span>
<span class="n">G</span><span class="p">.</span><span class="n">patchmax</span> <span class="p">=</span> <span class="n">patchmax</span><span class="p">;</span>
<span class="n">G</span><span class="p">.</span><span class="n">fs</span> <span class="p">=</span> <span class="n">fs</span><span class="p">;</span>
<span class="n">G</span><span class="p">.</span><span class="n">a</span> <span class="p">=</span> <span class="n">param</span><span class="p">.</span><span class="n">a</span><span class="p">;</span>
<span class="n">G</span><span class="p">.</span><span class="n">features</span> <span class="p">=</span> <span class="n">featuremat</span><span class="p">;</span>
 
<span class="n">timing_graph</span> <span class="p">=</span> <span class="n">toc</span><span class="p">(</span><span class="n">tgraph</span><span class="p">);</span>
 
<span class="n">timing</span> <span class="p">=</span> <span class="p">[</span><span class="n">timing_features</span><span class="p">,</span> <span class="n">timing_graph</span><span class="p">];</span>
<span class="k">end</span>
</pre></div>

';
printpage($name,$subdir,$title,$keywords,$seealso,$demos,$content,$doctype);
?>
