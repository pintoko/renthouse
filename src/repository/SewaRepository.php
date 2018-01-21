<?php
namespace Repository;
use Medoo\Medoo;

class SewaRepository {
	const PREFIX = 'sewa_';

	public function __construct(Medoo $db)
	{
		$this->db = $db;
	}

	private function changeKeyById($data = array(), $keyName, $prefix)
	{
		foreach ($data as $key => $value) {
			$data[$prefix.$value[$keyName]] = $value;
			unset($data[$key]);
		}
		return $data;
	}

	public function getAllActive()
	{
		$sewas = $this->db->select('sewa',
			[
				'[>]rumah' => ['id_rumah' => 'id'],
				'[>]pelanggan' => ['id_pelanggan' => 'id'],
				'[>]harga' => ['id_harga' => 'id'],
			],
			[
				'id_rumah',
				'id_pelanggan',
				'rumah.nama(rumah)',
				'pelanggan.nama(pelanggan)',
				'harga.nominal',
				'phone',
				'awal',
			],
			[
				'akhir' => null,
				'ORDER' => ['rumah.nama' => 'ASC']
			]
		);
		$sewas = $this->changeKeyById($sewas, 'id_pelanggan', self::PREFIX);
		return $sewas;
	}
}
