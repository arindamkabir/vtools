<?php
// username
//
// Note: for user tribes, username is the unique token field

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

$Username = null;
if (isset(v::$a["_username"])) {
	$Username = vClean(v::$a["_username"]);
} else if (isset(v::$a["username"])) {
	$Username = vClean(v::$a["username"]);
} else if (isset(v::$a["_token"])) {
	$Username = vClean(v::$a["_token"]);
} else if (isset(v::$a["token"])) {
	$Username = vClean(v::$a["token"]);
}

if (strlen($Username) > 0) {
	vDBconnect();
	if (isset(v::$a["_check"])) {
		vLog("Check if username exists");
		if (strlen(v::$v["sid"]) > 20 && strlen($Username) >= 8) {
			// Must be logged in to check
			$Q = vQ1("select id from tribes where lower(token)=lower('".$Username."');");
			vLog($Q);
			v::$r = $Q;
		}
	} else {
		vLog("Attempt to update token to ".$Username." in tribe ".$Tribe);
		if (vAllowed($Tribe)) {
			$Q = vQ("update tribes set token='".$Username."' where id='".$Tribe."';");
			v::$r = vR(200, "Update token to ".$Username." in tribe ".$Tribe." with response: ".vPR($Q));
			// BACKLOG: Need to clean up or reconcile all data references to previous username
	
		} else {
			v::$r = vR(500, v::$v["uid"]." attempted to update username to ".$Username." in tribe ".$Tribe);
		}
	}
	vDBclose();
} else {
	v::$r = vR(300, "Attempt to update username but null provided in tribe ".$Tribe);
}
