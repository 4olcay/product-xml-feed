<?php

require_once dirname(__FILE__) . '/../repository/ProductRepository.php';

require_once dirname(__FILE__) . '/GeneratorService.php';
require_once dirname(__FILE__) . '/ResponseService.php';

require_once dirname(__FILE__) . '/../client/AkakceClient.php';
require_once dirname(__FILE__) . '/../client/CimriClient.php';
require_once dirname(__FILE__) . '/../client/EpeyClient.php';

class ProductService
{
    private $repository;

    private $generatorService;
    private $responseService;

    private $baseGeneratedDir;

    private $akakceClient;
    private $cimriClient;
    private $epeyClient;

    public function __construct()
    {
        $this->repository = new ProductRepository();

        $this->generatorService = new GeneratorService();
        $this->responseService = new ResponseService();

        $this->akakceClient = new AkakceClient();
        $this->cimriClient = new CimriClient();
        $this->epeyClient = new EpeyClient();

        $this->baseGeneratedDir = dirname(__FILE__) . '/../../generated';
    }

    public function SyncMarketPlaces()
    {
        $productList = $this->repository->GetAllProductList();
        $generateXML = [
            'akakce' => json_decode($this->generatorService->GenerateXMLByArray('akakce', 'product', $productList), JSON_PRESERVE_ZERO_FRACTION),
            'cimri' => json_decode($this->generatorService->GenerateXMLByArray('cimri', 'product', $productList), JSON_PRESERVE_ZERO_FRACTION),
            'epey' => json_decode($this->generatorService->GenerateXMLByArray('epey', 'product', $productList), JSON_PRESERVE_ZERO_FRACTION)
        ];

        $akakcePath = $this->baseGeneratedDir . '/' . $generateXML['akakce']['detail']['file'];

        if (file_exists($akakcePath)) {
            $this->akakceClient->SyncProductList(file_get_contents($akakcePath));
        }

        $cimriPath = $this->baseGeneratedDir . '/' . $generateXML['cimri']['detail']['file'];

        if (file_exists($cimriPath)) {
            $this->cimriClient->SyncProductList(file_get_contents($cimriPath));
        }

        $epeyPath = $this->baseGeneratedDir . '/' . $generateXML['epey']['detail']['file'];

        if (file_exists($epeyPath)) {
            $this->epeyClient->SyncProductList(file_get_contents($epeyPath));
        }

        return $this->responseService->Send(true, 201, 'PRODUCT_SYNCMARKETPLACES_SUCCESS', null);
    }
}