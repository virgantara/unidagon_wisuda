<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use app\models\SkpItem;
use app\models\SkpItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SkpItemController implements the CRUD actions for SkpItem model.
 */
class SkpItemController extends Controller
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
                ])->one();

                if(empty($model)){
                    $model = new SkpItem;
                    $model->id = MyHelper::gen_uuid();
                }
                
                $model->attributes = $dataPost;

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
