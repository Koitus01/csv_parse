<?php

class Tree
{
    private array $items = [];

    public function getItem(int $id): ?TreeItem
    {
        return $this->items[$id]??null;
    }

    public function setItem(TreeItem $item, int $id): void
    {
        $this->items[$id] = $item;
    }

    public function getRootItems(): array
    {
        $rootItems = [];
        foreach ($this->items as $item) {
            if ($item->getParent() === null)
                $rootItems[] = $item;
        }

        return $rootItems;
    }
}

class TreeItem
{
    private ?TreeItem $parent = null;
    private array $children = [];
    private array $data = [];

    public function setParent(?TreeItem $parent): void
    {
        $this->parent = $parent;
    }

    public function addChild(TreeItem $child): void
    {
        $this->children[] = $child;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}

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

$tree = new Tree();
foreach($list as $itemData) {

    $id = $itemData['id'];
    $pid = $itemData['parent_id'];

    // if ZERO we have root element no parent exists

    $parent = $tree->getItem($pid);
    if ($pid > 0 && $parent === null) {
        $parent = new TreeItem();
        $tree->setItem($parent, $pid);
    }

    // create new tree item if not exists / otherwise get existing item
    $item = $tree->getItem($id);
    if ($item === null) {
        $item = new TreeItem();
        $item->setParent($parent);
        $tree->setItem($item, $id);
    }

    if ($parent !== null) {
        $parent->addChild($item);
    }

    $item->setData($itemData);
}

var_dump((array)$tree->getItem(1), $item);
die;
echo $item;
echo(json_encode($tree, JSON_UNESCAPED_UNICODE));
die;