<?php

namespace aleksandrzhiliaev\altcoind\components;

use Monero\Wallet;
use yii\base\Component;

class Monero extends Component
{
    /**
     * @var Wallet
     */
    private $moneroClient;

    public $host;

    public $port;

    public function init()
    {
        parent::init();
        $this->moneroClient = new Wallet($this->host, $this->port);
    }

    public function generateAddress($accountName = '')
    {
        $address = $this->moneroClient->integratedAddress();
        $address = json_decode($address, true);

        return $address['integrated_address'];
    }

    public function validateAddress($address)
    {

    }

    public function send($address, $amount)
    {
        $options = [
            'destinations' => (object)[
                'amount' => $amount,
                'address' => $address,
            ],
        ];
        $txid = $this->moneroClient->transfer($options);
        $txid = json_decode($txid, true);

        return $txid['tx_hash'];
    }

    public function getInfo()
    {
        $info = $this->moneroClient->getBalance();
        $info = json_decode($info, true);

        return $info;
    }

    public function showTransactions($accountName = '')
    {

    }

    public function showAddresses($accountName = '')
    {

    }

    public function dumpPrivateKey($address)
    {

    }

}