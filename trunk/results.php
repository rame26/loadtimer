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

require_once("db.inc");

$gData = "blog";
$gRange = "createdate >= 1322599749 and createdate <= 1322606100";
if ( array_key_exists("data", $_GET) && "all" === $_GET["data"] ) {
	$gData = "all";
	$gRange = "createdate > 0";
}


$ghUrls = array(
				// no load "http://www.google.com/search?q=flowers",
				// no load "http://www.facebook.com/",
				"http://www.yahoo.com/" => "Yahoo",
				// no load "http://www.youtube.com/",
				"http://www.amazon.com/" => "Amazon",
				"http://en.wikipedia.org/wiki/Flowers" => "Wikipedia",
				// no load "http://www.twitter.com/",
				"http://www.craigslist.com/" => "Craigslist",
				"http://www.ebay.com/" => "eBay",
				"http://www.linkedin.com/" => "LinkedIn",
				"http://www.bing.com/search?q=flowers" => "Bing",
				"http://www.msn.com/" => "MSN",
				// framebuster "http://www.nytimes.com/",
				"http://www.engadget.com/" => "Engadget",
				"http://www.cnn.com/" => "CNN",
				"http://www.reddit.com/" => "Reddit"
				);

$ghExceptions = array();
$ghExceptions["Galaxy 7.0 SGH-T849"] = array();
$ghExceptions["Galaxy 7.0 SGH-T849"]["http://www.amazon.com/"] = 19;
$ghExceptions["Galaxy 7.0 SGH-T849"]["http://en.wikipedia.org/wiki/Flowers"] = 32;
$ghExceptions["Galaxy 7.0 SGH-T849"]["http://www.ebay.com/"] = 22;
$ghExceptions["Galaxy 7.0 SGH-T849"]["http://www.linkedin.com/"] = 2;
$ghExceptions["Galaxy 7.0 SGH-T849"]["http://www.bing.com/search?q=flowers"] = 7;
$ghExceptions["Galaxy 7.0 SGH-T849"]["http://www.cnn.com/"] = 20;
$ghExceptions["Galaxy 10.1 GT-P7510"] = array();
$ghExceptions["Galaxy 10.1 GT-P7510"]["http://www.linkedin.com/"] = 2;
$ghExceptions["Galaxy 10.1 GT-P7510"]["http://www.yahoo.com/"] = 46;
$ghExceptions["Silk (accel off) 1.1.0"] = array();
$ghExceptions["Silk (accel off) 1.1.0"]["http://www.yahoo.com/"] = 58;
$ghExceptions["Silk (accel off) 1.1.0"]["http://www.ebay.com/"] = 18;
$ghExceptions["Silk (accel on) 1.1.0"] = array();
$ghExceptions["Silk (accel on) 1.1.0"]["http://www.yahoo.com/"] = 58;
$ghExceptions["Silk (accel on) 1.1.0"]["http://www.ebay.com/"] = 18;
$ghExceptions["iPad 2 5.0"] = array();
$ghExceptions["iPad 2 5.0"]["http://www.yahoo.com/"] = 23;
$ghExceptions["iPad 1 5.0.1"] = array();
$ghExceptions["iPad 1 5.0.1"]["http://www.yahoo.com/"] = 60;

$ghResources = array();
$ghResources["http://www.yahoo.com/"] = 67;
$ghResources["http://www.amazon.com/"] = 43;
$ghResources["http://en.wikipedia.org/wiki/Flowers"] = 50;
$ghResources["http://www.craigslist.com/"] = 4;
$ghResources["http://www.ebay.com/"] = 64;
$ghResources["http://www.linkedin.com/"] = 11;
$ghResources["http://www.bing.com/search?q=flowers"] = 13;
$ghResources["http://www.msn.com/"] = 47;
$ghResources["http://www.engadget.com/"] = 215;
$ghResources["http://www.cnn.com/"] = 124;
$ghResources["http://www.reddit.com/"] = 21;



// list of browsers
$gaBrowsers = array();

if ( array_key_exists("comparesel", $_GET) ) {
	$aKeys = array_keys($_GET);
	foreach($aKeys as $key) {
		if ( 0 === strpos($key, "b_") ) {
			$gaBrowsers[] = $_GET[$key];
		}
	}
}

if ( 0 === count($gaBrowsers) ) {
	$query = "select browser from $gBeaconsTable where $gRange group by browser order by browser;";
	$result = doQuery($query);
	while ($row = mysql_fetch_assoc($result)) {
		$browser = $row['browser'];
		$gaBrowsers[] = $browser;
	}
	mysql_free_result($result);
}


// table column headers
$sTH = "<tr> <th>browser</th> ";
$aUrls = array_keys($ghUrls);
foreach($aUrls as $url) {
	$sTH .= "<th class=sortnum><a href='$url' target='_blank'>" . $ghUrls[$url] . "</a></th> ";
}
$sTH .= "<th class=sortnum>&#35; data</th>" .  
	"</tr>\n";


// track min & max (initialize values for each URL)
$ghMin = array();
$ghMax = array();
foreach($aUrls as $url) {
	$ghMin[$url] = 9999999;
	$ghMax[$url] = 0;
}

