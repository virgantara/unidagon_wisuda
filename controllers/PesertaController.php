<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Periode;
use app\models\Peserta;
use app\models\PesertaSearch;
use app\models\Syarat;
use app\models\PesertaSyarat;

use app\helpers\MyHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
// use \Firebase\JWT\JWT;
use yii\httpclient\Client;
/**
 * PesertaController implements the CRUD actions for Peserta model.
 */
class PesertaController extends Controller
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
                'only' => ['create','update','delete','index','view','riwayat'],
                'rules' => [
                    
                    [
                        'actions' => ['create','update','delete','index','view','riwayat'],
                        'allow' => true,
                        'roles' => ['theCreator','admin']
                    ],
                    [
                        'actions' => ['create','update','view'],
                        'allow' => true,
                        'roles' => ['member']
                    ],
                    [
                        'actions' => ['create','update','view'],
                        'allow' => true,
                        'roles' => ['member']
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

    public function actionAjaxApprove()
    {
        $results = [];

        if(Yii::$app->request->isPost && !empty($_POST['dataPost'])){
            $dataPost = $_POST['dataPost'];

            $nim = $dataPost['nim'];
            $model = Peserta::findOne(['nim' => $nim]);
            
            if (!empty($model)) {
                
                $transaction = Yii::$app->db->beginTransaction();
                
                try {
                    $model->status_validasi = 'VALID';
                    if($model->save()){
                        $user = User::findOne(['nim' => $nim]);
                        $emailTemplate = $this->renderPartial('//site/email_approval',[
                            'model' => $model
                        ]);
                    
                        Yii::$app->mailer->compose()
                        ->setTo($user->email)
                        // ->setTo('vinux.edu@gmail.com')
                        ->setFrom([Yii::$app->params['supportEmail'] => 'Administrator'])
                        ->setSubject('[Approval] WISUDA UNIDA Gontor')
                        ->setHtmlBody($emailTemplate)
                        ->send();

                        $results = [
                            'code' => 200,
                            'message' => Yii::t('app', 'An email has been sent to this user')
                        ];

                        $activity = new \wdmg\activity\models\Activity;
                        $activity->setActivity(
                            Yii::$app->user->identity->username.' Approved Peserta Wisuda', 
                            'peserta_wisuda', 
                            'approval', 2
                        );
                        $transaction->commit();
                    }

                    else{
                        throw new \Exception(MyHelper::logError($model));
                        
                    }
                } 

                catch (\Exception $e) {
                    $transaction->rollBack();
                    $errors = $e->getMessage();
                    $results = [
                        'code' => 500,
                        'message' => Yii::t('app', $errors)
                    ];
                }
            }

            else{
                $results = [
                    'code' => 404,
                    'message' => Yii::t('app', 'Peserta not found')
                ];
            }
            
        }
        else{
            $results = [
                'code' => 400,
                'message' => Yii::t('app', 'Bad Request')
            ];
        }
        echo json_encode($results);
        exit;
    }

    public function actionAjaxTotalBelumLengkap()
    {
        $results = [];

        if(Yii::$app->request->isPost){
            $query = Peserta::find();
            $query->joinWith(['periode as p']);
            $query->where(['p.status_aktivasi' =>'Y']);
            $list_wisudawan = $query->all();

            $list_syarat = Syarat::find()->where(['is_aktif'=> 'Y'])->all();
            $total_syarat = count($list_syarat);
            $total = 0;
            foreach($list_wisudawan as $model){
                $counter = 0;
                $list_bukti_peserta = [];
                foreach($list_syarat as $syarat){
                    $ps = PesertaSyarat::findOne([
                        'peserta_id' => $model->id,
                        'syarat_id' => $syarat
                    ]);

                    if(!empty($ps)){
                        $counter++;
                    }
                }    

                $sisa = $total_syarat - $counter;
                if($sisa > 0) $total++;
            }
            

            $results = [
                'code' => 200,
                'total' => $total,
            ];
        }

        echo json_encode($results);
        exit;
    }


    public function actionAjaxTotalWisudawan()
    {
        $results = [];

        if(Yii::$app->request->isPost){
            $query = Peserta::find();
            $query->joinWith(['periode as p']);
            $query->where(['p.status_aktivasi' =>'Y']);
            $total_wisudawan = $query->count();

            $query = Peserta::find();
            $query->joinWith(['periode as p']);
            $query->where([
                'p.status_aktivasi' =>'Y',
                'status_validasi' => 'VALID'
            ]);
            $total_valid = $query->count();

            $query = Peserta::find();
            $query->joinWith(['periode as p']);
            $query->where([
                'p.status_aktivasi' =>'Y',                
            ]);

            $query->andWhere(['<>','status_validasi','VALID']);

            $total_invalid = $query->count();

            $results = [
                'code' => 200,
                'total_wisudawan' => $total_wisudawan,
                'total_invalid' => $total_invalid,
                'total_valid' => $total_valid
            ];
        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxProceed()
    {
        $results = [];

        if(Yii::$app->request->isPost && !empty($_POST['dataPost'])){
            $dataPost = $_POST['dataPost'];
            
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];

            $nim = $dataPost['nim'];

            $params = [
                'nim' => $nim
            ];
            $response = $client->get('/m/profil/nim', $params,$headers)->send();
            
            if ($response->isOk) {
                $tmp = $response->data['values'];
                $tmp = $tmp[0];
                $user = User::findOne(['username'=>$nim]);
                $auth = Yii::$app->authManager;
                $transaction = Yii::$app->db->beginTransaction();
                
                try {
                    if(empty($user)){
                        $pwd = MyHelper::getRandomString(6,6,true,false,true);
                        $user = new User;
                        $user->password = $pwd;
                        $user->setPassword($user->password);
                        $user->auth_key = Yii::$app->security->generateRandomString();
                        $user->username = $nim;
                        $user->status = 10;
                        $user->created_at = strtotime(date('Y-m-d H:i:s'));
                        $user->access_role = 'member';
                        $user->updated_at = strtotime(date('Y-m-d H:i:s'));
                        $user->email = $tmp['email'];
                        $user->uuid = $tmp['uuid'];
                        $user->nim = $nim;
                        
                        if($user->save()){
                            $role = $auth->getRole('member');
                            $info = $auth->assign($role, $user->getId());

                            if (!$info) {
                                $results = [
                                    'code' => 500,
                                    'message' => Yii::t('app', 'There was some error while saving user role.')
                                ];
                            }

                            if ($user->validate()) {
                                
                                
                                $emailTemplate = $this->renderPartial('//site/email',[
                                    'user'=>$user,
                                    'password' => $pwd
                                ]);
                            
                                Yii::$app->mailer->compose()
                                ->setTo($user->email)
                                // ->setTo('vinux.edu@gmail.com')
                                ->setFrom([Yii::$app->params['supportEmail'] => 'Administrator'])
                                ->setSubject('[Registration] WISUDA UNIDA Gontor')
                                ->setHtmlBody($emailTemplate)
                                ->send();

                                $results = [
                                    'code' => 200,
                                    'message' => Yii::t('app', 'Your account has been sent to your email. Please check your inbox/spam')
                                ];

                                $transaction->commit();
                            }
                            else{


                                $results = [
                                    'code' => 500,
                                    'message' => Yii::t('app', 'There was some error while registration')
                                ];
                            }
                        }   

                        else{
                            throw new \Exception(\app\helpers\MyHelper::logError($user));

                        } 

                    }

                    else{
                        $results = [
                            'code' => 200,
                            'message' => Yii::t('app', 'Your have been registered before. Please check your email inbox/spam')
                        ];
                    }

                    
                } 

                catch (\Exception $e) {
                    $transaction->rollBack();
                    $errors = $e->getMessage();
                    $results = [
                        'code' => 500,
                        'message' => Yii::t('app', $errors)
                    ];
                }
            }
            
        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxCekSiakad()
    {
        
        $results = [];
        if(Yii::$app->request->isPost && !empty($_POST['dataPost'])){

            $dataPost = $_POST['dataPost'];
            $api_baseurl = Yii::$app->params['api_baseurl'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $client_token = Yii::$app->params['client_token'];
            $headers = ['x-access-token'=>$client_token];

            $params = [
                'nim' => $dataPost['nim']
            ];
            $response = $client->get('/m/profil/nim', $params,$headers)->send();
            
            if ($response->isOk) {
                $tmp = $response->data['values'];
                if(!empty($tmp)) {
                    $results = [
                        'code' => 200,
                        'message' => 'Success',
                        'items' => $tmp[0]
                    ];
                }   
                else {

                    $results = [
                        'code' => 404,
                        'message' => 'Your data is not found'
                    ];    
                } 
                   
            }

            
        }

        else{

            header('HTTP/1.0 400 Bad Request');
            $results = [
                'code' => 400,
                'message' => 'Bad Request'
            ];
        }

        echo json_encode($results);
        exit;
    }

    public function actionRiwayat()
    {
        
        $searchModel = new PesertaSearch();
        $dataProvider = $searchModel->searchRiwayat(Yii::$app->request->queryParams);

        return $this->render('riwayat', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all Peserta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $periode = Periode::findOne(['status_aktivasi' => 'Y']);

        if(empty($periode)){
            Yii::$app->session->setFlash('danger','Mohon maaf, belum ada pendaftaran wisuda yang dibuka');
            return $this->redirect(['site/index']);
        }

        $searchModel = new PesertaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'periode' => $periode
        ]);
    }

    /**
     * Displays a single Peserta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $results = [];
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];

        $params = [
            'nim' => $model->nim
        ];
        $response = $client->get('/m/profil/nim', $params,$headers)->send();
        
        if ($response->isOk) {
            $tmp = $response->data['values'];
            if(!empty($tmp)) {
                $results = [
                    'code' => 200,
                    'message' => 'Success',
                    'items' => $tmp[0]
                ];
            }   
            else {

                $results = [
                    'code' => 404,
                    'message' => 'Your data is not found',
                    'items' => []
                ];    
            } 
               
        }

        
        $list_syarat = Syarat::find()->where(['is_aktif'=> 'Y'])->all();
        $list_bukti_peserta = [];
        $counter = 0;
        $jumlah_syarat = count($list_syarat);
        foreach($list_syarat as $syarat){
            $ps = PesertaSyarat::findOne([
                'peserta_id' => $model->id,
                'syarat_id' => $syarat
            ]);

            if(!empty($ps)){
                $counter++;
                $list_bukti_peserta[$syarat->id] = $ps; 
            }
        }

        $sisa = $jumlah_syarat - $counter;

        // print_r($results);exit;
        return $this->render('view_new', [
            'model' => $model,
            'sisa' => $sisa,
            'list_syarat' => $list_syarat,
            'results' => $results,
            'list_bukti_peserta' => $list_bukti_peserta
        ]);
    }

    /**
     * Creates a new Peserta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($step=1)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/logout']);
        }

        $list_syarat = Syarat::find()->where(['is_aktif'=> 'Y'])->all();
        $list_bukti_peserta = [];
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];

        $model = Peserta::findOne(['nim' => Yii::$app->user->identity->nim]);
        $periode = Periode::findOne(['status_aktivasi' => 'Y']);

        if(empty($model)){
            $model = new Peserta();
            $model->periode_id = $periode->id_periode;
            
            $params = [
                'nim' => Yii::$app->user->identity->nim
            ];
            $response = $client->get('/m/profil/nim', $params,$headers)->send();
            
            if ($response->isOk) {
                $tmp = $response->data['values'];

                if(count($tmp) > 0){
                    $tmp = $tmp[0];
                    $model->nim = $tmp['nim_mhs'];
                    $model->nama_lengkap = $tmp['nama_mahasiswa'];
                    $model->fakultas = $tmp['nama_fakultas'];
                    $model->prodi = $tmp['nama_prodi'];    
                    $model->tempat_lahir = $tmp['tempat_lahir'];
                    $model->tanggal_lahir = $tmp['tgl_lahir'];
                    $model->jenis_kelamin = $tmp['jenis_kelamin'];
                    $model->status_warga = $tmp['sw'];
                    $model->warga_negara = $tmp['wn'];
                    $model->alamat = $tmp['alamat'].' RT '.$tmp['rt'].'/RW '.$tmp['rw'].', '.$tmp['dusun'].', '.$tmp['desa'].', '.$tmp['kecamatan'].', '.$tmp['kab'].', '.$tmp['prov'];
                    $model->no_telp = $tmp['telepon'];
                }
                
            }
        }

        else{
            $params = [
                'nim' => Yii::$app->user->identity->nim
            ];
            $response = $client->get('/m/profil/nim', $params,$headers)->send();
            
            if ($response->isOk) {
                $tmp = $response->data['values'];
                
                if(count($tmp) > 0){
                    $tmp = $tmp[0];
                    $model->nim = $tmp['nim_mhs'];
                    $model->nama_lengkap = $tmp['nama_mahasiswa'];
                    $model->fakultas = $tmp['nama_fakultas'];
                    $model->prodi = $tmp['nama_prodi'];    
                    $model->tempat_lahir = $tmp['tempat_lahir'];
                    $model->tanggal_lahir = $tmp['tgl_lahir'];
                    $model->jenis_kelamin = $tmp['jenis_kelamin'];
                    $model->status_warga = $tmp['sw'];
                    $model->warga_negara = $tmp['wn'];
                    $model->alamat = $tmp['alamat'].' RT '.$tmp['rt'].'/RW '.$tmp['rw'].', '.$tmp['dusun'].', '.$tmp['desa'].', '.$tmp['kecamatan'].', '.$tmp['kab'].', '.$tmp['prov'];
                    $model->no_telp = $tmp['telepon'];
                }
                
            }
        }
        
        $model->scenario = 'sce_form'.$step;

        switch($step){
            case 1:
            break;
            case 2:
                $params = [
                    'nim' => Yii::$app->user->identity->nim
                ];
                $response = $client->get('/m/ortu', $params,$headers)->send();
                if ($response->isOk) {
                    $tmp = $response->data['values'];
                    foreach($tmp as $item){
                        if($item['hub'] == 'AYAH'){
                            $model->nama_ayah = $item['nm'];
                            $model->pekerjaan_ayah = $item['label'];
                        }

                        if($item['hub'] == 'IBU'){
                            $model->nama_ibu = $item['nm'];
                            $model->pekerjaan_ibu = $item['label'];
                        }                        
                    }
                }
            break;
            case 3 :
            case 4:
                

                foreach($list_syarat as $syarat){
                    $ps = PesertaSyarat::findOne([
                        'peserta_id' => $model->id,
                        'syarat_id' => $syarat
                    ]);
                    $list_bukti_peserta[$syarat->id] = $ps; 
                }
            break;
        }



        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");

            if($step == 4){
                return $this->redirect(['view', 'id' => $model->id]);
            }

            else{
                $step++;

                return $this->redirect(['create', 'step' => $step]);
            }
                 
        }


        return $this->render('create', [
            'model' => $model,
            'step' => $step,
            'list_syarat' => $list_syarat,
            'list_bukti_peserta' => $list_bukti_peserta
        ]);
    }

    /**
     * Updates an existing Peserta model.
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
     * Deletes an existing Peserta model.
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
     * Finds the Peserta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Peserta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Peserta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
