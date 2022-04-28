<?php
// username

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Tribe = null;
if (isset(v::$a["_tribe"])) {
	$Tribe = vClean(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
	$Tribe = vClean(v::$a["tribe"]);
} else {
	$Tribe = v::$v["uid"];
}

if (isset(v::$a["_username"])) {
	$Username = vClean(v::$a["_username"]);
} else if (isset(v::$a["username"])) {
	$Username = vClean(v::$a["username"]);
}

vDBconnect();
if (isset(v::$a["_check"])) {
	vLog("Check if username exists");
	if (strlen(v::$v["sid"]) > 20 && strlen($Username) >= 8) {
		// Must be logged in to check
		$Q = vQ1("select id from tribes where username='".$Username."';");
		vLog($Q);
		v::$r = $Q;
	}
} else {
	vLog("Attempt to update username to ".$Username." in tribe ".$Tribe);
	if (vAllowed($Tribe)) {
		$Q = vQ("update tribes set username='".$Username."' where id='".$Tribe."';");
		v::$r = vR(200, "Update username to ".$Username." in tribe ".$Tribe." with response: ".vPR($Q));
	} else {
		v::$r = vR(500, v::$v["uid"]." attempted to update username to ".$Username." in tribe ".$Tribe);
	}
}
vDBclose();
