<?php
namespace Controller;

class RumahController extends BaseController {
	public function getRumah($request, $response) {
		$stmt = $this->db->query('select * from rumah');
		$data['rumahs'] = $stmt->fetchAll(parent::FETCH_ASSOC);
		$data['menu'] = 'rumah';
		$data['dropdown_active'] = true;

		return $this->view->render($response, '/rumah/list.html', $data);
	}

	public function editRumah($request, $response, $args)
	{
		$stmt = $this->db->prepare('select * from rumah where id = :id');
		$stmt->bindParam(':id', $args['id']);
		if($stmt->execute()) {
			$data['rumah'] = $stmt->fetch(parent::FETCH_ASSOC);
		}

		return $this->view->render($response, '/rumah/form.html', $data);
	}

	public function simpan($request, $response, $args)
	{
		$body = $request->getParsedBody();
		$query = 'update rumah set nama = :nama, alamat = :alamat, no_pln = :no_pln where id = :id';
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':nama', $body['nama']);
		$stmt->bindParam(':alamat', $body['alamat']);
		$stmt->bindParam(':no_pln', $body['no_pln']);
		$stmt->bindParam(':id', $args['id']);

		if ($stmt->execute()) {
			$data['title'] = 'Ubah data sukses';
			$data['message'] = 'Ubahan data untuk rumah id: '.$args['id']. ' berhasil';
			$data['url'] = '/rumah';
			$data['page_name'] = 'Daftar Rumah';
			$viewFile = '/general/success.html';
		}

		return $this->view->render($response, $viewFile, $data);
	}
}