<?php
// assets.php
//
// Mode 1) List of assets by input tribe
// Mode 2) Return asset if id also provided
// Mode 3) If additional input data is provided, update asset

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"]."v.php");

$ID = null;
if (isset(v::$a["_id"])) {
        $ID = vClean(v::$a["_id"]);
        unset(v::$a["_id"]);
}
$Tribe = null;
if (isset(v::$a["_tribe"])) {
        $Tribe = vClean(v::$a["_tribe"]);
        unset(v::$a["_tribe"]);
}

vDBconnect();
$DQ = '$v$'; // Postgres dollar quote
if (strlen($ID) > 0) { 
	// Return one asset
	$Q = vQ1("select id,meta->>'minted' as minted,name,description,media,media as filename,mediacdn,meta->>'access' as access,meta->>'linkedurl' as linkedurl,meta->>'mintprice' as mintprice,meta->>'mintunlockassets' as mintunlockassets,meta->>'mintlockedcontenturl' as mintlockedcontenturl,time from tribes where id='".$ID."';");
	v::$r = $Q;
} else if (strlen($Tribe) > 0) { // Return all assets for tribe
	$Q = vQ("select id,meta->>'minted' as minted,name,description,media,media as filename,mediacdn,meta->>'access' as access,meta->>'linkedurl' as linkedurl,meta->>'mintprice' as mintprice,meta->>'mintunlockassets' as mintunlockassets,meta->>'mintlockedcontenturl' as mintlockedcontenturl,time from tribes where id in ( select id from lists where list='assets' and tribe='".$Tribe."' order by time desc ) order by time desc;");
	if (isset($Q["id"])) {
		v::$r[$Q["id"]] = $Q;
	} else {
		foreach($Q as $Asset) {
			v::$r[$Asset["id"]] = $Asset;
		}
	}
}
vDBclose();
