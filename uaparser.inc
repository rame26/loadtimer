<?php

/*
Copyright 2009 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/


// Given a User-Agent HTTP header string, parse it to extract the browser "family", 
// (eg, "Safari", "Firefox", "IE"), and the major, minor, and tertiary version numbers.
//
// These values are returned within the input reference paramemeters.
//
// The return value is a user-readable browser string.
//
// Note: Some browsers have a quaternary number, but this code stops at tertiary version numbers.

function parseUserAgent($useragent, &$browser=NULL, &$v1=NULL, &$v2=NULL, &$v3=NULL) {

	// THESE ARE SPECIAL CASES THAT MUST GO BEFORE THE MAIN PARSING
	// must go before Opera
	if ( ereg("(Wii)", $useragent, $regs) ) {
		$browser = $regs[1];
	}
	// must go before Browser/v1.v2 - eg: Minefield/3.1a1pre
	else if ( ereg("(Namoroka|Shiretoko|Minefield)/([0-9]+)\.([0-9]+)\.?([ab]?[0-9]+[a-z]*)", $useragent, $regs) ||
			  ereg("(Namoroka|Shiretoko|Minefield)/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Firefox (" .  $regs[1] . ")";
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Namoroka|Shiretoko|Minefield)/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Firefox (" .  $regs[1] . ")";
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	else if ( ereg("(SeaMonkey|Fennec|Camino)/([0-9]+)\.([0-9]+)([ab][0-9]+[a-z]*)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	// eg: Fennec/0.9pre
	else if ( ereg("(Fennec)/([0-9]+)\.([0-9]+)(pre)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Navigator)/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Netscape";
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Navigator)/([0-9]+)\.([0-9]+[ab][0-9]+)", $useragent, $regs) ) {
		$browser = "Netscape";
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	else if ( ereg("(Netscape6)/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Netscape";
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(MyIBrow)/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "My Internet Browser";
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	else if ( ereg("Firefox.*Tablet browser ([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		// ex: Mozilla/5.0 (X11; U; Linux armv6l; de-DE; rv:1.9a6pre) Gecko/20080606 Firefox/3.0a1 Tablet browser 0.3.7 RX-34+RX-44+RX-48_DIABLO_4.2008.23-14
		$browser = "MicroB";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("Opera/9.80.*Version\/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		// Opera will stop at 9.80 and hide the real version in the Version string.
		// http://dev.opera.com/articles/view/opera-ua-string-changes/
		$browser = "Opera";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("Opera/9.80.*Version\/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		// Opera will stop at 9.80 and hide the real version in the Version string.
		// http://dev.opera.com/articles/view/opera-ua-string-changes/
		$browser = "Opera";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)\.([0-9]+) \(Swiftfox\)", $useragent, $regs) ) {
		$browser = "Swiftfox";
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)\.([0-9]+pre) \(Swiftfox\)", $useragent, $regs) ) {
		$browser = "Swiftfox";
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)([ab][0-9]+[a-z]*) \(Swiftfox\)", $useragent, $regs) ) {
		$browser = "Swiftfox";
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+) \(Swiftfox\)", $useragent, $regs) ) {
		$browser = "Swiftfox";
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	else if ( ereg("Android ([0-9]+)\.([0-9]+).*SGH-T849", $useragent, $regs) ) {
		$browser = "Galaxy 7.0";
		$v1 = "7";
		$v2 = "0";
	}
	else if ( ereg("Android ([0-9]+)\.([0-9]+).*GT-P7510", $useragent, $regs) ) {
		$browser = "Galaxy 10.1";
		$v1 = "10";
		$v2 = "1";
	}
	// END SPECIAL CASES THAT MUST GO BEFORE THE MAIN PARSING


	// MAIN PARSING CONDITIONS - this catches > 50% of all browsers
	// Browser/v1.v2.v3
	else if ( ereg("(Jasmine|ANTGalio|Midori|Fresco|Lobo|Maxthon|Lynx|OmniWeb|Dillo|Camino|Demeter|Fluid|Fennec|Shiira|Sunrise|Chrome|Flock|Netscape|Lunascape|Epiphany|WebPilot|Vodafone|NetFront|Konqueror|SeaMonkey|Kazehakase|Vienna|Iceape|Iceweasel|IceWeasel|Iron|K-Meleon|Sleipnir|Galeon|GranParadiso|Opera Mini|iCab|NetNewsWire|Iron)/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	// Browser/v1.v2
	else if ( ereg("(Bolt|Jasmine|Maxthon|Lynx|Arora|IBrowse|Dillo|Camino|Shiira|Fennec|Phoenix|Chrome|Flock|Netscape|Lunascape|Epiphany|WebPilot|Opera|Vodafone|NetFront|Konqueror|SeaMonkey|Kazehakase|Vienna|Iceape|Iceweasel|IceWeasel|Iron|K-Meleon|Sleipnir|Galeon|GranParadiso|Opera Mini|iCab|NetNewsWire|Iron)/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	// Browser v1.v2.v3 (space instead of slash)
	else if ( ereg("(iRider|Crazy Browser|SkipStone|iCab|Lunascape|Sleipnir) ([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	// Browser v1.v2 (space instead of slash)
	else if ( ereg("(IEMobile|iCab|Lunascape|Opera|Android) ([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		if ( "IEMobile" == $browser ) {
			$browser = "IE Mobile";
		}
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)([ab][0-9]+[a-z]*)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)(pre)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("(Firefox)/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	// END MAIN PARSING CONDITIONS


	// SPECIAL CASES
	else if ( ereg("Obigo[^0-9]*([0-9]+)", $useragent, $regs) || ereg("OBIGO[^0-9]*([0-9]+)", $useragent, $regs) ) {
		$browser = "Obigo";
		$v1 = $regs[1];
	}
	else if ( ereg("(MAXTHON|Maxthon) ([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Maxthon";
		$v1 = $regs[2];
		$v2 = $regs[3];
	}
	else if ( ereg("(Maxthon|MyIE2|Uzbl)", $useragent, $regs) ) {
		// no version numbers
		$browser = $regs[1];
		$v1 = 0;
	}
	else if ( ereg("PLAYSTATION ([0-9]+)", $useragent, $regs) ) {
		$browser = "PlayStation";
		$v1 = $regs[1];
	}
	else if ( ereg("(BrowseX) \(([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = $regs[1];
		$v1 = $regs[2];
		$v2 = $regs[3];
		$v3 = $regs[4];
	}
	else if ( ereg("Opera/([0-9]+)\.([0-9]+).*Opera Mobi", $useragent, $regs) ) {
		$browser = "Opera Mobile";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("POLARIS/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Polaris";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("BonEcho/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Bon Echo";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("iPhone OS ([0-9]+)_([0-9]+)_([0-9]+)", $useragent, $regs) ) {
		$browser = "iPhone";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("iPhone OS ([0-9]+)_([0-9]+)", $useragent, $regs) ) {
		$browser = "iPhone";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("iPad.*CPU OS ([0-9]+)_([0-9]+)_([0-9]+).*Mobile/([0-9]*)", $useragent, $regs) ) {
		$browser = "iPad " . ( "9A405" === $regs[4] ? "1" : "2" );
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("iPad.*CPU OS ([0-9]+)_([0-9]+)_([0-9]+)", $useragent, $regs) ) {
		$browser = "iPad";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("iPad.*CPU OS ([0-9]+)_([0-9]+).*Mobile/([0-9]*)", $useragent, $regs) ) {
		$browser = "iPad " . ( "9A405" === $regs[4] ? "1" : "2" );
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("iPad.*CPU OS ([0-9]+)_([0-9]+)", $useragent, $regs) ) {
		$browser = "iPad";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("Avant", $useragent, $regs) ) {
		$browser = "Avant";
		$v1 = "1";
	}
	else if ( ereg("Nokia[EN]([0-9]+)", $useragent, $regs) ) {
		$browser = "Nokia";
		$v1 = $regs[1];  // we can't store "N95" as the version - it must be numerical
	}
	else if ( ereg("Nokia([0-9]+)", $useragent, $regs) ) {
		$browser = "Nokia";
		$v1 = $regs[1];
	}
	else if ( ereg("Blackberry([0-9]+)", $useragent, $regs) || ereg("BlackBerry([0-9]+)", $useragent, $regs) ) {
		$browser = "Blackberry";
		$v1 = $regs[1];
	}
	else if ( ereg("OmniWeb/v([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "OmniWeb";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("Blazer/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Palm Blazer";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("Pre/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Palm Pre";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("Links \(([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Links";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("QtWeb Internet Browser/([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "QtWeb";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("Silk/([0-9]+)\.([0-9]+)\.([0-9]+).*Silk-Accelerated=([a-z]*)", $useragent, $regs) ) {
		$sAccelerated = ( "true" === $regs[4] ? "(accel on)" : "(accel off)" );
		$browser = "Silk $sAccelerated";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("Silk/([0-9]+)\.([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "Silk ???";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("Version/([0-9]+)\.([0-9]+)\.([0-9]+).*Safari/", $useragent, $regs) ) {
		$browser = "Safari";
		$v1 = $regs[1];
		$v2 = $regs[2];
		$v3 = $regs[3];
	}
	else if ( ereg("Version/([0-9]+)\.([0-9]+).*Safari/", $useragent, $regs) ) {
		$browser = "Safari";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("OLPC/Update([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "OLPC";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("OLPC/Update\.([0-9]+)", $useragent, $regs) ) {
		$browser = "OLPC";
		$v1 = 0;
		$v2 = $regs[1];
	}
	else if ( ereg("MSIE ([0-9]+)\.([0-9]+).*chromeframe", $useragent, $regs) ) {
		$browser = "Chrome Frame IE";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}
	else if ( ereg("MSIE ([0-9]+)\.([0-9]+)", $useragent, $regs) ) {
		$browser = "IE";
		$v1 = $regs[1];
		$v2 = $regs[2];
	}

	return prettyBrowser($browser, $v1, $v2, $v3);
}


function prettyBrowser($browser, $v1, $v2, $v3) {
	$prettyBrowser = $browser;

	if ( NULL !== $v1 ) {
		$prettyBrowser .= " $v1";
		if ( NULL !== $v2 ) {
			$prettyBrowser .= ".$v2";
			if ( NULL !== $v3 ) {
				$prettyBrowser .= ( ereg("^[0-9]", $v3) ? ".$v3" : "$v3" );
			}
		}
	}

	return $prettyBrowser;
}

?>
