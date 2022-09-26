<?php

class ProductRepository
{
    private $productList;

    public function __construct()
    {
        $this->productList = json_decode(file_get_contents(dirname(__FILE__) . '/products.json'), JSON_PRESERVE_ZERO_FRACTION);
    }

    public function GetAllProductList()
    {
        return $this->productList;
    }
}