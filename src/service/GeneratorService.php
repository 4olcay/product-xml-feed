<?php

require_once dirname(__FILE__) . '/ResponseService.php';

class GeneratorService
{
    private $responseService;

    public function __construct()
    {
        $this->responseService = new ResponseService();
    }

    public function GenerateXMLByArray(string $fileName, string $childName, array $data)
    {
        if (count($data) < 1) {
            return $this->responseService->Send(false, 400, "GENERATOR_FAILED_DATA_IS_EMPTY", null);
        }

        $xml = new SimpleXMLElement('<?xml version="1.0"?><dataset></dataset>');

        $this->ArrayToXML($childName, $data, $xml);

        $safeFileName = trim($fileName);
        $saveFileName = $safeFileName . '-' . time() . '.xml';
        $xml->asXML(dirname(__FILE__) . '/../../generated/' . $saveFileName);
        
        return $this->responseService->Send(true, 201, 'GENERATOR_CREATED_' . strtoupper($safeFileName) . '_XML_FILE', [
            "file" => $saveFileName
        ]);
    }

    function ArrayToXML($childName, $data, &$xml)
    {
        foreach($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($childName);
                $this->ArrayToXML($childName, $value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }
}