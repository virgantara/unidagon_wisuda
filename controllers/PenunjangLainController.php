<?php

namespace app\controllers;

use Yii;
use app\models\PenunjangLain;
use app\models\PenunjangLainSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\UploadedFile;

/**
 * PenunjangLainController implements the CRUD actions for PenunjangLain model.
 */
class PenunjangLainController extends AppController
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

    public function actionAjaxList()
    {
        $dataPost = $_POST['dataPost'];
        
        $session = Yii::$app->session;
        $tahun_id = '';
        $sd = '';
        $ed = '';
        $bkd_periode = null;
        if($session->has('bkd_periode'))
        {
          $tahun_id = $session->get('bkd_periode');
          // $session->get('bkd_periode_nama',$bkd_periode->nama_periode);
          $sd = $session->get('tgl_awal');
          $ed = $session->get('tgl_akhir');  
          $bkd_periode = \app\models\BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
        }
        else{
          $bkd_periode = \app\models\BkdPeriode::find()->where(['buka' => 'Y'])->one();
          $tahun_id = $bkd_periode->tahun_id;
          $sd = $bkd_periode->tanggal_bkd_awal;
          $ed = $bkd_periode->tanggal_bkd_akhir;
        }

        $query = PenunjangLain::find();
        $query->where([
          'NIY' => Yii::$app->user->identity->NIY,
        ]);
        $tgl = date('Y-m-d');
        $query->andWhere('"'.$sd.'" BETWEEN tanggal_mulai AND tanggal_selesai');

        $results = [];

        foreach($query->all() as $item)
        {
            $results[] = [
                'id' =>$item->id,
                'peran' => $item->jenisPanitia->nama,
                'nama_kegiatan' => $item->nama_kegiatan,
                'instansi' => $item->instansi,
                'tanggal_mulai' => $item->tanggal_mulai,
                'tanggal_selesai' => $item->tanggal_selesai,
                'sks_bkd' => $item->sks_bkd,
                'is_claimed' => $item->is_claimed
            ];
        }

        echo \yii\helpers\Json::encode($results);
        die();
    }

    /**
     * Lists all PenunjangLain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenunjangLainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PenunjangLain model.
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
     * Creates a new PenunjangLain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PenunjangLain();
        
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
       
        $model->NIY = Yii::$app->user->identity->NIY;

        $list_jenis = \app\models\JenisPanitia::find()->all();


        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $s3config = Yii::$app->params['s3'];

        $s3 = new \Aws\S3\S3Client($s3config);
        $errors = '';
        try 
        {
            if ($model->load(Yii::$app->request->post())) 
            {
                $komponen = \app\models\KomponenKegiatan::findOne($model->komponen_kegiatan_id);
                if(empty($komponen))
                {
                    $errors .= 'Komponen BKD wajib diisi';
                    throw new \Exception;
                }

                $model->file_path = UploadedFile::getInstance($model,'file_path');
                
                if($model->file_path)
                {
                  $file_path = $model->file_path->tempName;
                  $mime_type = $model->file_path->type;
                  $file = 'PENUNJANG_LAIN_'.$model->NIY.'_'.$model->tanggal_mulai.'_'.date('YmdHis').'.'.$model->file_path->extension;
                  $key = 'penunjang-lain/'.$file;
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

                
                $model->sks_bkd = $komponen->angka_kredit;
               

                if(!$model->save())
                {
                    $errors .= \app\helpers\MyHelper::logError($model);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['penunjang-lain/view', 'id' => $model->id]);
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            Yii::$app->session->setFlash('danger', $errors);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            Yii::$app->session->setFlash('danger', $errors);
        }

        return $this->render('create', [
            'model' => $model,
            'list_jenis' => $list_jenis,
        ]);
    }

    /**
     * Updates an existing PenunjangLain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->NIY = Yii::$app->user->identity->NIY;
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $list_jenis = \app\models\JenisPanitia::find()->all();
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $s3config = Yii::$app->params['s3'];

        $file_path = $model->file_path;

        $s3 = new \Aws\S3\S3Client($s3config);
        $errors = '';
        try 
        {
            if ($model->load(Yii::$app->request->post())) 
            {
                $komponen = \app\models\KomponenKegiatan::findOne($model->komponen_kegiatan_id);
                if(empty($komponen))
                {
                    $errors .= 'Komponen BKD wajib diisi';
                    throw new \Exception;
                }

                $model->file_path = UploadedFile::getInstance($model,'file_path');
                
                if($model->file_path)
                {
                  $file_path = $model->file_path->tempName;
                  $mime_type = $model->file_path->type;
                  $file = 'PENUNJANG_LAIN_'.$model->NIY.'_'.$model->tanggal_mulai.'_'.date('YmdHis').'.'.$model->file_path->extension;
                  $key = 'penunjang-lain/'.$file;
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
                
                $model->sks_bkd = $komponen->angka_kredit;
                
                if (empty($model->file_path)){
                     $model->file_path = $file_path;
                }

                if(!$model->save())
                {
                    $errors .= \app\helpers\MyHelper::logError($model);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['penunjang-lain/view', 'id' => $model->id]);
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            Yii::$app->session->setFlash('danger', $errors);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            Yii::$app->session->setFlash('danger', $errors);
        }

        return $this->render('update', [
            'model' => $model,
            'list_jenis' => $list_jenis,
        ]);
    }

    /**
     * Deletes an existing PenunjangLain model.
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
     * Finds the PenunjangLain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenunjangLain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenunjangLain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
