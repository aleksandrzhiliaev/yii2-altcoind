<?php

namespace aleksandrzhiliaev\altcoind\components;

use Graze\GuzzleHttp\JsonRpc\Client;
use Yii;
use yii\base\Component;


class Bytecoin extends Component
{
    public $host;
    
    const DIGITS = 1e8;

    /* @var  Client */
    protected $httpClient;

    public function init()
    {
        $this->httpClient = Client::factory($this->host.'/json_rpc');
    }


    /**
     * Отправить запрос на ноду
     * @param $method
     * @param null $params
     * @param string $id
     * @return string
     */
    protected function _request($method, $params = null, $id = 'test')
    {
        if (is_array($params)) {
            $response = $this->httpClient->send($this->httpClient->request($id, $method, $params));
        } else {
            $response = $this->httpClient->send($this->httpClient->request($id, $method));
        }

        $response = json_decode($response->getBody());

        if (property_exists($response, 'error')) {
            return $response->error;
        } else {
            return $response->result;
        }
    }

    public function genPaymentId()
    {
        return hash('sha256', time().Yii::$app->security->generateRandomString(64));
    }


    /**
     * Method returns information about the current RPC Wallet state: block_count, known_block_count, last_block_hash and peer_count.
     * @link https://wiki.bytecoin.org/wiki/Get_status_-_Bytecoin_RPC_Wallet_API
     * @return bool|mixed
     */
    public function getStatus()
    {
        return $this->_request('getStatus');
    }

    /**
     * Method returns an array of your RPC Wallet's addresses.
     * @link https://wiki.bytecoin.org/wiki/Get_addresses-_Bytecoin_RPC_Wallet_API
     * @return bool|mixed
     */
    public function getAddresses()
    {
        $result = $this->_request('getAddresses');

        return $result->addresses;
    }

    /**
     * Method returns a balance for a specified address.
     * @link https://wiki.bytecoin.org/wiki/Get_balance_-_Bytecoin_RPC_Wallet_API
     * @param $address
     * @return string
     */
    public function getBalance($address)
    {
        return $this->_request('getBalance', ['address' => $address]);
    }

    /**
     * Method creates an additional address in your wallet.
     * @link https://wiki.bytecoin.org/wiki/Create_address_-_Bytecoin_RPC_Wallet_API
     * @return string
     */
    public function createAddress()
    {
        return $this->_request('createAddress');
    }

    /**
     * Method deletes a specified address
     * @link https://wiki.bytecoin.org/wiki/Delete_address_-_Bytecoin_RPC_Wallet_API
     * @param $address
     * @return string
     */
    public function deleteAddress($address)
    {
        return $this->_request('deleteAddress', ['address' => $address]);
    }

    /**
     * Method returns an array of block and transaction hashes.
     * @link https://wiki.bytecoin.org/wiki/Get_transactions_-_Bytecoin_RPC_Wallet_API
     * @param $addresses
     * @param $paymentId
     * @param int $blockCount
     * @return string
     */
    public function getTransactions($addresses, $paymentId, $firstBlockIndex = 1396658, $blockCount = 100)
    {
        if (!is_array($addresses) && is_string($addresses)) {
            $addresses = [$addresses];
        }

        return $this->_request('getTransactions', [
            'addresses' => $addresses,
            'blockCount' => $blockCount,
            'firstBlockIndex' => (int)$firstBlockIndex,
            'paymentId' => $paymentId,
        ]);
    }

    /**
     * Человекочитаемая сумма
     * @param $amount
     * @return float
     */
    public function amount2Human($amount)
    {
        if ($amount == 0) {
            return 0;
        }

        return $amount / self::DIGITS;
    }

    /**
     * Человекочитаемую сумму конвентировать в сумму для сети
     * @param $amount
     * @return float
     */
    public function amount2Network($amount)
    {
        return self::DIGITS / $amount;
    }

    /**
     * Method allows you to send transaction to one or several addresses.
     * Also, it allows you to use a payment_id for a transaction to a single address.
     * @link https://wiki.bytecoin.org/wiki/Send_transaction_-_Bytecoin_RPC_Wallet_API
     * @param $paymentId
     * @param $address
     * @param $amount
     * @param null $changeAddress
     * @param int $fee
     * @param int $anonymity
     * @return string
     */
    public function sendTransaction(
        $paymentId,
        $address,
        $amount,
        $changeAddress = null,
        $fee = 10000000000,
        $anonymity = 7
    ) {
        $data = [
            'paymentId' => $paymentId,
            'transfers' => [
                [
                    'amount' => $amount,
                    'address' => $address,
                ],
            ],
            'anonymity' => $anonymity,
            'fee' => $fee,
            'unlockTime' => 0,
        ];
        if ($changeAddress != null) {
            $data['changeAddress'] = $changeAddress;
        } else {
            if ($changeAddress == null) {
                $addresses = $this->getAddresses();
                $data['changeAddress'] = current($addresses);
            }
        }

        return $this->_request('sendTransaction', $data);
    }

    /**
     * Системный интерфейс
     * @param $address
     * @param $amount
     * @return mixed
     */
    public function send($address, $amount)
    {
        $amount = $amount * self::DIGITS;
        $result = $this->sendTransaction($this->genPaymentId(), $address, $amount, null);
        return (string)$result->transactionHash;
    }

    /**
     * Системный
     * @return array
     */
    public function getInfo()
    {
        $addresses = $this->getAddresses();

        $info = [];
        $balance = 0;
        if (is_array($addresses)) {
            foreach ($addresses as $address) {
                $row = $this->getBalance($address);
                $info[$address][] = $row;
                $balance += $row->availableBalance;
            }

            return [
                'balance' => round($balance / self::DIGITS, 2),
                'address' => $info,
            ];
        }

        return [
            'balance' => -1,
            'address' => var_export($addresses, true),
        ];
    }
}
