<?php
// txns

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Tribe = vArg("tribe", "");
$Type = vArg("type", "gem_asset_purchase");
$Org = vArg("org", "", "domain");
$Limit = vArg("limit", "100", "numeric");
$Offset = vArg("offset", "0", "numeric");

$Meta = "";
if (isset(v::$a["meta"])) {
    $Meta = ",meta";
}

if (strlen($Tribe) > 0 || strlen($Org) > 0) {
    vDBConnect();
    if (strlen($Tribe) > 0) {
        $Q = vQ("SELECT tribe, context, time, meta -> 'get_nft_response' -> 'data' ->> 'name' as name, meta -> 'get_nft_response' -> 'data' ->> 'image_url' as image_url, meta -> 'meta' ->> 'amount' AS price,type,org,instance" . $Meta . " FROM txn WHERE type ='" . $Type . "' and context = '" . $Tribe . "' order by time desc limit " . $Limit . " offset " . $Offset . ";");
    } else {
        $Q = vQ("SELECT tribe, context, time, meta -> 'get_nft_response' -> 'data' ->> 'name' as name, meta -> 'get_nft_response' -> 'data' ->> 'image_url' as image_url, meta -> 'meta' ->> 'amount' AS price,type,org,instance" . $Meta . " FROM txn WHERE type ='" . $Type . "' and org = '" . $Org . "' order by time desc limit " . $Limit . " offset " . $Offset . ";");
    }

    if (count($Q) === 1 && empty($Q[0])) {
        v::$r = [];
    } else {
        v::$r = $Q;
    }

    vDBclose();
} else {
    v::$r = vR(300, "Tribe or Org required.");
}
