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

// Группируем продукты по parent_id
$groupedProducts = [];
foreach ($flatProducts as $product) {
    $groupedProducts[$product['parent_id']][$product['id']] = $product;
}

$productsTree = [];
foreach ($groupedProducts as $groupOfProduct) {
    $parentId = reset($groupOfProduct)['parent_id'];
    while ($parent = $flatProducts[$parentId] ?? null) {
        if ($parentId) {
            $tree = $flatProducts[$parentId];
            $tree['children'] = $groupOfProduct;
            $parentId = $parent['parent_id'];
        } else {
            #$productsTree[$parentId] = $groupOfProduct;
        }


    }

    if (isset($tree)) {
        $productsTree[] = $tree;
    }

/*    if ($parent = $flatProducts[$parentId]) {
        $productsTree[$parentId] = [
            'name' => $parent['name'],
            'children' => $groupOfProduct
        ];
    }*/

/*    while (!isset($productsTree[$parentId])) {
        $productsTree[$parentId] = $flatProducts;
        echo 'cock';
    }*/
}


echo(json_encode($productsTree, JSON_UNESCAPED_UNICODE));
die;

$arr = array(
    array('id' => 100, 'parentid' => 0, 'name' => 'a'),
    array('id' => 101, 'parentid' => 100, 'name' => 'a'),
    array('id' => 102, 'parentid' => 101, 'name' => 'a'),
    array('id' => 103, 'parentid' => 101, 'name' => 'a'),
);

$new = [];
foreach ($arr as $a) {
    $new[$a['parentid']][] = $a;
}
$tree = createTree($new, $new[0]); // changed
print_r($tree);

function createTree(&$list, $parent)
{
    $tree = array();
    foreach ($parent as $k => $l) {
        if (isset($list[$l['id']])) {
            $l['children'] = createTree($list, $list[$l['id']]);
        }
        $tree[] = $l;
    }
    return $tree;
}

echo(json_encode($treeProducts, JSON_UNESCAPED_UNICODE));

fclose($handle);