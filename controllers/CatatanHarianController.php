<?php

namespace app\controllers;

use Yii;
use yii\httpclient\Client;
use app\helpers\MyHelper;
use app\models\User;
use app\models\SkpItem;
use app\models\BkdPeriode;
use app\models\CatatanHarian;
use app\models\CatatanHarianSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\UnsurKegiatan;

/**
 * CatatanHarianController implements the CRUD actions for CatatanHarian model.
 */
class CatatanHarianController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create','update','delete','index','list','reports'],
                'rules' => [
                    
                    [
                        'actions' => ['create','update','delete','index','reports'],
                        'allow' => true,
                        'roles' => ['Dosen','Staf'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','list','ajax-setuju','ajax-tolak','reports'
                        ],
                        'allow' => true,
                        'roles' => ['Dekan','Kepala','Kaprodi','Kepala Bagian'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','list','reports'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','bayar'],
                ],
            ],
        ];
    }

    public function actionAjaxGet()
    {

        $results = [];

        $id = $_POST['id'];
        if(!empty($id))
        {
            $model = CatatanHarian::findOne($id);
                
            $results = $model->attributes;
            $results['skp_id'] = $model->skpItem->skp_id;
        }

        
        echo json_encode($results);
        exit;
    }

    public function actionAjaxUpdate()
    {

        $results = [];

        $errors = '';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try 
        {
            $dataPost = $_POST;
            if(!empty($dataPost))
            {

                $model = CatatanHarian::findOne($dataPost['id']);
                    $model->attributes = $dataPost;
                    // $model->poin = $skpItem->target_ak;

                    if(!$model->save())
                    {
                        $errors .= MyHelper::logError($model);
                        throw new \Exception;
                    }

                    $transaction->commit();
                    $results = [
                        'code' => 200,
                        'message' => 'Data successfully updated'
                    ];

                
            }

            else
            {
                $errors .= 'Oops, you cannot POST empty data';
                throw new \Exception;
                
            }   
        } 

        catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            
            
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            
        }
        echo json_encode($results);
        exit;
    }

    public function actionAjaxAdd()
    {

        $results = [];

        $errors = '';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        
        try 
        {
            $dataPost = $_POST;
            if(!empty($dataPost))
            {

                $skpItem = \app\models\SkpItem::findOne($dataPost['skp_item_id']);

                if(!empty($skpItem))
                {

                    if(!empty($skpItem->komponenKegiatan) && in_array($skpItem->komponenKegiatan->kode, ['B1','B2'])){
                        $api_baseurl = Yii::$app->params['api_baseurl'];
                        $client = new Client(['baseUrl' => $api_baseurl]);
                        $client_token = Yii::$app->params['client_token'];
                        $headers = ['x-access-token'=>$client_token];
                        
                        $jadwal_id = $dataPost['jadwal_id'];
                        
                        $params = [
                            'uuid' => Yii::$app->user->identity->uuid,
                            'tahun' => $skpItem->skp->periode_id,
                            'jadwal_id' => $jadwal_id
                        ];

                        $response = $client->get('/jadwal/dosen/uuid', $params,$headers)->send();
                        
                        $data_jadwal = [];
                        if ($response->isOk) {
                            $tmp = $response->data['values'];
                            $status = $response->data['status'];
                            if($status == 200) {
                                if(count($tmp) > 0) {
                                    $data_jadwal = $tmp[0];
                                }
                            }
                        }


                        $params = [
                            'jadwal_id' => $jadwal_id,
                        ];

                        $response = $client->get('/jadwal/dosen/jurnal', $params,$headers)->send();
                        if ($response->isOk) {
                            $items = $response->data['values'];
                            $jumlah_item = count($items);
                            foreach ($items as $key => $value) {
                                $deskripsi = $skpItem->nama.' pertemuan ke-'.$value['pertemuan_ke'].' materi '.$value['materi'];
                                $model = CatatanHarian::find()->where([
                                    'skp_item_id' => $skpItem->id,
                                    'user_id' => Yii::$app->user->identity->id,
                                    'kondisi' => $skpItem->id.'_'.$value['pertemuan_ke']
                                ])->one();

                                if(empty($model)){
                                    $model = new CatatanHarian;
                                    $model->user_id = Yii::$app->user->identity->id;
                                    $model->skp_item_id = $skpItem->id;
                                    $model->kondisi = $skpItem->id.'_'.$value['pertemuan_ke'];
                                }

                                if(!empty($data_jadwal)){
                                    $model->kode_mk = $data_jadwal['kode_mk'];
                                    $model->nama_mk = $data_jadwal['nama_mk'];
                                    $model->sks_mk = $data_jadwal['sks_mk'];
                                    $model->sks_bkd = $data_jadwal['sks_mk'] * $skpItem->komponenKegiatan->angka_kredit;
                                    $model->jadwal_id = $jadwal_id;
                                }
                                $model->deskripsi = $deskripsi;
                                $model->tanggal = date('Y-m-d',strtotime($value['waktu']));                                
                                if($jumlah_item > 0)
                                    $model->poin = $skpItem->target_ak / $jumlah_item;

                                if(!$model->save())
                                {
                                    $errors .= MyHelper::logError($model);
                                    throw new \Exception;
                                }

                                             
                            }

                            $transaction->commit();                  
                            
                            
                        }

                    }

                    else{
                        $model = CatatanHarian::find()->where([
                            'skp_item_id' => $dataPost['skp_item_id'],
                            'user_id' => $dataPost['user_id'],
                            'deskripsi' => trim($dataPost['deskripsi'])
                        ])->one();

                        if(empty($model)){
                            $model = new CatatanHarian;
                        }
                        
                        $model->attributes = $dataPost;
                        
                        $model->poin = $skpItem->target_ak;

                        if(!$model->save())
                        {
                            $errors .= MyHelper::logError($model);
                            throw new \Exception;
                        }

                        $transaction->commit();
                    }
                    $results = [
                        'code' => 200,
                        'message' => 'Data successfully added'
                    ];
                }

                
            }

            else
            {
                $errors .= 'Oops, you cannot POST empty data';
                throw new \Exception;
                
            }   
        } 

        catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            
            
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            
        }
        echo json_encode($results);
        exit;
    }

    public function actionAjaxList()
    {   
        setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
        if(Yii::$app->request->isPost)
        {
            $dataPost = $_POST['dataPost'];
            $params = $dataPost['params'];
            $results = [];
           
            
            
            $tmp = [];
            if($params == 'today')
            {
                $query = CatatanHarian::find();
                $sd = date('Y-m-d 00:00:00');
                $ed = date('Y-m-d 23:59:59');
                // $query->joinWith(['skpItem as si','skpItem.komponenKegiatan as kk']);
                $query->andWhere(['between','tanggal',$sd,$ed]);
                $query->andWhere([
                    // 'u.jenis_pegawai' => Yii::$app->user->identity->access_role, 
                    'user_id' => Yii::$app->user->identity->id
                ]);
                $tmp = $query->all();
                foreach($tmp as $r)
                {
                    $results[] = [
                        'id' => $r->id,
                        'nama' => $r->deskripsi,
                        'tanggal' => strftime('%A, %d %B %Y',strtotime($r->tanggal)),
                        'poin' => $r->poin,
                        'is_selesai' => $r->is_selesai,
                        'unsur' => $r->skpItem->komponenKegiatan->nama.' '.$r->skpItem->komponenKegiatan->subunsur,
                        // 'subunsur' => $r->skpItem->komponenKegiatan->subunsur,
                        'induk' => $r->skpItem->komponenKegiatan->unsur->nama,
                        'role' => '',//$r->unsur->jenis_pegawai
                    ];
                }
            }
            
            else if($params == 'week')
            {
                $list_unsur_utama = \app\models\UnsurUtama::find()->all();
                foreach($list_unsur_utama as $utama)
                {
                    $total_poin = 0;
                    // foreach($induk->unsurKegiatans as $uk)
                    // {
                        $query = CatatanHarian::find();
                        $query->alias('t');
                        $query->joinWith(['skpItem.komponenKegiatan as kk']);
                        $sd = date('Y-m-d 00:00:00', strtotime("last Saturday"));
                        $ed = date('Y-m-d 23:59:59');
                        $query->andWhere(['between','tanggal',$sd,$ed]);
                        $query->andWhere([
                            'kk.unsur_id'=>$utama->id,
                            // 'u.jenis_pegawai' => Yii::$app->user->identity->access_role, 
                            't.user_id' => Yii::$app->user->identity->id
                        ]);
                        $tmp = $query->sum('t.poin');
                        $total_poin += $tmp;
                    // }

                    $results[] = [
                        'id' => $utama->id,
                        'nama' => $utama->nama,
                        'tanggal' => null,
                        'poin' => $total_poin,
                        'is_selesai' => null,
                        'unsur' => $utama->nama,
                        'induk' => ''
                    ];
                }
            }

            else if($params == 'month')
            {
                $list_unsur_utama = \app\models\UnsurUtama::find()->all();
                foreach($list_unsur_utama as $utama)
                {
                    $total_poin = 0;
                    // foreach($induk->unsurKegiatans as $uk)
                    // {
                        $query = CatatanHarian::find();
                        $query->alias('t');
                        $query->joinWith(['skpItem.komponenKegiatan as kk']);
                        $sd = date('Y-m-01 00:00:00');
                        $ed = date('Y-m-t 23:59:59');
                        $query->andWhere(['between','tanggal',$sd,$ed]);
                        $query->andWhere([
                            'kk.unsur_id'=>$utama->id,
                            // 'u.jenis_pegawai' => Yii::$app->user->identity->access_role, 
                            't.user_id' => Yii::$app->user->identity->id
                        ]);
                        $tmp = $query->sum('poin');
                        $total_poin += $tmp;
                    // }

                    $results[] = [
                        'id' => $utama->id,
                        'nama' => $utama->nama,
                        'tanggal' => null,
                        'poin' => $total_poin,
                        'is_selesai' => null,
                        'unsur' => $utama->nama,
                        'induk' => ''
                    ];
                }
            }
            
            echo json_encode($results);

            die();

        }
    }

    public function actionReports()
    {
        
          
        return $this->render('reports', [
        ]);
        
    }

    public function actionAjaxTolak()
    {
        if(Yii::$app->request->isPost)
        {
            $dataPost = $_POST['dataPost'];

            $model = CatatanHarian::findOne($dataPost['id']);
            
            $results = [];
            
            if(!empty($model))
            {
                $model->is_selesai = '2';
                $model->approved_by = Yii::$app->user->identity->id;
                $model->poin = 0;
                if($model->save())
                {
                    $results = [
                        'code' => 200,
                        'message' => 'Data updated'
                    ];
                }

                else
                {
                    $results = [
                        'code' => 500,
                        'message' => 'Something went wrong'
                    ];
                }

                
            }

            else{
                $results = [
                    'code' => 500,
                    'message' => 'Data not found'
                ];
            }

            echo json_encode($results);

            die();

        }
    }

    public function actionAjaxSetuju()
    {
        if(Yii::$app->request->isPost)
        {
            $dataPost = $_POST['dataPost'];

            $model = CatatanHarian::findOne($dataPost['id']);
            
            $results = [];
            
            if(!empty($model))
            {
                $model->is_selesai = '1';
                $model->approved_by = Yii::$app->user->identity->id;
                if($model->save())
                {
                    $results = [
                        'code' => 200,
                        'message' => 'Data updated'
                    ];
                }

                else
                {
                    $results = [
                        'code' => 500,
                        'message' => 'Something went wrong'
                    ];
                }

                
            }

            else{
                $results = [
                    'code' => 500,
                    'message' => 'Data not found'
                ];
            }

            echo json_encode($results);

            die();

        }
    }

    public function actionAjaxListCatatan()
    {
        if(Yii::$app->request->isPost)
        {
            $dataPost = $_POST['dataPost'];

            $results = [];
            if(!empty($dataPost['periode']))
            {
                $query = CatatanHarian::find();
                $query->where([
                    'user_id' => $dataPost['user_id']
                ]);
                $periode = explode(' - ', $dataPost['periode']);
            
                $tgl_awal = $periode[0];
                $tgl_akhir = $periode[1];

                $query->andFilterWhere(['between','tanggal',$tgl_awal,$tgl_akhir]);

                $list = $query->all();
                foreach($list as $item)
                {
                    $results[] = [
                        'id' => $item->id,
                        'deskripsi' => $item->deskripsi,
                        'tanggal' => $item->tanggal,
                        'is_selesai' => $item->is_selesai,
                        'poin' => $item->poin,
                        'unsur_id' => $item->unsur_id,
                        'unsur_nama' => $item->unsur->nama

                    ];
                }
            }

            echo json_encode($results);

            die();

        }
    }

    public function actionList()
    {
        // $query = CatatanHarian::find();
        $user = User::findOne(Yii::$app->user->identity->id);
        $results = [];
        return $this->render('list', [
            'results' => $results,
            'user' => $user
        ]);
    }

    /**
     * Lists all CatatanHarian models.
     * @return mixed
     */
    public function actionIndex()
    {

        if(Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $session->remove('token');
            Yii::$app->user->logout();
            $url = Yii::$app->params['sso_logout'];
            return $this->redirect($url);
        }

        $query = SkpItem::find();
        $query->alias('t');
        $query->select(['t.id','t.nama']);
        $query->joinWith(['skp as s','skp.periode as p']);
        $query->andWhere([
            'p.buka' => 'Y',
            's.pegawai_dinilai' => Yii::$app->user->identity->NIY
        ]);

        $list_skp_item = $query->all();


        $searchModel = new CatatanHarianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list_skp_item' => $list_skp_item
        ]);
    }

    /**
     * Displays a single CatatanHarian model.
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
     * Creates a new CatatanHarian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatatanHarian();
        $model->user_id = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post())) {
            $unsur = UnsurKegiatan::findOne($model->unsur_id);
            $model->poin = $unsur->poin;

            $model->save();
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CatatanHarian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $unsur = UnsurKegiatan::findOne($model->unsur_id);
            $model->poin = $unsur->poin;
            
            $model->save();
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CatatanHarian model.
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
     * Finds the CatatanHarian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatatanHarian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatatanHarian::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
