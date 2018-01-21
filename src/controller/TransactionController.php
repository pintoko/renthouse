<?php
namespace Controller;

use NumberToWords\NumberToWords;
use Helper\MonthHelper;
use Repository\SewaRepository;
use Repository\TransactionRepository;

class TransactionController extends BaseController {
	public function __construct($container)
	{
		parent::__construct($container);
		$this->monthHelper = $container->get(MonthHelper::class);
	}

	private function getPembayaranById($id)
	{
		$query =
		"
			select 
				pm.id,
				p.nama as pelanggan,
				p.id as pid,
				p.phone as phone,
				p.gender as gender,
				r.nama as rumah,
				r.id as rid,
                r.alamat,
				pm.tanggal_bayar,
				pm.harga,
                pm.awal,
                pm.akhir,
                s.awal as awal_sewa
			from pembayaran as pm
				join pelanggan as p on pm.id_pelanggan = p.id
				join rumah as r on pm.id_rumah = r.id
				join sewa as s on s.id_rumah = r.id and s.id_pelanggan = p.id
			where
				pm.id = :id
		";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':id', $id);
		
		if($stmt->execute()) {
			$data = $stmt->fetch(parent::FETCH_ASSOC);
		}

		return $data;
	}

	public function newGetAll($request, $response, $args)
	{
		$trxRepo = new TransactionRepository($this->dbmeedo);
		$sewaRepo = new SewaRepository($this->dbmeedo);

		$data = $request->getQueryParams();
		$data['menu'] = 'daftar_pembayaran';
		$data['today'] = $this->monthHelper->getTodayDate();
		$data['year_list'] = $this->monthHelper->getYearList();
		$data['month_list'] = $this->monthHelper->getMonthList();
		$data['pembayarans'] = $trxRepo->getByPeriode($this->monthHelper->createPeriode($data['month'], $data['year']));
		$data['sewas'] = $sewaRepo->getAllActive();

		return $this->view->render($response, '/pembayaran/list.html', $data);
	}

	public function getAll($request, $response, $args)
	{
		$query = 
		"
		select 
			pm.id, 
			p.nama as pelanggan, 
			p.phone as phone, 
			r.nama as rumah, 
			pm.tanggal_bayar, 
			pm.periode,
			pm.harga, 
			pm.awal, 
			pm.akhir 
		from
			pembayaran as pm 
			join pelanggan as p on pm.id_pelanggan=p.id 
			join rumah as r on pm.id_rumah = r.id
		where
			deleted is null and
			periode = :periode
		order by 
			rumah asc,
			pm.awal desc
		";
		$data = $request->getQueryParams();
		$stmt = $this->db->prepare($query);
		$periode = $this->monthHelper->createPeriode($data['month'], $data['year']);
		$stmt->bindParam(':periode', $periode);
		if ($stmt->execute()) {
			$data['pembayarans'] = $stmt->fetchAll(parent::FETCH_ASSOC);
		}

		$data['menu'] = 'daftar_pembayaran';
		$data['today'] = $this->monthHelper->getTodayDate();
		$data['year_list'] = $this->monthHelper->getYearList();
		$data['month_list'] = $this->monthHelper->getMonthList();
		
		return $this->view->render($response, '/pembayaran/list.html', $data);
	}

	public function formPrint($request, $response, $args)
	{
		$data['pembayaran'] = $this->getPembayaranById($args['id']);
		$data['pembayaran']['tanggal_bayar'] = date('d M Y', strtotime($data['pembayaran']['tanggal_bayar']));
		$data['pembayaran']['awal'] = date('d M Y', strtotime($data['pembayaran']['awal']));
		$data['pembayaran']['akhir'] = date('d M Y', strtotime($data['pembayaran']['akhir']));
		$data['pembayaran']['gender'] = $data['pembayaran']['gender'] == 'm' ? 'Bpk.' : 'Ibu';
		$data['today'] = getdate(time());

		$numberToWords = new NumberToWords();
		$numberTransformer = $numberToWords->getNumberTransformer('id');
		$data['pembayaran']['harga_words'] = ucwords($numberTransformer->toWords($data['pembayaran']['harga'])).' Rupiah';

		return $this->view->render($response, '/pembayaran/print.html', $data);
	}

	public function editPembayaran($request, $response, $args)
	{
		$data['pembayaran'] = $this->getPembayaranById($args['id']);
		$data['id'] = $args['id'];
		$data['today'] = date('d M Y');

		return $this->view->render($response, '/pembayaran/form_ubah.html', $data);
	}

	public function simpanEditPembayaran($request, $response, $args)
	{
		$body = $request->getParsedBody();
		$awal = $body['awal'];
		$akhir = date(self::DATE_FORMAT, strtotime($awal.' + 1 month - 1 day'));
		$query = 
		'
			update
				pembayaran
			set
				tanggal_bayar = :tanggal_bayar, 
				awal = :awal,
				akhir = :akhir,
				periode = :periode
			where 
				id = :id
		';
		$stmtInsert = $this->db->prepare($query);
		$stmtInsert->bindParam(':tanggal_bayar', $body['tanggal_bayar']);
		$stmtInsert->bindParam(':awal', $awal);
		$stmtInsert->bindParam(':akhir', $akhir);
		$stmtInsert->bindParam(':periode', $this->monthHelper->countDominantMonth($awal, $akhir));
		$stmtInsert->bindParam(':id', $args['id']);

		if($stmtInsert->execute()) {
			$data['title'] = 'Ubah Data Pembayaran sukses';
			$data['message'] = 'Ubahan data pembayaran untuk pelanggan berhasil';
			$data['url'] = '/pembayaran';
			$data['page_name'] = 'Daftar Pembayaran';
			$viewFile = '/general/success.html';
		} else {
			$data['ok'] = false;
		}

		return $this->view->render($response, $viewFile, $data);
	}

	public function hapusPembayaran($request, $response, $args)
	{
		$query = 
		'
			update
				pembayaran
			set
				deleted = :deleted
			where 
				id = :id
		';
		$stmtInsert = $this->db->prepare($query);
		$stmtInsert->bindParam(':deleted', date(parent::DATETIME_FORMAT));
		$stmtInsert->bindParam(':id', $args['id']);
		if($stmtInsert->execute()) {
			$data['title'] = 'Hapus Data Pembayaran sukses';
			$data['message'] = 'Penghapusan data pembayaran berhasil';
			$data['url'] = '/pembayaran';
			$data['page_name'] = 'Daftar Pembayaran';
			$viewFile = '/general/success.html';
		} else {
			$data['ok'] = false;
		}

		return $this->view->render($response, $viewFile, $data);
	}
}