<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use app\models\Skp;
use app\models\SkpTemplate;
use app\models\KomponenKegiatan;
use app\models\DataDiri;
use app\models\SkpPerilaku;
use app\models\MJabatan;
use app\models\Jabatan;
use app\models\BkdPeriode;
use app\models\SkpPeriode;
use app\models\SkpItem;
use app\models\Pengajaran;
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
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create','update','delete','index','list','riwayat','list-penilaian','realisasi','pengisian','print-ptkis'],
                'rules' => [
                    [
                        'actions' => [
                            'create','update','delete','index','list','riwayat','list-penilaian','realisasi','pengisian','print-ptkis'
                        ],
                        'allow' => true,
                        'roles' => ['Dosen','Dekan','Kaprodi','Kepala','Kepala Bagian','Kepala Biro','Kepala TU','Staf','Staf TU','Staf UPT','Staf Biro'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','list','riwayat','list-penilaian','realisasi','pengisian','print-ptkis'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator','admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','bayar'],
                ],
            ],
        ];
    }

    public function actionPrintPtkis($id)
    {
        $model = $this->findModel($id);
        
        try
        {
            $session = Yii::$app->session;
            $tahun_id = '';
            $sd = '';
            $ed = '';
            $periode = SkpPeriode::findOne(['buka' => 'Y']);
            // if($session->has('bkd_periode'))
            // {
            //     $tahun_id = $session->get('bkd_periode');
            //   // $session->get('bkd_periode_nama',$bkd_periode->nama_periode);
            //     $sd = $session->get('tgl_awal');
            //     $ed = $session->get('tgl_akhir');  
            //     $bkd_periode = BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
            // }
            // else{
            //     $bkd_periode = BkdPeriode::find()->where(['buka' => 'Y'])->one();
            //     $tahun_id = $bkd_periode->tahun_id;
            //     $sd = $bkd_periode->tanggal_bkd_awal;
            //     $ed = $bkd_periode->tanggal_bkd_akhir;
            // }

            $skpPerilaku = $model->skpPerilaku;
       
            $pegawaiDinilai = $model->pegawaiDinilai;
            $pejabatPenilai = $model->pejabatPenilai;
            $atasanPejabatPenilai = $model->atasanPejabatPenilai;

            $capaian_total = 0;
            $avg_capaian_skp = 0;
            $counter=0;
            foreach($model->skpItems as $q => $item)
            {
                $counter++;

                $item->hitungSkp();
                $penghitungan = $item->capaian;
                $tmp = $item->capaian_skp;
                $capaian_total += $tmp;
            }

            if($counter > 0)
                $avg_capaian_skp = $capaian_total / $counter;

            $bobot_capaian_skp = $avg_capaian_skp * 0.6;
            $bobot_avg_perilaku = !empty($skpPerilaku) ? $skpPerilaku->rata_rata * 0.4 : 0;

            $total_prestasi = $bobot_capaian_skp + $bobot_avg_perilaku;
       
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->SetMargins(30, 10, 15, true); // set the margins 
            $fontpath = Yii::getAlias('@webroot').'/klorofil/assets/fonts/Cambria.ttf';
            
            $fontreg = \TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 86);
            $pdf->SetFont($fontreg, '', 12);
            

            $pdf->AddPage('P');

            $pdf->SetLineStyle( array( 'width' => 0.7, 'color' => array(0,0,0)));
            $pdf->Rect(15, 30, $pdf->getPageWidth()-30, $pdf->getPageHeight()-60);

            ob_start();
            echo $this->renderPartial('cover_ptkis', [
                'user' => $model->pegawaiDinilai,
                'model' => $model,
                'periode' =>   $periode,
            ]);

            
            $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo_garuda.png';
            
            $data = ob_get_clean();
            // ob_start();
            $pdf->Image($imgdata,$pdf->getPageWidth()/2 - 15,50,30);
            $pdf->Ln(50);
            $pdf->writeHTML($data);
            
            // $pdf->writeHTMLCell(50, 38, '', $y, $grades, 1, 0, 0, true, 'J', true);
            // $pdf->writeHTMLCell($pdf->getPageWidth() - 50,10,25,50,$data);

            ob_start();
            echo $this->renderPartial('print_formulir_ptkis', [
                 'model' => $model,
                 'user' => $model->pegawaiDinilai,
                 'periode' =>   $periode,
                 'atasanPejabatPenilai' => $atasanPejabatPenilai, 
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->SetMargins(10, 10, 10, true); // set the margins

            $pdf->AddPage('L');
            // $pdf->resetColumns();
            // $pdf->setEqualColumns(2, 130);  // KEY PART -  number of cols and width
            // $pdf->selectColumn();               
            // $content =' ';
            $pdf->writeHTML($data);
            // $pdf->resetColumns();

            $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo_garuda.png';
            
            $data = ob_get_clean();
            // ob_start();
            $pdf->Image($imgdata,$pdf->getPageWidth() * 0.68,10,18);
            $pdf->Ln(50);
            $pdf->writeHTML($data);

            ob_start();
            echo $this->renderPartial('print_formulir_ptkis2', [
                 'model' => $model,
                 'user' => $model->pegawaiDinilai,
                 'periode' =>   $periode,
                 'atasanPejabatPenilai' => $atasanPejabatPenilai, 
                 'skpPerilaku' => $skpPerilaku,
                'avg_capaian_skp' => $avg_capaian_skp,
                'bobot_capaian_skp' => $bobot_capaian_skp,
                'bobot_avg_perilaku' => $bobot_avg_perilaku,
                'total_prestasi' => $total_prestasi,
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->SetMargins(10, 10, 10, true); // set the margins

            $pdf->AddPage('L');
            // $pdf->resetColumns();
            // $pdf->setEqualColumns(2, 130);  // KEY PART -  number of cols and width
            // $pdf->selectColumn();               
            // $content =' ';
            $pdf->writeHTML($data);
            
            $list_unsur = [
                'AJAR' => 'A. Melaksanakan Pendidikan dan Pengajaran',
                'RISET' => 'B. Melaksanakan Penelitian',
                'ABDIMAS' => 'C. Melaksanakan Pengabdian Masyarakat',
                'PENUNJANG' => 'D. Melaksanakan Kegiatan Penunjang Akademik'
            ];

            $list_tridharma = [];
            foreach($list_unsur as $q => $v):
                $query = SkpItem::find();
                $query->alias('t');
                $query->joinWith(['komponenKegiatan as kk','komponenKegiatan.unsur as u']);
                $query->where([
                    't.skp_id' => $model->id,
                    'u.kode' => $q
                ]);

                $list_tridharma[$q] = $query->all();
            endforeach;
            ob_start();
            echo $this->renderPartial('print_sasaran_kinerja', [
                'list_tridharma' => $list_tridharma,
                'list_unsur' => $list_unsur,
                'model' => $model,
                 'user' => $model->pegawaiDinilai,
                 'periode' =>   $periode,
                 'atasanPejabatPenilai' => $atasanPejabatPenilai, 
                 'skpPerilaku' => $skpPerilaku,
                'avg_capaian_skp' => $avg_capaian_skp,
                'bobot_capaian_skp' => $bobot_capaian_skp,
                'bobot_avg_perilaku' => $bobot_avg_perilaku,
                'total_prestasi' => $total_prestasi,
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->SetMargins(10, 10, 10, true); // set the margins

            $pdf->AddPage('P');
            // $pdf->resetColumns();
            // $pdf->setEqualColumns(2, 130);  // KEY PART -  number of cols and width
            // $pdf->selectColumn();               
            // $content =' ';
            $pdf->writeHTML($data);

            $nama = $model->pegawai_dinilai;
            $pdf->Output('skp_'.$nama.''.rand(1,100).'.pdf','I');
        }
        catch(\HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
        die();
    }

    public function actionPrintRencana($id)
    {
        $model = $this->findModel($id);
        
        try
        {
            $session = Yii::$app->session;
            $tahun_id = '';
            $sd = '';
            $ed = '';
            $bkd_periode = null;
            if($session->has('bkd_periode'))
            {
                $tahun_id = $session->get('bkd_periode');
              // $session->get('bkd_periode_nama',$bkd_periode->nama_periode);
                $sd = $session->get('tgl_awal');
                $ed = $session->get('tgl_akhir');  
                $bkd_periode = BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
            }
            else{
                $bkd_periode = BkdPeriode::find()->where(['buka' => 'Y'])->one();
                $tahun_id = $bkd_periode->tahun_id;
                $sd = $bkd_periode->tanggal_bkd_awal;
                $ed = $bkd_periode->tanggal_bkd_akhir;
            }

            $skpPerilaku = $model->skpPerilaku;
       
            $pegawaiDinilai = $model->pegawaiDinilai;
            $pejabatPenilai = $model->pejabatPenilai;
            $atasanPejabatPenilai = $model->atasanPejabatPenilai;

            $capaian_total = 0;
            $avg_capaian_skp = 0;
            $counter=0;
            foreach($model->skpItems as $q => $item)
            {
                $counter++;

                $item->hitungSkp();
                $penghitungan = $item->capaian;
                $tmp = $item->capaian_skp;
                $capaian_total += $tmp;
            }

            if($counter > 0)
                $avg_capaian_skp = $capaian_total / $counter;

            $bobot_capaian_skp = $avg_capaian_skp * 0.6;
            $bobot_avg_perilaku = !empty($skpPerilaku) ? $skpPerilaku->rata_rata * 0.4 : 0;

            $total_prestasi = $bobot_capaian_skp + $bobot_avg_perilaku;
       
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $fontpath = Yii::getAlias('@webroot').'/klorofil/assets/fonts/pala.ttf';
            
            $fontreg = \TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 86);
            $pdf->SetFont($fontreg, '', 12);
            $pdf->AddPage();
            ob_start();
            echo $this->renderPartial('cover_rencana', [
                'user' => $model->pegawaiDinilai,
                'model' => $model,
                'bkd_periode' =>   $bkd_periode,
            ]);

            $data = ob_get_clean();
            ob_start();
            $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            $pdf->Image($imgdata,$pdf->getPageWidth()/2 - 10,10,20);
            $pdf->Ln(50);
            // $pdf->writeHTMLCell(50, 38, '', $y, $grades, 1, 0, 0, true, 'J', true);
            $pdf->writeHTMLCell($pdf->getPageWidth() - 50,10,25,50,$data, 0, 0, 0, true, 'J', true);

            ob_start();
            echo $this->renderPartial('print_formulir', [
                 'model' => $model,
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->AddPage();
            // $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            // $pdf->Image($imgdata,10,10,15);
            $pdf->writeHTML($data);


            

            $nama = $model->pegawai_dinilai;
            $pdf->Output('skp_'.$nama.''.rand(1,100).'.pdf','I');
        }
        catch(\HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
        die();
    }

    public function actionRiwayat()
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
        $list_staf = MyHelper::listRoleStaf();
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

            else if(in_array(Yii::$app->user->identity->access_role, $list_staf) && !empty($unker) && !empty($unker->pejabat))
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

        return $this->render('riwayat',[
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

    public function actionListPenilaian()
    {

        if(Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $session->remove('token');
            Yii::$app->user->logout();
            $url = Yii::$app->params['sso_logout'];
            return $this->redirect($url);
        }

        $session = Yii::$app->session;
        $tahun_id = '';
        $sd = '';
        $ed = '';
        $bkd_periode = null;
        if($session->has('bkd_periode'))
        {
            $tahun_id = $session->get('bkd_periode');
          // $session->get('bkd_periode_nama',$bkd_periode->nama_periode);
            $sd = $session->get('tgl_awal');
            $ed = $session->get('tgl_akhir');  
            $bkd_periode = BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
        }
        else{
            $bkd_periode = BkdPeriode::find()->where(['buka' => 'Y'])->one();
            $tahun_id = $bkd_periode->tahun_id;
            $sd = $bkd_periode->tanggal_bkd_awal;
            $ed = $bkd_periode->tanggal_bkd_akhir;
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
        $atasanPejabatPenilai = null;
        $jabatanAtasanPenilai = null;

        $skpPerilaku = null;



        $model = Skp::findOne([
            'periode_id' => $bkd_periode->tahun_id,
            'pegawai_dinilai' => $user->NIY
        ]);

        $capaian_total = 0;
        $avg_capaian_skp = 0;
        $counter=0;
        $bobot_capaian_skp = 0;
        $bobot_avg_perilaku = 0;
        $total_prestasi = 0;
        if(!empty($model))
        {
            $skpPerilaku = $model->skpPerilaku;
           
            $pegawaiDinilai = $model->pegawaiDinilai;
            $pejabatPenilai = $model->pejabatPenilai;
            $atasanPejabatPenilai = $model->atasanPejabatPenilai;

            
            foreach($model->skpItems as $q => $item)
            {
                $counter++;

                $item->hitungSkp();
                $penghitungan = $item->capaian;
                $tmp = $item->capaian_skp;
                $capaian_total += $tmp;
            }



            if($counter > 0)
                $avg_capaian_skp = $capaian_total / $counter;

            $bobot_capaian_skp = $avg_capaian_skp * 0.6;
            $bobot_avg_perilaku = !empty($skpPerilaku) ? $skpPerilaku->rata_rata * 0.4 : 0;

            $total_prestasi = $bobot_capaian_skp + $bobot_avg_perilaku;

        }

        $access_role = Yii::$app->user->identity->access_role;
        $list_staf = MyHelper::listRoleStaf();
        if(!empty($jabatan))
        {
            $unker = $jabatan->unker;

            if(in_array($access_role,['Kaprodi','Dekan','Kepala UPT','Kepala','Kepala TU']) && !empty($unker) && !empty($unker->parent) && !empty($unker->parent->pejabat))
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

            else if(in_array($access_role, $list_staf) && !empty($unker) && !empty($unker->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->pejabat) ? $unker->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();    
            }

            if(!empty($unker->parent) && !empty($unker->parent->pejabat))
            {
                $niyAtasanAsesor = $unker->parent->pejabat->NIY;

                $jabatanAtasanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->parent->jabatan_id,
                    'NIY' => $niyAtasanAsesor
                ])->one();

                $atasanPejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAtasanAsesor])->one();
                
            }
            

            $jabatanPegawai = !empty($jabatan) ? $jabatan : null;
        }

        $searchModel = new SkpSearch();
        $dataProvider = $searchModel->searchPenilaianAtasan(Yii::$app->request->queryParams);

    
        return $this->render('list_penilaian',[
            'pejabatPenilai' => $pejabatPenilai,
            'pegawaiDinilai' => $pegawaiDinilai,
            'jabatanPegawai' => $jabatanPegawai,
            'jabatanPenilai' => $jabatanPenilai,
            'jabatanAtasanPenilai' => $jabatanAtasanPenilai,
            'atasanPejabatPenilai' => $atasanPejabatPenilai,
            'searchModel' => $searchModel,
            'skpPerilaku' => $skpPerilaku,
            'dataProvider' => $dataProvider,
            'avg_capaian_skp' => $avg_capaian_skp,
            'bobot_capaian_skp' => $bobot_capaian_skp,
            'bobot_avg_perilaku' => $bobot_avg_perilaku,
            'total_prestasi' => $total_prestasi
        ]);

    }

    public function actionPenilaian($id)
    {
        $model = $this->findModel($id);

        $skpPerilaku = $model->skpPerilaku;
       
        $pegawaiDinilai = $model->pegawaiDinilai;
        $pejabatPenilai = $model->pejabatPenilai;
        $atasanPejabatPenilai = $model->atasanPejabatPenilai;

        $capaian_total = 0;
        $avg_capaian_skp = 0;
        $counter=0;
        foreach($model->skpItems as $q => $item)
        {
            $counter++;

            $item->hitungSkp();
            $penghitungan = $item->capaian;
            $tmp = $item->capaian_skp;
            $capaian_total += $tmp;
        }

        if($counter > 0)
            $avg_capaian_skp = $capaian_total / $counter;

        $bobot_capaian_skp = $avg_capaian_skp * 0.6;
        $bobot_avg_perilaku = !empty($skpPerilaku) ? $skpPerilaku->rata_rata * 0.4 : 0;

        $total_prestasi = $bobot_capaian_skp + $bobot_avg_perilaku;

        if (Yii::$app->request->post('hasEditable')) 
        {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $skpPerilaku = SkpPerilaku::find($id)->where(['skp_id' => $id])->one();
            if(empty($skpPerilaku)){
                $skpPerilaku = new SkpPerilaku;
                $skpPerilaku->id = MyHelper::gen_uuid();
                $skpPerilaku->skp_id = $id;
            }
            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            // $posted = $_POST['Skp'];
            $post = ['SkpPerilaku' => $_POST];
            
            
            // load model like any single model validation
            if ($skpPerilaku->load($post)) {
            // can save model or do something before saving model
                // print_r($post);exit;
                $skpPerilaku->total = $skpPerilaku->orientasi + $skpPerilaku->integritas + $skpPerilaku->komitmen + $skpPerilaku->disiplin + $skpPerilaku->kerjasama + $skpPerilaku->kepemimpinan;
                $skpPerilaku->rata_rata = $skpPerilaku->total / 6;

                if($skpPerilaku->save())
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($skpPerilaku);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }
       
        return $this->render('penilaian', [
            'model' => $model,
            'pegawaiDinilai' => $pegawaiDinilai,
            'pejabatPenilai' => $pejabatPenilai,
            'atasanPejabatPenilai' => $atasanPejabatPenilai,    
            'skpPerilaku' => $skpPerilaku,
            'avg_capaian_skp' => $avg_capaian_skp,
            'bobot_capaian_skp' => $bobot_capaian_skp,
            'bobot_avg_perilaku' => $bobot_avg_perilaku,
            'total_prestasi' => $total_prestasi        
        ]);
    }

    public function actionPrintFormulir($id)
    {
        $model = $this->findModel($id);
        
        try
        {
            $session = Yii::$app->session;
            $tahun_id = '';
            $sd = '';
            $ed = '';
            $bkd_periode = null;
            if($session->has('bkd_periode'))
            {
                $tahun_id = $session->get('bkd_periode');
              // $session->get('bkd_periode_nama',$bkd_periode->nama_periode);
                $sd = $session->get('tgl_awal');
                $ed = $session->get('tgl_akhir');  
                $bkd_periode = BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
            }
            else{
                $bkd_periode = BkdPeriode::find()->where(['buka' => 'Y'])->one();
                $tahun_id = $bkd_periode->tahun_id;
                $sd = $bkd_periode->tanggal_bkd_awal;
                $ed = $bkd_periode->tanggal_bkd_akhir;
            }

            $skpPerilaku = $model->skpPerilaku;
       
            $pegawaiDinilai = $model->pegawaiDinilai;
            $pejabatPenilai = $model->pejabatPenilai;
            $atasanPejabatPenilai = $model->atasanPejabatPenilai;

            $capaian_total = 0;
            $avg_capaian_skp = 0;
            $counter=0;
            foreach($model->skpItems as $q => $item)
            {
                $counter++;

                $item->hitungSkp();
                $penghitungan = $item->capaian;
                $tmp = $item->capaian_skp;
                $capaian_total += $tmp;
            }

            if($counter > 0)
                $avg_capaian_skp = $capaian_total / $counter;

            $bobot_capaian_skp = $avg_capaian_skp * 0.6;
            $bobot_avg_perilaku = !empty($skpPerilaku) ? $skpPerilaku->rata_rata * 0.4 : 0;

            $total_prestasi = $bobot_capaian_skp + $bobot_avg_perilaku;
       
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $fontpath = Yii::getAlias('@webroot').'/klorofil/assets/fonts/pala.ttf';
            
            $fontreg = \TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 86);
            $pdf->SetFont($fontreg, '', 12);
            $pdf->AddPage();
            ob_start();
            echo $this->renderPartial('cover', [
                'user' => $model->pegawaiDinilai,
                'model' => $model,
                'bkd_periode' =>   $bkd_periode,
            ]);

            $data = ob_get_clean();
            ob_start();
            $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            $pdf->Image($imgdata,$pdf->getPageWidth()/2 - 10,10,20);
            $pdf->Ln(50);
            // $pdf->writeHTMLCell(50, 38, '', $y, $grades, 1, 0, 0, true, 'J', true);
            $pdf->writeHTMLCell($pdf->getPageWidth() - 50,10,25,50,$data, 0, 0, 0, true, 'J', true);

            ob_start();
            echo $this->renderPartial('print_formulir', [
                 'model' => $model,
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->AddPage();
            // $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            // $pdf->Image($imgdata,10,10,15);
            $pdf->writeHTML($data);

            ob_start();
            echo $this->renderPartial('print_pencapaian', [
                 'model' => $model,
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->AddPage('L');
            // $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            // $pdf->Image($imgdata,10,10,15);
            $pdf->writeHTML($data);

            ob_start();
            echo $this->renderPartial('print_perilaku', [
                'model' => $model,
                'pegawaiDinilai' => $pegawaiDinilai,
                'pejabatPenilai' => $pejabatPenilai,
                'atasanPejabatPenilai' => $atasanPejabatPenilai,    
                'skpPerilaku' => $skpPerilaku,
                'avg_capaian_skp' => $avg_capaian_skp,
                'bobot_capaian_skp' => $bobot_capaian_skp,
                'bobot_avg_perilaku' => $bobot_avg_perilaku,
                'total_prestasi' => $total_prestasi,
                'bkd_periode' =>   $bkd_periode,
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 9);
            $pdf->AddPage('P');
            // $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            // $pdf->Image($imgdata,10,10,15);
            $pdf->writeHTML($data);

            $nama = $model->pegawai_dinilai;
            $pdf->Output('skp_'.$nama.''.rand(1,100).'.pdf','I');
        }
        catch(\HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
        die();
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
        $list_staf = MyHelper::listRoleStaf();
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

            else if(in_array(Yii::$app->user->identity->access_role, $list_staf) && !empty($unker) && !empty($unker->pejabat))
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
        $dataProvider = $searchModel->searchAktif(Yii::$app->request->queryParams);

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

    public function actionPengisian($id)
    {

        if(Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $session->remove('token');
            Yii::$app->user->logout();
            $url = Yii::$app->params['sso_logout'];
            return $this->redirect($url);
        }

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
       
        return $this->render('pengisian', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider            
        ]);
    }

    public function actionRealisasi($id)
    {

        if(Yii::$app->user->isGuest)
        {
            $session = Yii::$app->session;
            $session->remove('token');
            Yii::$app->user->logout();
            $url = Yii::$app->params['sso_logout'];
            return $this->redirect($url);
        }

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
       
        return $this->render('realisasi', [
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
        $list_staf = MyHelper::listRoleStaf();
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

            else if(in_array($access_role, $list_staf) && !empty($unker) && !empty($unker->pejabat))
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
        $dataProvider = $searchModel->searchAktif(Yii::$app->request->queryParams);

        return $this->render('index', [
            'pejabatPenilai' => $pejabatPenilai,
            'pegawaiDinilai' => $pegawaiDinilai,
            'jabatanPegawai' => $jabatanPegawai,
            'jabatanPenilai' => $jabatanPenilai,
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
        $skpPerilaku = $model->skpPerilaku;
        $searchModel = new SkpItemSearch();
        $searchModel->skp_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving

            if(!empty($_POST['skp_id'])){
                $id = Yii::$app->request->post('skp_id');
                $tmp = SkpPerilaku::findOne(['skp_id' => $id]);
                if(empty($tmp)){
                    $tmp = new SkpPerilaku;
                    $tmp->id = MyHelper::gen_uuid();
                    $tmp->skp_id = $id;
                }
                // store a default json response as desired by editable
                $out = json_encode(['output'=>'', 'message'=>'']);

                
                // $posted = $_POST['Skp'];
                $post = ['SkpPerilaku' => $_POST];
                
                
                // load model like any single model validation
                if ($tmp->load($post)) {


                    if($tmp->save())
                    {
                        $out = json_encode(['output'=>'', 'message'=>'']);
                    }

                    else
                    {
                        $error = \app\helpers\MyHelper::logError($tmp);
                        $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                    }

                    
                }
            }

            else if(!empty($_POST['SkpItem'])){
                $id = Yii::$app->request->post('editableKey');
                $model = SkpItem::findOne($id);

                // store a default json response as desired by editable
                $out = json_encode(['output'=>'', 'message'=>'']);

                
                $posted = current($_POST['SkpItem']);
                $post = ['SkpItem' => $posted];

                // load model like any single model validation
                if ($model->load($post)) {
                // can save model or do something before saving model
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

            }

            else if(!empty($_POST['editableKeyStatusSKP'])){


                $id = Yii::$app->request->post('editableKeyStatusSKP');
                $model = Skp::findOne($id);

                // store a default json response as desired by editable
                $out = json_encode(['output'=>'', 'message'=>'']);

                $model->status_skp = $_POST['Skp']['status_skp'];
                if($model->save(false, ['status_skp']))
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
            'skpPerilaku' => $skpPerilaku,
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
        $session = Yii::$app->session;
        $tahun_id = '';
        $sd = '';
        $ed = '';
        $bkd_periode = null;
        if($session->has('bkd_periode'))
        {
            $tahun_id = $session->get('bkd_periode');
            // $session->get('bkd_periode_nama',$bkd_periode->nama_periode);
            $sd = $session->get('tgl_awal');
            $ed = $session->get('tgl_akhir');  
            $bkd_periode = \app\models\BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();
        }
        else{
            $bkd_periode = \app\models\BkdPeriode::find()->where(['buka' => 'Y'])->one();
            $tahun_id = $bkd_periode->tahun_id;
            $sd = $bkd_periode->tanggal_bkd_awal;
            $ed = $bkd_periode->tanggal_bkd_akhir;
        }

        $model = new Skp();
        $model->id = MyHelper::gen_uuid();
        $model->status_skp = '1';
        $model->pegawai_dinilai = Yii::$app->user->identity->NIY;

        $loggedInAs = MJabatan::find()->where(['nama'=>Yii::$app->user->identity->access_role])->one();

        $jabatan = Jabatan::find()->where([
          'jabatan_id' => !empty($loggedInAs) ? $loggedInAs->id : '-',
          'NIY' => $model->pegawai_dinilai
        ])->one();

        $access_role = Yii::$app->user->identity->access_role;
        $list_staf = MyHelper::listRoleStaf();
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
                $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;
                $model->pejabat_penilai = $niyAsesor;    
            }

            else if($access_role == 'Dosen' && !empty($unker) && !empty($unker->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->pejabat) ? $unker->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();  
                $model->pejabat_penilai = $niyAsesor;            
                
                $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;  

                if(!empty($unker->parent) && !empty($unker->parent->pejabat))
                {
                    $niyAtasanAsesor = $unker->parent->pejabat->NIY;

                    $jabatanAtasanPenilai = Jabatan::find()->where([
                        'jabatan_id' => $unker->parent->jabatan_id,
                        'NIY' => $niyAtasanAsesor
                    ])->one();

                    $atasanPejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAtasanAsesor])->one();
                    $model->jabatan_atasan_penilai_id = !empty($jabatanAtasanPenilai) ? $jabatanAtasanPenilai->ID : null;
                    $model->atasan_pejabat_penilai = $niyAtasanAsesor;    
                }
            }

            else if(in_array($access_role, $list_staf) && !empty($unker) && !empty($unker->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->pejabat) ? $unker->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();  
                $model->pejabat_penilai = $niyAsesor;            
                
                $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;  

                // if(!empty($unker->parent) && !empty($unker->parent->pejabat))
                // {
                    // $niyAtasanAsesor = $unker->parent->pejabat->NIY;

                    // $jabatanAtasanPenilai = Jabatan::find()->where([
                    //     'jabatan_id' => $unker->parent->jabatan_id,
                    //     'NIY' => $niyAtasanAsesor
                    // ])->one();

                    // $atasanPejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAtasanAsesor])->one();
                $model->jabatan_atasan_penilai_id = $model->jabatan_penilai_id;//!empty($jabatanAtasanPenilai) ? $jabatanAtasanPenilai->ID : null;
                $model->atasan_pejabat_penilai = $model->pejabat_penilai;//$niyAtasanAsesor;    
                // }
            }
            

            $model->jabatan_pegawai_id = !empty($jabatan) ? $jabatan->ID : null;
        }

        if ($model->load(Yii::$app->request->post())) 
        {

            $errors = '';
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            
            try 
            {

                if($model->save()) {
                    $counter = 0;
                    if($access_role == 'Dosen'){
                        
                        $list_skp_templates = SkpTemplate::find()->where(['peran' => $access_role])->all();
                        $dataDiri = DataDiri::findOne(['NIY'=>$model->pegawai_dinilai]);
                        // $kode_komponen = '-';

                        if(!empty($dataDiri))
                        {
                            $jabfung = !empty($dataDiri->jabatanFungsional) ? $dataDiri->jabatanFungsional->kode : '-';

                            if(in_array($jabfung,['AA','TT'])) {
                                $kode_komponen = 'B1';
                            }

                            else if(in_array($jabfung,['L','LK','GB'])) {
                                $kode_komponen = 'B2';
                            }
                        }

                        foreach($list_skp_templates as $tmp){
                            $komponen = $tmp->komponenKegiatan;
                            $total_sks = $tmp->target_qty;
                            
                            if(in_array($komponen->kode, ['B1','B2']) && $kode_komponen == $komponen->kode) {

                                $nama_kegiatan = $tmp->nama.' '.$total_sks.' '.$tmp->target_satuan;

                                $item = SkpItem::findOne([
                                    'skp_id' => $model->id,
                                    // 'komponen_kegiatan_id' => $komponen->id,
                                    'nama' => $nama_kegiatan
                                ]);

                                if(empty($item)){
                                    $item = new SkpItem;
                                    $item->id = MyHelper::gen_uuid();
                                    
                                    $item->komponen_kegiatan_id = $komponen->id;
                                    $item->nama = $nama_kegiatan;
                                    $item->skp_id = $model->id;
                                    
                                    $ak = $total_sks * $komponen->angka_kredit_pak;
                                    if($total_sks > 10)
                                    {
                                        $ak = 10 * $komponen->angka_kredit_pak;
                                        $sisa = $total_sks - 10;

                                        $ak = $ak + ($sisa * ($komponen->angka_kredit_pak / 2));
                                    }

                                    $item->target_ak = $ak;
                                    $item->realisasi_ak = 0;
                                    $item->target_qty = $tmp->target_qty;
                                    $item->target_satuan = $tmp->target_satuan;
                                    $item->target_mutu = $tmp->target_mutu;
                                    $item->target_waktu = $tmp->target_waktu;
                                    $item->target_waktu_satuan = $tmp->target_waktu_satuan;

                                    
                                    if($item->save()) {
                                        $counter++;

                                    }
                                    else{
                                        $errors .= MyHelper::logError($item);
                                        throw new \Exception;
                                    }
                                }
                            }    

                            else if(!in_array($komponen->kode, ['B1','B2'])){
                                $item = SkpItem::find()->where([
                                    'skp_id' => $model->id,
                                    'komponen_kegiatan_id' => $komponen->id,
                                    'nama' => $tmp->nama
                                ])->one();

                                if(empty($item)){
                                    $item = new SkpItem;
                                    $item->id = MyHelper::gen_uuid();
                                }
                                
                                $item->komponen_kegiatan_id = $komponen->id;
                                $item->nama = $tmp->nama;
                                $item->skp_id = $model->id;
                                
                                $ak = 0;
                                
                                $item->target_ak = $ak;
                                $item->realisasi_ak = 0;
                                $item->target_qty = $tmp->target_qty;
                                $item->target_satuan = $tmp->target_satuan;
                                $item->target_mutu = $tmp->target_mutu;
                                $item->target_waktu = $tmp->target_waktu;
                                $item->target_waktu_satuan = $tmp->target_waktu_satuan;

                                
                                if($item->save()) {
                                    $counter++;
                                }

                                else{
                                    $errors .= MyHelper::logError($item);
                                    throw new \Exception;
                                }
                            }
                        }
                    }
                    
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data tersimpan. ".$counter." items created");
                    return $this->redirect(['view', 'id' => $model->id]);    
                }
            }   

            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
                
                
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
                
            }     
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
        $loggedInAs = MJabatan::find()->where(['nama'=>Yii::$app->user->identity->access_role])->one();

        $jabatan = Jabatan::find()->where([
          'jabatan_id' => !empty($loggedInAs) ? $loggedInAs->id : '-',
          'NIY' => $model->pegawai_dinilai
        ])->one();

        $access_role = Yii::$app->user->identity->access_role;
        $list_staf = MyHelper::listRoleStaf();
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
                $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;
                $model->pejabat_penilai = $niyAsesor;    
            }

            else if($access_role == 'Dosen' && !empty($unker) && !empty($unker->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->pejabat) ? $unker->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();  
                $model->pejabat_penilai = $niyAsesor;            
                
                $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;  

                if(!empty($unker->parent) && !empty($unker->parent->pejabat))
                {
                    $niyAtasanAsesor = $unker->parent->pejabat->NIY;

                    $jabatanAtasanPenilai = Jabatan::find()->where([
                        'jabatan_id' => $unker->parent->jabatan_id,
                        'NIY' => $niyAtasanAsesor
                    ])->one();

                    $atasanPejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAtasanAsesor])->one();
                    $model->jabatan_atasan_penilai_id = !empty($jabatanAtasanPenilai) ? $jabatanAtasanPenilai->ID : null;
                    $model->atasan_pejabat_penilai = $niyAtasanAsesor;    
                }
            }

            else if(in_array($access_role, $list_staf) && !empty($unker) && !empty($unker->pejabat))
            {
                $niyAsesor = !empty($unker) && !empty($unker->pejabat) ? $unker->pejabat->NIY : null;

                $jabatanPenilai = Jabatan::find()->where([
                    'jabatan_id' => $unker->jabatan_id,
                    'NIY' => $niyAsesor
                ])->one();

                $pejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAsesor])->one();  
                $model->pejabat_penilai = $niyAsesor;            
                
                $model->jabatan_penilai_id = !empty($jabatanPenilai) ? $jabatanPenilai->ID : null;  

                if(!empty($unker->parent) && !empty($unker->parent->pejabat))
                {
                    $niyAtasanAsesor = $unker->parent->pejabat->NIY;

                    $jabatanAtasanPenilai = Jabatan::find()->where([
                        'jabatan_id' => $unker->parent->jabatan_id,
                        'NIY' => $niyAtasanAsesor
                    ])->one();

                    $atasanPejabatPenilai = \app\models\User::find()->where(['NIY' => $niyAtasanAsesor])->one();
                    $model->jabatan_atasan_penilai_id = !empty($jabatanAtasanPenilai) ? $jabatanAtasanPenilai->ID : null;
                    $model->atasan_pejabat_penilai = $niyAtasanAsesor;    
                }
            }
            

            $model->jabatan_pegawai_id = !empty($jabatan) ? $jabatan->ID : null;
        }
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

        return $this->redirect(['list']);
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
