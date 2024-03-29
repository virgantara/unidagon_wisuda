<?php

namespace app\helpers;

use app\models\Peserta;
use app\models\PesertaSyarat;
use app\models\Syarat;
use app\models\User;
use Yii;

/**
 * Css helper class.
 */
class MyHelper
{

	public static function startWith($str, $prefix)
	{
		return strpos($str, $prefix) === 0;
	}

	public static function getStatusAktif()
	{
		return ['aktif' => 'Aktif', 'cuti' => 'Cuti', 'tugasbelajar' => 'Tugas Belajar', 'resign' => 'Resign', 'nonaktif' => 'Non-Aktif'];
	}

	public static function getListTugasDosen()
	{
		$list = [
			'DS' => 'Dosen Biasa (DS)',
			'DT' => 'Dosen dengan Tugas Tambahan (DT)',
			'PS' => 'Profesor',
			'PT' => 'Profesor dengan Tugas Tambahan'
		];

		return $list;
	}

	public static function getListStatusBKDColor()
	{
		$list = [
			'0' => 'success',
			'1' => 'warning',
			'2' => 'primary',
			'3' => 'danger'
		];

		return $list;
	}

	public static function getUkuranKaos($id = null)
	{

		$list = array(
			'XS'  => 'XS',
			'M'   => 'M',
			'L'   => 'L',
			'XL'  => 'XL',
			'X2L' => 'X2L',
			'X3L' => 'X3L',
			'X4L' => 'X4L',
			'X5L' => 'X5L',
		);

		return $id == null ? $list : $list[$id];
	}

	public static function getPesertaId($nim)
	{

		$peserta = Peserta::find()->where([
			'nim' => $nim
		])->one();

		$result = [
			'id' =>  $peserta ? $peserta->id : '',
			'status' => $peserta ? true : false,
		];
		return $result;
	}

	public static function getEmailUser($nim)
	{

		$user = User::find()->where([
			'nim' => $nim
		])->one();

		return isset($user->email) ? $user->email : "";
	}

	public static function getStatusSyarat($peserta_id, $syarat_id)
	{
		$peserta_syarat = PesertaSyarat::find()->where([
			'peserta_id' => $peserta_id,
			'syarat_id' => $syarat_id,
		])->one();

		return isset($peserta_syarat) ? 'O' : 'X';
	}

	public static function getKampus($id = null)
	{

		$list = array(
			1  => "Siman",
			2  => "Gontor",
			3  => "Mantingan Guru",
			4  => "Kediri",
			5  => "Kandangan",
			6  => "Magelang",
			7  => "Mantingan Pascasarjana",
			8  => "Mantingan Reguler",
			9  => "Banyuwangi",
		);

		return $id == null ? $list : $list[$id];
	}

	public static function getListStatusBKD()
	{
		$list = [
			'0' => 'Selesai',
			'1' => 'Beban Lebih',
			'2' => 'Berlanjut',
			'3' => 'Gagal'
		];

		return $list;
	}

	public static function listRoleStaf()
	{
		$list_staf = ['Staf', 'Staf TU', 'Tendik', 'Staf Biro', 'Staf UPT'];
		return $list_staf;
	}

	public static function kesimpulan($val)
	{
		$kesimpulan = '';
		if ($val <= 50)
			$kesimpulan = 'Buruk';
		else if ($val <= 60)
			$kesimpulan = 'Sedang';
		else if ($val <= 75)
			$kesimpulan = 'Cukup';
		else if ($val <= 90.99)
			$kesimpulan = 'Baik';
		else
			$kesimpulan = 'Baik Sekali';

		return $kesimpulan;
	}
	public static function statusSkp()
	{
		return [
			'1' => [
				'label' => 'warning',
				'nama' => 'Menunggu persetujuan atasan'
			],
			'2' => [
				'label' => 'success',
				'nama' => 'Disetujui atasan'
			],
			'3' => [
				'label' => 'danger',
				'nama' => 'Dikembalikan'
			],
			'4' => [
				'label' => 'info',
				'nama' => 'Diarsipkan'
			]
		];
	}

