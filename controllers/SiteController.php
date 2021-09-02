<?php
namespace app\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\Buku;
use app\models\DataDiri;
use app\models\Hki;
use app\models\LoginForm;
use app\models\Pengajaran;
use app\models\CatatanHarian;
use app\models\Organisasi;
use app\models\PengelolaJurnal;
use app\models\OrasiIlmiah;
use app\models\VisitingScientist;
use app\models\TugasDosenBkd;
use app\models\Pembicara;
use app\models\Penelitian;
use app\models\Publikasi;
use app\models\Pengabdian;
use app\models\Penghargaan;
use app\models\MasterLevel;
use app\models\GameLevelClass;
use app\models\Prodi;
use app\models\Tendik;
use app\models\User;
use app\models\SisterFiles;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use \Firebase\JWT\JWT;
use yii\httpclient\Client;

/**
 * Site controller
 */
class SiteController extends AppController
{
    public $successUrl = '';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['logout', 'signup','testing'],
                'rules' => [
                    [
                        'actions' => [
                            'testing'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                    [
                        'actions' => ['signup','test'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => $this->successUrl
            ],
        ];
    }

    public function actionTest(){
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        print_r($user->sister_id);exit;
    }

    public function actionAjaxImport()
    {
        
        $errors ='';
        $results = [];
        
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);

        if(empty($user->sister_id))
        {
            $results = [
                'code' => 404,
                'message' => 'Oops, data Anda belum dipetakan'
            ];
        }

        else
        {
            $res1 = $this->importPengabdian();
            $res2 = $this->importPenelitian();
            $res3 = $this->importPenugasan();
            $res4 = $this->importInpassing();
            $res5 = $this->importJurnal();
            $res6 = $this->importPengajaran();
            $res7 = $this->importPublikasi();
            $res8 = $this->importPengelolaJurnal();
            $res9 = $this->importOrasiIlmiah();
            $res10 = $this->importVisitingScientist();
            $res11 = $this->importBahanAjar();
            $res12 = $this->importHki();
            $res13 = $this->importPembicara();
            
            $code = $res1['code'];
            
            $results = [
                'code' => $code,
                'items' => [
                    [
                        'modul' => 'pengabdian',
                        'data' => $res1['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'penelitian',
                        'data' => $res2['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'penugasan',
                        'data' => $res3['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'inpassing',
                        'data' => $res4['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'jurnal_pengajaran',
                        'data' => $res5['message'],
                        'source' => 'SIAKAD'
                    ],
                    [
                        'modul' => 'pengajaran',
                        'data' => $res6['message'],
                        'source' => 'SIAKAD'
                    ],
                    [
                        'modul' => 'publikasi',
                        'data' => $res7['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'pengelola_jurnal',
                        'data' => $res8['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'orasi_ilmiah',
                        'data' => $res9['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'visiting_scientist',
                        'data' => $res10['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'bahan_ajar',
                        'data' => $res11['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'hki',
                        'data' => $res12['message'],
                        'source' => 'SISTER'
                    ],
                    [
                        'modul' => 'pembicara',
                        'data' => $res13['message'],
                        'source' => 'SISTER'
                    ],
                    
                ]
            ];

        }
        echo json_encode($results);
        die(); 
    }

    

    protected function importPembicara()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = \app\helpers\MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/Pembicara';
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
        $counter = 0;
        $errors ='';    
        $response = json_decode($response->getBody());
        
        if($response->error_code == 0)
        {
            $results = $response->data;
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            
            try     
            {
                foreach($results as $item)
                {
                    $full_url = $sister_baseurl.'/Pembicara/detail';
                    $response = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_riwayat_pembicara_orasi' => $item->id_riwayat_pembicara_orasi
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]); 
                    
                    
                    $response = json_decode($response->getBody());
                    if($response->error_code == 0){
                        $detail = $response->data;
                        // echo '<pre>';
                        // print_r($detail);
                        // echo '</pre>';
                        // exit;

                        $model = Pembicara::find()->where([
                            'sister_id' => $item->id_riwayat_pembicara_orasi
                        ])->one();

                        if(empty($model))
                            $model = new Pembicara;

                        $model->NIY = Yii::$app->user->identity->NIY;
                        $model->sister_id = $item->id_riwayat_pembicara_orasi;
                        $model->nama_kategori_kegiatan = $detail->nama_kategori_kegiatan;
                        $model->nama_kategori_pencapaian = $detail->nama_kategori_pencapaian;
                        $model->judul_makalah = $detail->judul_buku_makalah;
                        $model->nama_pertemuan_ilmiah = $detail->nama_pertemuan_ilmiah;
                        $model->penyelenggara_kegiatan = $detail->penyelenggara_kegiatan;
                        $model->tanggal_pelaksanaan = $detail->tanggal_pelaksanaan;
                        $model->id_kategori_kegiatan = (string)$detail->id_kategori_kegiatan;
                        $model->id_kategori_pembicara = $detail->id_kategori_pembicara;
                        $model->id_kategori_capaian_luaran = $detail->id_kategori_capaian_luaran;
                        $model->no_sk_tugas = $detail->no_sk_tugas;
                        $model->tanggal_sk_penugasan = $detail->tanggal_sk_penugasan;
                        $model->bahasa = $detail->bahasa;

                        if($model->save())
                        {
                            $counter++;

                        
                            if(!empty($results->files))
                            {
                                foreach($results->files as $file)
                                {
                                    $pf = SisterFiles::findOne($file->id_dokumen);
                                    if(empty($pf))
                                        $pf = new SisterFiles;

                                    $pf->id_dokumen = $file->id_dokumen;
                                    $pf->parent_id = $item->id_riwayat_pembicara_orasi;
                                    $pf->nama_dokumen = $file->nama_dokumen;
                                    $pf->nama_file = $file->nama_file;
                                    $pf->jenis_file = $file->jenis_file;
                                    $pf->tanggal_upload = $file->tanggal_upload;
                                    $pf->nama_jenis_dokumen = $file->nama_jenis_dokumen;
                                    $pf->tautan = $file->tautan;
                                    $pf->keterangan_dokumen = $file->keterangan_dokumen;

                                    if(!$pf->save())
                                    {
                                        $errors .= 'PF: '.\app\helpers\MyHelper::logError($pf);
                                        throw new \Exception;
                                    }
                                }
                            }
                            

                        }

                        else
                        {
                            $errors .= \app\helpers\MyHelper::logError($model);
                            throw new \Exception;
                        }
                    }
                    
                    
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importHki()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = \app\helpers\MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/Paten';
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
                    $full_url = $sister_baseurl.'/Paten/detail';
                    $response = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_riwayat_publikasi_paten' => $item->id_riwayat_publikasi_paten
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]);

                    $response = json_decode($response->getBody());
                    if($response->error_code == 0)
                    {
                        $detail = $response->data;
                        
                        $model = Hki::find()->where([
                            'sister_id' => $item->id_riwayat_publikasi_paten
                        ])->one();

                        if(empty($model))
                            $model = new Hki;

                        $model->NIY = Yii::$app->user->identity->NIY;
                        $model->sister_id = $item->id_riwayat_publikasi_paten;
                        $model->judul = $item->judul_publikasi_paten;
                        $model->nama_jenis_publikasi = $item->nama_jenis_publikasi;
                        $model->tanggal_terbit = $item->tanggal_terbit;
                        $model->tahun_pelaksanaan = date('Y',strtotime($item->tanggal_terbit));
                        $model->ver = 'Sudah diverifikasi';

                        if($model->save())
                        {

                            $author = \app\models\HkiAuthor::find()->where([
                                'hki_id' => $model->id,
                                'NIY' => $model->NIY
                            ])->one();

                            if(empty($author))
                                $author = new \app\models\HkiAuthor;
                            $author->hki_id = $model->id;
                            $author->NIY = $model->NIY;
                            
                            if(!$author->save())
                            {
                                foreach($author->getErrors() as $attribute){
                                    foreach($attribute as $error){
                                        $errors .= $error.' ';
                                    }
                                }
                                
                                throw new \Exception;
                            }
                            $counter++;


                        }

                        else
                        {
                            $errors .= \app\helpers\MyHelper::logError($model);
                            throw new \Exception;
                        }

                        if(!empty($detail->files))
                        {
                            foreach($detail->files as $file)
                            {
                                $pf = SisterFiles::findOne($file->id_dokumen);
                                if(empty($pf))
                                    $pf = new SisterFiles;

                                $pf->id_dokumen = $file->id_dokumen;
                                $pf->parent_id = $item->id_riwayat_publikasi_paten;
                                $pf->nama_dokumen = $file->nama_dokumen;
                                $pf->nama_file = $file->nama_file;
                                $pf->jenis_file = $file->jenis_file;
                                $pf->tanggal_upload = $file->tanggal_upload;
                                $pf->nama_jenis_dokumen = $file->nama_jenis_dokumen;
                                $pf->tautan = $file->tautan;
                                $pf->keterangan_dokumen = $file->keterangan_dokumen;

                                if(!$pf->save())
                                {
                                    $errors .= 'BK: '.\app\helpers\MyHelper::logError($pf);
                                    throw new \Exception;
                                }
                            }
                        }
                    }

                   
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importBahanAjar()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/BahanAjar';
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
                   
                    
                    $full_url = $sister_baseurl.'/BahanAjar/detail';
                    $response = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_riwayat_bahan_ajar' => $item->id_riwayat_bahan_ajar
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]);

                    $response = json_decode($response->getBody());
                    if($response->error_code == 0)
                    {
                        $detail = $response->data;
                        

                        // if($detail->id_kategori_capaian_luaran == 'Buku')
                        // {
                            $model = \app\models\Buku::find()->where([
                                'sister_id' => $item->id_riwayat_bahan_ajar
                            ])->one();

                            if(empty($model))
                                $model = new \app\models\Buku;


                            $model->NIY = Yii::$app->user->identity->NIY;
                            $model->sister_id = $detail->id_riwayat_bahan_ajar;
                            $model->judul = $detail->judul_bahan_ajar;
                            $model->penerbit = $detail->nama_penerbit;
                            $model->ISBN = $detail->ISBN_bahan_ajar;
                            $model->tanggal_terbit = $detail->tanggal_terbit;
                            $model->tahun = date('Y',strtotime($detail->tanggal_terbit));
                            $model->id_kategori_capaian_luaran = $detail->id_kategori_capaian_luaran;
                            $model->id_jenis_bahan_ajar = $detail->id_jenis_bahan_ajar;
                            $model->no_sk_tugas = $detail->no_sk_tugas;
                            $model->tanggal_sk_penugasan = $detail->tanggal_sk_penugasan;
                            $model->ver = 'Sudah diverifikasi';
                            $model->nama_kategori_kegiatan = $detail->nama_kategori_kegiatan;
                            
                            if($model->save())
                            {
                                $author = \app\models\BukuAuthor::find()->where([
                                    'buku_id' => $model->ID,
                                    'NIY' => $model->NIY
                                ])->one();
                                
                                if(empty($author))
                                    $author = new \app\models\BukuAuthor;
                                
                                $author->buku_id = $model->ID;
                                $author->NIY = $model->NIY;
                                if(!$author->save())
                                {
                                    foreach($author->getErrors() as $attribute){
                                        foreach($attribute as $error){
                                            $errors .= $error.' ';
                                        }
                                    }
                                    
                                    throw new \Exception;
                                }

                                if(!empty($detail->files))
                                {
                                    foreach($detail->files as $file)
                                    {
                                        $pf = SisterFiles::findOne($file->id_dokumen);
                                        if(empty($pf))
                                            $pf = new SisterFiles;

                                        $pf->id_dokumen = $file->id_dokumen;
                                        $pf->parent_id = $item->id_riwayat_bahan_ajar;
                                        $pf->nama_dokumen = $file->nama_dokumen;
                                        $pf->nama_file = $file->nama_file;
                                        $pf->jenis_file = $file->jenis_file;
                                        $pf->tanggal_upload = $file->tanggal_upload;
                                        $pf->nama_jenis_dokumen = $file->nama_jenis_dokumen;
                                        $pf->tautan = $file->tautan;
                                        $pf->keterangan_dokumen = $file->keterangan_dokumen;

                                        if(!$pf->save())
                                        {
                                            $errors .= 'BK: '.\app\helpers\MyHelper::logError($pf);
                                            throw new \Exception;
                                        }
                                    }
                                }
                                $counter++;
                          
                            }

                            else
                            {
                                $errors .= \app\helpers\MyHelper::logError($model);
                                throw new \Exception;
                            }
                        // }

                        
                    } 
                    

                    
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }


    protected function importVisitingScientist()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/VisitingScientist';
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
                   
                    
                    $full_url = $sister_baseurl.'/VisitingScientist/detail';
                    $response = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_riwayat_visitingscientist' => $item->id_riwayat_visitingscientist
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]);

                    $response = json_decode($response->getBody());
                    if($response->error_code == 0)
                    {
                        $detail = $response->data;
                        // echo '<pre>';
                        // print_r($item);
                        // print_r($detail);
                        // echo '</pre>';
                        // exit;

                        $model = \app\models\VisitingScientist::find()->where([
                            'sister_id' => $item->id_riwayat_visitingscientist
                        ])->one();

                        if(empty($model))
                            $model = new \app\models\VisitingScientist;


                        $model->NIY = Yii::$app->user->identity->NIY;
                        $model->sister_id = $detail->id_riwayat_visitingscientist;
                        $model->perguruan_tinggi_pengundang = $detail->perguruan_tinggi_pengundang;
                        $model->durasi_kegiatan = $detail->durasi_kegiatan;
                        $model->tanggal_pelaksanaan = $detail->tanggal_pelaksanaan;
                        $model->kategori_kegiatan_id = (string)$detail->id_kategori_kegiatan;
                        $model->nama_penelitian_pengabdian = $detail->nama_penelitian_pengabdian;
                        $model->id_penelitian_pengabdian = $detail->id_penelitian_pengabdian;
                        $model->nama_kategori_pencapaian = $detail->nama_kategori_pencapaian;
                        $model->id_kategori_capaian_luaran = $detail->id_kategori_capaian_luaran;
                        $model->id_universitas = $detail->id_universitas;
                        $model->no_sk_tugas = $detail->no_sk_tugas;
                        $model->tanggal_sk_penugasan = $detail->tanggal_sk_penugasan;
                        $model->durasi = $detail->durasi;
                        $model->kegiatan_penting_yang_dilakukan = $detail->kegiatan_penting_yang_dilakukan;

                        
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
                    

                    
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importOrasiIlmiah()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/OrasiIlmiah';
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
                   
                    
                    $full_url = $sister_baseurl.'/OrasiIlmiah/detail';
                    $response = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_riwayat_pembicara_orasi' => $item->id_riwayat_pembicara_orasi
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]);

                    $response = json_decode($response->getBody());
                    if($response->error_code == 0)
                    {
                        $detail = $response->data;
                        // echo '<pre>';
                        // print_r($item);
                        // print_r($detail);
                        // echo '</pre>';
                        // exit;

                        $model = \app\models\OrasiIlmiah::find()->where([
                            'sister_id' => $item->id_riwayat_pembicara_orasi
                        ])->one();

                        if(empty($model))
                            $model = new \app\models\OrasiIlmiah;


                        $model->NIY = Yii::$app->user->identity->NIY;
                        $model->sister_id = $detail->id_riwayat_pembicara_orasi;
                        $model->nama_kategori_kegiatan = $detail->nama_kategori_kegiatan;
                        $model->id_kategori_pembicara = $detail->id_kategori_pembicara;
                        $model->id_kategori_capaian_luaran = $detail->id_kategori_capaian_luaran;
                        $model->nama_kategori_pencapaian = $detail->nama_kategori_pencapaian;
                        $model->kategori_kegiatan_id = (string)$detail->id_kategori_kegiatan;
                        $model->judul_buku_makalah = $detail->judul_buku_makalah;
                        $model->nama_pertemuan_ilmiah = $detail->nama_pertemuan_ilmiah;
                        $model->penyelenggara_kegiatan = $detail->penyelenggara_kegiatan;
                        $model->tanggal_pelaksanaan = $detail->tanggal_pelaksanaan;
                        $model->no_sk_tugas = $detail->no_sk_tugas;
                        $model->tanggal_sk_penugasan = $detail->tanggal_sk_penugasan;
                        $model->bahasa = $detail->bahasa;
                        
                        if(!empty($detail->files))
                        {
                            foreach($detail->files as $file)
                            {
                                $pf = SisterFiles::findOne($file->id_dokumen);
                                if(empty($pf))
                                    $pf = new SisterFiles;

                                $pf->id_dokumen = $file->id_dokumen;
                                $pf->parent_id = $item->id_riwayat_pembicara_orasi;
                                $pf->nama_dokumen = $file->nama_dokumen;
                                $pf->nama_file = $file->nama_file;
                                $pf->jenis_file = $file->jenis_file;
                                $pf->tanggal_upload = $file->tanggal_upload;
                                $pf->nama_jenis_dokumen = $file->nama_jenis_dokumen;
                                $pf->tautan = $file->tautan;
                                $pf->keterangan_dokumen = $file->keterangan_dokumen;

                                if(!$pf->save())
                                {
                                    $errors .= 'OI: '.\app\helpers\MyHelper::logError($pf);
                                    throw new \Exception;
                                }
                            }
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
                    

                    
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importPengelolaJurnal()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/PengelolaJurnal';
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
                   
                    // print_r($item);exit;
                    $model = \app\models\PengelolaJurnal::find()->where([
                        'sister_id' => $item->id_riwayat_pengelola_jurnal
                    ])->one();

                    if(empty($model))
                        $model = new \app\models\PengelolaJurnal;


                    $model->NIY = Yii::$app->user->identity->NIY;
                    $model->sister_id = $item->id_riwayat_pengelola_jurnal;
                    $model->peran_dalam_kegiatan = $item->peran_dalam_kegiatan;
                    $model->no_sk_tugas = $item->no_sk_tugas;
                    $model->apakah_masih_aktif = $item->apakah_masih_aktif;
                    $model->nama_media_publikasi = $item->nama_media_publikasi;
                    $model->kategori_kegiatan_id = (string)$item->id_kategori_kegiatan;
                    $model->tgl_sk_tugas = $item->sk_penugasan_terhitung_mulai_tanggal;
                    $model->tgl_sk_tugas_selesai = $item->tanggal_berakhir_sk;

                    

                    if($model->save())
                    {
                        $counter++;
                        
                        // $full_url = $sister_baseurl.'/PengelolaJurnal/detail';
                        // $response = $client->post($full_url, [
                        //     'body' => json_encode([
                        //         'id_token' => $sisterToken,
                        //         'id_dosen' => $user->sister_id,
                        //         'id_riwayat_pengelola_jurnal' => $item->id_riwayat_pengelola_jurnal
                        //     ]), 
                        //     'headers' => ['Content-type' => 'application/json']

                        // ]);

                        // $response = json_decode($response->getBody());
                        // if($response->error_code == 0)
                        // {
                        //     $detail = $response->data;
                        //     print_r($detail);exit;
                        // } 
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
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importPublikasi()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $list_peran = \app\helpers\MyHelper::getPeranPublikasi();
        $flipped_list_peran = array_flip($list_peran);
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
       
        ]);
        $full_url = $sister_baseurl.'/Publikasi';
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
        if($response->error_code == 0){
            $results = $response->data;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
           
            $errors ='';
            try     
            {
                $counter = 0;
                foreach($results as $item)
                {

                    $jenisPublikasi = \app\models\JenisPublikasi::find()->where(['nama'=> $item->nama_jenis_publikasi])->one();

                    if(empty($jenisPublikasi)){
                        $errors .= 'Jenis Publikasi '.$item->nama_jenis_publikasi.' belum ada di database';
                        throw new \Exception;
                    }
                        
                    

                    $model = \app\models\Publikasi::find()->where([
                        'sister_id' => $item->id_riwayat_publikasi_paten
                    ])->one();

                    if(empty($model))
                        $model = new \app\models\Publikasi;


                    $model->NIY = Yii::$app->user->identity->NIY;
                    $model->sister_id = $item->id_riwayat_publikasi_paten;
                    $model->judul_publikasi_paten = $item->judul_publikasi_paten;
                    $model->nama_jenis_publikasi = $item->nama_jenis_publikasi;
                    $model->jenis_publikasi_id = $jenisPublikasi->id;
                    
                    $model->tanggal_terbit = $item->tanggal_terbit;
                    $full_url = $sister_baseurl.'/Publikasi/detail';
                    $response = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_riwayat_publikasi_paten' => $item->id_riwayat_publikasi_paten
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]); 
                    
                    $results = [];
                   
                    $response = json_decode($response->getBody());
                    if($response->error_code == 0){
                        $detail = $response->data;

                        $model->tautan_laman_jurnal = $detail->tautan_laman_jurnal;
                        $model->tautan = $detail->tautan;
                        $model->volume = $detail->volume;
                        $model->nomor = $detail->nomor_hasil_publikasi;
                        $model->halaman = $detail->halaman;
                        $model->penerbit = $detail->nama_penerbit;
                        $model->doi = $detail->DOI_publikasi;
                        $model->issn = $detail->ISSN_publikasi;    
                        $model->nama_kategori_kegiatan = $detail->nama_kategori_kegiatan;
                        $kategoriKegiatan = \app\models\KategoriKegiatan::find()->where(['nama'=> $detail->nama_kategori_kegiatan])->one();



                        if(empty($kategoriKegiatan)){
                            $errors .= 'KategoriKegiatan '.$detail->nama_kategori_kegiatan.' belum ada di database';
                            throw new \Exception;
                        }

                        $model->kategori_kegiatan_id = $kategoriKegiatan->id;

                        

                        if(!empty($detail->files))
                        {
                            foreach($detail->files as $file)
                            {
                                $pf = \app\models\SisterFiles::findOne($file->id_dokumen);
                                if(empty($pf))
                                    $pf = new \app\models\SisterFiles;

                                $pf->id_dokumen = $file->id_dokumen;
                                $pf->parent_id = $item->id_riwayat_publikasi_paten;
                                $pf->nama_dokumen = $file->nama_dokumen;
                                $pf->nama_file = $file->nama_file;
                                $pf->jenis_file = $file->jenis_file;
                                $pf->tanggal_upload = $file->tanggal_upload;
                                $pf->nama_jenis_dokumen = $file->nama_jenis_dokumen;
                                $pf->tautan = $file->tautan;
                                $pf->keterangan_dokumen = $file->keterangan_dokumen;

                                if(!$pf->save())
                                {
                                    $errors .= 'PF: '.\app\helpers\MyHelper::logError($pf);
                                    throw new \Exception;
                                }
                            }
                        }
                    }



                    if($model->save())
                    {
                        foreach($detail->data_penulis as $author)
                        {
                            // print_r($author);exit;
                            $pa = \app\models\PublikasiAuthor::find()->where([
                                'author_id' => $author->id_dosen,
                                'publikasi_id' => $item->id_riwayat_publikasi_paten
                            ])->one();

                            if(empty($pa))
                                $pa = new \app\models\PublikasiAuthor;

                            $dd = DataDiri::find()->where(['sister_id' => $author->id_dosen])->one();

                            $pa->pub_id = $model->id;
                            $pa->NIY = !empty($dd) ? $dd->NIY : Yii::$app->user->identity->NIY;
                            $pa->author_id = $author->id_dosen;
                            $pa->author_nama = $author->nama;
                            $pa->publikasi_id = $item->id_riwayat_publikasi_paten;
                            $pa->urutan = $author->no_urut;
                            $pa->afiliasi = $author->afiliasi_penulis;
                            $pa->peran_nama = $author->peran_dalam_kegiatan;
                            $pa->peran_id = $flipped_list_peran[$pa->peran_nama];
                            $pa->corresponding_author = $author->apakah_corresponding_author;
                            $pa->jenis_peranan = $author->jenis_peranan;
                            if(!$pa->save())
                            {
                                $errors .= \app\helpers\MyHelper::logError($pa);
                                throw new \Exception;
                            }
                        }
                        $counter++;


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
                    'message' => $counter.' data imported'
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
        }


        else
        {
            Yii::$app->getSession()->setFlash('danger',json_encode($response));
            $results = [
                'code' => 500,
                'message' => json_encode($response)
            ];
        }

        return $results;
    }

    protected function importPengajaran()
    {
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
       
        $results = [];
        $params = [
            
        ];

        $response = $client->get('/tahun/list', $params,$headers)->send();
         
        $tahun_akademik_list = '';

        if ($response->isOk) {
            $results = $response->data['values'];
            if(!empty($results))
            {
                $tahun_akademik_list = $results;
            }
        }

        $results = [];

        $transaction = Yii::$app->db->beginTransaction();
        $errors = '';
        $count_sukses = 0;
        $count_failed = 0;
        $list_prodi = \app\models\Prodi::find()->all();
        try 
        {

            $komponen = \app\models\KomponenKegiatan::find()->where(['kondisi'=>'A'])->one();
            foreach($tahun_akademik_list as $tahun_akademik)
            {
                $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
                $params = [
                    'uuid' => $user->uuid,
                    'tahun' => $tahun_akademik['tahun_id']
                ];

                $response = $client->get('/jadwal/dosen/uuid', $params,$headers)->send();
             
                if ($response->isOk) 
                {
                    $results = $response->data['values'];
                    $status = $response->data['status'];
                    if($status == 200)
                    {
                        foreach($results as $res)
                        {

                            $model = \app\models\Pengajaran::find()->where(['jadwal_id'=>$res['id']])->one();
                            if(empty($model))
                            {
                                $model = new \app\models\Pengajaran;
                                $model->jadwal_id = $res['id'];
                                $model->NIY = $user->NIY;
                            }

                            $model->matkul = $res['nama_mk'];
                            $model->kode_mk = $res['kode_mk'];
                            $model->jurusan = $res['prodi'];
                            $model->jam = $res['jam'];
                            $model->hari = $res['hari'];
                            $model->kelas = $res['kelas'];
                            $model->sks = $res['sks'];
                            $model->tahun_akademik = $res['ta'];
                            $model->ver = 'Sudah Diverifikasi';
                            $model->komponen_id = $komponen->id;
                            $model->sks_bkd = $komponen->angka_kredit;

                            if($model->save())
                            {
                                $count_sukses++;

                            }

                            else
                            {
                                
                                foreach($model->getErrors() as $attribute){
                                    foreach($attribute as $error){
                                        $errors .= $error.' ';
                                    }
                                }

                                throw new \Exception;
                                
                            }


                        }    
                    }
                    
                }
            }
            $transaction->commit();
            $results = [
                'code' => 200,
                'message' => $count_sukses.' Data synced'
            ];
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            $transaction->rollback();
        }

        return $results;
       
 
    }

    protected function importJurnal()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $list = \app\models\Pengajaran::find()->where(['NIY'=>Yii::$app->user->identity->NIY])->all();
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $unsur = \app\models\UnsurKegiatan::findOne(1);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $counter = 0;
        $errors ='';
        $results = [];
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
            $results = [
                'code' => 200,
                'message' => $counter.' data imported'
                
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

        return $results;
    }

    protected function importInpassing()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/Inpassing';
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
                    
                    $model = \app\models\Inpassing::find()->where([
                        'sister_id' => $item->id_riwayat_inpassing
                    ])->one();

                    if(empty($model))
                        $model = new \app\models\Inpassing;

                    $model->NIY = Yii::$app->user->identity->NIY;
                    $model->sister_id = $item->id_riwayat_inpassing;
                    $model->nama_golongan = $item->nama_golongan;
                    $model->nomor_sk_inpassing = $item->nomor_sk_inpassing;
                    $model->tanggal_sk = $item->tanggal_sk;
                    $model->sk_inpassing_terhitung_mulai_tanggal = $item->sk_inpassing_terhitung_mulai_tanggal;
                   
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
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importPenugasan()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/Penempatan';
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
                    
                    $model = \app\models\Penugasan::find()->where([
                        'sister_id' => $item->id_riwayat_penempatan
                    ])->one();

                    if(empty($model))
                        $model = new \app\models\Penugasan;

                    $model->NIY = Yii::$app->user->identity->NIY;
                    $model->sister_id = $item->id_riwayat_penempatan;
                    $model->status_pegawai = $item->status_pegawai;
                    $model->nama_ikatan_kerja = $item->nama_ikatan_kerja;
                    $model->nama_jenjang_pendidikan = $item->nama_jenjang_pendidikan;
                    $model->unit_kerja = $item->unit_kerja;
                    $model->perguruan_tinggi = $item->perguruan_tinggi;
                    $model->terhitung_mulai_tanggal_surat_tugas = $item->terhitung_mulai_tanggal_surat_tugas;

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
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importPengabdian()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $sisterToken = \app\helpers\MyHelper::getSisterToken();
        if(!isset($sisterToken)){
            $sisterToken = MyHelper::getSisterToken();
        }

        // print_r($sisterToken);exit;
        $sister_baseurl = Yii::$app->params['sister_baseurl'];
        $headers = ['content-type' => 'application/json'];
        $client = new \GuzzleHttp\Client([
            'timeout'  => 5.0,
            'headers' => $headers,
            // 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
        ]);
        $full_url = $sister_baseurl.'/Pengabdian';
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

                    $full_url = $sister_baseurl.'/Pengabdian/detail';
                    $resp = $client->post($full_url, [
                        'body' => json_encode([
                            'id_token' => $sisterToken,
                            'id_dosen' => $user->sister_id,
                            'id_penelitian_pengabdian' => $item->id_penelitian_pengabdian
                        ]), 
                        'headers' => ['Content-type' => 'application/json']

                    ]); 
                    $response = json_decode($resp->getBody());
                    if($response->error_code == 0){
                        $res = $response->data;
                        // echo '<pre>';
                        // print_r($res);
                        // echo '</pre>';
                        // exit;

                        $model = \app\models\Pengabdian::find()->where([
                            'sister_id' => $item->id_penelitian_pengabdian
                        ])->one();

                        if(empty($model))
                            $model = new \app\models\Pengabdian;


                        $model->NIY = Yii::$app->user->identity->NIY;
                        $model->sister_id = $item->id_penelitian_pengabdian;
                        $model->judul_penelitian_pengabdian = $item->judul_penelitian_pengabdian;
                        $model->nama_skim = $item->nama_skim;
                        $model->nama_tahun_ajaran = $item->nama_tahun_ajaran;
                        $model->durasi_kegiatan = $item->durasi_kegiatan;
                        $model->jenis_penelitian_pengabdian = $item->jenis_penelitian_pengabdian;
                        
                        $model->tempat_kegiatan = !empty($res->tempat_kegiatan) ? $res->tempat_kegiatan : '-';
                        $model->tahun_usulan = $res->nama_tahun_anggaran;
                        $model->tahun_kegiatan = $res->nama_tahun_anggaran;
                        $model->tahun_dilaksanakan = $res->nama_tahun_anggaran;
                        $model->tahun_pelaksanaan_ke = $res->tahun_pelaksanaan_ke;
                        $model->dana_dikti = $res->dana_dari_dikti;
                        $model->dana_pt = $res->dana_dari_PT;
                        $model->dana_institusi_lain = $res->dana_dari_instansi_lain;
                        
                        if($model->save())
                        {

                            $pa = \app\models\PengabdianAnggota::find()->where([
                                'pengabdian_id' => $model->ID,
                                'NIY' => Yii::$app->user->identity->NIY
                            ])->one();

                            if(empty($pa))
                                $pa = new \app\models\PengabdianAnggota;

                            
                            $pa->NIY = Yii::$app->user->identity->NIY;
                            $pa->pengabdian_id = $model->ID;
                            $pa->status_anggota = '-';
                            $pa->beban_kerja = '0';

                            if($pa->save())
                            {
                                $counter++;
                            }

                            else{
                                $errors .= 'PengabdianAnggota_ERR: '.\app\helpers\MyHelper::logError($model);
                                throw new \Exception;
                            }

                            
                        }

                        else
                        {
                            $errors .= \app\helpers\MyHelper::logError($model);
                            throw new \Exception;
                        }
                    } 

                    else{
                        print_r($response);exit;
                        $errors .= 'PengabdianAnggota_ERR: '.\app\helpers\MyHelper::logError($model);
                        throw new \Exception;
                    }

                    

                    
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;

    }

    protected function importPenelitian()
    {
        $results = [];
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
                        // print_r($res);exit;
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
                    'message' => $counter.' data imported'
                    
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
        }


        else
        {
            $errors .= json_encode($response);
            $results = [
                'code' => 500,
                'message' => $errors
            ];
        }

        return $results;


    }

    public function actionSync()
    {
        return $this->render('sync');
    }

    public function actionAjaxCariUser() {

        $q = $_GET['term'];
        
        $query = DataDiri::find();
        $query->where(['LIKE','nama',$q]);
        $query->orWhere(['LIKE','NIY',$q]);
        $query->limit(10);
        $result1 = $query->asArray()->all();

        $query = Tendik::find();
        $query->where(['LIKE','nama',$q]);
        $query->orWhere(['LIKE','NIY',$q]);
        $query->limit(10);
        $result2 = $query->asArray()->all();
        $result = array_merge($result1, $result2);
        $out = [];

        // print_r($result);exit;
        if(count($result) > 0)
        {
            foreach ($result as $d) {
                $d = (object)$d;
                $out[] = [
                    'id' => $d->NIY,
                    'niy' => $d->NIY,
                    'label'=> $d->NIY.' - '.$d->nama,

                ];
            }
        }

        else
        {

           
            $out[] = [
                'id' => 0,
                'label'=> 'Data user tidak ditemukan',

            ];
            
        }
        
        

        echo \yii\helpers\Json::encode($out);


    }

    public function actionChange()
    {

        $id = Yii::$app->user->identity->id;
        // load user data
        $user = \app\models\User::findOne($id);

        $auth = Yii::$app->authManager;

        $roles = $auth->getRolesByUser($id);

        $user->item_name = $user->access_role;

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('change', ['user' => $user, 'role' => $user->item_name]);
        }


        $user->access_role = $user->item_name;
        if (!$user->save()) {
            return $this->render('change', ['user' => $user, 'role' => $user->item_name]);
        }

        

        // take new role from the form
        $newRole = $auth->getRole($user->item_name);
            
        $isExist = false;
        foreach($roles as $role)
        {
            $isExist = $role->name == $newRole->name;
            if($isExist)
                break;
            
        }

        $info = true;
        if(!$isExist){
            $info = $auth->assign($newRole, $user->id);
        }        

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Role changed successfuly.'));
        return $this->redirect(['change', 'id' => $user->id]);
        
        
    }


    public function actionAjaxTahunList()
    {
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];

        $results = [];
        // foreach($listTahun as $tahun)
        // {
        $params = [
            
        ];

        $response = $client->get('/tahun/list', $params,$headers)->send();
         // print_r($params);exit;
        if ($response->isOk) {
            $results = $response->data['values'];
            
        }

        // }

        echo \yii\helpers\Json::encode($results);
        die();
    }

