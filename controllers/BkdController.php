<?php

namespace app\controllers;

use Yii;
use app\models\Ewmp;
use app\models\BkdPeriode;
use app\models\BkdDosen;
use app\models\TugasDosenBkd;
use app\models\TugasDosen;
use app\models\KomponenKegiatan;
use app\models\Pengajaran;
use app\models\BkdPengajaran;
use app\models\Organisasi;
use app\models\PengelolaJurnal;
use app\models\Publikasi;
use app\models\PublikasiAuthor;
use app\models\Pengabdian;
use yii\httpclient\Client;

class BkdController extends AppController
{

    public function actionGantiPeriode()
    {
        $tahun_id = $_POST['tahun'];
        // $tahun_id = $dataPost['tahun_id'];
        $bkd_periode = BkdPeriode::find()->where(['tahun_id' => $tahun_id])->one();

        if(!empty($bkd_periode))
        {
          $session = Yii::$app->session;
          $session->set('bkd_periode',$bkd_periode->tahun_id);
          $session->set('bkd_periode_nama',$bkd_periode->nama_periode);
          $session->set('tgl_awal',$bkd_periode->tanggal_bkd_awal);
          $session->set('tgl_akhir',$bkd_periode->tanggal_bkd_akhir);
          Yii::$app->getSession()->setFlash('success','Periode BKD telah diubah');
          
        }

        else{
          Yii::$app->getSession()->setFlash('danger','Oops, Periode BKD not found');
          
        }

        return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionIndex()
    {
        if(!parent::handleEmptyUser())
        {
            return $this->redirect(Yii::$app->params['sso_login']);
        }

        $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
        
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

        $unsur_utama = \app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all();
        $results = [];

        foreach($unsur_utama as $item)
        {
          $tmp = [];
          foreach($item->komponenKegiatans as $komponen)
          {
            $list_bkd = BkdDosen::find()->where([
              'tahun_id' => $tahun_id,
              'dosen_id' => $user->ID,
              'komponen_id' => $komponen->id
            ])->all();
            foreach($list_bkd as $bkd)
            {
              $tmp[] = $bkd;
            }
            
          }

          $results[$item->id] = [
            'unsur' => $item->nama,
            'items' => $tmp
          ];
        }

        $bkd_periode = \app\models\BkdPeriode::find()->where(['buka' => 'Y'])->one();

        $pengajaran = Pengajaran::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            // 'is_claimed' => 1,
            'tahun_akademik' => $tahun_id
        ])->all();

        // print_r($tahun_akademik);exit;

        $query = Publikasi::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $query->andWhere(['not',['kegiatan_id' => null]]);



        $query->andFilterWhere(['between','tanggal_terbit',$sd, $ed]);
        $query->orderBy(['tanggal_terbit'=>SORT_ASC]);

        $publikasi = $query->all();

        $query = Pengabdian::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        // $sd = $tahun_akademik['kuliah_mulai'];
        // $ed = $tahun_akademik['nilai_selesai'];

        // $query->andFilterWhere(['between','tahun_kegiatan',$sd, $ed]);
        $query->orderBy(['tahun_kegiatan'=>SORT_ASC]);

        $pengabdian = $query->all();

        $query = Organisasi::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $organisasi = $query->all();

