<?php

namespace app\controllers;

use Yii;
use app\models\Peserta;
use app\models\PesertaSyarat;
use app\models\PesertaSyaratSearch;
use app\models\Syarat;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * PesertaSyaratController implements the CRUD actions for PesertaSyarat model.
 */
class PesertaSyaratController extends Controller
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

    public function actionDropzoneUpload()
    {

        $results = [
            'code' => 404,
            'message' => 'Not Found'
        ];
        if (isset($_FILES['file']))
        {

            $transaction = \Yii::$app->db->beginTransaction();
            $errors = '';
            try 
            {
                $s3config = Yii::$app->params['s3'];

                $s3 = new \Aws\S3\S3Client($s3config);
                
                $peserta = Peserta::findOne(['nim' => Yii::$app->user->identity->nim]);
                $syarat_id = $_REQUEST['syarat_id'];
                $syarat = Syarat::findOne($syarat_id);
                
                $ps = PesertaSyarat::findOne([
                    'syarat_id' => $syarat_id,
                    'peserta_id' => $peserta->id
                ]);

                if(empty($ps)){
                    $ps = new PesertaSyarat;
                    $ps->syarat_id = $syarat->id;
                    $ps->peserta_id = $peserta->id;
                }

                $uploadedFile = UploadedFile::getInstanceByName('file');
                if($uploadedFile) {
                    $f = $uploadedFile->tempName;
                    $mime_type = $uploadedFile->type;
                    $file = $peserta->nim.'_'.date('YmdHis').'.'.$uploadedFile->extension;
                    $key = 'wisuda/bukti/'.$syarat->nama.'/'.$file;
                    $insert = $s3->putObject([
                       'Bucket' => 'siakad',
                       'Key'    => $key,
                       'Body'   => 'This is the Body',
                       'SourceFile' => $f,
                       'ContentType' => $mime_type
                    ]);

                    $plainUrl = $s3->getObjectUrl('siakad', $key);
                    $ps->file_path = $plainUrl;

                }
               
                if($ps->save()){
                    $transaction->commit();
                    $results = [
                        'code' => 200,
                        'message' => 'File successfully uploaded'
                    ];    
                }

                else{
                    throw new \Exception(\app\helpers\MyHelper::logError($ps));
                    
                }
                

            } catch (\Exception $e) {
                $errors .= $e->getMessage();
                
                $results = [
                    'code' => 501,
                    'message' => $errors
                ];
                $transaction->rollBack();
                   
            }
        }

        else{
            $results = [
                'code' => 404,
                'message' => 'Params FILES not found'
            ];
        }

        echo json_encode($results);
        die();
    }

    /**
     * Lists all PesertaSyarat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PesertaSyaratSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PesertaSyarat model.
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
     * Creates a new PesertaSyarat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PesertaSyarat();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PesertaSyarat model.
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
     * Deletes an existing PesertaSyarat model.
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
     * Finds the PesertaSyarat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PesertaSyarat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PesertaSyarat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
