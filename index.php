<?php 
/*
Copyright 2011 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

See the source code here:
     http://code.google.com/p/loadtimer/
*/

$bLoop = array_key_exists("loop", $_GET);
$gaUrls = array(
				"http://en.wikipedia.org/wiki/Flowers",
				"http://www.about.com/",
				"http://www.amazon.com/",
				"http://www.aol.com/",
				"http://www.ask.com/",
				"http://www.bing.com/search?q=news",
				"http://www.cnn.com/",
				"http://www.craigslist.com/",
				"http://www.dailymotion.com/",
				"http://www.ebay.com/",
				"http://www.engadget.com/",
				//"http://www.flickr.com/",
				"http://www.huffingtonpost.com/",
				"http://www.imdb.com/",
				"http://www.imgur.com/",
				//"http://www.myspace.com/",
				//"http://www.pinterest.com/",
				"http://www.reddit.com/"
				);
?>
<!doctype html>
<html>
<head>
<title>Loadtimer</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	   margin: 0;
	   padding: 0;
	   border: 0;
	   outline: 0;
	   font-size: 100%;
	   vertical-align: baseline;
	   background: transparent;
}
body { font: .813em "Lucida Grande","Lucida Sans Unicode",sans-serif; color: #333; line-height: 1.4em; padding: 8px; }
code { font-size: 1.2em; }
h1 { font: 2em Arial, Helvetica, sans-serif; color: #1D2432; font-weight: bold; margin-bottom: .8em; }
h2 { font: 1.75em Arial, Helvetica, sans-serif; font-weight: bold; margin-bottom: .5em; }
h3 { font: 1.5em Arial, Helvetica, sans-serif; font-weight: bold; margin-bottom: .5em; }
h3 a { color: #FFF; text-decoration: none; }
h3 a:hover { text-decoration: underline; }
h4 { font: 1.25em Arial, Helvetica, sans-serif;font-weight: bold; margin-bottom: .5em; }
ul, ol { font-size: 1em; margin: 0 0 1.25em 1.538em; }
li { margin-bottom: .25em; }
p { margin-bottom: 1em; }
p.meta { font: .923em Arial, Helvetica, sans-serif; color: #43768B; }
p.meta a { color: #43768B; }
p.meta a:hover { color: #973100; }
a { color: #315667; }
a:hover, #sidebar a:hover { color: #973100; }
dt { margin-left: 1em; }
dd { margin-left: 2em; margin-bottom: .25em; }
dl { margin: 0 0 1.25em 0; }
#navlinks LI { display: inline; margin: 0 8px; }
#footer { margin-top: 2em; width: 800px; text-align: center; }
</style>
</head>
<body>

<span style="position: absolute; left: 500px;">
<ul id=navlinks>
  <li> <a href="results.php">results</a>
  <li> <a href="#about">about</a>
  <li> <a href="http://code.google.com/p/loadtimer/">code</a>
  <li> <a href="http://groups.google.com/group/loadtimer/topics">contact</a>
</ul>
</span>
<h1 style="color: #353335; font-size: 2.5em;">
<a href="index.php" style="text-decoration: none; color: #353335;"><img src="loadtimer-42x42.gif" style="vertical-align: bottom; border: 0;"></a>
<a href="index.php" style="text-decoration: none; color: #353335;"> Loadtimer</a>
</h1>

<div style="padding: 8px;">

<table cellspacing=0 cellpadding=0 border=0 style="margin-bottom: 1em;">
<form onsubmit="testCache(); return false;">
<tr>
<td>
<nobr>
URLs:
<textarea id=urls rows=10 cols=50 style="vertical-align: top; font-family: monospace; font-size: 10pt;">
<?php
shuffle($gaUrls);
foreach ( $gaUrls as $url ) {
	echo "$url\n";
}
?>
</textarea>
</nobr>
</td>
<td>
<div id=dprint style="font-family: monospace; font-size: 10pt; margin-left: 2em; max-height: 200px; overflow: auto;">
</div>
</td>
</tr>

<?php
if ( $bLoop ) {
	echo <<<OUTPUT
<tr>
<td colspan=2>
<div style="margin-top: 0.5em;">
loop through the list of URLs <input type=text id=maxrepeat value=1 size=3 style="text-align: right;"> time(s)
</div>
</td>
</tr>
OUTPUT;
}
?>

<tr>
<td colspan=2>
<div style="margin-top: 0.5em;">
<nobr>
<input type=checkbox name=sendbeacon onchange="document.getElementById('beaconurl').disabled=(this.checked ? false : true)"> record load times
<span style="visibility: visible; margin-left: 2em;" id=beaconurldiv>
beacon URL: 
<input type=text id=beaconurl value="http://loadtimer.org/savetime.php" style="text-align: left;" size=30 disabled=true>
<!--
<code>?<strong>url=</strong>&lt;escaped URL&gt;&amp;<strong>loadtime=</strong>&lt;milliseconds&gt;</code>
-->
</span>
</nobr>
</div>

<input id=startbtn type=submit value="Start" style="margin-top: 1em;">
</td>
</tr>

</form>
</table>


<script>
// stub to avoid race conditions
function doFrameLoad() {
}
</script>
<iframe id=iframe1 src="about:blank" frameborder="1" style="margin-top: 3em; border: 1px solid;" width=98% height=500 onload="doFrameLoad()" onerror="doFrameLoad(true)"></iframe>

<h2 id=about style="margin-top: 2em;">about</h2>

<p>
Loadtimer is intended to be used to measure page load times on mobile devices.
Here's how to use it:
</p>
<ul>
  <li> Edit the list of URLs - one per line.
  <li> Check "record load times" to send the load time to the specified "beacon URL". 
The beacon URL defaults to Loadtimer's server where the data is aggregated in the <a href="results.php">results page</a>.
Change the beacon URL to send the beacons to your own server if desired.
  <li> Clear your cache.
  <li> Click "Start" to load the URLs one-by-one into the iframe.
  <li> The iframe load time is displayed.
</ul>

<p>
Notes about this test harness:
</p>
<ul>
  <li> Some websites have "framebusting" code that prevent them from loading in an iframe. 
Some sites simply won't load (e.g., <a href="http://www.google.com/">Google</a>, 
<a href="http://www.youtube.com/">YouTube</a>, and 
<a href="http://www.twitter.com/">Twitter</a>).
Other sites will break out of the iframe (e.g., <a href="http://www.nytimes.com/">NYTimes</a>).
  <li> It's possible that loading the URL in an iframe affects load times in some way. 
       Extra steps have been taken to mitigate the effect:
      <ul style="margin-bottom: 0">
        <li> In between pages <code>about:blank</code> is loaded in the iframe so that one page's <u><strong>un</strong></u>load time does not affect the next page's load time.
        <li> The order of the preset URLs is randomized so that any interdependencies are mitigated (e.g., URL 1 loads resources used by URL 2).
        <li> In some browsers a website's favicon is not downloaded when the website is loaded in an iframe. This could affect page load times, but is likely negligible.
      </ul>
  <li> There's a simple check to help remind you to clear the cache between runs.
</ul>


</div> <!-- end wrapper -->


<script>
var t_start;
var gbBeacon = true;
var gbStarted = false;
var giUrl = 0; // zero-based
var giRepeat = 0; // zero-based
var gMaxRepeat = 1;
var gTimeout = 20; // number of seconds afterwhich to move on
var gTimeoutID; // timer to kill
var gaUrls;
var gId;
var t_cacheStart;

function testCache() {
	toggleStart();

    t_cacheStart = Number(new Date());
    var elem = document.createElement("script");
    elem.type = "text/javascript";
    elem.onload = function() { elem.onreadystatechange = null; testCacheOnload(); };
	elem.onreadystatechange = function() { if ( elem.readyState && ("complete" == elem.readyState || "loaded" == elem.readyState) ) { elem.onload = null; testCacheOnload(); } };
    elem.src = "cachetest.php";
    document.getElementsByTagName("head")[0].appendChild(elem);
}


function testCacheOnload() {
    var delta = Number(new Date()) - t_cacheStart;
	var bContinue = true;
    if ( delta < 3000 ) {
		bContinue = confirm("It appears the cache wasn't cleared. Do you want to continue anyway?");
	}

	if ( bContinue ) {
        doLoadUrls();
    }
    else {
		toggleStart();
    }
}


function doLoadUrls() {
	gbStarted = true;
	giUrl = 0;
	giRepeat = 0;
	gMaxRepeat = parseInt(document.getElementById('maxrepeat').value);
	gId = Math.floor(Math.random() * 1000) + "_" + Number(new Date());
	parseUrls();
	clearDprint();
	doNextUrl();
}


function parseUrls() {
	var urls = document.getElementById("urls");
	gaUrls = urls.value.split("\n");
}


function doBlank() {
	var iframe1 = document.getElementById('iframe1');
	iframe1.src = "about:blank";
	setTimeout(doNextUrl, 200);
}


function doNextUrl() {
	if ( giUrl < gaUrls.length ) {
		var url = gaUrls[giUrl];
		var iframe1 = document.getElementById('iframe1');
		t_start = Number(new Date());
		iframe1.src = url;
		gTimeoutID = setTimeout(doBail, gTimeout*1000);
	}
}


function doBail() {
	doFrameLoad(true, true);
}


function doFrameLoad(bError, bTimeout) {
	var t_end = Number(new Date());
	var loadtime = t_end - t_start;

	if ( gTimeoutID ) {
		clearTimeout(gTimeoutID); // clear the "bail" timer
	}

	var iframe1 = document.getElementById("iframe1");
	if ( "about:blank" != iframe1.src ) {
		var url = gaUrls[giUrl];
		giUrl++;
		dprint(giUrl + ": " + ( bError ? "error, " : loadtime + " ms, " ) + url);
		if ( gbBeacon && ! bError && ! bTimeout ) {
			var img = new Image();
			img.src = document.getElementById("beaconurl").value + "?url=" + escape(url) + "&loadtime=" + loadtime + "&id=" + gId;
		}

		if ( giUrl < gaUrls.length && "" != gaUrls[giUrl] ) {
			setTimeout(doBlank, 2000);
		}
		else if ( (giUrl === gaUrls.length || (giUrl === gaUrls.length-1 && "" == gaUrls[giUrl])) && giRepeat < (gMaxRepeat-1) ) { // skip a blank URL at the end
			giRepeat++;
			giUrl = 0;
			setTimeout(doBlank, 2000);
		}
		else {
			gbStarted = false;
			// display alert dialog async so we don't block other onload behavior in the iframe's document
			setTimeout("toggleStart(); alert('Done');", 10);
		}
	}
}


function toggleStart() {
	var btn = document.getElementById('startbtn');
	if ( btn.disabled ) {
		btn.disabled = false;
		btn.value = "Start";
	}
	else {
		btn.disabled = true;
		btn.value = "running";
	}
}


function dprint(sText) {
	var elem = document.getElementById("dprint");
	elem.innerHTML += sText + "<br>";
}


function clearDprint(sText) {
	var elem = document.getElementById("dprint");
	elem.innerHTML = "";
}
</script>

</body>

</html>

