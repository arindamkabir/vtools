<?php
// check.php
//
// Check for valid session
// Input: _sid
// Output: uid, tribe
//
// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

// Session checked at end of v.php

if (strlen(v::$v["sid"]) > 20) {
	v::$r = array("uid" => v::$v["uid"], "tribe" => v::$v["tribe"]);
} else {
	v::$r = array("tribe" => false);
}

if (v::$v["uid"] == "j" || v::$v["uid"] == "jw" || v::$v["uid"] == "jeremy") {
	v::$r["u"] = v::$u;
	v::$r["t"] = v::$t;
	v::$r["v"] = v::$v;
}
