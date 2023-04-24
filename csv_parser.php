<?php

// Берем заголовки и удаляем их из массива
$headers = str_getcsv(fgets(fopen('products.csv', 'r')));
$file = file('products.csv');
array_shift($file);
// Формируем список продуктов
$flatProducts = [];
array_map(function ($str) use (&$flatProducts, $headers) {
    $str = array_combine($headers, str_getcsv($str));
    $flatProducts[$str['id']] = array_combine($headers, $str);
}, $file);


$productsTree = [];
foreach ($flatProducts as $product) {
    $parentId = $product['parent_id'];
    $level = 0;
    while ($flatProducts[$parentId] ?? null) {
        if (isset($productsTree[$parentId])) {
            $productsTree[$parentId]['children'][] = $product;
        } else {
            $productsTree[$parentId]['name'] = $flatProducts[$parentId]['name'];
            $productsTree[$parentId]['parent_id'] = $flatProducts[$parentId]['parent_id'];
            $productsTree[$parentId]['children'][$product['id']] = $product;
        }

        $parentId = $flatProducts[$parentId]['parent_id'];
        if ($parentId) {
            $product = $productsTree[$parentId];
        }
    }
}

echo(json_encode($productsTree, JSON_UNESCAPED_UNICODE));
die;