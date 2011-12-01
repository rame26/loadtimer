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

require_once("private.inc");

$gBeaconsTable = "beacons";

function getLink() {
	global $gMysqlServer, $gMysqlDb, $gMysqlUsername, $gMysqlPassword;

	$link = mysql_connect($gMysqlServer, $gMysqlUsername, $gMysqlPassword);
	if ( $link ) {
		mysql_select_db($gMysqlDb); // this sets the active DB for the most recent $link
	}
	
	return $link;
}


function doSimpleCommand($query) {
	$link = getLink();
	if ( $link ) {
		$result = mysql_query($query, $link);
		mysql_free_result($result);
		return true;
	}

	return NULL;
}


function doQuery($query) {
	$link = getLink();
	if ( $link ) {
		return mysql_query($query, $link);
	}

	return NULL;
}


function doSimpleQuery($query) {
	$value = NULL;

	$link = getLink();
	if ( $link ) {
		$result = mysql_query($query, $link);
		$row = mysql_fetch_assoc($result);
		if ( $row ) {
			$aKeys = array_keys($row);
			$value = $row[$aKeys[0]];
		}
		mysql_free_result($result);
	}

	return $value;
}


function tableExists($tablename) {
	return ( $tablename == doSimpleQuery("show tables like '$tablename';") );
}


function createTables() {
	global $gBeaconsTable;

	if ( ! tableExists($gBeaconsTable) ) {
		$command = "create table $gBeaconsTable (" .
			"createdate int(10) unsigned not null" .
			", sessid varchar (32)" .
			", url varchar (255) not null" .
			", loadtime int(10) unsigned" .
			", id varchar (32) not null" .
			", browser varchar (64) not null" .
			", useragent text not null" .
			", primary key (url, id)" .
			");";
		doSimpleCommand($command);
	}
}

?>
