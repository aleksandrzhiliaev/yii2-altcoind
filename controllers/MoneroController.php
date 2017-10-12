<?php
/**
 * Author yakovlev.vladimir@hotmail.com.
 * Date: 12.10.2017 18:01
 *
 *
 */

namespace aleksandrzhiliaev\altcoind\controllers;



use yii\httpclient\Client;
use yii\data\ArrayDataProvider;

class MoneroController extends DefaultController
{

    protected $client;

    public function init()
    {
        parent::init();
        $this->client = new Client(['baseUrl' => 'http://'. getenv('XMR_HOST'). ':18082']);
    }

    public function actionIndex() {
        return $this->redirect('/cryptowallet/default/index');
    }

    public function actionPayment() {
        $payment_id = \Yii::$app->request->post('payment_id', null);

        $payment = null;
        if (\Yii::$app->request->isPost) {

            $response  =  $this->client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl('json_rpc')
                ->setData([
                    'jsonrpc' => '2.0',
                    'id' => '0',
                    'method' => 'get_payments',
                    'params' => [
                        'payment_id' => $payment_id
                    ]
                ])->send();
            if ($response->isOk) {
                $payment = json_decode($response->content);
            }
        }

        return $this->render('payment', [
            'payment_id' => $payment_id,
            'payment' => $payment
        ]);
    }


    /**
     * @return string
     */
    public function actionTransfers() {
        $params = [
            'in' => true,
            'out' => true,
            'pending' => true,
            'failed' => true,
            'pool' => true
        ];
        $transfers = [];
        $response  =  $this->client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('json_rpc')
            ->setData([
                'jsonrpc' => '2.0',
                'id' => '0',
                'method' => 'get_transfers',
                'params' => $params
            ])->send();

        if ($response->isOk) {
            $result = json_decode($response->content);
            if ($result instanceof \stdClass) {
                foreach ($params as  $key => $param) {
                    if (isset($result->result->$key) && is_array($result->result->$key))
                        $transfers = array_merge($transfers, $result->result->$key);
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $transfers,
        ]);

        return $this->render('transfers', [
            'transfers' => $dataProvider,
            'params' => $params
        ]);
    }
}