<?php
// getattributes

require_once(v::$v["root"] . "v.php");

$Org = vArg("org", null);

if (strlen($Org) > 0) {
    $Org .= "_";
}

if (strlen($Org) > 0) {
    vDBConnect();

    $Q = vQ("select distinct attributes->>'attribute_name' as name,attributes->>'value' as value
    from  (
    select jsonb_array_elements(t.meta->'attributes') as attributes
    from   tribes t
    where  jsonb_typeof(t.meta->'attributes') = 'array' and
    type='" . $Org . "asset'
   ) x
    where x.attributes->>'value' is not null and x.attributes->>'attribute_name' is not null;");

    v::$r = $Q;

    vDBclose();
} else {
    v::$r = vR(300, "Org required.");
}
