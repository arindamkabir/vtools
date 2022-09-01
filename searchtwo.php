<?php
// search

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Org = vArg("org");


if (isset(v::$a["term"]) && strlen($Org) > 0) {
    $Org .= "_";
    $Term = preg_replace("/[^a-z0-9-_ ]/", "", strtolower(substr(trim(v::$a["term"]), 0, 144)));
    $type = vClean(v::$a["type"]);
    $vType = "";
    if (strlen($type) > 0) {
        $vType = " type='" . $Org . $type . "' and ";
    } else {
        $vType = " (type='" . $Org . "asset' or type='user') and ";
    }

    vDBConnect();
    // BACKLOG: Omit tribes with type ~ _question
    $Q = vQ("select id, token, name, description, media, mediacdn, meta, type from tribes where " . $vType . " ( lower(name) ~ '" . $Term . "' or lower(description) ~ '" . $Term . "' or lower(token) ~ '" . $Term . "' or lower(meta->>'name') ~ '" . $Term . "' or (meta->>'revealed'='true' and (lower(meta->'reveal_json'->>'name') ~ '" . $Term . "')) or ts_meta @@ plainto_tsquery('English', '" . $Term . "') ) limit 500 ;");

    if (count($Q) === 1 && empty($Q[0])) {
        v::$r = vR(200, "No results found.");
    } else {
        v::$r = $Q;
    }

    vDBclose();
} else {
    v::$r = vR(300, "No search term or org specified.");
}