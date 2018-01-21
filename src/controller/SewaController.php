<?php
namespace Controller;

class SewaController extends BaseController {
	public function __construct($container)
	{
		parent::__construct($container);
		$this->data['menu'] = 'sewa';
		$this->data['dropdown_active'] = true;
		$this->data['today'] = date(self::DATE_FORMAT);
	}
	private function getAllByQuery($query)
	{
		$stmt = $this->db->query($query);
		$data = $stmt->fetchAll(parent::FETCH_ASSOC);
		return $data;
	}
	private function getRumah()
	{
		$query = 
		'select * 
		from rumah
		where id not in (
			select id_rumah from sewa where akhir is null
		)';
		$data = $this->getAllByQuery($query);
		return $data;
	}

	private function getPelanggan()
	{
		$query = 'select * from pelanggan where status_active is true';
		$data = $this->getAllByQuery($query);
		return $data;
	}

	private function getHarga()
	{
		$query = 'select * from harga';
		$data = $this->getAllByQuery($query);
		return $data;
	}

	public function showList($request, $response)
	{
		$query = 'select s.id, id_rumah, r.nama as rumah, id_pelanggan, p.nama as pelanggan, p.phone, id_harga, h.nominal, s.awal, s.akhir
				from sewa s 
				join rumah r on s.id_rumah = r.id
				join pelanggan p  on s.id_pelanggan = p.id
				join harga h on s.id_harga = h.id
				where s.akhir is null
				order by r.nama asc';
		$this->data['sewas'] = $this->getAllByQuery($query);
		return $this->view->render($response, '/sewa/list.html', $this->data);
	}

	public function save($request, $response, $args)
	{
		$this->data['rumah'] = $this->getRumah();
		$this->data['pelanggan'] = $this->getPelanggan();
		$this->data['harga'] = $this->getHarga();
		return $this->view->render($response, '/sewa/form.html', $this->data);
	}
}