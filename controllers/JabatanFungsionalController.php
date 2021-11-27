<?php

namespace app\controllers;

use Yii;
use app\models\JabatanFungsional;
use app\models\JabatanFungsionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * JabatanFungsionalController implements the CRUD actions for JabatanFungsional model.
 */
class JabatanFungsionalController extends AppController
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
        $counter = 0;
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
            $full_url = $sister_baseurl.'/jabatan_fungsional';

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
        
            $results = $response;
            foreach($results as $item) {
                $model = JabatanFungsional::find()->where([
                    'sister_id' => $item->id
                ])->one();

                if(empty($model))
                    $model = new JabatanFungsional;

            
                $model->NIY = Yii::$app->user->identity->NIY;
                $model->sister_id = $item->id;
                $model->sk_jabatan_fungsional = $item->sk;
                $model->jabatan_fungsional = $item->jabatan_fungsional;
                $model->terhitung_mulai_tanggal_jabatan_fungsional = $item->tanggal_mulai;
                
                $full_url = $sister_baseurl.'/jabatan_fungsional/'.$item->id;
                $resp = $client->get($full_url, [
                    
                     'headers' => [
                        'Content-type' => 'application/json',
                        'Authorization' => 'Bearer '.$sisterToken
                    ]

                ]); 
                
                $res = json_decode($resp->getBody());
                
                $model->angka_kredit = $res->angka_kredit;
                $model->kelebihan_pengajaran = $res->kelebihan_pengajaran;
                $model->kelebihan_penelitian = $res->kelebihan_penelitian;
                $model->kelebihan_pengabdian_masyarakat = $res->kelebihan_pengabdian;
                $model->kelebihan_kegiatan_penunjang = $res->kelebihan_penunjang;
                $model->id_jabfung = $res->id_jabatan_fungsional;
                   
                    
                

                if($model->save())
                {
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
        
    }

    /**
     * Lists all JabatanFungsional models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JabatanFungsionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JabatanFungsional model.
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
     * Creates a new JabatanFungsional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JabatanFungsional();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing JabatanFungsional model.
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
     * Deletes an existing JabatanFungsional model.
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
     * Finds the JabatanFungsional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JabatanFungsional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JabatanFungsional::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
