<?php

namespace app\controllers;

use Yii;
use app\models\SisterFiles;
use app\models\SisterFilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * PembicaraFilesController implements the CRUD actions for PembicaraFiles model.
 */
class SisterFilesController extends Controller
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
     * Lists all PembicaraFiles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SisterFilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PembicaraFiles model.
     * @param string $id
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
     * Creates a new PembicaraFiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent_id, $redirect_uri)
    {
        $model = new SisterFiles();
        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
       

        try 
        {
            if ($model->load(Yii::$app->request->post())) {
                
                $model->tautan = UploadedFile::getInstance($model,'tautan');
                $model->keterangan_dokumen = 'Manually uploaded file';
                $model->tanggal_upload = date('Y-m-d');

                if($model->tautan)
                {
                  $tautan = $model->tautan->tempName;
                  $mime_type = $model->tautan->type;
                  $file = 'MANUAL_UPLOAD_DOK_'.date('YmdHis').'.'.$model->tautan->extension;
                  $key = 'docs/manual/'.$file;
                  $insert = $s3->putObject([
                       'Bucket' => 'dosen',
                       'Key'    => $key,
                       'Body'   => 'This is the Body',
                       'SourceFile' => $tautan,
                       'ContentType' => $mime_type
                  ]);
                  
                  $plainUrl = $s3->getObjectUrl('dosen', $key);
                  $model->tautan = $plainUrl;

                }

                $model->id_dokumen = \app\helpers\MyHelper::gen_uuid();
                $model->parent_id = $parent_id;
                if($model->save())
                {

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data tersimpan");
                    return $this->redirect([$redirect_uri]);
                    
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
     * Updates an existing PembicaraFiles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id_dokumen]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PembicaraFiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PembicaraFiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PembicaraFiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SisterFiles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
