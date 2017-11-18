<?php

namespace aleksandrzhiliaev\altcoind\components;

use ErrorException;

class Zcash extends Altcoin
{

    public function generateAddress($accountName = '')
    {
        $address = $this->altcoinClient->getnewaddress();
        if ($this->altcoinClient->error == "") {
            return $address;
        } else {
            throw new ErrorException('getnewaddress error: '.$this->altcoinClient->error);
        }
    }

    public function showAddresses($accountName = '')
    {
        $datas = $this->altcoinClient->listreceivedbyaddress(0, true);
        if ($this->altcoinClient->error == "") {
            $onlyAddressesList = [];
            foreach ($datas as $data) {
                $onlyAddressesList[] = $data['address'];
            }

            return $onlyAddressesList;
        } else {
            throw new ErrorException('z_listaddresses error: '.$this->altcoinClient->error);
        }
    }


}