	public static function listJenisSumberDana()
	{
		return ['mandiri' => 'Mandiri/PT', 'dalam' => 'Institusi Dalam Negeri', 'luar' => 'Institusi Luar Negeri'];
	}

	public static function clearLogSync($niy)
	{
		$models = \app\models\LogSync::find([
			'NIY' => $niy
		])->all();

		foreach ($models as $m)
			$m->delete();
	}

	public static function createLogSync($niy, $keterangan)
	{

		$model = new \app\models\LogSync;
		$model->id = \app\helpers\MyHelper::gen_uuid();
		$model->NIY = $niy;
		$model->keterangan = $keterangan;
		$model->save();
	}

	public static function listSatker()
	{
		$query = \app\models\UnitKerja::find();
		$results = [];
		if (Yii::$app->user->identity->access_role == 'fakultas') {
			$query->andWhere([
				'id' => Yii::$app->user->identity->satker_id
			]);

			$res = $query->one();
			$results[] = [
				'id' => $res->id,
				'nama' => $res->nama
			];
			foreach ($res->unitKerjas as $r) {
				$results[] = [
					'id' => $r->id,
					'nama' => $r->nama
				];
			}
		} else {
			$res = $query->all();

			foreach ($res as $r) {
				$results[] = [
					'id' => $r->id,
					'nama' => $r->nama
				];
			}
		}


		return $results;
	}

	public static function getListSkema()
	{
		$api_baseurl = Yii::$app->params['api_baseurl'];
		$client = new \yii\httpclient\Client(['baseUrl' => $api_baseurl]);
		$client_token = Yii::$app->params['client_token'];
		$headers = ['x-access-token' => $client_token];

		$list_skema = [];

		$params = [];

		$response = $client->get('/litab/skema/abdimas', $params, $headers)->send();
		if ($response->isOk) {
			$res = $response->data['values'];
			$status = $response->data['status'];
			foreach ($res as $m) {
				$list_skema[$m['kode']] = $m['nama'];
			}
		}

		return $list_skema;
	}
	public static function getPeranPublikasi()
	{
		$list_peran = [
			'A' => 'Penulis',
			'B' => 'Editor',
			'C' => 'Penerjemah',
			'D' => 'Penemu/inventor'
		];

		return $list_peran;
	}

	public static function experience($L)
	{
		$a = 0;
		for ($x = 1; $x < $L; $x++) {
			$a += floor($x + 300 * pow(2, ($x / 7)));
		}
		return floor($a / 4);
	}

	public static function convertKategoriKegiatan($prefix)
	{
		$listKategori = \app\models\KategoriKegiatan::find()->where(['like', 'id', $prefix . '%', false])->orderBy(['id' => SORT_ASC])->one();

		// print_r($listKategori);exit;
		$results = [];
		$v = $listKategori;
		// foreach($listKategori as $q=>$v)
		// {
		$strlen = strlen($prefix);
		$prefix = substr($prefix, 0, $strlen);

		$pos = 6 - $strlen;
		$last3 = substr($v->id, -$pos, $pos);

		// if($pos == 3 && $last3 == '000')
		// 	continue;

		// else if($pos == 2 && $last3 == '00')
		// 	continue;

		if ($pos == 3) {
			$listKategori = \yii\helpers\ArrayHelper::map(\app\models\KategoriKegiatan::find()->where(['like', 'id', $prefix . '%', false])->orderBy(['id' => SORT_ASC])->all(), 'id', function ($data) {
				return $data->id;
			});

			$results = [];
			foreach ($listKategori as $q => $v) {
				$prefix = substr($prefix, 0, 3);

				$last3 = substr($v, -3, 3);
				if ($last3 == '000') continue;

				if ($last3 % 100 == 0) {
					$induk = \app\models\KategoriKegiatan::findOne($prefix . $last3);
					if (empty($induk)) continue;
					for ($i = 0; $i <= 50; $i++) {
						$id = $prefix . ($last3 + $i);

						$m = \app\models\KategoriKegiatan::findOne($id);
						if (!empty($m)) {
							$results[$induk->nama][$id] = $m->nama;
						}
					}
				}
			}
		} else if ($pos == 2) {

			// if($last3 % 100 == 0)
			// {


			for ($i = 0; $i <= 50; $i++) {
				$suffix = MyHelper::appendZeros($i, 2);
				$id = $prefix . $suffix;

				$m = \app\models\KategoriKegiatan::findOne($id);
				if (!empty($m)) {
					$results[$v->nama][$id] = $m->nama;
				}
			}


			// }   
		}

		// }



		$temps = [];

		foreach ($results as $q => $values) {
			$tmp = [];
			if (count($values) == 1) {

				$tmp = $values;
			} else {
				foreach ($values as $qq => $vv) {

					if (substr($qq, -1) != '0') {
						$tmp[$qq] = $vv;
					}
				}
			}

			$temps[$q] = $tmp;
		}

		return $temps;
	}

