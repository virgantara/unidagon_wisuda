<?php
namespace app\helpers;

use Yii;
use yii\helpers\Url;
/**
 * Css helper class.
 */
class MenuHelper
{

	
    public static function getMenuItems()
    {


        $menuItems = [];

		if(!Yii::$app->user->isGuest)
		{
			$menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-home"></i><span>Dashboard</span>', 
		        'url' => ['site/index'],
		    ];

		    $menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-user"></i><span>Wisuda</span>', 
		        'url' => ['peserta/create'],
		        'visible' => Yii::$app->user->identity->access_role == ('member'),
		    ];		

			$menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-users"></i><span>Calon Wisudawan</span>', 
		        'url' => ['peserta/index'],
		        'visible' => Yii::$app->user->can('admin'),
		    ];

		    $menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-history"></i><span>Riwayat Pendaftaran</span>', 
		        'url' => ['peserta/riwayat'],
		        'visible' => Yii::$app->user->can('admin'),
		    ];		    

		    $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
	    		'visible' => Yii::$app->user->can('admin'),
		        'label' => '<i class="lnr lnr-cog"></i><span>Master</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_profil' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_profil">{label}</a>',
		        'items'=>[
		           	['label' => 'Periode', 'url' => ['/periode/index']],
		           	['label' => 'Syarat Wisuda', 'url' => ['/syarat/index']],
		           	['label' => 'Setting', 'url' => ['/setting/index']],
	               
		        ]
	        ];

	        $menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-user"></i><span>User</span>', 
		        'url' => ['user/index'],
		        'visible' => Yii::$app->user->can('theCreator'),
		    ];	

		    $menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-user"></i><span>Activity Logs</span>', 
		        'url' => ['logs/index'],
		        'visible' => Yii::$app->user->can('theCreator'),
		    ];		    
	          
		}

		else
		{
			// $menuItems[] = [ 
	  //           ['label' => 'Home', 'url' => ['/site/index']],
	  //           ['label'=>'Lecturer',
	  //                'items' => [
	  //                   ['label' => 'Search Lecturer', 'url' => ['/dosen/index']],    
	  //                   ['label' => 'Faculty of Ushuluddin', 'url' => ['/dosen/faculty','kategori'=>'1']],    
	  //                   ['label' => 'Faculty of Islamic Education', 'url' => ['/dosen/faculty','kategori'=>'2']],    
	  //                   ['label' => "Faculty of Shari'ah", 'url' => ['/dosen/faculty','kategori'=>'3']],    
	  //                   ['label' => 'Faculty of Economics and Management', 'url' => ['/dosen/faculty','kategori'=>'4']],    
	  //                   ['label' => 'Faculty of Humanities', 'url' => ['/dosen/faculty','kategori'=>'5']],    
	  //                   ['label' => 'Faculty of Science and Technology', 'url' => ['/dosen/faculty','kategori'=>'6']],    
	  //                   ['label' => 'Faculty of Health Science', 'url' => ['/dosen/faculty','kategori'=>'7']],    
	  //                           ],
	  //           ],

	  //           ['label' => 'Login', 'url' => ['/site/login']]

	  //       ];   
		}



		return $menuItems;
    }

    public static function getTopMenus()
    {
    	$menuItems = [];
    	$list_apps = [];
    	if(!Yii::$app->user->isGuest)
    	{
	    	$key = Yii::$app->params['jwt_key'];
	    	$session = Yii::$app->session;
	    	if($session->has('token'))
	    	{
		    	$token = $session->get('token');
		    	try
            	{
		        	$decoded = \Firebase\JWT\JWT::decode($token, base64_decode(strtr($key, '-_', '+/')), ['HS256']);
		        	foreach($decoded->apps as $d)
			        {
			        	$list_apps[] = [
			        		'template' => '<a target="_blank" href="{url}">{label}</a>',
			        		'label' => $d->app_name,
			        		'url' => $d->app_url.$token
			        	];
			        }
		        }
	        	catch(\Exception $e) 
	            {
	                // return Yii::$app->response->redirect(Yii::$app->params['sso_login']);
	            }
	        

		        
		    }
        }

    	if(!Yii::$app->user->isGuest)
    	{	
			
			$menuItems[] = [
		     	'template' => '<a href="{url}" >Logged in as {label}</a>',
		        'label' => '<strong>'.Yii::$app->user->identity->access_role.'</strong>',
		        'url' => ['site/change'],
		        
		    ];
    		
    		 $menuItems[] = [
		     	'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown"><i class="lnr lnr-layers"></i> <span>{label}</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>',
		        'label' => 'Your apps', 
		        'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
	         	'items' => $list_apps
		        
		    ];

		    $menuItems[] = [
		     	'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown"><i class="lnr lnr-user"></i> <span>{label}</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>',
		        'label' => Yii::$app->user->identity->nama, 
		        'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
	         	'items' => [
	         		[
	         			'template' => '<a href="{url}">{label}</a>',
		        		'label' => 'My Profile', 
	         			'url' => ['data-diri/create'],
	         			'visible' => !(Yii::$app->user->identity->access_role == 'Staf' || Yii::$app->user->identity->access_role == 'Tendik')
	         		],
	         		[
	         			'template' => '<a href="{url}">{label}</a>',
		        		'label' => 'My Profile', 
	         			'url' => ['tendik/view'],
	         			'visible' => (Yii::$app->user->identity->access_role == 'Staf' || Yii::$app->user->identity->access_role == 'Tendik')
	         		],
	         		// [
	         		// 	'template' => '<a href="{url}">{label}</a>',
		        		// 'label' => 'Change Role', 
	         		// 	'url' => ['site/change'],
	         		// 	'visible' => !(Yii::$app->user->identity->access_role == 'Staf' || Yii::$app->user->identity->access_role == 'Tendik')
	         		// ],
	         		[
	         			'template' => '<a href="{url}" data-method="POST">{label}</a>',
		        		'label' => 'Sign Out', 
	         			'url' => ['site/logout']
	         		]
	         	]
		        
		    ];
		}


    	return $menuItems;
    }

    public static function getUserMenus()
    {
    	$menuItems = [];

    	if(!Yii::$app->user->isGuest){

	
			$menuItems[] = [
		     	'template' => '<a data-widget="pushmenu" href="{url}" role="button" class="nav-link">{label}</a>',
		        'label' => '<i class="fas fa-bars"></i>', 
		        'url' => '#'
		    ];
		   

		}


    	return $menuItems;
    }
}