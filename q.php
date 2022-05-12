<?php
// q

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

if (v::$v["uid"] == "j" || v::$v["uid"] == "jw" || v::$v["uid"] == "rangers") {
	vDBConnect();
	v::$r = vTime(time() - 3600);
	vDBclose();
} else {
	v::$r = vR(500, v::$v["uid"]." tried to access admin query");
}
