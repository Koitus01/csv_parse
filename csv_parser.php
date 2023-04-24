<?php


$products = array_map('str_getcsv', file('products.csv'));
array_walk($products, function(&$a) use ($products) {
    $a = array_combine($products[0], $a);
});
array_shift($products); # remove column header

$flat = [];
$tree = [];

foreach ($products as $product) {
    $child = $product;
    $parent = $products['parent_id'] ;
    if (!isset($flat[$child['id']])) {
        $flat[$child['id']] = [];
    }
    if (!empty($parent['id'])) {
        $flat[$parent['id']][$child] =& $flat[$child['id']];
    } else {
        $tree[$child['id']] =& $flat[$child['id']];
    }
}

echo(json_encode($tree, JSON_UNESCAPED_UNICODE));

#fclose($handle);