    public function actionAuthCallback()
    {

        // $input = json_decode(file_get_contents('php://input'),true);
        // header('Content-type:application/json;charset=utf-8');

        $results = [];
         
        try
        {
            $token = $_SERVER['HTTP_X_JWT_TOKEN'];
            $key = Yii::$app->params['jwt_key'];
            $decoded = JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
            $results = [
                'code' => 200,
                'message' => 'Valid'
            ];   
        }
        catch(\Exception $e) 
        {

            $results = [
                'code' => 500,
                'message' => $e->getMessage()
            ];
        }

        echo json_encode($results);

        die();
        
       
    }

    public function actionLoginSso($token)
    {
        // print_r($token);exit;
        
        $key = Yii::$app->params['jwt_key'];
        $decoded = JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
        
        $uuid = $decoded->uuid; // will print "1"
        $user = \app\models\User::find()
            ->where([
                'uuid'=>$uuid,
            ])
            ->one();

        if(!empty($user))
        {
            
            $session = Yii::$app->session;
            $session->set('token',$token);
            

            
            // $exp = $total_bkd * 1000;
            // $exp += $totalCatatanHarian;
            // $level = MasterLevel::getLevel($exp);
            // $currentClass = GameLevelClass::getCurrentClass($level);
            // $nextLevel = MasterLevel::getNextLevel($exp);
            // $remainingExp = $nextLevel['nextExp'] - $exp;

            // $session->set('level',$user->level);
            // $session->set('class',$currentClass['class']);
            // $session->set('rank',$currentClass['rank']);
            // $session->set('stars',$currentClass['stars']);
            // $session->set('remainingExp',$remainingExp);

            Yii::$app->user->login($user);
            return $this->redirect(['site/index']);
        }

        else{
            
            
            return $this->redirect($decoded->iss.'/site/sso-callback?code=302')->send();
        }
       
    }

