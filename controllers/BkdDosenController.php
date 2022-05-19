<?php

namespace app\controllers;

use Yii;
use app\models\BkdDosen;
use app\models\BkdDosenSearch;
use app\models\BkdPeriode;
use app\models\CatatanHarian;
use app\models\KomponenKegiatan;
use app\models\SkpItem;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BkdDosenController implements the CRUD actions for BkdDosen model.
 */
class BkdDosenController extends Controller
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

    public function actionAjaxRemove()
    {
        $results = [
            'code' => 400,
            'message' => 'Invalid request'
        ];

        if(!empty($_POST['dataPost']) && Yii::$app->request->isPost){
            $dataPost = $_POST['dataPost'];
            $model = BkdDosen::findOne($dataPost['id']);

            if(!empty($model)){
                $model->delete();

                $results = [
                    'code' => 200,
                    'message' => 'Data successfully deleted'
                ];
            }

            else{
                $results = [
                    'code' => 404,
                    'message' => 'Data not found'
                ];
            }
        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxListPenunjang()
    {
        $results = [];

        if(!empty($_POST['dataPost']) && Yii::$app->request->isPost){
            $dataPost = $_POST['dataPost'];
        
            $session = Yii::$app->session;
            $tahun_id = '';
            $sd = '';
            $ed = '';
            $bkd_periode = null;
            if($session->has('bkd_periode')) {
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

            $rows = (new \yii\db\Query())
                ->select(['bd.deskripsi','bd.id','bd.sks','bd.sks_pak'])
                ->from('bkd_dosen bd')
                ->join('LEFT JOIN','komponen_kegiatan kk','bd.komponen_id = kk.id')
                ->join('LEFT JOIN','unsur_utama uu','kk.unsur_id = uu.id')
                ->where([
                    'uu.kode' => 'PENUNJANG',
                    'bd.tahun_id' => $bkd_periode->tahun_id,
                    'bd.dosen_id' => Yii::$app->user->identity->id
                ])
                ->all();

            foreach($rows as $item)
            {
                $results[] = [
                    'id' =>$item['id'],
                    'peran' => '',
                    'nama_kegiatan' => $item['deskripsi'],
                    'instansi' => '',
                    'tanggal_mulai' => '',
                    'tanggal_selesai' => '',
                    'sks_bkd' => $item['sks'],
                    'is_claimed' => ''
                ];
            }
        }
        

        echo \yii\helpers\Json::encode($results);
        die();
    }


    /**
     * Lists all BkdDosen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BkdDosenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BkdDosen model.
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
     * Creates a new BkdDosen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BkdDosen();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BkdDosen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing BkdDosen model.
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
     * Finds the BkdDosen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BkdDosen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BkdDosen::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
