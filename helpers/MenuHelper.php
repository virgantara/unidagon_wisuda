<?php
namespace app\helpers;

use Yii;
use yii\helpers\Url;
/**
 * Css helper class.
 */
class MenuHelper
{

	public static function getMenus()
	{
		if(!Yii::$app->user->isGuest)
		{
			$list_staf = MyHelper::listRoleStaf();

			if(in_array(Yii::$app->user->identity->access_role, $list_staf))
			{
				return MenuHelper::getStafMenuItems();
			}

			else
			{
				return MenuHelper::getMenuItems();
			}
		}
	}

	public static function getStafMenuItems()
	{
		$menuItems = [];

		$menuItems[] = [
	    		'label' => '<i class="lnr lnr-list"></i><span>SKP</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_skp' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_skp">{label}</a>',
		        'items'=>[
		        	['label' => 'Form SKP', 'url' => ['/skp/list']],
		           	['label' => 'Pengukuran', 'url' => ['/skp/index']],
		           	// ['label' => 'Perilaku Kerja', 'url' => ['/skp/penilaian']],
		           	['label' => 'Penilaian', 'url' => ['/skp/list-penilaian']],
		           	['label' => 'Riwayat', 'url' => ['/skp/riwayat']],
		        ]
	        ];

	    return $menuItems;
	}

    public static function getMenuItems()
    {

    	// $userRole = Yii::$app->user->identity->access_role;
        $menuItems = [];

        // $currentRoute = Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
		if(!Yii::$app->user->isGuest)
		{
			$menuItems[] = [
		     	'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-home"></i><span>Dashboard</span>', 
		        'url' => ['site/index'],
		    ];

		    $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-user"></i><span>Profil</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_profil' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_profil">{label}</a>',
		        'items'=>[
		           	['label' => 'Data Pribadi', 'url' => ['/data-diri/create']],
	                ['label' => 'Inpassing', 'url' => ['/inpassing']],
	                ['label' => 'Jabatan Fungsional', 'url' => ['/jabatan-fungsional/index']],
	                ['label' => 'Kepangkatan', 'url' => ['/kepangkatan/index']],
	                ['label' => 'Penempatan', 'url' => ['/penugasan/index']],
		        ]
	        ];


	        
	        $menuItems[] = [
	    		'label' => '<i class="lnr lnr-list"></i><span>SKP</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_skp' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_skp">{label}</a>',
		        'items'=>[
		        	['label' => 'Form SKP', 'url' => ['/skp/list']],
		           	['label' => 'Pengisian', 'url' => ['/skp/index']],
		           	// ['label' => 'Perilaku Kerja', 'url' => ['/skp/penilaian']],
		           	['label' => 'Penilaian', 'url' => ['/skp/list-penilaian']],
		           	['label' => 'Riwayat', 'url' => ['/skp/riwayat']],
		        ]
	        ];
	        
	        $roles = ['Dekan','Kaprodi','Kepala','Ketua','Direktur','Rektor','Wakil Rektor'];
		    $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-book"></i><span>Catatan Harian</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_notes' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_notes">{label}</a>',
		        'items'=>[
		           	['label' => 'Daily', 'url' => ['/catatan-harian/index']],
		           	['label' => 'Manage', 'url' => ['/catatan-harian/list'],'visible'=>in_array(Yii::$app->user->identity->access_role, $roles)],
	                ['label' => 'Reports', 'url' => ['/catatan-harian/reports']],
		        ]
	        ];

