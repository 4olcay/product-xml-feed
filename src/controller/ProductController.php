<?php


require_once dirname(__FILE__) . '/../service/ProductService.php';

class ProductController
{
    private $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    public function SyncMarketPlaces()
    {
        echo $this->service->SyncMarketPlaces();
        exit;
    }
}