    public function actionLoginOtp($otp)
    {
        
        $user = User::find()
            ->where([
                'otp'=>$otp,
            ])
            ->one();

        if(!empty($user))
        {
            $api_baseurl = Yii::$app->params['invoke_token_uri'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $headers = [];

            $params = [
                'uuid' => $user->uuid
            ];
            
            $response = $client->get($api_baseurl, $params,$headers)->send();
            if ($response->isOk) {
                $res = $response->data;

                if($res['code'] != '200')
                {
                    return $this->redirect(Yii::$app->params['sso_login']);
                }

                else{
                    $session = Yii::$app->session;
                    $session->set('token',$res['token']);
                    $user->otp = null;
                    $user->save(false,['otp']);
                    
                    $api_baseurl = Yii::$app->params['api_baseurl'];
                    $client = new Client(['baseUrl' => $api_baseurl]);
                    $client_token = Yii::$app->params['client_token'];
                    $headers = ['x-access-token'=>$client_token];

                    $results = [];
                    // foreach($listTahun as $tahun)
                    // {
                    $params = [
                        
                    ];

                    $response = $client->get('/tahun/aktif', $params,$headers)->send();
                     
                    $tahun_akademik = '';

                    if ($response->isOk) {
                        $results = $response->data['values'];
                        if(!empty($results[0]))
                        {
                            $tahun_akademik = $results[0];
                        }
                    }

                    $pengajaran = Pengajaran::find()->where([
                        'NIY' => $user->NIY,
                        // 'is_claimed' => 1,
                        'tahun_akademik' => $tahun_akademik['tahun_id']
                    ])->all();

                    // print_r($tahun_akademik);exit;

                    $query = Publikasi::find()->where([
                        'NIY' => $user->NIY,
                        'is_claimed' => 1,
                    ]);

                    $query->andWhere(['not',['kegiatan_id' => null]]);

                    $sd = $tahun_akademik['kuliah_mulai'];
                    $ed = $tahun_akademik['nilai_selesai'];

                    $totalCatatanHarian = $this->sumPoinCatatanHarian($sd, $ed, $user->ID);

                    $query->andFilterWhere(['between','tanggal_terbit',$sd, $ed]);
                    $query->orderBy(['tanggal_terbit'=>SORT_ASC]);

                    $publikasi = $query->all();

                    $query = Pengabdian::find()->where([
                        'NIY' => $user->NIY,
                        'is_claimed' => 1,
                    ]);

                    // $sd = $tahun_akademik['kuliah_mulai'];
                    // $ed = $tahun_akademik['nilai_selesai'];

                    // $query->andFilterWhere(['between','tahun_kegiatan',$sd, $ed]);
                    $query->orderBy(['tahun_kegiatan'=>SORT_ASC]);

                    $pengabdian = $query->all();

                    $query = Organisasi::find()->where([
                        'NIY' => $user->NIY,
                        'is_claimed' => 1,
                    ]);

                    $organisasi = $query->all();

                    $query = PengelolaJurnal::find()->where([
                        'NIY' => $user->NIY,
                        'is_claimed' => 1,
                    ]);

                    $pengelolaJurnal = $query->all();
                    $total_abdi = 0;
                    $total_penunjang = 0;
                    $total_ajar = 0;
                    $total_pub = 0;
                    $total_ajar = 0; 
                    foreach ($pengajaran as $key => $value) 
                    {
                        $total_ajar += $value->sks_bkd;
                    }

                    foreach ($publikasi as $key => $value) 
                    {
                        $total_pub += $value->sks_bkd;
                    }

                    foreach ($pengabdian as $key => $value) 
                    {
                        $total_abdi += $value->nilai;
                    }

                    foreach ($organisasi as $key => $value) 
                    {
                        $total_penunjang += $value->sks_bkd;
                    }
                    foreach ($pengelolaJurnal as $key => $value) 
                    {
                        $total_penunjang += $value->sks_bkd;
                    }

                    $total_bkd = $total_ajar+$total_pub+$total_abdi+$total_penunjang;

                    $exp = $total_bkd * 1000;
                    $exp += $totalCatatanHarian;
                    $level = MasterLevel::getLevel($exp);
                    $currentClass = GameLevelClass::getCurrentClass($level);
                    $nextLevel = MasterLevel::getNextLevel($exp);
                    $remainingExp = $nextLevel['nextExp'] - $exp;

                    $session->set('level',$level);
                    $session->set('class',$currentClass['class']);
                    $session->set('rank',$currentClass['rank']);
                    $session->set('stars',$currentClass['stars']);
                    $session->set('remainingExp',$remainingExp);

                    Yii::$app->user->login($user);
                    return $this->redirect(['site/index']);
                }
            }
            

        }

        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid OTP. Please contact your department administrator.'));
            
            return $this->redirect(['site/login']);
        }
        
    }

    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        $user = User::find()
            ->where([
                'email'=>$attributes['email'],
            ])
            ->one();