	        $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-book"></i><span>Unit Kerja</span>', 
		        'url' => ['jabatan/list'],
		        'visible' => Yii::$app->user->can('pimpinan')
	        ];

		    $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-graduation-hat"></i><span>Kualifikasi</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_kualifikasi' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_kualifikasi">{label}</a>',
		        'items'=>[
		           	['label' => 'Pendidikan Formal', 'url' => ['/pendidikan/index']],
	                ['label' => 'Diklat', 'url' => ['/pelatihan/index']]
		        ]
	        ];

	        $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="fa fa-cubes"></i><span>Kompetensi</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_kompetensi' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_kompetensi">{label}</a>',
		        'items'=>[
		           	['label' => 'Sertifikasi', 'url' => ['/sertifikasi/index']],
	                ['label' => 'Tes', 'url' => ['/tes/index']]
		        ]
	        ];

	        $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-book"></i><span>Pelaks. Pendidikan</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_pendidikan' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_pendidikan">{label}</a>',
		        'items'=>[
		           	['label' => 'Pengajaran', 'url' => ['/pengajaran/index']],
		           	['label' => 'Bimbingan Mahasiswa', 'url' => ['/bimbingan-mahasiswa/index']],
		           	['label' => 'Pengujian Mahasiswa', 'url' => ['/uji-mahasiswa/index']],
		           	['label' => 'Visiting Scientist', 'url' => ['/visiting-scientist/index']],
		           	['label' => 'Bahan Ajar', 'url' => ['/bahan-ajar/index']],
		           	['label' => 'Orasi ilmiah', 'url' => ['/orasi-ilmiah/index']],
		           	['label' => 'Tugas Tambahan', 'url' => ['/jabatan/index']],
		        ]
	        ];

	        
	       	$menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-book"></i><span>Pelaks. Penelitian</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_penelitian' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_penelitian">{label}</a>',
		        'items'=>[
		           	['label' => 'Penelitian', 'url' => ['/penelitian/index']],
		           	['label' => 'Publikasi karya', 'url' => ['/publikasi/index']],
		           	// ['label' => 'Buku', 'url' => ['/buku/index']],
		           	['label' => 'Karya Lain', 'url' => ['/luaran-lain/index']],
		           	['label' => 'Paten/HKI', 'url' => ['/hki/index']],
		           	
		        ]
	        ];

	        $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-book"></i><span>Pelaks. Pengabdian</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_pengabdian' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_pengabdian">{label}</a>',
		        'items'=>[
		           	['label' => 'Pengabdian', 'url' => ['/pengabdian/index']],
		           	['label' => 'Pengelola jurnal', 'url' => ['/pengelola-jurnal/index']],
		           	['label' => 'Pembicara', 'url' => ['/pembicara/index']],
		           	['label' => 'Jabatan Struktural', 'url' => ['/organisasi/index']],
		           	
		        ]
	        ];

	        $menuItems[] = [
	    		'template' => '<a href="{url}">{label}</a>',
		        'label' => '<i class="lnr lnr-book"></i><span>Penunjang</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_penunjang' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_penunjang">{label}</a>',
		        'items'=>[
		           	['label' => 'Anggota Profesi', 'url' => ['/organisasi/index']],
		           	
		           	['label' => 'Penghargaan', 'url' => ['/penghargaan/index']],
		           	
		           	['label' => 'Penunjang lain', 'url' => ['penunjang-lain/index']],
		        ]
	        ];

	      //   $menuItems[] = [
	    		// 'label' => '<i class="lnr lnr-chart-bars"></i><span>Luaran</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		     //    'url' => '#',
		     //    'submenuTemplate' => "\n<div id='pages_luaran' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		     //    'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_luaran">{label}</a>',
		     //    'items'=>[
		     //       	['label' => 'Jurnal', 'url' => ['/jurnal/index']],
       //              ['label' => 'Buku', 'url' => ['/buku/index']],    
       //              ['label' => 'Forum Ilmiah', 'url' => ['/konferensi/index']],
       //              ['label' => 'HKI', 'url' => ['/hki/index']],
       //              ['label' => 'Luaran Lain', 'url' => ['/luaran-lain/index']], 
		     //    ]
	      //   ];


	        $menuItems[] = [
	    		'label' => '<i class="lnr lnr-list"></i><span>Layanan BKD</span><i class="icon-submenu lnr lnr-chevron-left"></i>', 
		        'url' => '#',
		        'submenuTemplate' => "\n<div id='pages_bkd' class='collapse'><ul class='nav'>\n{items}\n</ul></div>\n",
		        'template' => '<a class="collapsed" data-toggle="collapse" href="#pages_bkd">{label}</a>',
		        'items'=>[
		        	['label' => 'Klaim Kegiatan', 'url' => ['/bkd/klaim']],
		           	['label' => 'BKD Saya', 'url' => ['/bkd/index']],
		           	['label' => 'Rubrik', 'url' => ['/tugas-dosen-bkd/index']],
		        ]
	        ];

	        $menuItems[] = [
	    		'label' => '<i class="lnr lnr-sync"></i><span>Sinkronisasi</span>', 
		        'url' => ['site/sync'],
		        
	        ];

	            
	          
		}

		else
		{
			$menuItems[] = [ 
	            ['label' => 'Home', 'url' => ['/site/index']],
	            ['label'=>'Lecturer',
	                 'items' => [
	                    ['label' => 'Search Lecturer', 'url' => ['/dosen/index']],    
	                    ['label' => 'Faculty of Ushuluddin', 'url' => ['/dosen/faculty','kategori'=>'1']],    
	                    ['label' => 'Faculty of Islamic Education', 'url' => ['/dosen/faculty','kategori'=>'2']],    
	                    ['label' => "Faculty of Shari'ah", 'url' => ['/dosen/faculty','kategori'=>'3']],    
	                    ['label' => 'Faculty of Economics and Management', 'url' => ['/dosen/faculty','kategori'=>'4']],    
	                    ['label' => 'Faculty of Humanities', 'url' => ['/dosen/faculty','kategori'=>'5']],    
	                    ['label' => 'Faculty of Science and Technology', 'url' => ['/dosen/faculty','kategori'=>'6']],    
	                    ['label' => 'Faculty of Health Science', 'url' => ['/dosen/faculty','kategori'=>'7']],    
	                            ],
	            ],

	            ['label' => 'Login', 'url' => ['/site/login']]

	        ];   
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
		        'url' => '#'
		        
		    ];
    		$class = Yii::$app->user->identity->class ?: '';
    		$stars = Yii::$app->user->identity->stars ?: '';
    		$label_stars = '';
    		for($i=0;$i<$stars;$i++){
    			$label_stars .= '<i class="lnr lnr-star"></i>';
    		}
    		$menuItems[] = [
		     	'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown">'.$label_stars.' <span class="badge bg-success">'.$class.'</span></a>',
		        'label' => ''
		        
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
	         		[
	         			'template' => '<a href="{url}">{label}</a>',
		        		'label' => 'Change Role', 
	         			'url' => ['site/change'],
	         			'visible' => !(Yii::$app->user->identity->access_role == 'Staf' || Yii::$app->user->identity->access_role == 'Tendik')
	         		],
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