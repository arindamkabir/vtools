<?php
// chop
//
// Delete sid from sessions
//
// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
}
$ID = null;
if (isset(v::$a["_id"])) {
        $ID = vCleanFilename(v::$a["_id"]);
        unset(v::$a["_id"]);
}

vDBconnect();
if (strlen($ID) > 0) {
	$Q = vQ("delete from sessions where sid='".$ID."';");
	v::$r["sid"] = $ID;
	v::$r["_"] = array("x" => 200, "r" => "Destroy session: ".v::$a["_id"]);
vLog("Destroy session ".$ID);
} else {
	v::$r["_"] = array("x" => 400, "r" => "No session specified to destroy");
}
vDBclose();
