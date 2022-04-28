<?php
// ticket

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
$Token = null;
if (isset(v::$a["_token"])) {
        $Token = vClean(v::$a["_token"]);
        unset(v::$a["_token"]);
} else if (isset(v::$a["token"])) {
        $Token = vClean(v::$a["token"]);
        unset(v::$a["token"]);
} else {
        $Token = "GA";
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

if (strlen(v::$v["sid"]) > 20) {
	// Logged in
	vDBConnect();
	$T = vQ1("select * from tribes where username='".$Token."';");
	if (isset($T["username"])) { // Username is token id
		// Token exists
		for($i = 0; $i < 10; $i++) {
			$ID[] = vGUID();
		}
		$Q = vQ("delete from scans where uid='".v::$v["uid"]."' or token='".$Token."';");
		$Q = vQ("insert into scans (token,uid) values ('".$Token."','".v::$v["uid"]."')");
		$Q = vQ("update scans set id=".vJSONdq($ID)." where token='".$Token."';");
		v::$r = $ID;
	} else {
		v::$r = vR(300, "Token doesn't exist: ".$Token);
	}
	vDBclose();
}
