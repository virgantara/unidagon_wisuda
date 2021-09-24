<?php

namespace app\controllers;

use Yii;
use app\models\KomponenKegiatan;
use app\models\KomponenKegiatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Json;

/**
 * KomponenKegiatanController implements the CRUD actions for KomponenKegiatan model.
 */
class KomponenKegiatanController extends Controller
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

    public function actionAjaxGet()
    {
        $id = $_POST['dataPost']['id'];

        $results = [];
        $model = KomponenKegiatan::findOne($id);
        if(!empty($model))
            $results = $model->attributes;
        

        echo json_encode($results);
        exit;
    }

    private function getListKomponen($id)
    {
        $list = KomponenKegiatan::find()->where(['unsur_id'=>$id])->orderBy(['nama'=>SORT_ASC])->all();

        $result = [];
        foreach($list as $item)
        {
            $result[] = [
                'id' => $item->id,
                'name' => $item->nama.' - '.$item->subunsur
            ];
        }

        return $result;
    }

    public function actionSubkomponen()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getListKomponen($cat_id); 
               
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                exit;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
        exit;
    }

    public function actionAjaxCari()
    {
        $q = $_GET['term'];

        $results = [];
        $query = KomponenKegiatan::find();

        $query->andFilterWhere(['like','nama',$q]);
        $query->limit(10);
        $tmps = $query->all();

        foreach($tmps as $tmp)
        {
            $results[] = [
                'id' => $tmp->id,
                'label' => $tmp->nama.' '.$tmp->subunsur,
                'kode' => $tmp->kode,
                'ak' => $tmp->angka_kredit_pak
            ];
        }

        echo json_encode($results);
        exit;
    }

    /**
     * Lists all KomponenKegiatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KomponenKegiatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KomponenKegiatan model.
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
     * Creates a new KomponenKegiatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KomponenKegiatan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KomponenKegiatan model.
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
     * Deletes an existing KomponenKegiatan model.
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
     * Finds the KomponenKegiatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KomponenKegiatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KomponenKegiatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
