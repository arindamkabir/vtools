<?php
// chop
//
// Delete item from list
//
// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$List = null;
if (isset(v::$a["_list"])) {
        $List = vClean(v::$a["_list"]);
        unset(v::$a["_list"]);
}
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
if (vAllowed($Tribe)) {
	if (strlen($ID) > 0) {
		$Q = vQ("delete from lists where tribe='".$Tribe."' and list='".$List."' and id='".$ID."';");
		v::$r["sid"] = $ID;
		v::$r["_"] = array("x" => 200, "r" => "Chop: Item".v::$a["_id"]." deleted from list: ".$List);
vLog("Deleted ".$ID." from ".$List." in ".$Tribe);
	} else {
		v::$r["_"] = array("x" => 400, "r" => "Chop Failed: No ID specified");
	}
} else {
	v::$r["_"] = array("x" => 500, "r" => "WARNING: Chop failed. uid not authorized to chop list item.");
}
vDBclose();
