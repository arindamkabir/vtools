<?php
// search

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

if (isset(v::$a["org"])) {
}

if (
    isset(v::$a["org"]) &&
    isset(v::$a["term"])
) {
    $org = vClean(v::$a["org"]);
    $term = vClean(v::$a["term"]);
    vDBConnect();

    v::$r = vQ("select * from tribes_test2 where id in ( select id from lists where list='assets' and tribe='" . $org . "') and (ts @@ to_tsquery('english', '" . $term . "') or ts_meta @@ to_tsquery('english', '" . $term . "') );");

    vDBclose();
} else {
    v::$r = vR(300, "No org or search term specified.");
}