	public static function getFeederToken()
	{
		$feederToken = '';
		$tokenPath = Yii::getAlias('@webroot') . '/credentials/token/feeder_token.json';

		if (file_exists($tokenPath)) {
			$accessToken = json_decode(file_get_contents($tokenPath), true);
			$feederToken = $accessToken['id_token'];
		} else {
			if (!MyHelper::wsFeederLogin()) {
				throw new \Exception("Error Creating FEEDER Token", 1);
			} else {
				$accessToken = json_decode(file_get_contents($tokenPath), true);
				$feederToken = $accessToken['id_token'];
			}
		}

		return $feederToken;
	}

	public static function wsFeederLogin()
	{
		$feeder_baseurl = Yii::$app->params['feeder']['baseurl'];
		$feeder_username = Yii::$app->params['feeder']['username'];
		$feeder_password = Yii::$app->params['feeder']['password'];

		$client = new \GuzzleHttp\Client([
			'base_uri' => $feeder_baseurl

		]);
		$token = '';
		$errors = '';
		try {
			$headers = ['Content-Type' => 'application/json'];

			$params = [
				'act' => 'GetToken',
				'username'   => $feeder_username,
				'password'   => $feeder_password,
			];


			$response = $client->request('POST', '/ws/live2.php', [
				'headers' => $headers,
				'body' => json_encode($params)
			]);

			$results = $response->getBody()->getContents();
			$results = json_decode($results);
			if ($results->error_code == 0) {
				$data = [
					'id_token' => $results->data->token,
					'created_at' => date('Y-m-d H:i:s')
				];

				$tokenPath = Yii::getAlias('@webroot') . '/credentials/token/feeder_token.json';


				file_put_contents($tokenPath, json_encode($data));
			} else {
				$errors .= $results->error_desc;
				throw new \Exception;
			}

			return true;
		} catch (\Exception $e) {
			$errors .= $e->getMessage();
			print_r($errors);
			exit;
			return false;
		}
	}

	public static function getSisterToken()
	{
		$sisterToken = '';
		try {
			$tokenPath = Yii::getAlias('@webroot') . '/credentials/token/sister_token.json';


			if (file_exists($tokenPath)) {
				$accessToken = json_decode(file_get_contents($tokenPath), true);
				$sisterToken = $accessToken['id_token'];
				$created_at = $accessToken['created_at'];
				$date     = new \DateTime(date('Y-m-d H:i:s', strtotime($created_at)));
				$current  = new \DateTime(date('Y-m-d H:i:s'));
				$interval = $date->diff($current);
				// $inv = $interval->format('%I');
				$minutes = $interval->days * 24 * 60;
				$minutes += $interval->h * 60;
				$minutes += $interval->i;
				if ($minutes > 5) {
					if (!MyHelper::wsSisterLogin()) {
						throw new \Exception("Error Creating SISTER Token", 1);
					} else {
						$accessToken = json_decode(file_get_contents($tokenPath), true);
						$sisterToken = $accessToken['id_token'];
					}
				} else {

					$accessToken = json_decode(file_get_contents($tokenPath), true);
					$sisterToken = $accessToken['id_token'];
				}
			} else {
				if (!MyHelper::wsSisterLogin()) {
					throw new \Exception("Error Creating SISTER Token", 1);
				} else {
					$accessToken = json_decode(file_get_contents($tokenPath), true);
					$sisterToken = $accessToken['id_token'];
				}
			}
		} catch (\Exception $e) {
			print_r($e->getMessage());
			// exit;
		}


		return $sisterToken;
	}

