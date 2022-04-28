<?php
// cut
// (unfollow)

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

if (isset(v::$a["_tribe"])) {
	$T = vClean(v::$a["_tribe"]);
} else if (isset(v::$a["tribe"])) {
	$T = vClean(v::$a["tribe"]);
} else {
	$T = v::$v["uid"];
}

if (isset(v::$a["_link"])) {
	$L = vClean(v::$a["_link"]);
} else if (isset(v::$a["link"])) {
	$L = vClean(v::$a["link"]);
} else {
	$L = v::$v["tribe"];
}

vDBconnect();
// Unlink context from tribe (unfollow)
v::$r = vUnlink($L, $T);
vDBclose();
