<?php

// Берем заголовки и удаляем их из массива
$headers = str_getcsv(fgets(fopen('products.csv', 'r')));
$file = file('products.csv');
array_shift($file);
// Формируем список продуктов
$list = [];
array_map(function ($str) use (&$list, $headers) {
    $str = array_combine($headers, str_getcsv($str));
    $list[$str['id']] = array_combine($headers, $str);
}, $file);

$tree = [];

foreach ($list as $itemData) {
    $id = $itemData['id'];
    $pid = $itemData['parent_id'];

    $parent = $list[$pid] ?? null;
    if ($pid > 0 && $parent === null) {
        $tree[$pid] = $parent;
    }

    // create new tree item if not exists / otherwise get existing item
    $item = $tree[$id] ?? null;
    if ($item === null) {
        $item = $itemData;
        $tree[$id] = $item;
    }

    if ($parent !== null) {
        $parent['children'][] = $item;
    }

    $item = $itemData;
}


echo(json_encode($tree, JSON_UNESCAPED_UNICODE));
die;