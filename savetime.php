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

// This is a beacon - don't return any content.
header("HTTP/1.1 204 No Content");

require_once("db.inc");
require_once("uaparser.inc");

createTables();

if ( array_key_exists("action", $_GET) && "done" === $_GET["action"] ) {
	// could do some agg stuff here
	exit(0);
}

// only for my testing 
// $gLFID = ( array_key_exists("REMOTE_ADDR", $_SERVER) ? crypt($_SERVER["REMOTE_ADDR"], "DC") : "" );
$gLFID = "public";
$gUrl = ( array_key_exists("url", $_GET) ? $_GET["url"] : "" );
$gTime = ( array_key_exists("loadtime", $_GET) ? $_GET["loadtime"] : 0 );
$gId = ( array_key_exists("id", $_GET) ? $_GET["id"] : 0 );
$gUA = $_SERVER['HTTP_USER_AGENT'];
$gBrowser = parseUserAgent($gUA);

if ( !$gUrl || !$gTime || !$gId || !$gBrowser ) {
	exit(1);
}


// save the results
$now = time();
$command = "insert into $gBeaconsTable set createdate=$now, sessid='$gLFID', url='$gUrl', loadtime=$gTime, id='$gId', browser='$gBrowser', useragent='$gUA';";
doSimpleCommand($command);

?>
