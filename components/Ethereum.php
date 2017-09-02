<?php

namespace aleksandrzhiliaev\altcoind\components;

use aleksandrzhiliaev\altcoind\clients\RpcClient;
use yii\base\Component;
use yii\httpclient\Client;

class Ethereum extends Component
{
    
    /**
     * @var RpcClient
     */
    private $ethereumClient;

    public $host;

    public $port;

    public function init()
    {
        parent::init();
        $this->ethereumClient = new RpcClient(new Client(['baseUrl' => 'http://'.$this->host.':'.$this->port]));
    }

    public function generateAddress($password = '')
    {
        $data = $this->ethereumClient->call('personal_newAccount', [$password]);

        return $data['result'];
    }

    public function send($address, $amount)
    {
        #TODO: sendTransaction support
    }

    public function getInfo()
    {
        $info['balance'] = 0;
        $data = $this->ethereumClient->call('eth_accounts', []);
        $addresses = $data['result'];

        foreach ($addresses as $address) {
            $data = $this->ethereumClient->call('eth_getBalance', [$address, 'latest']);
            $info['balance'] += floatval($data['result']);
        }

        return $info;
    }

    public function showAddresses()
    {
        $data = $this->ethereumClient->call('eth_accounts', []);
        $addresses = $data['result'];

        return $addresses;
    }

}