	public static function wsSisterLogin()
	{
		try {
			$sister_baseurl = Yii::$app->params['sister_baseurl'];
			$sister_id_pengguna = Yii::$app->params['sister_id_pengguna'];
			$sister_username = Yii::$app->params['sister_username'];
			$sister_password = Yii::$app->params['sister_password'];
			$headers = ['content-type' => 'application/json'];
			$client = new \GuzzleHttp\Client([
				'timeout'  => 10.0,
				'headers' => $headers,
				// 'base_uri' => 'http://sister.unida.gontor.ac.id/api.php/0.1'
			]);
			// $full_url = $sister_baseurl.'/Login';
			$id_token = '';
			$full_url = $sister_baseurl . '/authorize';

			$response = $client->post($full_url, [
				'body' => json_encode([
					'username' => $sister_username,
					'password' => $sister_password,
					'id_pengguna' => $sister_id_pengguna
				]),
				'headers' => ['Content-type' => 'application/json']

			]);

			$response = json_decode($response->getBody());

			$data = [
				'id_token' => $response->token,
				'created_at' => date('Y-m-d H:i:s')
			];

			$tokenPath = Yii::getAlias('@webroot') . '/credentials/token/sister_token.json';


			file_put_contents($tokenPath, json_encode($data));
			return true;
		} catch (\Exception $e) {
			print_r($e->getMessage());
			return false;
		}
	}

	public static function gen_uuid()
	{
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),

			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,

