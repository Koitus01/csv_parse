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

// Группируем продукты по parent_id, определяем деревья
$groupedProducts = [];
$trees = [];
foreach ($flatProducts as $product) {
    if (!$product['parent_id']) {
        $trees[$product['id']] = $product;
    }
    $groupedProducts[$product['parent_id']][] = $product;
}

/*foreach ($groupedProducts as $group) {

    while ($flatProducts[$parentId] ?? null) {

    }
}*/

foreach ($trees as &$branch) {
    while ($branch->hasChildren()) {
        foreach ($groupedProducts as $id => $group) {
            if ($group[0]['parent_id'] == $branch['id']) {
                $branch['children'] = $group;
            }
        }
    }
}

echo(json_encode($trees, JSON_UNESCAPED_UNICODE));
die;