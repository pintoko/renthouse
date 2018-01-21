<?php
namespace Repository;
use Medoo\Medoo;

class TransactionRepository
{
	const TABLE = 'pembayaran';
	const PREFIX = 'trx_';

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

	public function getByPeriode($periode)
	{
		$transactions = $this->db->select(
			self::TABLE,
			[
				'id',
				'id_pelanggan',
				'id_rumah',
				'tanggal_bayar',
				'periode',
				'harga',
				'awal',
				'akhir'
			],
			[
				'deleted' => null,
				'periode' => $periode
			]
		);
		$transactions = $this->changeKeyById($transactions, 'id_pelanggan', self::PREFIX);
		return $transactions;
	}
}
