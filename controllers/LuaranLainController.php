<?php

namespace app\controllers;

use Yii;
use app\models\LuaranLain;
use app\models\LuaranLainAuthor;
use app\models\LuaranLainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Verify;
use app\helpers\MyHelper;

/**
 * LuaranLainController implements the CRUD actions for LuaranLain model.
 */
class LuaranLainController extends Controller
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
     * Lists all LuaranLain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LuaranLainSearch();
        $dataProvider = $searchModel->searchItemku(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LuaranLain model.
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
     * Creates a new LuaranLain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LuaranLain();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        $s3config = Yii::$app->params['s3'];

        $s3 = new \Aws\S3\S3Client($s3config);

        $errors = '';
        try 
        {
            if ($model->load(Yii::$app->request->post())) {
                
                $model->NIY = Yii::$app->user->identity->NIY;
                $model->berkas = UploadedFile::getInstance($model,'berkas');
                if($model->berkas){
                    $berkas = $model->berkas->tempName;
                    $mime_type = $model->berkas->type;
                    $file = 'LUARAN_LAIN_'.$model->NIY.'_'.$model->tahun_pelaksanaan.'_'.date('YmdHis').'.'.$model->berkas->extension;
                    $key = 'luaran_lain/'.$file;

                    $insert = $s3->putObject([
                        'Bucket' => 'dosen',
                        'Key'    => $key,
                        'Body'   => 'This is the Body',
                        'SourceFile' => $berkas,
                        'ContentType' => $mime_type
                    ]);

                    $plainUrl = $s3->getObjectUrl('dosen', $key);
                    $model->berkas = $plainUrl;

                }
                $model->ver = 'Sudah Diverifikasi';
                $model->save();
                if(!empty($_POST['author_id']))
                {
                    foreach($_POST['author_id'] as $aid)
                    {
                        if(empty($aid)) continue;

                        $author = new LuaranLainAuthor;
                        $author->luaran_lain_id = $model->id;
                        $author->NIY = $aid;
                        if(!$author->save())
                        {
                            foreach($author->getErrors() as $attribute){
                                foreach($attribute as $error){
                                    $errors .= $error.' ';
                                }
                            }
                            
                            throw new \Exception($errors);
                        }
                    }
                }
                
                else
                {
                    $errors .= 'Author tidak boleh kosong';
                    throw new \Exception;
                }

                
                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['luaran-lain/view', 'id' => $model->id]);
            }

        } catch (\Exception $e) {
            $model->addError('id',$e->getMessage());
            $transaction->rollBack();
            
        } catch (\Throwable $e) {
            $model->addError('id',$e->getMessage());
            $transaction->rollBack();
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LuaranLain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $s3config = Yii::$app->params['s3'];

        $s3 = new \Aws\S3\S3Client($s3config);

        $berkas = $model->berkas;
        

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
       

        try 
        {

          // print_r(Yii::$app->request->post());exit;
            $model->ver = 'Sudah Diverifikasi';
            if ($model->load(Yii::$app->request->post())) {
               
                $model->berkas = UploadedFile::getInstance($model,'berkas');
                if($model->berkas){
                    $berkas = $model->berkas->tempName;
                    $mime_type = $model->berkas->type;
                    $file = 'Luaran_lain_'.$model->id.'_'.$model->tahun_pelaksanaan.'_'.date('YmdHis').'.'.$model->berkas->extension;
                    
                    $key = 'luaran_lain/'.$file;
                    $errors = '';

                     
                    $insert = $s3->putObject([
                         'Bucket' => 'dosen',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $berkas,
                         'ContentType' => $mime_type
                    ]);

                    $plainUrl = $s3->getObjectUrl('dosen', $key);
                    $model->berkas = $plainUrl;
                }

                if (empty($model->berkas)){
                     $model->berkas = $berkas;
                }

                if($model->validate())
                    $model->save();

                $listAuthors = $model->luaranLainAuthors;
                foreach($listAuthors as $d)
                {
                    $d->delete();
                }

                if(!empty($_POST['author_id']))
                {
                    foreach($_POST['author_id'] as $aid)
                    {
                        if(empty($aid)) continue;

                        $author = new LuaranLainAuthor;
                        $author->luaran_lain_id = $model->id;
                        $author->NIY = $aid;
                        if(!$author->save())
                        {
                            $errors .= \app\helpers\MyHelper::logError($author);
                            
                            throw new \Exception;
                        }
                    }
                }
                
                else
                {
                    $errors .= 'Author tidak boleh kosong';
                    throw new \Exception;
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['luaran-lain/view', 'id' => $model->id]);
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
     * Deletes an existing LuaranLain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        foreach($model->luaranLainAuthors as $author)
            $author->delete();

        return $this->redirect(['index']);
    }

    public function actionDownload($id) 
    { 
        $download = LuaranLain::findOne($id); 
        $path=Yii::getAlias('@webroot').'/uploads/luaran_lain/'.$id.'/'.$download->berkas;
        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }else{
            echo 'file not exists...';    }
    }

    public function actionDisplay($id) 
    { 
        $download = LuaranLain::findOne($id); 
        $path=Yii::getAlias('@webroot').'/uploads/luaran_lain/'.$id.'/'.$download->berkas;
        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path,$download->berkas,['inline'=>true]);
        }else{
            echo 'file not exists...';
        }
    }


    /**
     * Finds the LuaranLain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LuaranLain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LuaranLain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
