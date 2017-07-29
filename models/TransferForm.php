<?php

namespace aleksandrzhiliaev\altcoind\models;

use yii\base\Model;
use yii\validators\Validator;


class TransferForm extends Model
{
    public $currency;

    public $amount;

    public $address;


    public function rules()
    {
        return [
            [['address'], 'string', 'min' => 30],
            [['currency', 'address', 'amount'], 'required'],
            [['amount'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'currency' => 'Payment system',
            'address' => 'Address',
            'amount' => 'Amount',
        ];
    }

    public function setMaxAmount($maxAmount)
    {
        $validator = Validator::createValidator('number', $this, ['amount'], ['max' => $maxAmount]);
        $this->validators[] = $validator;
    }


}