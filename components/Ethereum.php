<?php

namespace aleksandrzhiliaev\altcoind\components;

use aleksandrzhiliaev\altcoind\clients\RpcClient;
use yii\base\Component;
use yii\httpclient\Client;

class Ethereum extends Component
{
    const ETHER_DIGITS = 1000000000000000000;

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

    public function send($fromAddress, $toAddress, $amount, $feePaySender = true)
    {
        $value = $amount * self::ETHER_DIGITS;

        $gas = 21000;
        $gasPrice = 21000000000;
        $tranFee = $gasPrice * $gas;

        if (!$feePaySender) {
            $value -= $tranFee;
        }

        $data = $this->ethereumClient->call('personal_unlockAccount', [$fromAddress, '', 0]);
        if (isset($data['error'])) {
            throw new \Exception('personal_unlockAccount error raised: '.$data['error']['message']);
        }

        $params = [
            'from' => $fromAddress,
            'to' => $toAddress,
            'gas' => '0x'.dechex($gas),
            'gasPrice' => '0x'.dechex($gasPrice),
            'value' => '0x'.dechex($value),
        ];

        $data = $this->ethereumClient->call('eth_sendTransaction', [$params]);
        if (isset($data['error'])) {
            throw new \Exception('eth_sendTransaction error raised: '.$data['error']['message']);
        }

        // return txid
        return $data['result'];
    }

    public function getInfo()
    {
        $info['balance'] = 0;
        $info['accounts'] = [];
        $data = $this->ethereumClient->call('eth_accounts', []);
        $addresses = $data['result'];

        foreach ($addresses as $address) {
            $data = $this->ethereumClient->call('eth_getBalance', [$address, 'latest']);
            $info['accounts'][$address] = floatval(hexdec($data['result'])) / self::ETHER_DIGITS;
            $info['balance'] += $info['accounts'][$address];
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
