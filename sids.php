<?php
// sids
//
// Get list of sids for tribe
// Or delete session if Nix provided
//
// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
}

vDBConnect();
if (vAllowed($Tribe)) {
	v::$r = vSIDs($Tribe);
} else {
	v::$r = vR(500, v::$v["uid"]." tried to get list of sessions for: ".$Tribe);
}
vDBclose();
