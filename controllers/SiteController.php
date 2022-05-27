<?php
namespace app\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\helpers\MyHelper;

use app\models\LoginForm;


use app\models\User;
use app\models\Periode;
use app\models\Peserta;
use app\models\Setting;

use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use \Firebase\JWT\JWT;
use yii\httpclient\Client;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $successUrl = '';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['logout', 'signup','testing'],
                'rules' => [
                    [
                        'actions' => [
                            'testing'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                    [
                        'actions' => ['signup','test'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => $this->successUrl
            ],
        ];
    }

    public function beforeAction($action)
    {
        
        $session = Yii::$app->session;

        if($session->has('token'))
        {

            try
            {

                $token = $session->get('token');
                $key = Yii::$app->params['jwt_key'];
                $decoded = \Firebase\JWT\JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);

            }

            catch(\Exception $e) 
            {
                $this->refreshToken($token);
            }
            
            if (!parent::beforeAction($action)) {
                return false;
            } 
        }

        else
        {
            
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        

        // other custom code here

        return true; // or false to not run the action
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        if(Yii::$app->user->isGuest){

            return $this->redirect(['site/registrasi']);
        }

        else{
            $setting = Setting::findOne(['kode_setting' => 'MAKLUMAT']);
            $periode = Periode::findOne(['status_aktivasi' => 'Y']);
            return $this->render('index',[
                'setting' => $setting,
                'periode' => $periode,
                // 'user' => $user  
            ]);    
        }
        
        // if(!parent::handleEmptyUser())
        // {
        //     return $this->redirect(Yii::$app->params['sso_login']);
        // }
        
        // $user = \app\models\User::findOne(Yii::$app->user->identity->id);
       
        

        
        
    }

    public function actionRegistrasi()
    {
        $this->layout = 'default';    
        $model = new Peserta;
        // $model->nim = '402019611018';
        $setting = Setting::findOne(['kode_setting' => 'MAKLUMAT']);


        return $this->render('registrasi',[
            'model' => $model,
            'setting' => $setting
        ]);  
    }

    public function actionAjaxCariUser() {

        $q = $_GET['term'];
        
        $query = DataDiri::find();
        $query->where(['LIKE','nama',$q]);
        $query->orWhere(['LIKE','NIY',$q]);
        $query->limit(10);
        $result1 = $query->asArray()->all();

        $query = Tendik::find();
        $query->where(['LIKE','nama',$q]);
        $query->orWhere(['LIKE','NIY',$q]);
        $query->limit(10);
        $result2 = $query->asArray()->all();
        $result = array_merge($result1, $result2);
        $out = [];

        // print_r($result);exit;
        if(count($result) > 0)
        {
            foreach ($result as $d) {
                $d = (object)$d;
                $out[] = [
                    'id' => $d->NIY,
                    'niy' => $d->NIY,
                    'label'=> $d->NIY.' - '.$d->nama,

                ];
            }
        }

        else
        {

           
            $out[] = [
                'id' => 0,
                'label'=> 'Data user tidak ditemukan',

            ];
            
        }
        
        

        echo \yii\helpers\Json::encode($out);


    }

    // public function actionChange()
    // {

    //     $id = Yii::$app->user->identity->id;
    //     // load user data
    //     $user = \app\models\User::findOne($id);

    //     $auth = Yii::$app->authManager;

    //     $roles = $auth->getRolesByUser($id);

    //     $user->item_name = $user->access_role;

    //     if (!$user->load(Yii::$app->request->post())) {
    //         return $this->render('change', ['user' => $user, 'role' => $user->item_name]);
    //     }


    //     $user->access_role = $user->item_name;
    //     if (!$user->save()) {
    //         return $this->render('change', ['user' => $user, 'role' => $user->item_name]);
    //     }

        

    //     // take new role from the form
    //     $newRole = $auth->getRole($user->item_name);
            
    //     $isExist = false;
    //     foreach($roles as $role)
    //     {
    //         $isExist = $role->name == $newRole->name;
    //         if($isExist)
    //             break;
            
    //     }

    //     $info = true;
    //     if(!$isExist){
    //         $info = $auth->assign($newRole, $user->id);
    //     }        

    //     if (!$info) {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
    //     }

    //     Yii::$app->session->setFlash('success', Yii::t('app', 'Role changed successfuly.'));
    //     return $this->redirect(['change', 'id' => $user->id]);
        
        
    // }


    public function actionAuthCallback()
    {
        $results = [];
        header('Content-Type: application/json');
        try
        {
            if(!empty($_SERVER['HTTP_X_JWT_TOKEN'])){
                
                $request_method = $_SERVER["REQUEST_METHOD"];

                switch($request_method)
                {
                    case 'POST' :
                        $token = $_SERVER['HTTP_X_JWT_TOKEN'];
                        $key = Yii::$app->params['jwt_key'];
                        $decoded = JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
                        $results = [
                            'code' => 200,
                            'message' => 'Valid'
                        ];   
                    break;
                    default:
                        header("HTTP/1.0 405 Method Not Allowed");
                        $results = [
                            'code' => 405,
                            'message' => $request_method.' Method not allowed '
                        ];   

                        echo json_encode($results);
                        exit;
                    break;
                }
            }

            else{
                header("HTTP/1.0 401 Bad Request");

                $results = [
                    'code' => 401,
                    'message' => 'Unauthorized Request '
                ];   

                echo json_encode($results);
                exit;
            }
            
        }
        catch(\Exception $e) 
        {

            $results = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($results);

        die();
       
    }

    public function actionLoginSso($token)
    {
        // print_r($token);exit;
        
        $key = Yii::$app->params['jwt_key'];
        $decoded = JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
        
        $uuid = $decoded->uuid; // will print "1"
        $user = \app\models\User::find()
            ->where([
                'uuid'=>$uuid,
            ])
            ->one();

        if(!empty($user))
        {
            
            $session = Yii::$app->session;
            $session->set('token',$token);
            
            
            // $exp = $total_bkd * 1000;
            // $exp += $totalCatatanHarian;
            // $level = MasterLevel::getLevel($exp);
            // $currentClass = GameLevelClass::getCurrentClass($level);
            // $nextLevel = MasterLevel::getNextLevel($exp);
            // $remainingExp = $nextLevel['nextExp'] - $exp;

            // $session->set('level',$user->level);
            // $session->set('class',$currentClass['class']);
            // $session->set('rank',$currentClass['rank']);
            // $session->set('stars',$currentClass['stars']);
            // $session->set('remainingExp',$remainingExp);
            // print_r($user);exit;
            Yii::$app->user->login($user);
            return $this->redirect(['site/index']);
        }

        else{
            
            
            return $this->redirect($decoded->iss.'/site/sso-callback?code=302')->send();
        }
       
    }

    public function actionLoginOtp($otp)
    {
        
        $user = User::find()
            ->where([
                'otp'=>$otp,
            ])
            ->one();

        if(!empty($user))
        {
            $api_baseurl = Yii::$app->params['invoke_token_uri'];
            $client = new Client(['baseUrl' => $api_baseurl]);
            $headers = [];

            $params = [
                'uuid' => $user->uuid
            ];
            
            $response = $client->get($api_baseurl, $params,$headers)->send();
            if ($response->isOk) {
                $res = $response->data;

                if($res['code'] != '200')
                {
                    return $this->redirect(Yii::$app->params['sso_login']);
                }

                else{
                    $session = Yii::$app->session;
                    $session->set('token',$res['token']);
                    $user->otp = null;
                    $user->save(false,['otp']);
                    
                    $api_baseurl = Yii::$app->params['api_baseurl'];
                    $client = new Client(['baseUrl' => $api_baseurl]);
                    $client_token = Yii::$app->params['client_token'];
                    $headers = ['x-access-token'=>$client_token];

                    $results = [];
                    
                    Yii::$app->user->login($user);
                    return $this->redirect(['site/index']);
                }
            }
            

        }

        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid OTP. Please contact your department administrator.'));
            
            return $this->redirect(['site/login']);
        }
        
    }

    public function successCallback($client)
    {

        $attributes = $client->getUserAttributes();
        // print_r($client);exit;
        $user = User::find()
            ->where([
                'email'=>$attributes['email'],
            ])
            ->one();

        if(!empty($user)){
            
            Yii::$app->user->login($user);
        }

        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid or Unregistered Email. Please use a valid unida.gontor.ac.id email address.'));
            
            return $this->redirect(['site/login']);
        }
        
    }

    

    public function actionHomelog()
    {

        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }else{ 
            $user = \app\models\User::findByEmail(Yii::$app->user->identity->email);
            $model = $user->dataDiri;
            return $this->render('homelog',['model'=>$model,]);
        }
    }


    public function actionUbahAkun()
    {
        $id = Yii::$app->user->identity->ID;
        // load user data
        $user = User::findOne($id);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('ubahAkun', ['user' => $user]);
        }

        // only if user entered new password we want to hash and save it
        if ($user->password) {
            $user->setPassword($user->password);
        }


        if (!$user->save()) {
            return $this->render('ubahAkun', ['user' => $user]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Data user telah diupdate.'));
        return $this->redirect(['ubah-akun']);
    }
        
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        
        // $session = Yii::$app->session;
        // $session->remove('token');
        Yii::$app->user->logout();
        // $url = Yii::$app->params['sso_logout'];
        return $this->redirect(['index']);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
