<?php

namespace app\controllers;

use Yii;
use app\models\Penghargaan;
use app\models\PenghargaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * PenghargaanController implements the CRUD actions for Penghargaan model.
 */
class PenghargaanController extends Controller
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
        

        $query = Penghargaan::find();
        $query->where([
          'NIY' => Yii::$app->user->identity->NIY,

        ]);

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

        $query->andFilterWhere(['between','tanggal',$sd, $ed]);
        $tmps = $query->all();

        $tmps = $query->all();

        $results = [];

        foreach($tmps as $tmp)
        {

            $bkd = \app\models\BkdDosen::find()->where([
                'tahun_id' => $tahun_id,
                'dosen_id' => Yii::$app->user->identity->ID,
                'komponen_id' => $tmp->komponen_kegiatan_id,
                'kondisi' => (string)$tmp->ID
            ])->one();

            $results[] = [
                'is_claimed' => !empty($bkd),
                'item' => $tmp,
                'tanggal' => date('d-m-Y',strtotime($tmp->tanggal)),
                'tingkat' => !empty($tmp->tingkatPenghargaan) ? $tmp->tingkatPenghargaan->nama : null,
                'sks_bkd' => !empty($tmp->komponenKegiatan) ? $tmp->komponenKegiatan->angka_kredit : null
            ];
        }
            
        echo \yii\helpers\Json::encode($results);
        die();
    }

    /**
     * Lists all Penghargaan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenghargaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

         if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $model = Penghargaan::findOne($id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['Penghargaan']);
            $post = ['Penghargaan' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
            // can save model or do something before saving model
                if($model->save())
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($model);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Penghargaan model.
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
     * Creates a new Penghargaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Penghargaan();
        $model->NIY = Yii::$app->user->identity->NIY;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $s3config = Yii::$app->params['s3'];

        $s3 = new \Aws\S3\S3Client($s3config);
        $errors = '';
        try 
        {
            if ($model->load(Yii::$app->request->post())) {
                

                $model->f_penghargaan = UploadedFile::getInstance($model,'f_penghargaan');
                $model->tahun = date('Y',strtotime($model->tanggal));

                if($model->f_penghargaan)
                {
                  $f_penghargaan = $model->f_penghargaan->tempName;
                  $mime_type = $model->f_penghargaan->type;
                  $file = 'PENGHARGAAN_'.$model->NIY.'_'.$model->tahun.'_'.date('YmdHis').'.'.$model->f_penghargaan->extension;
                  $key = 'penghargaan/'.$file;
                  $insert = $s3->putObject([
                       'Bucket' => 'dosen',
                       'Key'    => $key,
                       'Body'   => 'This is the Body',
                       'SourceFile' => $f_penghargaan,
                       'ContentType' => $mime_type
                  ]);
                  
                  $plainUrl = $s3->getObjectUrl('dosen', $key);
                  $model->f_penghargaan = $plainUrl;

                }

                
                $model->ver = 'Sudah Diverifikasi';
                if(!$model->save())
                {
                    $errors .= \app\helpers\MyHelper::logError($model);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['penghargaan/view', 'id' => $model->ID]);
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
        ]);
    }

    /**
     * Updates an existing Penghargaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $s3config = Yii::$app->params['s3'];

        $s3 = new \Aws\S3\S3Client($s3config);

        $f_penghargaan = $model->f_penghargaan;
        $errors = '';
        try 
        {
            if ($model->load(Yii::$app->request->post())) {
                

                $model->f_penghargaan = UploadedFile::getInstance($model,'f_penghargaan');
                $model->tahun = date('Y',strtotime($model->tanggal));

                if($model->f_penghargaan)
                {
                  $f_penghargaan = $model->f_penghargaan->tempName;
                  $mime_type = $model->f_penghargaan->type;
                  $file = 'PENGHARGAAN_'.$model->NIY.'_'.$model->tahun.'_'.date('YmdHis').'.'.$model->f_penghargaan->extension;
                  $key = 'penghargaan/'.$file;
                  $insert = $s3->putObject([
                       'Bucket' => 'dosen',
                       'Key'    => $key,
                       'Body'   => 'This is the Body',
                       'SourceFile' => $f_penghargaan,
                       'ContentType' => $mime_type
                  ]);
                  
                  $plainUrl = $s3->getObjectUrl('dosen', $key);
                  $model->f_penghargaan = $plainUrl;

                }

                if (empty($model->f_penghargaan)){
                     $model->f_penghargaan = $f_penghargaan;
                }

                $model->ver = 'Sudah Diverifikasi';
                if(!$model->save())
                {
                    $errors .= \app\helpers\MyHelper::logError($model);
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['penghargaan/view', 'id' => $model->ID]);
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
        ]);
    }

    /**
     * Deletes an existing Penghargaan model.
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
     * Finds the Penghargaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Penghargaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Penghargaan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
