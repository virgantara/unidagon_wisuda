<?php

namespace app\controllers;

use Yii;
use app\models\SisterFiles;
use app\models\SisterFilesSearch;
use app\models\Pembicara;
use app\models\PembicaraSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PembicaraController implements the CRUD actions for Pembicara model.
 */
class PembicaraController extends AppController
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
        // $dataPost = $_POST['dataPost'];
        
        $query = Pembicara::find();
        $query->andWhere([
          'NIY' => Yii::$app->user->identity->NIY,
        ]);

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

        $query->andWhere(['BETWEEN','tanggal_sk_penugasan',$sd,$ed]);  

        $tmps = $query->all();

        $results = [];

        foreach($tmps as $tmp)
        {

            $bkd = \app\models\BkdDosen::find()->where([
                'tahun_id' => $tahun_id,
                'dosen_id' => Yii::$app->user->identity->ID,
                'komponen_id' => $tmp->komponen_kegiatan_id,
                'kondisi' => (string)$tmp->id
            ])->one();

            $results[] = [
                'is_claimed' => !empty($bkd),
                'id' => $tmp->id,
                'peran_dalam_kegiatan' => !empty($tmp->kategoriPembicara) ? $tmp->kategoriPembicara->nama : null,
                'nama_pertemuan_ilmiah' => $tmp->nama_pertemuan_ilmiah,
                'penyelenggara_kegiatan' => $tmp->penyelenggara_kegiatan,
                'tanggal' => date('d-m-Y',strtotime($tmp->tanggal_sk_penugasan)),
                'sks_bkd' => !empty($tmp->komponenKegiatan) ? $tmp->komponenKegiatan->angka_kredit : null
            ];
        }
        echo \yii\helpers\Json::encode($results);
        die();
    }

    /**
     * Lists all Pembicara models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PembicaraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pembicara model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $searchModel = new SisterFilesSearch();
        $searchModel->parent_id = $model->sister_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Pembicara model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pembicara();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pembicara model.
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
     * Deletes an existing Pembicara model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        foreach($model->pembicaraFiles as $f)
            $f->delete();

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pembicara model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pembicara the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pembicara::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
