<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use app\models\SkpItem;
use app\models\KomponenKegiatan;
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
