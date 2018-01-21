<?php
namespace Controller;
use Helper\MonthHelper;

class StubController extends BaseController {
	public function __construct($container)
	{
		parent::__construct($container);
		$this->monthHelper = $container->get(MonthHelper::class);
	}

	public function test()
	{
		var_dump(getenv('MYSQL_DB_NAME'));
	}

	public function addColumnBulan($request, $response)
	{
		$select = "select * from pembayaran";
		$stmt = $this->db->query($select);
		$data = $stmt->fetchAll(parent::FETCH_ASSOC);
		echo "<pre>";
		// var_dump($data);die;
		foreach ($data as $key => $value) {
			$periode = $this->monthHelper->count($value['awal'], $value['akhir']);
			$update = "update pembayaran set periode=:periode where id=:id";
			var_dump($periode);
			$stmt = $this->db->prepare($update);
			$stmt->bindParam(':id', $value['id']);
			$stmt->bindParam(':periode', $periode);
			if ($stmt->execute()) {
				echo "ok <br/>";
			}
		}
	}
}
