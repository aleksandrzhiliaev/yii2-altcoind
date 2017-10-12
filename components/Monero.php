<?php

namespace aleksandrzhiliaev\altcoind\components;

use Monero\Wallet;
use yii\base\Component;

class Monero extends Component
{
    const MONERO_DIGITS = 1000000000000;

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

    public function generateAddress()
    {
        $address = $this->moneroClient->integratedAddress();
        $address = json_decode($address, true);

        return $address['integrated_address'];
    }

    public function send($address, $amount, $paymentId = '')
    {
        $options = [
            'destinations' => (object)[
                'amount' => $amount,
                'address' => $address,
            ],
        ];

        if ($paymentId) {
            $options['payment_id'] = $paymentId;
        }

        $txid = $this->moneroClient->transfer($options);
        $txid = json_decode($txid, true);

        if ($txid['code'] < 0) {
            throw new \Exception('Monero send exception raised: '.$txid['message']);
        }

        return $txid['tx_hash'];
    }

    public function getInfo()
    {
        $info = $this->moneroClient->getBalance();
        $info = json_decode($info, true);
        $info['balance'] /= self::MONERO_DIGITS;
        $info['total_balance'] = $info['balance'];
        $info['unlocked_balance'] /= self::MONERO_DIGITS;
        $info['balance'] = $info['unlocked_balance'];

        return $info;
    }

    public function getPaymentId($address)
    {
        $data = $this->moneroClient->splitIntegratedAddress($address);
        $data = json_decode($data, true);

        return $data['payment_id'];
    }

    public function getPayments($paymentId)
    {
        $data = $this->moneroClient->getPayments($paymentId);
        $data = json_decode($data, true);

        return $data;
    }

}