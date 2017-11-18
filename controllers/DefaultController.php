<?php

namespace aleksandrzhiliaev\altcoind\controllers;

use aleksandrzhiliaev\altcoind\models\TransferForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'addresses', 'newaddress', 'info'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return in_array(strtolower(Yii::$app->user->identity->login), $this->module->allowedUsers);
                        },
                    ],
                ],
            ],
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => $this->module->mainPageCache,
            ],
        ];
    }

    public function init()
    {
        $this->layout = $this->module->layout;
    }

    public function actionIndex()
    {
        $wallets = [];

        foreach ($this->module->wallets as $wallet) {

            $wallets[$wallet]['name'] = $wallet;

            try {
                $wallets[$wallet]['info'] = Yii::$app->get($wallet)->getInfo();
            } catch (\Exception $e) {
                $wallets[$wallet]['info']['balance'] = -1;
                \Yii::error($e->getMessage());
            }

        }

        $transferForm = new TransferForm();

        if ($transferForm->load(Yii::$app->request->post()) && $transferForm->validate()) {
            $txid = Yii::$app->get($transferForm->currency)->send($transferForm->address, $transferForm->amount);
            Yii::$app->session->setFlash('transfer', $txid);
            $this->refresh();
        }

        return $this->render('index', [
            'wallets' => $wallets,
            'transferForm' => $transferForm,
        ]);
    }

    public function actionAddresses($currency)
    {
        $addresses = [];

        try {
            $addresses = Yii::$app->get($currency)->showAddresses();
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }

        return $this->render('addresses', ['addresses' => $addresses, 'currency' => $currency]);
    }

    public function actionNewaddress($currency)
    {
        try {
            $address = Yii::$app->get($currency)->generateAddress();
            $this->renderContent('New generated address: '.$address);
            Yii::$app->end();
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }

        return $this->redirect(['index']);

    }

    public function actionInfo($currency)
    {
        $info = Yii::$app->get($currency)->getInfo();

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $info;
    }

}
