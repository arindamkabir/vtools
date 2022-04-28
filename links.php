<?php
// links
// Returns following and followers JSON arrays

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

if (isset(v::$a["_tribe"])) {
	$T = vClean(v::$a["_tribe"]);
	unset(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
	$T = vClean(v::$a["tribe"]);
	unset(v::$a["tribe"]);
} else {
	$T = v::$v["tribe"];
}

vDBconnect();
v::$r = vLinks($T);
vDBclose();
