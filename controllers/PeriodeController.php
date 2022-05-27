<?php

namespace app\controllers;

use Yii;
use app\models\Periode;
use app\models\PeriodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PeriodeController implements the CRUD actions for Periode model.
 */
class PeriodeController extends Controller
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
                'only' => ['create','update','delete','index','view'],
                'rules' => [
                    
                    [
                        'actions' => ['create','update','delete','index','view','ajax-count-serdos'],
                        'allow' => true,
                        'roles' => ['theCreator','admin']
                    ]
                ]
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all Periode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PeriodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Periode model.
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
     * Creates a new Periode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Periode();

        if ($model->load(Yii::$app->request->post())) {
            if($model->status_aktivasi == 'Y'){

                $list = Periode::find()->all();
                foreach($list as $item)
                {   
                    $item->status_aktivasi = 'N';
                    $item->save(false,['status_aktivasi']);
                }
            }

            if($model->save()){
                $activity = new \wdmg\activity\models\Activity;
                $activity->setActivity(
                    Yii::$app->user->identity->username.' added a new Periode to '.$model->status_aktivasi, 
                    'periode', 
                    'create', 2
                );

                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['view', 'id' => $model->id_periode]);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Periode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->status_aktivasi == 'Y')
            {


                $list = Periode::find()->all();
                foreach($list as $item)
                {   
                    $item->status_aktivasi = 'N';
                    $item->save(false,['status_aktivasi']);
                }
            }

            if($model->save()){
                $activity = new \wdmg\activity\models\Activity;
                $activity->setActivity(
                    Yii::$app->user->identity->username.' updated a Periode to '.$model->status_aktivasi, 
                    'periode', 
                    'update', 2
                );

                Yii::$app->session->setFlash('success', "Data tersimpan");
                return $this->redirect(['view', 'id' => $model->id_periode]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Periode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->delete()){
            $activity = new \wdmg\activity\models\Activity;
            $activity->setActivity(
                Yii::$app->user->identity->username.' deleted a Periode', 
                'periode', 
                'delete', 2
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Periode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Periode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Periode::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
