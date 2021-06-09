<?php

namespace app\controllers;

use Yii;

use app\models\User;
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
                        'roles' => ['Dekan','Kepala','Kaprodi'],
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

    public function actionAjaxList()
    {
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
                $query->joinWith(['unsur as u']);
                $query->andWhere(['between','tanggal',$sd,$ed]);
                $query->andWhere([
                    'u.jenis_pegawai' => Yii::$app->user->identity->access_role, 
                    'user_id' => Yii::$app->user->identity->id
                ]);
                $tmp = $query->all();
                foreach($tmp as $r)
                {
                    $results[] = [
                        'id' => $r->id,
                        'nama' => $r->deskripsi,
                        'tanggal' => $r->tanggal,
                        'poin' => $r->poin,
                        'is_selesai' => $r->is_selesai,
                        'unsur' => $r->unsur->nama,
                        'induk' => $r->unsur->induk->nama
                    ];
                }
            }
            
            else if($params == 'week')
            {
                $list_induk = \app\models\IndukKegiatan::find()->all();
                foreach($list_induk as $induk)
                {
                    $total_poin = 0;
                    foreach($induk->unsurKegiatans as $uk)
                    {
                        $query = CatatanHarian::find();
                        $query->alias('t');
                        $query->joinWith(['unsur as u']);
                        $sd = date('Y-m-d 00:00:00', strtotime("last Saturday"));
                        $ed = date('Y-m-d 23:59:59');
                        $query->andWhere(['between','tanggal',$sd,$ed]);
                        $query->andWhere([
                            'unsur_id'=>$uk->id,
                            'u.jenis_pegawai' => Yii::$app->user->identity->access_role, 
                            'user_id' => Yii::$app->user->identity->id
                        ]);
                        $tmp = $query->sum('t.poin');
                        $total_poin += $tmp;
                    }

                    $results[] = [
                        'id' => $induk->id,
                        'nama' => $induk->nama,
                        'tanggal' => null,
                        'poin' => $total_poin,
                        'is_selesai' => null,
                        'unsur' => $induk->nama,
                        'induk' => ''
                    ];
                }
            }

            else if($params == 'month')
            {
                $list_induk = \app\models\IndukKegiatan::find()->all();
                foreach($list_induk as $induk)
                {
                    $total_poin = 0;
                    foreach($induk->unsurKegiatans as $uk)
                    {
                        $query = CatatanHarian::find();
                        $sd = date('Y-m-01 00:00:00');
                        $ed = date('Y-m-t 23:59:59');
                        $query->andWhere(['between','tanggal',$sd,$ed]);
                        $query->andWhere([
                            'unsur_id'=>$uk->id,
                            'user_id' => Yii::$app->user->identity->id
                        ]);
                        $tmp = $query->sum('poin');
                        $total_poin += $tmp;
                    }

                    $results[] = [
                        'id' => $induk->id,
                        'nama' => $induk->nama,
                        'tanggal' => null,
                        'poin' => $total_poin,
                        'is_selesai' => null,
                        'unsur' => $induk->nama,
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
        // $roles = ['Dekan','Kaprodi','Kepala','Ketua','Direktur','Rektor','Wakil Rektor'];
        
        // if(in_array(Yii::$app->user->identity->access_role, $roles))
        // {
        //     return $this->redirect(['list']);
        // }

        // else{
        $searchModel = new CatatanHarianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        // }
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
