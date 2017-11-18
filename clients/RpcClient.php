<?php

namespace aleksandrzhiliaev\altcoind\clients;


use yii\httpclient\Client;

class RpcClient
{
    private $httpClient;

    function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function call($method, $params)
    {
        $response = $this->httpClient->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('json_rpc')
            ->setData([
                'jsonrpc' => '2.0',
                'id' => '0',
                'method' => $method,
                'params' => $params,
            ])->send();

        if ($response->isOk) {
            $data = json_decode($response->content, true);

            return $data;
        }

        throw new \HttpException('json rpc calling error: '.$response->content);
    }


}