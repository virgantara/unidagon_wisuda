<?php

namespace app\controllers;

use Yii;
use app\models\SisterFiles;
use app\models\Pengajaran;
use app\models\BkdPeriode;
use app\models\Verify;
use app\models\PengajaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\httpclient\Client;

/**
 * PengajaranController implements the CRUD actions for Pengajaran model.
 */
class PengajaranController extends AppController
{
    /**
     * @inheritdoc
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

    public function actionImportJurnal()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $list = Pengajaran::find()->where(['NIY'=>Yii::$app->user->identity->NIY])->all();
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $unsur = \app\models\UnsurKegiatan::findOne(1);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $counter = 0;
        $errors ='';
        try     
        {
          foreach($list as $model)
          {
            $params = [
                'jadwal_id' => $model->jadwal_id
            ];
            
            $response = $client->get('/jadwal/dosen/jurnal', $params,$headers)->send();
            $errors = '';
            if ($response->isOk) 
            {

                $results = $response->data['values'];
                $status = $response->data['status'];

                if($status == 200)
                {
                    foreach($results as $res)
                    {
                        $kondisi = 'CH'.$model->jadwal_id.'_'.$res['id'];    
                    

                        $catatan = \app\models\CatatanHarian::find()->where(['kondisi' => $kondisi])->one();
                        if(empty($catatan)){
                            $catatan = new \app\models\CatatanHarian;
                        }

                        $catatan->user_id = Yii::$app->user->identity->ID;
                        $catatan->unsur_id = $unsur->id;
                        $catatan->deskripsi = $unsur->nama.' pertemuan ke-'.$res['pertemuan_ke'].' matkul '.$model->matkul.' '.$model->sks.' di ruang '.$res['ruang'];
                        $catatan->is_selesai = '1';
                        $catatan->poin = 10;
                        $catatan->kondisi = $kondisi;
                        $catatan->tanggal = date('Y-m-d',strtotime($res['waktu']));
                        if($catatan->save())
                        {
                          $counter++;
                        }

                        else{
                          $errors .= \app\helpers\MyHelper::logError($catatan);
                          throw new \Exception;
                        }
                    }
                }
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

    public function actionAjaxJadwal()
    {
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        // $dataPost = $_POST['dataPost'];
        $results = [];
        // foreach($listTahun as $tahun)
        // {

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
          $bkd_periode = BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
        }
        else{
          $bkd_periode = BkdPeriode::find()->where(['buka' => 'Y'])->one();
          $tahun_id = $bkd_periode->tahun_id;
          $sd = $bkd_periode->tanggal_bkd_awal;
          $ed = $bkd_periode->tanggal_bkd_akhir;
        }
        $params = [
            'uuid' => Yii::$app->user->identity->uuid,
            'tahun' => $tahun_id
        ];

        $response = $client->get('/jadwal/dosen/uuid', $params,$headers)->send();
         // print_r($params);exit;
        if ($response->isOk) {
            $results = $response->data['values'];
           
        }

        // }

        echo \yii\helpers\Json::encode($results);
        die();
    }

    public function actionAjaxLocalJadwal()
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

        $query = Pengajaran::find();
        $query->where([
          'NIY' => Yii::$app->user->identity->NIY,
          'tahun_akademik' => $tahun_id
        ]);

        $results = $query->asArray()->all();
        // $results = [];



        // }

        echo \yii\helpers\Json::encode($results);
        die();
    }


    /**
     * Lists all Pengajaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $searchModel = new PengajaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pengajaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      $model = $this->findModel($id);
      
      $bukti_pengajaran = SisterFiles::find()->where([
        'parent_id' => $id,
        'keterangan_dokumen' => 'AJAR'
      ])->all(); 
      return $this->render('view', [
        'model' => $model,
        'bukti_pengajaran' => $bukti_pengajaran
      ]);
    }

    

    /**
     * Updates an existing Pengajaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      $model = $this->findModel($id);
      $fileBukti = new SisterFiles;
      
      $fileBukti->parent_id = $model->ID;
            $fileBukti->keterangan_dokumen = 'AJAR';
      $fileBuktiTautan = $fileBukti->tautan;
      $errors = '';

      $s3config = Yii::$app->params['s3'];

      $s3 = new \Aws\S3\S3Client($s3config);

      if ($fileBukti->load(Yii::$app->request->post())) 
      {
          $tmp = SisterFiles::find()->where([
            'parent_id' => $model->ID,
            'nama_jenis_dokumen' => $fileBukti->nama_jenis_dokumen,
            'keterangan_dokumen' => 'AJAR'
          ])->one();

          if(!empty($tmp))
          {
            $fileBukti = $tmp;
            $fileBukti->load(Yii::$app->request->post());
          }

          $connection = \Yii::$app->db;
          $transaction = $connection->beginTransaction();
          try 
          {
            $fileBukti->id_dokumen = \app\helpers\MyHelper::gen_uuid();
            $fileBukti->nama_dokumen = $fileBukti->nama_jenis_dokumen;
            $fileBukti->tautan =UploadedFile::getInstance($fileBukti,'tautan');

            if($fileBukti->tautan)
            {
              $fileBuktiTautan = $fileBukti->tautan->tempName;
              $mime_type = $fileBukti->tautan->type;
              $file = strtolower($fileBukti->nama_jenis_dokumen).'_'.$model->NIY.'.'.$fileBukti->tautan->extension;
              $fileBukti->jenis_file = $mime_type;
              $fileBukti->tanggal_upload = date('Y-m-d H:i:s');
              $key = 'pengajaran/'.$fileBukti->nama_jenis_dokumen.'/'.$model->tahun_akademik.'/'.$model->NIY.'/'.$file;
              $errors = '';

               
              $insert = $s3->putObject([
                   'Bucket' => 'dosen',
                   'Key'    => $key,
                   'Body'   => 'This is the Body',
                   'SourceFile' => $fileBuktiTautan,
                   'ContentType' => $mime_type
              ]);

              $plainUrl = $s3->getObjectUrl('dosen', $key);
              $fileBukti->tautan = $plainUrl;
            }

            if (empty($fileBukti->tautan)){
                $fileBukti->tautan = $sk_mengajar_tautan;
            }

            if($fileBukti->validate())
            {
              $fileBukti->save();
              $transaction->commit();
              Yii::$app->getSession()->setFlash('success','File successfully uploaded');
              return $this->redirect(['update','id'=>$id]);
            }

            else{
              $errors .= \app\helpers\MyHelper::logError($fileBukti);
              throw new \Exception;
            }
          }

          catch(\Exception $e)
          {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            Yii::$app->getSession()->setFlash('danger',$errors);
          } 
      }    

      return $this->render('update',[
        'model' => $model,
        'fileBukti' => $fileBukti,
        
      ]);
    }

    /**
     * Deletes an existing Pengajaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      $model = $this->findModel($id);
      $model->delete();
      
      
      return $this->redirect(['index']);
    }

    /**
     * Finds the Pengajaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pengajaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
      if (($model = Pengajaran::findOne($id)) !== null) {
        return $model;
      } else {
        throw new NotFoundHttpException('The requested page does not exist.');
      }
    }
    
    protected function findVer($id)
    {
      if (($very = Verify::findOne(['kategori'=>'12','ID_data'=>$id])) !== null) {
        return $very;
      } else {
        throw new NotFoundHttpException('The requested page does not exist.');
      }
    }
    
    // public function actionDownload($id) 
    // { 
    //   $download = Pengajaran::findOne($id); 
    //   $path=Yii::getAlias('@webroot').'/uploads/pengajaran/'.$download->f_penugasan;
    //   if (file_exists($path)) {
    //     echo 'sukese';
    //     return Yii::$app->response->sendFile($path);
    //   }else{
    //     echo 'file not exists...';
    //   }
    // }
    
    // public function actionDisplay($id) 
    // { 
    //   $download = Pengajaran::findOne($id); 
    //   $path=Yii::getAlias('@webroot').'/uploads/pengajaran/'.$download->f_penugasan;
    //   if (file_exists($path)) {
    //     echo 'sukese';
    //     return Yii::$app->response->sendFile($path,$download->f_penugasan,['inline'=>true]);
    //   }else{
    //     echo 'file not exists...';
    //   }
    // }
    
  }