$ghMedians = array();
foreach($gaBrowsers as $browser) {
	$ghMedians[$browser] = array();
	foreach($aUrls as $url) {
		$num = doSimpleQuery("select count(loadtime) as num from $gBeaconsTable where browser='$browser' and url='$url' and $gRange;");
		if ( $num > 0 ) {
			$median = doSimpleQuery("select loadtime as median from $gBeaconsTable where browser='$browser' and url='$url' and $gRange order by loadtime asc limit " .
									floor(($num-1)/2) . ",1;");
			$ghMedians[$browser][$url] = $median;
			if ( array_key_exists($browser, $ghExceptions) && array_key_exists($url, $ghExceptions[$browser]) ) {
				// don't let exceptions take the min
			}
			else if ( $median < $ghMin[$url] ) {
				$ghMin[$url] = $median;
			}
			if ( $median > $ghMax[$url] ) {
				$ghMax[$url] = $median;
			}
		}
	}
	$ghMedians[$browser]['num'] = $num;   // bogus if URLs have different # of beacons
}


$sRows = "";
$iBrowser = 0;
foreach($gaBrowsers as $browser) {
	$iBrowser++;
	$sRows .= "<tr> <td class=browser><input type=checkbox name='b_$iBrowser' value='$browser' checked> $browser</td> ";
	foreach($aUrls as $url) {
		$median = $ghMedians[$browser][$url];
		$class = "sortnum";
		if ( intval($median) < intval($ghMin[$url]) ) {
			$class .= " minexc";
		}
		else if ( intval($median) == intval($ghMin[$url]) ) {
			$class .= " min";
		}
		else if ( intval($median) == intval($ghMax[$url]) ) {
			$class .= " max";
		}
		else {
			$class .= " num";
		}
		$sRows .= "<td class='$class" . ( array_key_exists($browser, $ghExceptions) && array_key_exists($url, $ghExceptions[$browser]) ? " exc" : "" ) . "'>" . commaize($median) . "<br>(" .
			( array_key_exists($browser, $ghExceptions) && array_key_exists($url, $ghExceptions[$browser]) ? $ghExceptions[$browser][$url] : $ghResources[$url] ) . ")</td> ";
	}
	$num = $ghMedians[$browser]['num'];
	$sRows .= "<td class='sortnum'>$num</td> " .
		"</tr>\n";
}

// add commas to a big number
function commaize($num) {
	$sNum = "$num";
	$len = strlen($sNum);

	if ( $len <= 3 ) {
		return $sNum;
	}

	return commaize(substr($sNum, 0, $len-3)) . "," . substr($sNum, $len-3);
}
?>
<!doctype html>
<html>
<head>
<title>Loadtimer Results</title>
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
  <li> <a href="index.php">harness</a>
  <li> <a href="#about">about</a>
  <li> <a href="http://code.google.com/p/loadtimer/">code</a>
  <li> <a href="http://groups.google.com/group/loadtimer/topics">contact</a>
</ul>
</span>
<h1 style="color: #353335; font-size: 2.5em;">
<a href="index.php" style="text-decoration: none; color: #353335;"><img src="loadtimer-42x42.gif" style="vertical-align: bottom; border: 0;"></a>
<a href="results.php" style="text-decoration: none; color: #353335;"> Loadtimer Results</a>
</h1>

<div style="padding: 8px;">

<style>
TH { padding: 4px 8px 4px 8px; border-bottom: 2px solid #222; }
TD { text-align: right; padding: 4px 10px 4px 4px; border-bottom: 1px solid #777; }
TD.browser { text-align: left; }
.tablelabel { font-weight: bold; padding: 1em 0 0.5em 0; margin-left: 10em; }
TD.sortnum { font-family: monospace; font-size: 1.1em; }
.num { color: #333; }
.min { color: #292; font-weight: bold; }
.max { color: #F00; font-weight: bold; }
.minexc { color: #292; }
.exc { background: #E8E8E8; }
</style>

<p>
These are the median page load times for popular websites.
The times are in milliseconds.
The number of resources is shown in parentheses.
</p>
<ul>
  <li> Devices that receive mobile-specific versions of the site have a <span class=exc style="padding: 0 8px;">gray background</span>.
  <li> The slowest time is shown in <span class=max>red</span>.
  <li> The fastest time is shown in <span class=min>green</span> for devices that received the desktop version.
	<li> Devices that received a mobile version of the site and were faster than the desktop version are shown in <span class=minexc><span class=exc style="padding: 0 8px;">non-bold green (with a gray background)</span></span>.
</ul>

<form>
<div class=tablelabel>Table 1: Median page load time (milliseconds)</div>
<table class='tablesort' cellpadding=0 cellspacing=0 border=0 style="margin-bottom: 1em;"> 
<?php 
echo $sTH;
echo $sRows;
?>
</table>
<input type=submit name=comparesel value="Compare Selected">
<input type=button value="Reset" onclick="document.location='results.php'">
<div style="margin-top: 0.5em;">
	<input type=radio name=data value="blog" onchange="document.location='results.php?data=blog'"<?php echo ( "blog" === $gData ? " checked" : "" ) ?>> blog post's data
<br>
<input type=radio name=data value="all" onchange="document.location='results.php?data=all'"<?php echo ( "blog" === $gData ? "" : " checked" ) ?>> all data (including public)
</div>
</form>

<script type="text/javascript">
var tsjs = document.createElement('script');
tsjs.src = "tablesort.js";
tsjs.onload = function() { TS.init(); };
tsjs.onreadystatechange = function() { if ( tsjs.readyState == 'complete' || tsjs.readyState == 'loaded' ) { TS.init(); } };
document.getElementsByTagName('head')[0].appendChild(tsjs);
</script>

</div> <!-- wrapper -->

</body>
</html>
