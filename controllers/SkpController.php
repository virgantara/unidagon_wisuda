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

    public function actionList()
    {

        if(Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $session->remove('token');
            Yii::$app->user->logout();
            $url = Yii::$app->params['sso_logout'];
            return $this->redirect($url);
        }

        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        $loggedInAs = MJabatan::find()->where(['nama'=>Yii::$app->user->identity->access_role])->one();

        $jabatan = Jabatan::find()->where([
          'jabatan_id' => !empty($loggedInAs) ? $loggedInAs->id : '-',
          'NIY' => Yii::$app->user->identity->NIY
        ])->one();

        $pejabatPenilai = null;
        $pegawaiDinilai = $user;
        $jabatanPenilai = null;
        $jabatanPegawai = null;

        $access_role = Yii::$app->user->identity->access_role;

        if(!empty($jabatan))
        {
            $unker = $jabatan->unker;

            if($access_role == 'Kaprodi' && !empty($unker) && !empty($unker->parent) && !empty($unker->parent->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->parent) && !empty($unker->parent->pejabat) ? $unker->parent->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->parent->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();    
            }

            else if($access_role == 'Dosen' && !empty($unker) && !empty($unker->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->pejabat) ? $unker->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();    
            }
            

            $jabatanPegawai = !empty($jabatan) ? $jabatan : null;
        }

        $searchModel = new SkpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelApproval = new SkpSearch();
        $dataProviderApproval = $searchModelApproval->searchApproval(Yii::$app->request->queryParams);

        return $this->render('list',[
            'pejabatPenilai' => $pejabatPenilai,
            'pegawaiDinilai' => $pegawaiDinilai,
            'jabatanPegawai' => $jabatanPegawai,
            'jabatanPenilai' => $jabatanPenilai,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelApproval' => $searchModelApproval,
            'dataProviderApproval' => $dataProviderApproval,
        ]);

    }

    public function actionPengukuran($id)
    {
        $model = $this->findModel($id);
        $searchModel = new SkpItemSearch();
        $searchModel->skp_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $model = SkpItem::findOne($id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            // $posted = $_POST['Skp'];
            $post = ['SkpItem' => $_POST];
            
            // load model like any single model validation
            if ($model->load($post)) {
            // can save model or do something before saving model
                // print_r($post);exit;
                if($model->save())
                {
                    $model->hitungSkp();
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($model);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
       
        return $this->render('pengukuran', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider            
        ]);
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

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $model = Skp::findOne($id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = $_POST['Skp'];
            $post = ['Skp' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
            // can save model or do something before saving model
                // print_r($post);exit;
                if($model->save())
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($model);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
       
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
