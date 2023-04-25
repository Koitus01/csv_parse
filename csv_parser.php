<?php

class TreeItem implements JsonSerializable
{
    protected int $id;
    protected int $parent_id;
    protected string $name;
    protected array $children = [];

    /**
     * @param int $id
     * @param int $parent_id
     * @param string $name
     * @param array<TreeItem> $children
     */
    public function __construct(int $id, int $parent_id, string $name, array $children = [])
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
        $this->name = $name;
        $this->children = $children;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(TreeItem $child): static
    {
        if (in_array($child, $this->children)) {
            return $this;
        }

        $this->children[] = $child;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TreeItem
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        if ($this->parent_id) {
            return [
                $this->id => [
                    'name' => $this->name,
                    'parent_id' => $this->parent_id,
                    'children' => $this->children
                ]
            ];
        }

        return [
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'children' => $this->children
        ];
    }
}

// Get headers and remove it from array
$headers = str_getcsv(fgets(fopen('products.csv', 'r')));
$file = file('products.csv');
array_shift($file);

// Create flatten product list, count trees
$products = [];
$treesCount = 0;
array_map(function ($str) use (&$products, &$treesCount, $headers) {
    $str = array_combine($headers, str_getcsv($str));
    $product = array_combine($headers, $str);
    $products[] = new TreeItem(
        $product['id'],
        $product['parent_id'],
        $product['name']
    );

    if (!$product['parent_id']) {
        $treesCount++;
    }
}, $file);

/**
 * @param TreeItem $item
 * @param array<TreeItem> $products
 * @return void
 */
function findChildren(TreeItem $item, array &$products): void
{
    foreach ($products as $key => $product) {
        if ($item->getId() === $product->getParentId()) {
            $item->addChild($product);
            unset($products[$key]);
        }
    }
}

// Finding children, until all trees are created
while (count($products) !== $treesCount) {
    foreach ($products as $product) {
        findChildren($product, $products);
    }
}

echo(json_encode($products, JSON_UNESCAPED_UNICODE));
