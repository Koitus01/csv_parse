<?php


$handle = fopen("./products1.csv", "r");

$lineNumber = 0;
$products = [];
while ($raw_string = fgets($handle)) {
    // Skip the header
    if ($lineNumber === 0) {
        $lineNumber++;
        continue;
    }

    [$product_id, $parent_id, $name] = str_getcsv($raw_string);
    foreach ($products as $id => $product) {
        if ($parent_id == $id) {
            $products[$id]['children'][$product_id] = [
                'name' => $name
            ];
            unset($products[$product_id]);
            continue 2;
        }
    }

    $products[$product_id] = [
        'name' => $name,
        'children' => []
    ];

    // Increase the current line
    $lineNumber++;
}

echo(json_encode($products, JSON_UNESCAPED_UNICODE));

fclose($handle);