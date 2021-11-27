<?php

namespace app\controllers;

use Yii;
use app\models\SisterFiles;
use app\helpers\MyHelper;
use app\models\User;
use app\models\PenelitianAnggota;
use app\models\Penelitian;
use app\models\Verify;
use app\models\PenelitianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * PenelitianController implements the CRUD actions for Penelitian model.
 */
class PenelitianController extends AppController
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

    public function actionAjaxImport()
    {
        $start = microtime(true);
        
        $results = [];
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        
        
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $counter = 0;
        $errors ='';
        $results = [];

        $counter_insert = 0;
        $counter_update = 0;
        try {

            $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
            MyHelper::clearLogSync($user->NIY);
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
            $full_url = $sister_baseurl.'/penelitian';

            $response = $client->get($full_url, [
                'query' => [
                    'id_sdm' => $user->sister_id,
                ],
                
                'headers' => [
                    'Content-type' => 'application/json',
                    'Authorization' => 'Bearer '.$sisterToken
                ]

            ]); 
            
            
           
            $response = json_decode($response->getBody());

            $results = $response;
        
        
            foreach($results as $item)
            {
                

                $full_url = $sister_baseurl.'/penelitian/'.$item->id;
                $resp = $client->get($full_url, [
                    
                     'headers' => [
                        'Content-type' => 'application/json',
                        'Authorization' => 'Bearer '.$sisterToken
                    ]

                ]); 
                
                
                $detail = json_decode($resp->getBody());

                $model = Penelitian::find()->where([
                    'sister_id' => $item->id
                ])->one();

                if(empty($model)){
                    $model = new Penelitian;

                }

                if($model->isNewRecord)
                    $counter_insert++;
                else
                    $counter_update++;
                
                $model->NIY = Yii::$app->user->identity->NIY;
                $model->sister_id = $detail->id;
                $model->judul_penelitian_pengabdian = $detail->judul;
                $model->nama_skim = $detail->jenis_skim;
                $model->nama_tahun_ajaran = 'Tahun '.$detail->tahun_pelaksanaan;
                $model->durasi_kegiatan = $detail->lama_kegiatan;
                $model->tempat_kegiatan = $detail->lokasi;
                $model->tahun_usulan = $detail->tahun_usulan;
                $model->tahun_kegiatan = $detail->tahun_kegiatan;
                $model->tahun_dilaksanakan = $detail->tahun_pelaksanaan;
                $model->tahun_pelaksanaan_ke = $detail->tahun_pelaksanaan_ke;
                $model->dana_dikti = $detail->dana_dikti;
                $model->dana_pt = $detail->dana_perguruan_tinggi;
                $model->dana_institusi_lain = $detail->dana_institusi_lain;
                $model->no_sk_tugas = $detail->sk_penugasan;
                $model->tgl_sk_tugas = $detail->tanggal_sk_penugasan;
                $model->kategori_kegiatan_id = (string)$detail->id_kategori_kegiatan;
                    // print_r($res);exit;
                if($detail->dana_dikti > 0 || $detail->dana_institusi_lain > 0)
                {
                    $model->jenis_sumber_dana = 'dalam';   
                }

                else if($detail->dana_perguruan_tinggi > 0)
                {
                    $model->jenis_sumber_dana = 'mandiri';   
                }

                if($model->save())
                {

                    if(!empty($detail->dokumen))
                    {
                        foreach($detail->dokumen as $file)
                        {
                            $pf = SisterFiles::findOne($file->id);
                            if(empty($pf))
                                $pf = new SisterFiles;

                            $pf->id_dokumen = $file->id;
                            $pf->parent_id = $item->id;
                            $pf->nama_dokumen = $file->nama;
                            $pf->nama_file = $file->nama_file;
                            $pf->jenis_file = $file->jenis_file;
                            $pf->tanggal_upload = $file->tanggal_upload;
                            $pf->nama_jenis_dokumen = $file->jenis_dokumen;
                            $pf->tautan = $file->tautan;
                            $pf->keterangan_dokumen = $file->keterangan;

                            if(!$pf->save())
                            {
                                $errors .= 'PF: '.\app\helpers\MyHelper::logError($pf);
                                throw new \Exception;
                            }
                        }
                    }

                    $pa = \app\models\PenelitianAnggota::find()->where([
                        'penelitian_id' => $model->ID,
                        'NIY' => Yii::$app->user->identity->NIY
                    ])->one();

                    if(empty($pa))
                        $pa = new \app\models\PenelitianAnggota;

                    
                    $pa->NIY = Yii::$app->user->identity->NIY;
                    $pa->penelitian_id = $model->ID;
                    $pa->status_anggota = '-';
                    $pa->beban_kerja = '0';

                    if($pa->save())
                    {
                        $counter++;
                    }

                    else{
                        $errors .= 'PenelitianAnggota_ERR: '.\app\helpers\MyHelper::logError($model);
                        throw new \Exception;
                    }

                    
                }

                else
                {
                    $errors .= \app\helpers\MyHelper::logError($model);
                    throw new \Exception;
                }
            }

            $transaction->commit();
            $results = [
                'code' => 200,
                'message' => 'Total '.$counter_insert.' inserted, '.$counter_update.' updated'
                
            ];
        }

        catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        } 


        $time_elapsed_secs = microtime(true) - $start;
        $results['elapsed_time'] = $time_elapsed_secs;
        echo json_encode($results);
        die(); 


    }

    public function actionAjaxListAnggota()
    {
        $dataPost = $_POST['dataPost'];
        $query = PenelitianAnggota::find();
        $query->where([
          'penelitian_id' => $dataPost['penelitian_id'],
        ]);
        $list = $query->all();
        $results = [];

        foreach($list as $item)
        {
            $results[] = [
                'NIY' => $item->NIY,
                'nama' => $item->nIY->dataDiri->nama,
                'status_anggota' => $item->status_anggota,
                'beban_kerja' => $item->beban_kerja
            ];
        }

        echo \yii\helpers\Json::encode($results);
        die();
    }

    public function actionAjaxUpdateAuthor()
    {
        $list_peran = \app\helpers\MyHelper::getPeranPublikasi();
        $dataPost = $_POST['dataPost'];
        $model = PenelitianAnggota::find()->where([
            'penelitian_id' => $dataPost['penelitian_id'],
            'NIY' => $dataPost['NIY'],
        ])->one();
        if(!empty($model))
        {
            $user = User::find()->where([
                'NIY'=>$dataPost['NIY']
            ])->one();
            $model->NIY = $dataPost['NIY'];
            $model->penelitian_id = $dataPost['penelitian_id'];
            $model->status_anggota = $dataPost['status_anggota'];
            $model->beban_kerja = $dataPost['beban_kerja'];
            
            $results = [];
            if($model->save())
            {
                $results = [
                    'code' => 200,
                    'message' => 'Data Updated'
                ];
            }

            else
            {
                $results = [
                    'code' => 500,
                    'message' => \app\helpers\MyHelper::logError($model)
                ];
            }
        }
        
        else
        {
            $results = [
                'code' => 500,
                'message' => 'Author not found'
            ];
        }
        echo json_encode($results);
        die();
    }

    public function actionAjaxRemoveAuthor()
    {
        $list_peran = \app\helpers\MyHelper::getPeranPublikasi();
        $dataPost = $_POST['dataPost'];
        $model = PenelitianAnggota::find()->where([
            'penelitian_id' => $dataPost['penelitian_id'],
            'NIY' => $dataPost['NIY'],
        ])->one();
        
        $results = [];
        if(!empty($model))
        {
            $model->delete();
            $results = [
                'code' => 200,
                'message' => 'Data deleted'
            ];
        }

        else
        {
            $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($model)
            ];
        }

        echo json_encode($results);
        die();
    }

    public function actionAjaxAddAuthor()
    {
        $list_peran = \app\helpers\MyHelper::getPeranPublikasi();
        $dataPost = $_POST['dataPost'];
        $model = PenelitianAnggota::find()->where([
            'penelitian_id' => $dataPost['penelitian_id'],
            'NIY' => $dataPost['NIY'],
        ])->one();

        if(empty($model))
            $model = new PenelitianAnggota;

        $user = User::find()->where([
            'NIY'=>$dataPost['NIY']
        ])->one();
        $model->NIY = $dataPost['NIY'];
        $model->penelitian_id = $dataPost['penelitian_id'];
        $model->status_anggota = $dataPost['status_anggota'];
        $model->beban_kerja = $dataPost['beban_kerja'];
        
        $results = [];
        if($model->save())
        {
            $results = [
                'code' => 200,
                'message' => 'Data Added'
            ];
        }

        else
        {
            $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($model)
            ];
        }

        echo json_encode($results);
        die();
    }

    public function actionImport()
    {

        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        
        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 10.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/Penelitian';
        $response = $client->post($full_url, [
            'body' => json_encode([
                'id_token' => $sisterToken,
                'id_dosen' => $user->sister_id,
                'updated_after' => [
                    'tahun' => '2000',
                    'bulan' => '01',
                    'tanggal' => '01'
                ]
            ]), 
            'headers' => ['Content-type' => 'application/json']

        ]); 
        
        $results = [];
       
        $response = json_decode($response->getBody());
        
        if($response->error_code == 0)
        {
            $results = $response->data;
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $counter = 0;
            $errors ='';
            try     
            {
                foreach($results as $item)
                {
                    $model = Penelitian::find()->where([
                        'sister_id' => $item->id_penelitian_pengabdian
                    ])->one();

                    if(empty($model))
                        $model = new Penelitian;
                    $model->NIY = Yii::$app->user->identity->NIY;
                    $model->sister_id = $item->id_penelitian_pengabdian;
                    $model->judul_penelitian_pengabdian = $item->judul_penelitian_pengabdian;
                    $model->nama_skim = $item->nama_skim;
                    $model->nama_tahun_ajaran = $item->nama_tahun_ajaran;
                    $model->durasi_kegiatan = $item->durasi_kegiatan;

                    $full_url = $sister_baseurl.'/Penelitian/detail';
                    $resp = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_penelitian_pengabdian' => $model->sister_id
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]); 
                    
                    
                    $resp = json_decode($resp->getBody());
                    if($resp->error_code == 0){
                        $res = $resp->data;
                        $model->tahun_usulan = $res->nama_tahun_anggaran;
                        $model->tahun_kegiatan = $res->nama_tahun_anggaran;
                        $model->tahun_dilaksanakan = $res->nama_tahun_anggaran;
                        $model->tahun_pelaksanaan_ke = $res->tahun_pelaksanaan_ke;
                        $model->dana_dikti = $res->dana_dari_dikti;
                        $model->dana_pt = $res->dana_dari_PT;
                        $model->dana_institusi_lain = $res->dana_dari_instansi_lain;
                        // print_r($res);exit;
                    }

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


        else
        {
            Yii::$app->getSession()->setFlash('danger',json_encode($response));
            return $this->redirect(['index']);
        }


    }

    /**
     * Lists all Penelitian models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }else{  
        $searchModel = new PenelitianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }
    }

    /**
     * Displays a single Penelitian model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $searchModel = new \app\models\PenelitianAnggotaSearch();
        $searchModel->penelitian_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        // $sisterToken = \app\helpers\MyHelper::getSisterToken();
        // // if(!isset($sisterToken)){
        // //     $sisterToken = MyHelper::getSisterToken();
        // // }

        // // 
        // $sister_baseurl = Yii::$app->params['sister_baseurl'];
        // $headers = ['content-type' => 'application/json'];
        // $client = new \GuzzleHttp\Client([
        //     'timeout'  => 5.0,
        //     'headers' => $headers,
        //     // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        // ]);
        // $full_url = $sister_baseurl.'/Penelitian/detail';
        // $response = $client->post($full_url, [
        //     'body' => json_encode([
        //         'id_token' => $sisterToken,
        //         'id_dosen' => $user->sister_id,
        //         'id_penelitian_pengabdian' => $model->sister_id
        //     ]), 
        //     'headers' => ['Content-type' => 'application/json']

        // ]); 
        
        // $results = [];
       
        // $response = json_decode($response->getBody());
        // if($response->error_code == 0){
        //     $results = $response->data;
        // }

        
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Penelitian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $model = new Penelitian();
        $model->NIY = Yii::$app->user->identity->NIY;
       

        if ($model->load(Yii::$app->request->post())) 
        {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $counter = 0;
            $errors ='';
            try     
            {

                if($model->save())
                {   
                    // $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
                    // $sisterToken = \app\helpers\MyHelper::getSisterToken();
                    // $sister_baseurl = Yii::$app->params['sister_baseurl'];
                    // $sister_lembaga_iptek = Yii::$app->params['sister_lembaga_iptek'];
                    // $headers = ['content-type' => 'application/json'];
                    // $client = new \GuzzleHttp\Client([
                    //     'timeout'  => 5.0,
                    //     'headers' => $headers,
                    //     // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
                    // ]);
                    // $full_url = $sister_baseurl.'/Penelitian/tambah';
                    // $data = [
                    //     'id_penelitian_pengabdian' => 0,
                    //     'id_kategori_kegiatan' => $model->kategori_kegiatan_id,
                    //     'judul_penelitian_pengabdian' => $model->judul_penelitian_pengabdian,
                    //     'id_lembaga_iptek' => $sister_lembaga_iptek,
                    //     'id_kelompok_bidang' => $model->kelompok_bidang_id,
                    //     'id_jenis_skim' => $model->skim_kegiatan_id,
                    //     'tempat_kegiatan' => $model->tempat_kegiatan,
                    //     'tahun_usulan' => $model->tahun_usulan,
                    //     'tahun_kegiatan' => $model->tahun_kegiatan,
                    //     'tahun_dilaksanakan' => $model->tahun_dilaksanakan,
                    //     'durasi_kegiatan' => $model->durasi_kegiatan,
                    //     'tahun_pelaksanaan_ke' => $model->tahun_pelaksanaan_ke,
                    //     'dana_dari_dikti' => $model->dana_dikti,
                    //     'dana_dari_instansi_lain' => $model->dana_institusi_lain,
                    //     'dana_dari_PT' => $model->dana_pt,
                    //     'no_sk_tugas' => $model->no_sk_tugas,
                    //     'tanggal_sk_penugasan' => date('d/m/Y',strtotime($model->tgl_sk_tugas)),
                    //     'tanggal_sk_penugasan' => [
                    //         'tanggal_sk_penugasan_tanggal' => date('d',strtotime($model->tgl_sk_tugas)),
                    //         'tanggal_sk_penugasan_tahun' => date('Y',strtotime($model->tgl_sk_tugas)),
                    //         'tanggal_sk_penugasan_bulan' => date('m',strtotime($model->tgl_sk_tugas)),
                    //     ],
                        
                        
                    //     // 'tgl_sk_tugas' => date('d/m/Y',strtotime($model->tgl_sk_tugas)),
                    // ];
                    // echo '<pre>';
                    // // print_r($sisterToken);
                    // // echo '<br>';
                    // // print_r($user->sister_id);
                    // // echo '<br>';
                    // print_r($data);
                    // echo '</pre>';
                    // exit;
                    // $response = $client->post($full_url, [
                    //     'body' => json_encode([
                    //         'id_token' => $sisterToken,
                    //         'id_dosen' => $user->sister_id,
                    //         'data' => $data
                    //     ]), 
                    //     'headers' => ['Content-type' => 'application/json']

                    // ]); 
                    
                    // $results = [];
                   
                    // $response = json_decode($response->getBody());
                    
                    // if($response->error_code == 0)
                    // {
                    //     $results = $response->data;
                
                    //     $model->sister_id = $results;
                    //     $model->save();
                    //     $transaction->commit();
                    //     Yii::$app->getSession()->setFlash('success','Data successfully added');
                    //     return $this->redirect(['index']);
                    // }

                    // else{
                    //     // print_r($response);exit;
                    //     $errors .= 'SIST_ERR: '.json_encode($response);
                    //     throw new \Exception;
                    // }
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success','Data successfully added');
                    return $this->redirect(['index']);
       
                }

                else
                {
                    $errors .= \app\helpers\MyHelper::logError($model);        
                    throw new \Exception;
                }
            }

            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->getSession()->setFlash('danger',$errors);
                // return $this->redirect(['create']);
            } 
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Penelitian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $model->NIY = Yii::$app->user->identity->NIY;

        if ($model->load(Yii::$app->request->post())) 
        {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $counter = 0;
            $errors ='';
            try     
            {

                if($model->save())
                {   
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success','Data successfully added');
                    return $this->redirect(['index']);
       
                }

                else
                {
                    $errors .= \app\helpers\MyHelper::logError($model);        
                    throw new \Exception;
                }
            }

            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->getSession()->setFlash('danger',$errors);
                // return $this->redirect(['create']);
            } 
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Penelitian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
       
        return $this->redirect(['index']);
    }

    /**
     * Finds the Penelitian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Penelitian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Penelitian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findVer($id)
    {
        if (($very = Verify::findOne(['kategori'=>'10','ID_data'=>$id])) !== null) {
            return $very;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDownload($id) 
   { 
    $download = Penelitian::findOne($id); 
    $path=Yii::getAlias('@webroot').'/uploads/'.$download->NIY.'/penelitian/'.$download->f_penelitian;
    if (file_exists($path)) {
        return Yii::$app->response->sendFile($path);
    }else{
        echo 'file not exists...';
    }
   }
    
    public function actionDisplay($id) 
   { 
    $download = Penelitian::findOne($id); 
    $path=Yii::getAlias('@webroot').'/uploads/'.$download->NIY.'/penelitian/'.$download->f_penelitian;
    if (file_exists($path)) {
        return Yii::$app->response->sendFile($path,$download->f_penelitian,['inline'=>true]);
    }else{
        echo 'file not exists...';
    }
   }
}
