<?php

namespace app\controllers;

use Yii;
use app\models\Tendik;
use app\models\TendikSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * TendikController implements the CRUD actions for Tendik model.
 */
class TendikController extends Controller
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
     * Lists all Tendik models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TendikSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tendik model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {

        $id = Yii::$app->user->identity->NIY;
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Tendik model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tendik();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->NIY]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tendik model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = $model->nIY;
        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        $foto_path = $user->foto_path;
        $errors = '';
        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {

            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
           

            try 
            {

                $user->foto_path =UploadedFile::getInstance($user,'foto_path');
                if($user->foto_path)
                {
                    $foto_path = $user->foto_path->tempName;
                    $mime_type = $user->foto_path->type;
                    $file = $user->nama.'_'.$user->NIY.'.'.$user->foto_path->extension;

                    $key = 'foto/profil/'.urlencode($file);

                     
                    $insert = $s3->putObject([
                         'Bucket' => 'tendik',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $foto_path,
                         'ContentType' => $mime_type
                    ]);

                    $plainUrl = $s3->getObjectUrl('tendik', $key);
                    $user->foto_path = $plainUrl;
                }

                if (empty($user->foto_path)){
                    $user->foto_path = $foto_path;
                }

                if($model->save() && $user->save())
                {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data tersimpan");
                    return $this->redirect(['view', 'id' => $model->NIY]);
                }

                else
                {
                    $errors .= MyHelper::logError($model);
                    $errors .= MyHelper::logError($user);
                }
            } 

            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->getSession()->setFlash('danger',$errors);
                
            } 


            
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user
        ]);
    }

    /**
     * Deletes an existing Tendik model.
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
     * Finds the Tendik model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tendik the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tendik::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
