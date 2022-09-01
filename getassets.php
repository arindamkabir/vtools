<?php
// getassets

// gets assets where the current user is a chief or where the tribe id ilike contract

require_once(v::$v["root"] . "v.php");

$Tribe = vArg("tribe", null);
$Org = vArg("org", null);
$Type = vArg("type", "asset");
$Limit = vArg("limit", "100", "numeric");
$Offset = vArg("offset", "0", "numeric");
//BACKLOG: Needs to remove code injections
$Traits = v::$a["traits"];
$Sort = v::$a["sort"];
$SearchTerm = v::$a["search_term"];
$NotListed = vArg("not_listed");

$Traits = (strlen($Traits) > 0) ? explode(",", $Traits) : [];

$TraitsQuery = "";
if (count($Traits) > 0) {

    $TraitsQuery .= " and(";
    foreach ($Traits as $index => $Trait) {
        if ($index !== 0) {
            $TraitsQuery .= "or ";
        }
        $TraitsQuery .= "tribes.meta->>'attributes' ILIKE '%" . $Trait . "%' ";
    }

    $TraitsQuery .= ")";
}

$OrderbyQuery = "";
if (strlen($Sort) > 0) {
    if ($Sort === "price_desc") {
        $OrderbyQuery = " order by price_order desc";
    } elseif ($Sort === "price_asc") {
        $OrderbyQuery = " order by price_order asc";
    } elseif ($Sort === "likes_desc") {
        $OrderbyQuery = " order by linked_count desc";
    } else {
        $OrderbyQuery = " order by time desc";
    }
}

$SearchQuery = "";
if (strlen($SearchTerm) > 0) {
    $SearchQuery = " and (tribes.meta->>'name' ILIKE '%" . $SearchTerm . "%' or tribes.name ILIKE '%" . $SearchTerm . "%') ";
}

$TypeQuery = "";
if ($Type == 'asset') {
    $TypeQuery = " (type='" . $Org . '_' . $Type . "'or type='" . $Org . "_parent_pack' or type='" . $Org . "_pack') ";
} else {
    $TypeQuery = " type='" . $Org . '_' . $Type . "' ";
}

$ListedQuery = "";
if (strlen($NotListed) > 0) {
    $ListedQuery = " tribes.meta->>'sales_status' = 'true' and ";
}

if (strlen($Org) > 0) {
    vDBConnect();
    //BACKLOG: Separate this query by types. (Add default type)
    if (strlen($Tribe) > 0) {
        $Q = vQ("select * from tribes where " . $TypeQuery . " and meta->>'sales_status' is not null and chiefs->>'" . $Tribe . "'='chief' limit " . $Limit . " offset " . $Offset . ";");
    } else {
        $Q = vQ("select count(links) as linked_count, tribes.*, (tribes.meta->>'sales_price')::numeric as price_order from tribes left join links on links.link = tribes.id where " . $ListedQuery . " tribes.meta->>'pack_pool' is null and (tribes.meta->>'sales_price' is null or (tribes.meta->>'sales_price' is not null and tribes.meta->>'sales_price' != '')) and " . $TypeQuery . " " . $SearchQuery . " " . $TraitsQuery . " GROUP BY tribes.id " . $OrderbyQuery . " limit " . $Limit . " offset " . $Offset . ";");
    }
    if (count($Q) === 1 && empty($Q[0])) {
        v::$r = [];
    } else {
        v::$r = $Q;
    }

    vDBclose();
} else {
    v::$r = vR(300, "Org required.");
}
