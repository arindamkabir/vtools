<?php
// q

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

if (v::$v["uid"] == "j" || v::$v["uid"] == "jw" || v::$v["uid"] == "rangers") {
	vDBConnect();
	$Q = vQ("select * from lists where list='chalice420';");
	foreach($Q as $Row) {
		if ($Row["email"] == "jeremy@whitt.com") { continue; }
		$T = vGUID();

		// BACKLOG: Temporary hack for Chalice
		$QQ = vQ1("select * from tribes where phone='".vCleanPhone($Row["phone"])."';");
		if (vCleanPhone($QQ["phone"]) == vCleanPhone($Row["phone"])) {
			vLog($Row["phone"]." already exists");
			continue;
		}

		$A = array(
			"phonecountry" => "+1",
			"phone" => vCleanPhone($Row["phone"]),
			"email" => $Row["email"],
			"emailverified" => "no",
			"username" => strstr($Row["email"], "@", true),
			"context" => $T,
			"type" => "user",
			"name" => $Row["name"],
			"description" => "Chalice 420 member"
		);
		vPut($A, $T);
		vQ("update tribes set chiefs=".vJSONdq(array("chief" => $T))." where id='".$T."';");

	}
	vDBclose();
} else {
	v::$r = vR(500, v::$v["uid"]." tried to access admin query");
}