        if(!empty($user)){
            
            Yii::$app->user->login($user);
        }

        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid or Unregistered Email. Please use a valid unida.gontor.ac.id email address.'));
            
            return $this->redirect(['site/login']);
        }
        
    }

    protected function calcAchievement($params){

        $pengajaran = $params['pengajaran'];
        $publikasi = $params['publikasi'];
        $pengabdian = $params['pengabdian'];
        $organisasi = $params['organisasi'];
        $pengelolaJurnal = $params['pengelolaJurnal'];
        $bkd_ajar = $params['bkd_ajar'];
        $bkd_pub = $params['bkd_pub'];
        $bkd_abdi = $params['bkd_abdi'];
        $bkd_penunjang = $params['bkd_penunjang'];

        $total_abdi = 0;
        $total_penunjang = 0;
        $total_ajar = 0;
        $total_pub = 0;
        $total_ajar = 0; 
        foreach ($pengajaran as $key => $value) 
        {
            $total_ajar += $value->sks_bkd;
        }

        foreach ($publikasi as $key => $value) 
        {
            $total_pub += $value->sks_bkd;
        }

        foreach ($pengabdian as $key => $value) 
        {
            $total_abdi += $value->nilai;
        }

        foreach ($organisasi as $key => $value) 
        {
            $total_penunjang += $value->sks_bkd;
        }
        foreach ($pengelolaJurnal as $key => $value) 
        {
            $total_penunjang += $value->sks_bkd;
        }

        $total_bkd = $total_ajar+$total_pub+$total_abdi+$total_penunjang;

        $exp = $total_bkd * 1000;
        $exp += $results['totalCatatanHarian'];
        $level = MasterLevel::getLevel($exp);
        $currentClass = GameLevelClass::getCurrentClass($level);
        $nextLevel = MasterLevel::getNextLevel($exp);
        $remainingExp = $nextLevel['nextExp'] - $exp;

        $persen_a = 0;
        $persen_b = 0;
        $persen_c = 0;
        $persen_d = 0;
        $label_a = '';
        $label_b = '';
        $label_c = '';
        $label_d = '';

        if($bkd_ajar->nilai_minimal > 0){
            $persen_a = round(($total_ajar) / ($bkd_ajar->nilai_minimal) * 100,2);
            if($persen_a >= 100){
                $label_a = 'progress-bar-success';
            }

            else if($persen_a > 50){
                $label_a = 'progress-bar-warning';
            }

            else {
                $label_a = 'progress-bar-danger';
            }
        }

        if($bkd_pub->nilai_minimal > 0){
            $persen_b = round(($total_pub) / ($bkd_pub->nilai_minimal) * 100,2);
            if($persen_b >= 100){
                $label_b = 'progress-bar-success';
            }

            else if($persen_b > 50){
                $label_b = 'progress-bar-warning';
            }

            else {
                $label_b = 'progress-bar-danger';
            }
        }

        if($bkd_abdi->nilai_minimal > 0){
            $persen_c = round(($total_abdi) / ($bkd_abdi->nilai_minimal) * 100,2);
            if($persen_c >= 100){
                $label_c = 'progress-bar-success';
            }

            else if($persen_c > 50){
                $label_c = 'progress-bar-warning';
            }

            else {
                $label_c = 'progress-bar-danger';
            }
        }

        if($bkd_penunjang->nilai_minimal > 0){
            $persen_d = round(($total_penunjang) / ($bkd_penunjang->nilai_minimal) * 100,2);
            if($persen_d >= 100){
                $label_d = 'progress-bar-success';
            }

            else if($persen_d > 50){
                $label_d = 'progress-bar-warning';
            }

            else {
                $label_d = 'progress-bar-danger';
            }
        }
        $results = [
            'exp' => [
                'currentClass' => $currentClass,
                'remainingExp' => $remainingExp,
                'level' => $level
            ],
            'persen' => [
                'a' => $persen_a,
                'b' => $persen_b,
                'c' => $persen_c,
                'd' => $persen_d,
            ],
            'label' => [
                'a' => $label_a,
                'b' => $label_b,
                'c' => $label_c,
                'd' => $label_d,
            ],
        ];
        return $results;
    }

    protected function sumPoinCatatanHarian($sd, $ed, $user_id)
    {
        $query = CatatanHarian::find()->where(['user_id'=>$user_id]);
        // $query->andFilterWhere(['between','tanggal',$sd, $ed]);
        return $query->sum('poin');
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        if(empty($user->dataDiri))
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }
        $results = [];
        
        $bkd_periode = \app\models\BkdPeriode::find()->where(['buka' => 'Y'])->one();

        $pengajaran = Pengajaran::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            // 'is_claimed' => 1,
            'tahun_akademik' => $bkd_periode->tahun_id
        ])->all();

        // print_r($tahun_akademik);exit;

        $query = Publikasi::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $query->andWhere(['not',['kegiatan_id' => null]]);

        $sd = $bkd_periode->tanggal_bkd_awal;
        $ed = $bkd_periode->tanggal_bkd_akhir;

        $totalCatatanHarian = $this->sumPoinCatatanHarian($sd, $ed, Yii::$app->user->identity->ID);

        $query->andFilterWhere(['between','tanggal_terbit',$sd, $ed]);
        $query->orderBy(['tanggal_terbit'=>SORT_ASC]);

        $publikasi = $query->all();

        $query = Pengabdian::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        // $sd = $tahun_akademik['kuliah_mulai'];
        // $ed = $tahun_akademik['nilai_selesai'];

        // $query->andFilterWhere(['between','tahun_kegiatan',$sd, $ed]);
        $query->orderBy(['tahun_kegiatan'=>SORT_ASC]);

        $pengabdian = $query->all();

        $query = Organisasi::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $organisasi = $query->all();

        $query = PengelolaJurnal::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $pengelolaJurnal = $query->all();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'AJAR'
        ]);

        $bkd_ajar = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'RISET'
        ]);

        $bkd_pub = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'ABDIMAS'
        ]);

        $bkd_abdi = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'PENUNJANG'
        ]);

        $bkd_penunjang = $query->one();

        $listColumns = Yii::$app->db->createCommand('SHOW COLUMNS FROM data_diri')->queryAll();

        $countNotEmpty = 0;
        foreach($listColumns as $col)
        {
            $tmp = Yii::$app->db->createCommand('SELECT '.$col['Field'].' FROM data_diri WHERE '.$col['Field'].' IS NOT NULL AND NIY = "'.Yii::$app->user->identity->NIY.'" ')->queryOne();

            if(isset($tmp))
                $countNotEmpty++;
            

        }

        $persentaseProfil = round($countNotEmpty / count($listColumns) * 100,2);
        // print_r($countNotEmpty);exit;
        $results = [
            'totalCatatanHarian' => $totalCatatanHarian,
            'persentaseProfil' => $persentaseProfil
        ];
        return $this->render('index',[
            'pengajaran' => $pengajaran,
            'results' => $results,
            'publikasi' => $publikasi,
            'pengabdian' => $pengabdian,
            'organisasi' => $organisasi,
            'pengelolaJurnal' => $pengelolaJurnal,
            'bkd_ajar' => $bkd_ajar,
            'bkd_pub' => $bkd_pub,
            'bkd_abdi' => $bkd_abdi,
            'bkd_penunjang' => $bkd_penunjang,
            'bkd_periode' =>   $bkd_periode,
            'user' => $user  
        ]);
        
    }

    public function actionHomelog()
    {

        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }else{ 
            $user = \app\models\User::findByEmail(Yii::$app->user->identity->email);
            $model = $user->dataDiri;
            return $this->render('homelog',['model'=>$model,]);
        }
    }


    public function actionUbahAkun()
    {
        $id = Yii::$app->user->identity->ID;
        // load user data
        $user = User::findOne($id);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('ubahAkun', ['user' => $user]);
        }

        // only if user entered new password we want to hash and save it
        if ($user->password) {
            $user->setPassword($user->password);
        }


        if (!$user->save()) {
            return $this->render('ubahAkun', ['user' => $user]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Data user telah diupdate.'));
        return $this->redirect(['ubah-akun']);
    }
        
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){
                $usernya = User::findOne(['NIY'=>Yii::$app->user->identity->NIY]);
                if($usernya->status_admin == 'user'){
                    $model = DataDiri::findOne(['NIY'=>Yii::$app->user->identity->NIY]);
                    return $this->render('homelog',['model'=>$model,]);
                }
                Yii::$app->user->logout();
                Yii::$app->getSession()->setFlash('danger','You are admin dude!!!');
                return $this->render('login', [
                    'model' => $model,]);
            }
            return $this->render('login', [
                'model' => $model,]);
        } else {
            return $this->render('login', [
                'model' => $model,]);
        }
        
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        
        $session = Yii::$app->session;
        $session->remove('token');
        Yii::$app->user->logout();
        $url = Yii::$app->params['sso_logout'];
        return $this->redirect($url);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
