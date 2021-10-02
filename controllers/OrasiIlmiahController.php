<?php

namespace app\controllers;

use Yii;
use app\models\OrasiIlmiah;
use app\models\OrasiIlmiahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;


/**
 * OrasiIlmiahController implements the CRUD actions for OrasiIlmiah model.
 */
class OrasiIlmiahController extends Controller
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

    /**
     * Lists all OrasiIlmiah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrasiIlmiahSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrasiIlmiah model.
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
     * Creates a new OrasiIlmiah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrasiIlmiah();
        $model->NIY = Yii::$app->user->identity->NIY;
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        $model->NIY = Yii::$app->user->identity->NIY;
        try 
        {
            if ($model->load(Yii::$app->request->post())) {
                $model->file_path = UploadedFile::getInstance($model,'file_path');
                if($model->file_path){
                    $file = date('YmdHis').$model->file_path->name.'.'.$model->file_path->extension;

                    $file_path = $model->file_path->tempName;
                    $mime_type = $model->file_path->type;
                    $key = 'orasiilmiah/'.$model->NIY.'/'.$file;
                    $insert = $s3->putObject([
                       'Bucket' => 'dosen',
                       'Key'    => $key,
                       'Body'   => 'This is the Body',
                       'SourceFile' => $file_path,
                       'ContentType' => $mime_type
                    ]);

                    $plainUrl = $s3->getObjectUrl('dosen', $key);
                    $model->file_path = $plainUrl;
                    
                }

                if($model->save())
                {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data tersimpan");
                    return $this->redirect(['orasi-ilmiah/index']);  
                }

                
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrasiIlmiah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $file_path = $model->file_path;
        
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);

        try 
        {
            if ($model->load(Yii::$app->request->post())) {

                $model->file_path = UploadedFile::getInstance($model,'file_path');

                if($model->file_path)
                {
                    $file = date('YmdHis').$model->file_path->name.'.'.$model->file_path->extension;
                    $file_path = $model->file_path->tempName;
                    $mime_type = $model->file_path->type;
                    $key = 'orasiilmiah/'.$model->NIY.'/'.$file;
                    $insert = $s3->putObject([
                       'Bucket' => 'dosen',
                       'Key'    => $key,
                       'Body'   => 'This is the Body',
                       'SourceFile' => $file_path,
                       'ContentType' => $mime_type
                    ]);

                    $plainUrl = $s3->getObjectUrl('dosen', $key);
                    $model->file_path = $plainUrl;
                    
                }

                if (empty($model->file_path)){
                     $model->file_path = $file_path;
                }


                if($model->validate())
                    $model->save();

                

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['orasi-ilmiah/index']);
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrasiIlmiah model.
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
     * Finds the OrasiIlmiah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrasiIlmiah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrasiIlmiah::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
