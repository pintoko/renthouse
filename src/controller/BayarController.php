<?php
namespace Controller;

use Helper\MonthHelper;

class BayarController extends BaseController {
	private function getDetailPembayaranBydId($id = false)
	{
		$stmt = $this->db->prepare("
			select 
				p.id as pid, 
				p.nama as pelanggan, 
				p.phone, 
				r.id as rid, 
				r.nama as rumah, 
				s.awal, 
				h.id as hid, 
				h.nominal, 
				s.awal as awal 
			from 
				sewa as s 
				join rumah as r on s.id_rumah = r.id
				join pelanggan as p on s.id_pelanggan = p.id 
				join harga as h on s.id_harga = h.id 
			where 
			s.id = :id
		");
		$stmt->bindParam(':id', $id);
		if($stmt->execute()) {
			$data = $stmt->fetch(parent::FETCH_ASSOC);
		}

		return $data;
	}

	public function getDaftarBayar($request, $response)
	{
		$stmt = $this->db->query("
			select 
				s.id,
				p.nama as pelanggan,
				p.phone,
				r.nama as rumah,
				s.awal,
				h.nominal
			from sewa as s 
				join rumah as r on s.id_rumah=r.id
				join pelanggan as p on s.id_pelanggan=p.id
				join harga as h on s.id_harga=h.id
			where s.akhir is null
			order by rumah asc");
		$data['sewas'] = $stmt->fetchAll(parent::FETCH_ASSOC);
		$data['today'] = date('d M Y', time());
		$data['menu'] = 'catat_pembayaran';
		return $this->view->render($response, '/bayar/list.html', $data);
	}

	public function konfirmasi($request, $response, $args)
	{
		$data['sewa'] = $this->getDetailPembayaranBydId($args['id']);
		$awalSewa = getdate(strtotime($data['sewa']['awal']));
		$data['awalSewa'] = date('Y', time()).'-'.date('m', time()).'-'.$awalSewa['mday'];
		$data['today'] = date('d M Y', time());
		$data['todayInput'] = date(self::DATE_FORMAT, time());
		$data['id'] = $args['id'];
		return $this->view->render($response, '/bayar/konfirmasi.html', $data);
	}

	public function simpan($request, $response, $args)
	{
		$body = $request->getParsedBody();
		if($body['save'] == false) {
			return $response->withStatus(404);
		}

		$data['sewa'] = $this->getDetailPembayaranBydId($args['id']);

		$today = getdate(time());
		$awal = $body['awal'];
		$akhir = date(self::DATE_FORMAT, strtotime($awal.' + 1 month - 1 day'));

		$query = 
		'
			insert into pembayaran (
				tanggal_bayar, 
				id_pelanggan, 
				id_rumah, 
				harga,
				periode,
				awal, 
				akhir
			) values (
				:tanggal_bayar, 
				:pid, 
				:rid, 
				:harga, 
				:periode,
				:awal, 
				:akhir
			)
		';
		$periode = $this->container->get(MonthHelper::class)->countDominantMonth($awal, $akhir);
		$stmtInsert = $this->db->prepare($query);
		$stmtInsert->bindParam(':pid', $data['sewa']['pid']);
		$stmtInsert->bindParam(':rid', $data['sewa']['rid']);
		$stmtInsert->bindParam(':harga', $data['sewa']['nominal']);
		$stmtInsert->bindParam(':periode', $periode);
		$stmtInsert->bindParam(':awal', $awal);
		$stmtInsert->bindParam(':akhir', $akhir);
		$stmtInsert->bindParam(':tanggal_bayar', $body['tanggal_bayar']);
		if($stmtInsert->execute()) {
			$data['title'] = 'Pembayaran sukses';
			$data['message'] = 'Tambah data pembayaran untuk pelanggan '.$data['sewa']['pelanggan']. ' berhasil';
			$data['url'] = '/bayar';
			$data['page_name'] = 'Daftar Pembayaran';
			$viewFile = '/general/success.html';
		} else {
			$data['ok'] = false;
		}

		return $this->view->render($response, $viewFile, $data);
	}
}