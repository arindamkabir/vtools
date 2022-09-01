<?php
// activitylog
// returns the activity history for a user tribe or an asset tribe

// Kernel is always loaded by dispatch

// Also need Visyfy core functions
require_once(v::$v["root"] . "v.php");

$Tribe = vArg("tribe", v::$v["tribe"]);
$Asset = vArg("asset", null);
$Org = vArg("org", "", "domain");

if (
    strlen($Org) > 0
) {
    vDBConnect();

    if (strlen($Asset) > 0) {
        // Activity history for asset
        $Q = vQ("(select
    meta->>'id' as id,
    meta->>'asset_name' as asset_name,
    meta->>'image_url' as image_url,
    meta->>'sales_price' as sales_price,
    'listed' as activity_type,
    time as time
    from txn where type='put_tribe' and context='" . $Asset . "' and tribe!=context and org='" . $Org . "')
    UNION
    (select
    meta->'put_tribe_response'->>'id' as id,
    meta->'put_tribe_response'->>'asset_name' as asset_name,
    meta->'put_tribe_response'->>'image_url' as image_url,
    meta->'put_tribe_response'->>'sales_price' as sales_price,
    'purchased' as activity_type,
    time as time
    from txn where type='gem_asset_purchase' and context='" . $Asset . "' and org='" . $Org . "')
    order by time desc;");
    } else {
        // Activity history for user
        $Q = vQ("(select
    meta->>'id' as id,
    meta->>'asset_name' as asset_name,
    meta->>'image_url' as image_url,
    meta->>'sales_price' as sales_price,
    'listed' as activity_type,
    time as time
    from txn where type='put_tribe' and tribe='" . $Tribe . "' and tribe!=context and org='" . $Org . "')
    UNION
    (select
    meta->'put_tribe_response'->>'id' as id,
    meta->'put_tribe_response'->>'asset_name' as asset_name,
    meta->'put_tribe_response'->>'image_url' as image_url,
    meta->'put_tribe_response'->>'sales_price' as sales_price,
    'purchased' as activity_type,
    time as time
    from txn where type='gem_asset_purchase' and tribe='" . $Tribe . "' and org='" . $Org . "')
    UNION
    (select
    meta->'put_tribe_response'->>'id' as id,
    meta->'put_tribe_response'->>'asset_name' as asset_name,
    meta->'put_tribe_response'->>'image_url' as image_url,
    meta->'put_tribe_response'->>'sales_price' as sales_price,
    'sold' as activity_type,
    time as time
    from txn where type='gem_asset_purchase' and meta->>'seller_uid'='" . $Tribe . "' and org='" . $Org . "')
    order by time desc;");
    }
    vDBclose();

    if (count($Q) === 1 && empty($Q[0])) {
        v::$r = [];
    } else {
        v::$r = $Q;
    }
} else {
    v::$r = vR(300, "Missing org data.");
}
