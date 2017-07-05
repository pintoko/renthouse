<?php
namespace Controller;
use Interop\Container\ContainerInterface;

class RumahController {
	public function __construct(ContainerInterface $c)
	{
		$this->view = $c->get('view');
		$this->db = $c->get('db_sqlite');
	}

	public function getRumah($request, $response)
	{
		$data['title'] = 'coba';
		// $data['rumah'] = $this->db->query('select * from rumah');
		return $this->view->render($response, 'list_rumah.html', $data);
	}
}