			// 48 bits for "node"
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff)
		);
	}

	public static function getStatusSkripsi()
	{
		return [
			'1' => 'Belum Sidang Akhir', '2' => 'Sudah Sidang Akhir Belum Revisi', '3' => 'Sudah Sidang Akhir Sudah Revisi', '4' => 'Ganti Topik'
		];
	}

	public static function getRubrikCatatanHarian()
	{
		return ['1' => 'Tidak ada catatan', '2' => 'Sudah ada catatan, namun hanya laporan', '3' => 'Sudah ada catatan, namun hanya laporan dan evaluasi', '4' => 'Sudah ada catatan dan lengkap, namun kemajuan tidak/kurang signifikan', '5' => 'Sudah ada catatan, lengkap, dan ada kemajuan signifikan'];
	}

	public static function getStatusProposal()
	{
		return [
			'1' => 'Belum SPS', '2' => 'Sudah SPS Belum Revisi', '3' => 'Sudah SPS Sudah Revisi', '4' => 'Ganti Topik'
		];
	}

	public static function getLamaHari()
	{
		return [
			'1' => '1 hari', '3' => '3 hari', '7' => '1 minggu', '14' => '2 minggu', '21' => '3 minggu', '30' => '1 bulan'
		];
	}


	public static function appendZeros($str, $charlength = 6)
	{

		return str_pad($str, $charlength, '0', STR_PAD_LEFT);
	}

	public static function konversiEkdAngkaHuruf($skor)
	{
		$huruf = 'F';
		$ket  = '-';
		if ($skor >= 126) {
			$huruf = 'A';
			$ket  = 'Dilaporkan kepada Rektor untuk diberi reward sertifikat dan insentif tertentu penambah semangat kerja.';
		} else if ($skor >= 101) {
			$huruf = 'B';
			$ket  = 'Dilaporkan kepada Rektor untuk diberi reward sertifikat.';
		} else if ($skor >= 76) {
			$huruf = 'C';
			$ket  = 'Dilaporkan kepada Rektor bahwa yang bersangkutan telah mencukupi kinerjanya.';
		} else if ($skor >= 51) {
			$huruf = 'D';
			$ket  = 'Dilaporkan kepada Rektor untuk diberi peringatan.';
		} else if ($skor >= 30) {
			$huruf = 'E';
			$ket  = 'Dilaporkan kepada Rektor untuk diberikan sanksi tertentu yang mendukung peningkatan kinerjanya.';
		}

		return ['huruf' => $huruf, 'ket' => $ket];
	}

	public static function numberToAlphabet($index)
	{
		$alphabet = range('A', 'Z');

		return $alphabet[$index]; // returns D
	}

	public static function getListAbsensi()
	{
		$list = [
			'1' => 'H',
			'2' => 'I',
			'3' => 'S',
			'4' => 'G'

		];

		return $list;
	}

	public static function getListSemester()
	{
		$list_semester = [
			1 => 'Semester 1',
			2 => 'Semester 2',
			3 => 'Semester 3',
			4 => 'Semester 4',
			5 => 'Semester 5',
			6 => 'Semester 6',
			7 => 'Semester 7',
			8 => 'Semester 8',
			9 => 'Semester 9 ke atas',
		];

		return $list_semester;
	}

	public static function getSemester()
	{
		$list_semester = [
			0 => [1 => 1, 2 => 2],
			1 => [3 => 3, 4 => 4],
			2 => [5 => 5, 6 => 6],
			3 => [7 => 7, 8 => 8],
		];

		return $list_semester;
	}

	public static function convertTanggalIndo($date)
	{

		if (empty($date))
			return '';

		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $date);

		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun

		if (empty($pecahkan[2]))
			return '';

		return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
	}


	public static function getStatusAktivitas()
	{
		$roles = [
			'A' => 'AKTIF', 'C' => 'CUTI', 'D' => 'DO', 'K' => 'KELUAR', 'L' => 'LULUS', 'N' => 'NON-AKTIF', 'G' => 'DOUBLE DEGREE', 'M' => 'MUTASI'
		];


		return $roles;
	}



	public static function isBetween($sd, $ed)
	{
		return (($sd >= $ed) && ($sd <= $ed));
	}

	public static function dmYtoYmd($tgl)
	{
		$date = str_replace('/', '-', $tgl);
		return date('Y-m-d H:i:s', strtotime($date));
	}

	public static function YmdtodmY($tgl)
	{
		return date('d-m-Y H:i:s', strtotime($tgl));
	}


	public static function hitungDurasi($date1, $date2)
	{
		$date1 = new \DateTime($date1);
		$date2 = new \DateTime($date2);
		$interval = $date1->diff($date2);

		$elapsed = '';
		if ($interval->d > 0)
			$elapsed = $interval->format('%a hari %h jam %i menit %s detik');
		else if ($interval->h > 0)
			$elapsed = $interval->format('%h jam %i menit %s detik');
		else
			$elapsed = $interval->format('%i menit %s detik');


		return $elapsed;
	}

	public static function logError($model)
	{
		$errors = '';
		foreach ($model->getErrors() as $attribute) {
			foreach ($attribute as $error) {
				$errors .= $error . ' ';
			}
		}

		return $errors;
	}

	public static function formatRupiah($value, $decimal = 0)
	{
		return number_format($value, $decimal, ',', '.');
	}

	public static function getSelisihHariInap($old, $new)
	{
		$date1 = strtotime($old);
		$date2 = strtotime($new);
		$interval = $date2 - $date1;
		return round($interval / (60 * 60 * 24)) + 1;
	}

	function getRandomString($minlength = 12, $maxlength = 12, $useupper = true, $usespecial = false, $usenumbers = true)
	{
		$key = '';
		$charset = "abcdefghijklmnopqrstuvwxyz";

		if ($useupper) $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		if ($usenumbers) $charset .= "0123456789";

		if ($usespecial) $charset .= "~@#$%^*()_±={}|][";

		for ($i = 0; $i < $maxlength; $i++) $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];

		return $key;
	}
}
