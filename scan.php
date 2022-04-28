<?php
// scan

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
        $Tribe = vClean(v::$a["tribe"]);
        unset(v::$a["tribe"]);
} else {
        $Tribe = v::$v["tribe"];
}
$ID = null;
if (isset(v::$a["_id"])) {
        $ID = vClean(v::$a["_id"]);
        unset(v::$a["_id"]);
} else if (isset(v::$a["id"])) {
        $ID = vClean(v::$a["id"]);
        unset(v::$a["id"]);
} else {
        $ID = "GA";
}
if (isset(v::$a["_time"])) {
        $Time = vClean(v::$a["_time"]);
        unset(v::$a["_time"]);
} else if (isset(v::$a["time"])) {
        $Time = vClean(v::$a["time"]);
        unset(v::$a["time"]);
} else {
	$Time = time();
}


vDBConnect();
// BACKLOG: scan wallet for asset (token id)

// BACKLOG: handle multiple event open/close array

// Get scan id data

// Ticket's event id must be greater than eventopen

// Ticket's event id must be less than eventclosed

// First Condition: Ticket must be linked to user's tribe

// Ticket must not be used

v::$r = "pass";
vDBclose();
