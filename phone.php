<?php
// phone
//
// READ ONLY

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Phone = "";
if (isset(v::$a["phone"])) {
	$Phone = vClean(v::$a["phone"]);
}
$Country = null;
if (isset(v::$a["country"])) {
	$Country = "+".vCleanPhone(v::$a["country"]);
}

vDBconnect();
$Q = vQ1("select * from tribes where phonecountry='".$Country."' and phone='".$Phone."';");
if (count($Q) > 0) {
	v::$r = array("ref" => $Q["id"]);
} else {
	v::$r = vR(200, "Phone not used by any tribe: ".$Phone);
}
vDBclose();

