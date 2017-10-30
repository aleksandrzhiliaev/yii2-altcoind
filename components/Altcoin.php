<?php

namespace aleksandrzhiliaev\altcoind\components;

use aleksandrzhiliaev\altcoind\clients\EasyBitcoin;
use ErrorException;
use yii\base\Component;

class Altcoin extends Component
{
    /**
     * @var EasyBitcoin
     */
    private $altcoinClient;

    public $username;

    public $password;

    public $host;

    public $port;

    public function init()
    {
        parent::init();
        $this->altcoinClient = new EasyBitcoin($this->username, $this->password, $this->host, $this->port);
    }

    public function generateAddress($accountName = '')
    {
        if (!$accountName) {
            $accountName = $this->username;
        }

        $address = $this->altcoinClient->getnewaddress($accountName);
        if ($this->altcoinClient->error == "") {
            return $address;
        } else {
            throw new ErrorException('getnewaddress error: '.$this->altcoinClient->error);
        }
    }

    public function send($address, $amount)
    {
        $txid = $this->altcoinClient->sendtoaddress($address, $amount);

        if ($this->altcoinClient->error == "") {
            return $txid;
        } else {
            throw new ErrorException('sendtoaddress error: '.$this->altcoinClient->error);
        }
    }

    public function getInfo()
    {
        $info = $this->altcoinClient->getinfo();

        if ($this->altcoinClient->error == "") {
            return $info;
        } else {
            throw new ErrorException('getinfo error: '.$this->altcoinClient->error);
        }
    }

    public function getTransactionsList()
    {
        $trans = $this->altcoinClient->listtransactions();

        if ($this->altcoinClient->error == "") {
            return $trans;
        } else {
            throw new ErrorException('listtransactions error: '.$this->altcoinClient->error);
        }
    }

    public function showAddresses($accountName = '')
    {
        if (!$accountName) {
            $accountName = $this->username;
        }

        $datas = $this->altcoinClient->getaddressesbyaccount($accountName);

        if ($this->altcoinClient->error == "") {
            return $datas;
        } else {
            throw new ErrorException('getaddressesbyaccount error: '.$this->altcoinClient->error);
        }
    }

}