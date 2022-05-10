<?php
// event
/*
{
"_tribe":"event1",
"token":"event-id-00000001",
"name":"The Big Reveal x Ocavu",
"description":"Join the team in launching our next billion dollar venture. The Big Reveal, one night only, be there or be square, YOLO.",
"media":"https://www.ocavu.net/cdn/v/anon.png",
"mediacdn":"v",
"type":"event",
"eventhost":"ocavu",
"eventhostname":"Ocavu",
"eventphone":"9175979964",
"eventemail":"events@ocavu.net",
"eventurl":"https://www.ocavu.space",
"eventtime":"1650644857",
"eventopen":"1640644857",
"eventclosed":"1660644857",
"eventlocationcode":"OcavuHQ",
"eventlocationname":"Ocavu HQ Office, Lehi, UT",
"eventlocationdescription":"Younique Building, Thanksgiving Point, Lehi, UT",
"eventstatus":"pre-event"
}
 */

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Tribe = null;
if (isset(v::$a["_tribe"])) {
	$Tribe = vClean(v::$a["_tribe"]);
	unset(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
	$Tribe = vClean(v::$a["tribe"]);
	unset(v::$a["tribe"]);
}

vLog($Tribe);
vDBconnect();
v::$r = vPut(v::$a, $Tribe);
vDBclose();
