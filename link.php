<?php
// link
// follow

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
	// Default to user
	$T = v::$v["uid"];
}

if (isset(v::$a["_link"])) {
	$C = v::$a["_link"];
} else if (isset(v::$a["link"])) {
	$C = v::$a["link"];
} else {
	// Default to tribe context
	$C = v::$v["tribe"];
}

vDBconnect();
v::$r = vLink($T, $C);
vDBclose();
