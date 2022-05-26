<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Periode;
use app\models\Peserta;
use app\models\PesertaSearch;

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
            $response = $client->get('/u/mhs/nim', $params,$headers)->send();
            
            if ($response->isOk) {
                $tmp = $response->data['values'];
                
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
                                // ->setTo($user->email)
                                ->setTo('vinux.edu@gmail.com')
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Peserta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Peserta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data tersimpan");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
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
