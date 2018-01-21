<?php
namespace Controller;

use NumberToWords\NumberToWords;

class PelangganController extends BaseController {
	const gender = ['m' => 'Laki-laki', 'f' => 'Perempuan'];
	const status_active = [true => 'Aktif', false => 'Tidak Aktif'];

	private function setDefaultData($data = [])
	{
		$data['gender'] = self::gender;
		$data['status_active'] = self::status_active;
		return $data;
	}

	public function getPelanggan($request, $response)
	{
		$stmt = $this->db->query('select * from pelanggan order by status_active desc, nama asc');
		$data['pelanggans'] = $stmt->fetchAll(parent::FETCH_ASSOC);
		$data['menu'] = 'pelanggan';
		$data['dropdown_active'] = true;

		return $this->view->render($response, '/pelanggan/list.html', $data);
	}

	public function edit($request, $response, $args)
	{
		$stmt = $this->db->prepare('select * from pelanggan where id = :id');
		$stmt->bindParam(':id', $args['id']);
		if ($stmt->execute()) {
			$data['pelanggan'] = $stmt->fetch(parent::FETCH_ASSOC);
		}
		$data = $this->setDefaultData($data);
		return $this->view->render($response, '/pelanggan/form.html', $data);
	}

	public function simpan($request, $response, $args)
	{
		$body = $request->getParsedBody();
		if (isset($args['id'])) {
			$query = 'update pelanggan set nama = :nama, gender = :gender, nik = :nik, phone = :phone, pekerjaan = :pekerjaan, status_active = :status_active where id = :id';
		} else {
			$query = 'insert into pelanggan(id, nama, gender, nik, phone, pekerjaan, status_active) values(NULL, :nama, :gender, :nik, :phone, :pekerjaan, :status_active)';
		}
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':nama', $body['nama']);
		$stmt->bindParam(':gender', $body['gender']);
		$stmt->bindParam(':nik', $body['nik']);
		$stmt->bindParam(':phone', $body['phone']);
		$stmt->bindParam(':pekerjaan', $body['pekerjaan']);
		$stmt->bindParam(':status_active', $body['status_active']);
		if (isset($args['id'])) {
			$stmt->bindParam(':id', $args['id']);
		}
		
		if ($stmt->execute()) {
			$data['title'] = 'Simpan data pelanggan sukses';
			$data['message'] = 'Simpan data untuk pelanggan '.$body['nama']. ' berhasil';
			$data['url'] = '/pelanggan';
			$data['page_name'] = 'Daftar Pelanggan';
			$viewFile = '/general/success.html';
		}

		return $this->view->render($response, $viewFile, $data);
	}

	public function insert($request, $response)
	{
		$data = [];
		$data = $this->setDefaultData($data);
		return $this->view->render($response, '/pelanggan/form.html', $data);
	}
}
