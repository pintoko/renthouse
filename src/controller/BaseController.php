<?php
namespace Controller;
use PDO;
use Medoo\Medoo;

class BaseController{
	const FETCH_ASSOC = PDO::FETCH_ASSOC;
	const DATE_FORMAT = 'Y-m-d';
	const DATETIME_FORMAT = 'Y-m-d H:i:s';

	public function __construct($container)
	{
		$this->view = $container->get('view');
		$this->db = $container->get(PDO::class);
		$this->dbmeedo = $container->get(Medoo::class);
		$this->container = $container;
	}
}