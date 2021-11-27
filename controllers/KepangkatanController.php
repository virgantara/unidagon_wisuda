<?php

namespace app\controllers;

use Yii;
use app\models\Kepangkatan;
use app\models\KepangkatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * KepangkatanController implements the CRUD actions for Kepangkatan model.
 */
class KepangkatanController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionImport()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $errors = '';
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
        
            
            $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
            
            if(empty($user->sister_id)){
                throw new \Exception('Akun SISTER belum dipetakan');
            }

            $sisterToken = \app\helpers\MyHelper::getSisterToken();
            $headers = ['content-type' => 'application/json'];
            $client = new \GuzzleHttp\Client([
                'timeout'  => 10.0,
                'headers' => $headers,
                // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
            ]);
            $full_url = $sister_baseurl.'/kepangkatan';

            $response = $client->get($full_url, [
                'query' => [
                    'id_sdm' => $user->sister_id,
                ],
                
                'headers' => [
                    'Content-type' => 'application/json',
                    'Authorization' => 'Bearer '.$sisterToken
                ]

            ]); 
            
            $results = [];
           
            $response = json_decode($response->getBody());

            
            $counter = 0;
            
            $results = $response;
            foreach($results as $item) {

                
                $model = Kepangkatan::find()->where([
                    'sister_id' => $item->id
                ])->one();

                if(empty($model))
                    $model = new Kepangkatan;
                $model->NIY = Yii::$app->user->identity->NIY;
                $model->sister_id = $item->id;
                // 
                $model->nama_golongan = $item->pangkat_golongan;
                $model->no_sk_pangkat = $item->sk;
                $model->terhitung_mulai_tanggal_sk_pangkat = $item->tanggal_mulai;
                
                $full_url = $sister_baseurl.'/kepangkatan/'.$item->id;
                $resp = $client->get($full_url, [
                    
                     'headers' => [
                        'Content-type' => 'application/json',
                        'Authorization' => 'Bearer '.$sisterToken
                    ]

                ]); 
                
                
                $res = json_decode($resp->getBody());

                $model->kode_golongan = $res->golongan;
                $model->tanggal_sk_pengangkatan = $res->tanggal_sk;
                $model->masa_kerja_golongan_tahun = $res->masa_kerja_tahun;
                $model->masa_kerja_golongan_bulan = $res->masa_kerja_bulan;
                $model->id_pangkat_golongan = $res->id_pangkat_golongan;
                
                if($model->save()) {
                    $counter++;

                    
                }

                else
                {
                    $errors .= \app\helpers\MyHelper::logError($model);
                    throw new \Exception;
                }
            }

            $transaction->commit();
            Yii::$app->getSession()->setFlash('success',$counter.' data imported');
            return $this->redirect(['index']);
        }

        catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            Yii::$app->getSession()->setFlash('danger',$errors);
            return $this->redirect(['index']);
        } 
        


        // else
        // {
        //     Yii::$app->getSession()->setFlash('danger',json_encode($response));
        //     return $this->redirect(['index']);
        // }


    }


    /**
     * Lists all Kepangkatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KepangkatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kepangkatan model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Kepangkatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Kepangkatan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Kepangkatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Kepangkatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Kepangkatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kepangkatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kepangkatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
