<?php

namespace app\controllers;

use Yii;
use app\models\AuthAssignment;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                'only' => ['create','update','delete','index'],
                'rules' => [
                    [
                        'actions' => [
                            'create','update','delete','index'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                    
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionAjaxAddRole()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $user = $this->findModel($dataPost['user_id']);

        $auth = Yii::$app->authManager;
        if(!empty($user))
        {
            $auth = Yii::$app->authManager;

            $newRole = $auth->getRole($dataPost['item_name']);

            $userId = $user->getId();
            $info = $auth->assign($newRole, $userId);
            

            if (!$info) {
                $errors .= 'There was some error while saving user role.';
                throw new \Exception;
            }

            
            $results = [
                'code' => 200,
                'message' => 'Data added'
            ];
            

        }

        else
        {
            $results = [
                'code' => 500,
                'message' => 'User not found'
            ];
        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxDeleteRole()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $user = $this->findModel($dataPost['user_id']);

        
        if(!empty($user))
        {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $errors = '';
            try 
            {
                $items = AuthAssignment::find()->where([
                    'user_id' => $user->id
                ])->all();

                if(count($items) > 0)
                {
                    

                    if($user->access_role != $dataPost['item_name'])
                    {
                        $auth = Yii::$app->authManager;
                        if ($roles = $auth->getRolesByUser($user->id)) {
                            // it's enough for us the get first assigned role name
                            $role = '';
                            foreach($roles as $r)
                            {
                                
                                if($r->name == $dataPost['item_name'])
                                {
                                    $role = $r->name;
                                    break;
                                }
                            }

                        }

                        // print_r($roles);exit;

                        // remove role if user had it
                        if (isset($role)) {
                           
                            $info = $auth->revoke($auth->getRole($role), $user->id);
                        }

                        if(!$info)
                        {
                            $errors .= 'Something wrong when deleting role';
                            throw new \Exception;
                        }

                        $transaction->commit();
                        $results = [
                            'code' => 200,
                            'message' => 'Role deleted'
                        ];
                    }
                    
                    else
                    {
                        $errors .= 'Cannot delete assigned role';
                        throw new \Exception;
                    }
                }

                else
                {
                    $errors .= 'Cannot delete the last role';
                    throw new \Exception;
                    
                }

            } 

            catch (\Exception $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
                
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
            }
            
        }

        else
        {
            $results = [
                'code' => 500,
                'message' => 'User not found'
            ];
        }

        echo json_encode($results);
        exit;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) 
        {
            
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $model = User::findOne($id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['User']);
            $post = ['User' => $posted];
            if ($model->load($post)) {
                if($model->save())
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $errors = \app\helpers\MyHelper::logError($model);
                    $out = json_encode(['output'=>'', 'message'=>$errors]);
                }
            }

            echo $out;

            return;
            
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'create']);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('create', ['user' => $user]);
        }

        $user->setPassword($user->password);
        $user->generateAuthKey();
        // $user->access_role;
        $user->updated_at = strtotime(date('Y-m-d H:i:s'));
        $user->created_at = strtotime(date('Y-m-d H:i:s'));
        if (!$user->save()) {
            return $this->render('create', ['user' => $user]);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($user->access_role);

        $info = $auth->assign($role, $user->getId());

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

       

        return $this->redirect('index');
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $auth = Yii::$app->authManager;

        $authItems = \app\models\AuthItem::find()->select(['name'])->all();

        // get user role if he has one  
        $roles = $auth->getRolesByUser($id);
        $role = $user->access_role; 
        
        // if user has role, set oldRole to that role name, else offer 'member' as sensitive default
        $oldRole = (isset($role)) ? $auth->getRole($role) : $auth->getRole('member');

        // set property item_name of User object to this role name, so we can use it in our form
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $counter = 0;
        $errors = '';
        try 
        {
            if (!$user->load(Yii::$app->request->post())) {
                return $this->render('update', [
                    'user' => $user, 
                    'role' => $user->access_role,
                    'authItems' => $authItems
                ]);
            }

            // only if user entered new password we want to hash and save it
            if ($user->password) {
                $user->setPassword($user->password);
            }

            // if admin is activating user manually we want to remove account activation token
            if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
                $user->removeAccountActivationToken();
            }         
            

            // $user->access_role = $user->item_name;
            if (!$user->save()) {

                return $this->render('update', [
                    'user' => $user, 
                    'role' => $user->access_role,
                    'authItems' => $authItems
                ]);
            }

            $is_new = true;
            foreach($roles as $r)
            {
                if($r->name == $user->access_role)
                {
                    $is_new = false;
                    break;
                }
            }

            if($is_new)
            {

            // take new role from the form
                $newRole = $auth->getRole($user->access_role);
                // get user id too

                $userId = $user->getId();
                
                $info = $auth->assign($newRole, $userId);
                

                if (!$info) {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
                    throw new \Exception;
                    
                }
            }

            $transaction->commit();
            return $this->redirect(['view', 'id' => $user->id]);
        }

        catch(\Exception $e)
        {
            return $this->render('update', [
                'user' => $user, 
                'role' => $user->access_role,
                'authItems' => $authItems
            ]);
        }

    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
