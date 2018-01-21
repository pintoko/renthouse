<?php
namespace Controller;

class HargaController extends BaseController {
	public function getHarga($request, $response) {
		$stmt = $this->db->query('select * from harga');
		$data['hargas'] = $stmt->fetchAll(parent::FETCH_ASSOC);
		$data['menu'] = 'harga';
		$data['dropdown_active'] = true;

		return $this->view->render($response, '/harga/list.html', $data);
	}

	public function getHargaForm($request, $response) {
		$data['menu'] = 'harga';
		$data['dropdown_active'] = true;
		return $this->view->render($response, '/harga/form.html', $data);
	}

	public function editHarga($request, $response, $args)
	{
		$stmt = $this->db->prepare('select * from harga where id = :id');
		$stmt->bindParam(':id', $args['id']);
		if($stmt->execute()) {
			$data['harga'] = $stmt->fetch(parent::FETCH_ASSOC);
		}

		return $this->view->render($response, '/harga/form.html', $data);
	}

	public function updateHarga($request, $response, $args)
	{
		$body = $request->getParsedBody();
		$query = 'update harga set nama=:nama, nominal=:nominal, periode=:periode, is_active=:is_active where id=:id';
		$stmt = $this->db->prepare($query);
		$is_active = !empty($body['is_active']);
		$stmt->bindParam(':nama', $body['nama']);
		$stmt->bindParam(':nominal', $body['nominal']);
		$stmt->bindParam(':periode', $body['periode']);
		$stmt->bindParam(':is_active', $is_active);
		$stmt->bindParam(':id', $args['id']);

		if ($stmt->execute()) {
			$data['title'] = 'Update data sukses';
			$data['message'] = 'Update data untuk harga berhasil';
			$data['url'] = '/harga';
			$data['page_name'] = 'Daftar Harga';
			$viewFile = '/general/success.html';
		}
		return $this->view->render($response, $viewFile, $data);
	}

	public function simpanBaru($request, $response, $args)
	{
		$body = $request->getParsedBody();
		$query = 'insert into harga(nama, nominal, periode, is_active) values(:nama, :nominal, :periode, TRUE)';
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':nama', $body['nama']);
		$stmt->bindParam(':nominal', $body['nominal']);
		$stmt->bindParam(':periode', $body['periode']);

		if ($stmt->execute()) {
			$data['title'] = 'Insert data sukses';
			$data['message'] = 'Insert data untuk harga berhasil';
			$data['url'] = '/harga';
			$data['page_name'] = 'Daftar Harga';
			$viewFile = '/general/success.html';
		}

		return $this->view->render($response, $viewFile, $data);
	}
}