<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use app\models\Skp;
use app\models\MJabatan;
use app\models\Jabatan;
use app\models\SkpItem;
use app\models\SkpSearch;
use app\models\SkpItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SkpController implements the CRUD actions for Skp model.
 */
class SkpController extends Controller
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

    /**
     * Lists all Skp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SkpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Skp model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new SkpItemSearch();
        $searchModel->skp_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       
        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider            
        ]);
    }

    /**
     * Creates a new Skp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Skp();
        $model->id = MyHelper::gen_uuid();
        $model->status_skp = '1';
        $model->pegawai_dinilai = Yii::$app->user->identity->NIY;

        $loggedInAs = MJabatan::find()->where(['nama'=>Yii::$app->user->identity->access_role])->one();

        $jabatan = Jabatan::find()->where([
          'jabatan_id' => !empty($loggedInAs) ? $loggedInAs->id : '-',
          'NIY' => $model->pegawai_dinilai
        ])->one();

        if(!empty($jabatan))
        {
            $unker = $jabatan->unker;
          
            $niyAsesor = !empty($unker) ? $unker->pejabat->NIY : null;
            $jabatanPenilai = Jabatan::find()->where([
                'jabatan_id' => !empty($loggedInAs->parent) ? $loggedInAs->parent_id : '-',
                'NIY' => $niyAsesor
            ])->one();

            $model->pejabat_penilai = $niyAsesor;            
            $model->jabatan_pegawai_id = !empty($jabatan) ? $jabatan->ID : null;
            $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Skp model.
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
     * Deletes an existing Skp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Skp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Skp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Skp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