        $query = PengelolaJurnal::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $pengelolaJurnal = $query->all();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'AJAR'
        ]);

        $bkd_ajar = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'RISET'
        ]);

        $bkd_pub = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'ABDIMAS'
        ]);

        $bkd_abdi = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'PENUNJANG'
        ]);

        $bkd_penunjang = $query->one();

        return $this->render('index',[
            'results' => $results,
            'bkd_periode' =>   $bkd_periode,
            'pengajaran' => $pengajaran,
            'user' => $user,
            'publikasi' => $publikasi,
            'pengabdian' => $pengabdian,
            'organisasi' => $organisasi,
            'pengelolaJurnal' => $pengelolaJurnal,
            'bkd_ajar' => $bkd_ajar,
            'bkd_pub' => $bkd_pub,
            'bkd_abdi' => $bkd_abdi,
            'bkd_penunjang' => $bkd_penunjang,
        ]);
        
    }

    public function actionPrint()
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
        
        $asesor1 = '';
        $asesor2 = '';
        $niyAsesor1 = '';
        $niyAsesor2 = '';

        $asesors['1'] = [
          'nama' => $asesor1,
          'niy' => $niyAsesor1
        ];

        $asesors['2'] = [
          'nama' => $asesor2,
          'niy' => $niyAsesor2
        ];

        $loggedInAs = \app\models\MJabatan::find()->where(['nama'=>Yii::$app->user->identity->access_role])->one();

        $jabatan = \app\models\Jabatan::find()->where([
          'jabatan_id' => !empty($loggedInAs) ? $loggedInAs->id : '-',
          'NIY' => Yii::$app->user->identity->NIY
        ])->one();

        if(!empty($jabatan))
        {
          $unker = $jabatan->unker;
          $asesor1 = !empty($unker->parent) ? $unker->parent->pejabat->dataDiri->nama : '<span style="color:red">Pejabat belum diset</span>';
          $niyAsesor1 = !empty($unker->parent) ? $unker->parent->pejabat->NIY : '-';
          $asesors['1'] = [
            'nama' => $asesor1,
            'niy' => $niyAsesor1
          ];

          $asesor2 = !empty($unker) ? $unker->pejabat->dataDiri->nama : '<span style="color:red">Pejabat belum diset</span>';
          $niyAsesor2 = !empty($unker) ? $unker->pejabat->NIY : '-';
          $asesors['2'] = [
            'nama' => $asesor2,
            'niy' => $niyAsesor2
          ];
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


        $unsur_utama = \app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all();
        $results = [];

        foreach($unsur_utama as $item)
        {
          $tmp = [];
          foreach($item->komponenKegiatans as $komponen)
          {
            $list_bkd = BkdDosen::find()->where([
              'tahun_id' => $bkd_periode->tahun_id,
              'dosen_id' => $user->ID,
              'komponen_id' => $komponen->id
            ])->all();
            foreach($list_bkd as $bkd)
            {
              $tmp[] = $bkd;
            }
            
          }

          $results[$item->id] = [
            'unsur' => $item->nama,

            'items' => $tmp
          ];
        }


        $pengajaran = Pengajaran::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            // 'is_claimed' => 1,
            'tahun_akademik' => $bkd_periode->tahun_id
        ])->all();

        // print_r($tahun_akademik);exit;

        $query = Publikasi::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $query->andWhere(['not',['kegiatan_id' => null]]);

        $sd = $bkd_periode->tanggal_bkd_awal;
        $ed = $bkd_periode->tanggal_bkd_akhir;


        $query->andFilterWhere(['between','tanggal_terbit',$sd, $ed]);
        $query->orderBy(['tanggal_terbit'=>SORT_ASC]);

        $publikasi = $query->all();

        $query = Pengabdian::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        // $sd = $tahun_akademik['kuliah_mulai'];
        // $ed = $tahun_akademik['nilai_selesai'];

        // $query->andFilterWhere(['between','tahun_kegiatan',$sd, $ed]);
        $query->orderBy(['tahun_kegiatan'=>SORT_ASC]);

        $pengabdian = $query->all();

        $query = Organisasi::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $organisasi = $query->all();

        $query = PengelolaJurnal::find()->where([
            'NIY' => Yii::$app->user->identity->NIY,
            'is_claimed' => 1,
        ]);

        $pengelolaJurnal = $query->all();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'AJAR'
        ]);

        $bkd_ajar = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'RISET'
        ]);

        $bkd_pub = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'ABDIMAS'
        ]);

        $bkd_abdi = $query->one();

        $query = TugasDosenBkd::find();
        $query->joinWith(['unsur as u']);
        $query->where([
          'tugas_dosen_id'=>$user->dataDiri->tugas_dosen_id,
          'u.kode' => 'PENUNJANG'
        ]);

        $bkd_penunjang = $query->one();

        try
        {

            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $fontpath = Yii::getAlias('@webroot').'/klorofil/assets/fonts/pala.ttf';
            
            $fontreg = \TCPDF_FONTS::addTTFfont($fontpath, 'TrueTypeUnicode', '', 86);
            $pdf->SetFont($fontreg, '', 12);
            $pdf->AddPage();
            ob_start();
            echo $this->renderPartial('cover', [
                'user' => $user,
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
            echo $this->renderPartial('print', [
                 'results' => $results,
                 'user' => $user,
                'bkd_periode' =>   $bkd_periode,
                'pengajaran' => $pengajaran,
                // 'results' => $results,
                'publikasi' => $publikasi,
                'pengabdian' => $pengabdian,
                'organisasi' => $organisasi,
                'pengelolaJurnal' => $pengelolaJurnal,
                'bkd_ajar' => $bkd_ajar,
                'bkd_pub' => $bkd_pub,
                'bkd_abdi' => $bkd_abdi,
                'bkd_penunjang' => $bkd_penunjang,
                'asesors' => $asesors
            ]);

            $data = ob_get_clean();
            ob_start();
            
            
            $pdf->SetFont($fontreg, '', 10);
            $pdf->AddPage();
            // $imgdata = Yii::getAlias('@webroot').'/klorofil/assets/img/logo-ori.png';
            // $pdf->Image($imgdata,10,10,15);
            $pdf->writeHTML($data);

            
            $pdf->Output('lkd_'.$user->dataDiri->nama.'_'.rand(1,100).'.pdf','I');
        }
        catch(\HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
        die();
    }

    public function actionKlaim($step=1)
    {
        $list_bkd_periode = BkdPeriode::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
        $list_komponen = [];
        $results = [];
        $session = Yii::$app->session;
        $bkd_periode = null;
        if($session->has('bkd_periode')) {
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

        switch($step){
          case 1:

            $query = KomponenKegiatan::find();
            $query->joinWith(['unsur as u']);    
            $query->where(['u.kode' => 'AJAR']);
            $list_komponen = $query->all();

            return $this->render('klaim_pendidikan',[
              'list_bkd_periode' => $list_bkd_periode,
              'list_komponen' => $list_komponen,
              'results' => $results
            ]);
          break;
          case 2:
            return $this->render('klaim_penelitian',[
              'list_bkd_periode' => $list_bkd_periode,
              'list_komponen' => $list_komponen,
              'results' => $results
            ]);
          break;
          case 3:
            $query = KomponenKegiatan::find();
            $query->joinWith(['unsur as u']);    
            $query->where(['u.kode' => 'ABDIMAS']);
            $query->orderBy(['nama' => SORT_ASC]);
            $list_komponen = $query->all();
            foreach($list_komponen as $komponen){


              $rows = (new \yii\db\Query())
                ->select(['bd.deskripsi','bd.id','bd.sks','bd.sks_pak','bd.status_bkd'])
                ->from('bkd_dosen bd')
                ->join('LEFT JOIN','komponen_kegiatan kk','bd.komponen_id = kk.id')
                ->join('LEFT JOIN','unsur_utama uu','kk.unsur_id = uu.id')
                ->where([
                    'uu.kode' => 'ABDIMAS',
                    'kk.id' => $komponen->id,
                    'bd.tahun_id' => $bkd_periode->tahun_id,
                    'bd.dosen_id' => Yii::$app->user->identity->id
                ])
                ->all();

              foreach($rows as $row){
                $results[$komponen->id][] = $row;
              }
            }
            return $this->render('klaim_pengabdian',[
              'list_bkd_periode' => $list_bkd_periode,
              'list_komponen' => $list_komponen,
              'results' => $results
            ]);
          break;
          case 4:
            return $this->render('klaim_penunjang',[
              'list_bkd_periode' => $list_bkd_periode,
              'list_komponen' => $list_komponen,
              'results' => $results
            ]);
          break;
          case 5:
            return $this->render('simpulan',[
              'list_bkd_periode' => $list_bkd_periode,
              'list_komponen' => $list_komponen,
              'results' => $results
            ]);
          break;
        }
        
    }

    public function actionAjaxClaimPenghargaan()
    {
      $dataPost = $_POST['dataPost'];
      $model = \app\models\Penghargaan::findOne($dataPost['id']);
      $results = [];
      if(!empty($model))
      {
        $komponen = $model->komponenKegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->ID
          ])->one();

          if($is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = 'Memperoleh penghargaan '.$model->bentuk.' '.(!empty($model->tingkatPenghargaan) ? $model->tingkatPenghargaan->nama : '-');
            $bkd->kondisi = (string)$model->ID;
            $bkd->sks = $komponen->angka_kredit;
            $bkd->sks_pak = $komponen->angka_kredit_pak;

            if($bkd->save())
            {
              $results = [
                'code' => 200,
                'message' => 'Data claimed'
              ];

            }

            else{
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];
            }
          }

          else if($is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();

            $results = [
              'code' => 200,
              'message' => 'Data unclaimed'
            ];
          }

          
        }
      }

      echo json_encode($results);
      die();
    }

    public function actionAjaxClaim()
    {
      $dataPost = $_POST['dataPost'];
      $model = Pengajaran::findOne($dataPost['id']);
      
      if(!empty($model))
      {

        $komponen = KomponenKegiatan::findOne($model->komponen_id);
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $model->komponen_id = $komponen->id;
          $model->sks_bkd = $komponen->angka_kredit;
          $model->is_claimed = $dataPost['is_claimed'];

          
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => $model->jadwal_id
          ])->one();

          if($model->is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = 'Mengadakan perkuliahan '.$model->matkul.' kode mk '.$model->kode_mk.' kelas '.$model->kelas.' '.$model->sks.' sks';
            $bkd->kondisi = (string)$model->jadwal_id;
            $bkd->sks = $komponen->angka_kredit * $dataPost['sks'];
            $bkd->sks_pak = $komponen->angka_kredit_pak * $dataPost['sks'];

            if(!$bkd->save())
            {
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];

              // print_r($results);exit;
            }
          }

          else if($model->is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();
          }

          

          if($model->save(false,['is_claimed','komponen_id','sks_bkd']))
          {
          	$results = [
  	        	'code' => 200,
  	        	'message' => 'Data updated'
  	        ];
          }

          else
          {
            $results = [
              'code' => 500,
              'message' => 'Oops, something wrong'
            ];
          }
        }
        
      }

      echo json_encode($results);
      die();
    }

    public function actionAjaxClaimPublikasi()
    {
      $dataPost = $_POST['dataPost'];
      $model = Publikasi::findOne($dataPost['id']);
      
      if(!empty($model))
      {
        $komponen = $model->kegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $model->kegiatan_id = $komponen->id;
          $model->sks_bkd = $komponen->angka_kredit;
          $model->is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->id
          ])->one();

          if($model->is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = 'Melakukan publikasi '.$model->nama_kategori_kegiatan.' judul '.$model->judul_publikasi_paten;
            $bkd->kondisi = (string)$model->id;
            $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
            $publikasiAuthor = PublikasiAuthor::find()->where([
              'author_id' => $user->sister_id,
              'publikasi_id' => $model->sister_id
            ])->one();
            $multiplier = !empty($publikasiAuthor) && $publikasiAuthor->urutan == 1 ? 0.6 : 0.4;
            $bkd->sks = $komponen->angka_kredit * $multiplier;
            $bkd->sks_pak = $komponen->angka_kredit_pak * $multiplier;

            if(!$bkd->save())
            {
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];

              print_r($results);exit;
            }
          }

          else if($model->is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();
          }
          if($model->save(false,['is_claimed','kegiatan_id','sks_bkd']))
          {
            $results = [
              'code' => 200,
              'message' => 'Data updated'
            ];
          }

          else
          {
            $results = [
              'code' => 500,
              'message' => 'Oops, something wrong'
            ];
          }
        }
      }

      echo json_encode($results);
      die();
    }

    public function actionAjaxClaimPengabdian()
    {
      $dataPost = $_POST['dataPost'];
      $model = Pengabdian::findOne($dataPost['id']);
      $results = [];
      if(!empty($model))
      {
        $komponen = $model->komponenKegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->ID
          ])->one();

          if($is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = $model->judul_penelitian_pengabdian;
            $bkd->kondisi = (string)$model->ID;
            $bkd->sks = $komponen->angka_kredit;
            $bkd->sks_pak = $komponen->angka_kredit_pak;

            if($bkd->save())
            {
              $results = [
                'code' => 200,
                'message' => 'Data claimed'
              ];

            }

            else{
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];
            }
          }

          else if($is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();

            $results = [
              'code' => 200,
              'message' => 'Data unclaimed'
            ];
          }
        }
      }

      echo json_encode($results);
      die();
    }

    public function actionAjaxClaimOrganisasi()
    {
      $results = [];
      $dataPost = $_POST['dataPost'];
      $model = Organisasi::findOne($dataPost['id']);
      
      if(!empty($model))
      {
        $komponen = $model->komponenKegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $model->komponen_kegiatan_id = $komponen->id;
          $model->sks_bkd = $komponen->angka_kredit;
          $model->is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->ID
          ])->one();
          if($model->is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = 'Menjadi '.$model->jabatan.' pada '.$model->organisasi;
            $bkd->kondisi = (string)$model->ID;
            $bkd->sks = $komponen->angka_kredit;
            $bkd->sks_pak = $komponen->angka_kredit_pak;

            if(!$bkd->save())
            {
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];

              // print_r($results);exit;
            }
          }

          else if($model->is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();
          }
          if($model->save(false,['is_claimed','komponen_kegiatan_id','sks_bkd']))
          {
            $results = [
              'code' => 200,
              'message' => 'Data updated'
            ];
          }

          else
          {
            $results = [
              'code' => 500,
              'message' => 'Oops, something wrong'
            ];
          }
        }
      }

      echo json_encode($results);
      die();
    }

    public function actionAjaxClaimPembicara()
    {
      $dataPost = $_POST['dataPost'];
      $model = \app\models\Pembicara::findOne($dataPost['id']);
      $results = [];
      if(!empty($model))
      {
        $komponen = $model->komponenKegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->id
          ])->one();

          if($is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = (!empty($tmp->kategoriPembicara) ? $tmp->kategoriPembicara->nama : null).' pada '.$model->nama_pertemuan_ilmiah.' oleh '.$model->penyelenggara_kegiatan;
            $bkd->kondisi = (string)$model->id;
            $bkd->sks = $komponen->angka_kredit;
            $bkd->sks_pak = $komponen->angka_kredit_pak;

            if($bkd->save())
            {
              $results = [
                'code' => 200,
                'message' => 'Data claimed'
              ];

            }

            else{
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];
            }
          }

          else if($is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();

            $results = [
              'code' => 200,
              'message' => 'Data unclaimed'
            ];
          }

          
        }
      }

      echo json_encode($results);
      die();
    }


    public function actionAjaxClaimPengelolaJurnal()
    {
      $dataPost = $_POST['dataPost'];
      $model = PengelolaJurnal::findOne($dataPost['id']);
      $results = [];
      if(!empty($model))
      {
        $komponen = $model->komponenKegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $model->komponen_kegiatan_id = $komponen->id;
          $model->sks_bkd = $komponen->angka_kredit;
          $model->is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->id
          ])->one();
          if($model->is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = 'Menjadi '.$model->peran_dalam_kegiatan.' di jurnal '.$model->nama_media_publikasi;
            $bkd->kondisi = (string)$model->id;
            $bkd->sks = $komponen->angka_kredit;
            $bkd->sks_pak = $komponen->angka_kredit_pak;

            if(!$bkd->save())
            {
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];

            }
          }

          else if($model->is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();
          }

          if($model->save(false,['is_claimed','komponen_kegiatan_id','sks_bkd']))
          {
            $results = [
              'code' => 200,
              'message' => 'Data updated'
            ];
          }

          else
          {
            $results = [
              'code' => 500,
              'message' => 'Oops, something wrong'
            ];
          }
        }
      }

      echo json_encode($results);
      die();
    }

    public function actionAjaxClaimPenunjangLain()
    {
      $dataPost = $_POST['dataPost'];
      $model = \app\models\PenunjangLain::findOne($dataPost['id']);
      $results = [];
      if(!empty($model))
      {
        $komponen = $model->komponenKegiatan;
        
        if(empty($komponen))
        {
          $results = [
            'code' => 500,
            'message' => 'Oops, KomponenKegiatan is empty'
          ];
        }

        else
        {
          $model->komponen_kegiatan_id = $komponen->id;
          $model->sks_bkd = $komponen->angka_kredit;
          $model->is_claimed = $dataPost['is_claimed'];
          $bkd = BkdDosen::find()->where([
            'tahun_id' => $dataPost['tahun_id'],
            'dosen_id' => Yii::$app->user->identity->ID,
            'komponen_id' => $komponen->id,
            'kondisi' => (string)$model->id
          ])->one();
          if($model->is_claimed == '1')
          {
            if(empty($bkd))
            {
              $bkd = new BkdDosen;
            }

            $bkd->tahun_id = $dataPost['tahun_id'];
            $bkd->dosen_id = Yii::$app->user->identity->ID;
            $bkd->komponen_id = $komponen->id;
            $bkd->deskripsi = 'Menjadi '.$model->jenisPanitia->nama.' pada kegiatan '.$model->nama_kegiatan;
            $bkd->kondisi = (string)$model->id;
            $bkd->sks = $komponen->angka_kredit;
            $bkd->sks_pak = $komponen->angka_kredit_pak;

            if(!$bkd->save())
            {
              $results = [
                'code' => 500,
                'message' => \app\helpers\MyHelper::logError($bkd)
              ];

            }
          }

          else if($model->is_claimed == '0')
          {
            if(!empty($bkd))
              $bkd->delete();
          }
          if($model->save(false,['is_claimed','komponen_kegiatan_id','sks_bkd']))
          {
            $results = [
              'code' => 200,
              'message' => 'Data updated'
            ];
          }

          else
          {
            $results = [
              'code' => 500,
              'message' => 'Oops, something wrong'
            ];
          }
        }
      }

      echo json_encode($results);
      die();
    }
}
