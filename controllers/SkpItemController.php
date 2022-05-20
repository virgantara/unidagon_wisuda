<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\BkdDosen;
use app\models\BkdPeriode;
use app\models\CatatanHarian;
use app\models\KomponenKegiatan;
use app\models\SkpItem;
use app\models\SkpItemSearch;

/**
 * SkpItemController implements the CRUD actions for SkpItem model.
 */
class SkpItemController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create','update','delete','index','list'],
                'rules' => [
                    
                    [
                        'actions' => ['create','update','delete','index'],
                        'allow' => true,
                        'roles' => ['Dosen','Staf','Staf TU','Staf UPT','Staf Biro'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','list','ajax-list-pegawai','ajax-list-anggota','ajax-remove-anggota'
                        ],
                        'allow' => true,
                        'roles' => ['Dekan','Kepala','Kaprodi','Direktur','Ketua','Kepala UPT','Kepala Biro'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','list'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionAjaxClaimPengabdian()
    {
        $results = [
            'code' => 400,
            'message' => 'Bad request'
        ];

        if(Yii::$app->request->isPost && !empty($_POST['dataPost'])){
            $dataPost = $_POST['dataPost'];
            $periode = BkdPeriode::findOne($dataPost['tahun_id']);
            $komponen_kegiatan_id = $dataPost['komponen_kegiatan_id'];
            if(!empty($periode)){
                $rows = (new \yii\db\Query())
                    ->select(['si.komponen_kegiatan_id','si.id as skp_item_id','si.nama as nama_kegiatan','kk.angka_kredit as ak_bkd','kk.angka_kredit_pak as ak_pak'])
                    ->from('skp_item si')
                    ->join('LEFT JOIN','skp s','s.id = si.skp_id')
                    ->join('LEFT JOIN','komponen_kegiatan kk','si.komponen_kegiatan_id = kk.id')
                    ->join('LEFT JOIN','unsur_utama uu','kk.unsur_id = uu.id')
                    ->where([
                        'uu.kode' => 'ABDIMAS',
                        'kk.id' => $komponen_kegiatan_id,
                        's.periode_id' => $periode->tahun_id,
                        's.pegawai_dinilai' => Yii::$app->user->identity->NIY
                    ])
                    ->andWhere(['<>','si.realisasi_qty',0])
                    ->all();

                $counter = 0;
                $counterFailed = 0;
                $errors = '';

                foreach($rows as $item) {

                    $bkd = BkdDosen::findOne([
                        'tahun_id' => $periode->tahun_id,
                        'dosen_id' => Yii::$app->user->identity->id,
                        'komponen_id' => $item['komponen_kegiatan_id'],
                        'kondisi' => $item['skp_item_id']
                    ]);

                    if(empty($bkd)){
                        $bkd = new BkdDosen;
                        $bkd->tahun_id = $periode->tahun_id;
                        $bkd->dosen_id = Yii::$app->user->identity->id;
                        $bkd->komponen_id = $item['komponen_kegiatan_id'];
                        $bkd->kondisi = $item['skp_item_id'];
                    }

                    $bkd->skp_item_id = $item['skp_item_id'];
                    $bkd->deskripsi = $item['nama_kegiatan'];
                    $bkd->sks = $item['ak_bkd'];
                    $bkd->sks_pak = $item['ak_pak'];

                    $transaction = Yii::$app->db->beginTransaction();
                        // exit;
                    
                    try {
                        if($bkd->save()){
                            $counter++;
                            $transaction->commit();
                        }

                        else{
                            throw new \Exception;
                        }
                    }

                    catch(\Exception $e) {
                        $errors .= $item['nama_kegiatan'].' '.$e->getMessage();
                        $transaction->rollBack();
                        $counterFailed++;
                    }
                }

                $message = 'No data was claimed';
                if($counter > 0){
                    $message = $counter.' data have been updated';
                }

                $results = [
                    'code' => 200,
                    'message' => $message,
                    'error' => $errors
                ];
            }

        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxClaimPenunjang()
    {
        $results = [
            'code' => 400,
            'message' => 'Bad request'
        ];

        if(Yii::$app->request->isPost && !empty($_POST['dataPost'])){
            $dataPost = $_POST['dataPost'];
            $periode = BkdPeriode::findOne($dataPost['tahun_id']);

            if(!empty($periode)){
                $rows = (new \yii\db\Query())
                    ->select(['si.komponen_kegiatan_id','si.id as skp_item_id','si.nama as nama_kegiatan','kk.angka_kredit as ak_bkd','kk.angka_kredit_pak as ak_pak'])
                    ->from('skp_item si')
                    ->join('LEFT JOIN','skp s','s.id = si.skp_id')
                    ->join('LEFT JOIN','komponen_kegiatan kk','si.komponen_kegiatan_id = kk.id')
                    ->join('LEFT JOIN','unsur_utama uu','kk.unsur_id = uu.id')
                    ->where([
                        'uu.kode' => 'PENUNJANG',
                        's.periode_id' => $periode->tahun_id,
                        's.pegawai_dinilai' => Yii::$app->user->identity->NIY
                    ])
                    ->andWhere(['<>','si.realisasi_qty',0])
                    ->all();

                $counter = 0;
                $counterFailed = 0;
                $errors = '';
                foreach($rows as $item) {

                    $bkd = BkdDosen::findOne([
                        'tahun_id' => $periode->tahun_id,
                        'dosen_id' => Yii::$app->user->identity->id,
                        'komponen_id' => $item['komponen_kegiatan_id'],
                        'kondisi' => $item['skp_item_id']
                    ]);

                    if(empty($bkd)){
                        $bkd = new BkdDosen;
                        $bkd->tahun_id = $periode->tahun_id;
                        $bkd->dosen_id = Yii::$app->user->identity->id;
                        $bkd->komponen_id = $item['komponen_kegiatan_id'];
                        $bkd->kondisi = $item['skp_item_id'];
                    }

                    $bkd->skp_item_id = $item['skp_item_id'];
                    $bkd->deskripsi = $item['nama_kegiatan'];
                    $bkd->sks = $item['ak_bkd'];
                    $bkd->sks_pak = $item['ak_pak'];

                    $transaction = Yii::$app->db->beginTransaction();
                        // exit;
                    
                    try {
                        if($bkd->save()){
                            $counter++;
                            $transaction->commit();
                        }

                        else{
                            throw new \Exception;
                        }
                    }

                    catch(\Exception $e) {
                        $errors .= $item['nama_kegiatan'].' '.$e->getMessage();
                        $transaction->rollBack();
                        $counterFailed++;
                    }
                }

                $message = 'No data was update';
                if($counter > 0){
                    $message = $counter.' data have been updated';
                }

                $results = [
                    'code' => 200,
                    'message' => $message,
                    'error' => $errors
                ];
            }

        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxGet(){
        $results = [];
        if(Yii::$app->request->isPost){
            $dataPost = $_POST['dataPost'];

            $model = SkpItem::findOne($dataPost['id']);
            $query = CatatanHarian::find();
            $query->andWhere(['skp_item_id' => $model->id]);
            $realisasi_qty = $query->count();

            $results = [
                'id' => $model->id,
                'nama' => $model->nama,
                'unsur' => $model->komponenKegiatan->unsur->nama,
                'komponen' => $model->komponenKegiatan->nama,
                'target_qty' => $model->target_qty,
                'target_satuan' => $model->target_satuan,
                'target_mutu' => $model->target_mutu,
                'target_waktu' => $model->target_waktu,
                'target_biaya' => MyHelper::formatRupiah($model->target_biaya,2),
                'target_waktu_satuan' => $model->target_waktu_satuan,
                'realisasi_qty' => $realisasi_qty,//$model->realisasi_qty,
                'realisasi_mutu' => $model->realisasi_mutu,
                'realisasi_waktu' => $model->realisasi_waktu,
                'realisasi_biaya' => $model->realisasi_biaya,
            ];


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


                $model = SkpItem::findOne($dataPost['id']);

                if(empty($model)){
                    $results = [
                        'code' => 404,
                        'message' => 'Item not found'
                    ];
                    echo json_encode($results);
                    exit;
                }
                
                $model->attributes = $dataPost;
                
                $komponen = KomponenKegiatan::findOne($model->komponen_kegiatan_id);
                if(!empty($komponen))
                {
                    if(in_array($komponen->kode,['B1','B2']))
                    {
                        $total_sks = $model->target_qty;
                        $ak = $total_sks * $komponen->angka_kredit_pak;
                        if($total_sks > 10){
                            $ak = 10 * $komponen->angka_kredit_pak;
                            $sisa = $total_sks - 10;

                            $ak = $ak + ($sisa * ($komponen->angka_kredit_pak / 2));
                        }

                        $model->target_ak = $ak;
                        $model->realisasi_ak = $ak;
                    }

                    else
                    {
                        $model->target_ak = $model->target_qty * $komponen->angka_kredit_pak;
                    }
                }

                $model->hitungSkp();

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

    public function actionAjaxList()
    {
        $id = $_POST['id'];
        $list = SkpItem::find()->where(['skp_id'=>$id])->orderBy(['nama'=>SORT_ASC])->all();

        $result = [];
        foreach($list as $item)
        {
            $result[] = [
                'id' => $item->id,
                'name' => $item->nama.' ['.(!empty($item->komponenKegiatan) ? $item->komponenKegiatan->unsur->nama : null).']'
            ];
        }

        echo json_encode($result);
        die();
    }

    private function getList($id)
    {
        $list = SkpItem::find()->where(['skp_id'=>$id])->orderBy(['nama'=>SORT_ASC])->all();

        $result = [];
        foreach($list as $item)
        {
            $result[] = [
                'id' => $item->id,
                'name' => $item->nama.' ['.(!empty($item->komponenKegiatan) ? $item->komponenKegiatan->unsur->nama : null).']'
            ];
        }

        return $result;
    }

    public function actionSubitem()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getList($cat_id); 
               
                echo json_encode(['output'=>$out, 'selected'=>'']);
                exit;
            }
        }
        echo json_encode(['output'=>'', 'selected'=>'']);
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


                $model = SkpItem::find()->where([
                    'skp_id' => $dataPost['skp_id'],
                    'komponen_kegiatan_id' => $dataPost['komponen_kegiatan_id'],
                    'nama' => $dataPost['nama']
                ])->one();

                if(empty($model)){
                    $model = new SkpItem;
                    $model->id = MyHelper::gen_uuid();
                }
                
                $model->attributes = $dataPost;
                
                $komponen = KomponenKegiatan::findOne($model->komponen_kegiatan_id);
                if(!empty($komponen))
                {
                    if(in_array($komponen->kode,['B1','B2']))
                    {
                        $total_sks = $model->target_qty;
                        $ak = $total_sks * $komponen->angka_kredit_pak;
                        if($total_sks > 10){
                            $ak = 10 * $komponen->angka_kredit_pak;
                            $sisa = $total_sks - 10;

                            $ak = $ak + ($sisa * ($komponen->angka_kredit_pak / 2));
                        }

                        $model->target_ak = $ak;
                        $model->realisasi_ak = $ak;
                    }

                    else
                    {
                        $model->target_ak = $model->target_qty * $komponen->angka_kredit_pak;
                    }
                }

                if(!$model->save())
                {
                    $errors .= MyHelper::logError($model);
                    throw new \Exception;
                }

                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => 'Data successfully added'
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

    /**
     * Lists all SkpItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SkpItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SkpItem model.
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
     * Creates a new SkpItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SkpItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SkpItem model.
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
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SkpItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the SkpItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SkpItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SkpItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
