<?php
$app->get('/', Controller\BayarController::class.':getDaftarBayar');

$app->get('/stub/bulanbayar', Controller\StubController::class.':addColumnBulan');
$app->get('/stub/test', Controller\StubController::class.':test');

$app->get('/rumah', Controller\RumahController::class.':getRumah');
$app->get('/rumah/{id}/edit', Controller\RumahController::class.':editRumah');
$app->post('/rumah/{id}/edit', Controller\RumahController::class.':simpan');

$app->get('/bayar', Controller\BayarController::class.':getDaftarBayar');
$app->get('/bayar/{id}', Controller\BayarController::class.':konfirmasi');
$app->post('/bayar/{id}', Controller\BayarController::class.':simpan');

// $app->get('/pembayaran', Controller\TransactionController::class.':getAll');
$app->get('/pembayaran', Controller\TransactionController::class.':newGetAll');
$app->get('/pembayaran/{id}/cetak', Controller\TransactionController::class.':formPrint');
$app->get('/pembayaran/{id}/edit', Controller\TransactionController::class.':editPembayaran');
$app->post('/pembayaran/{id}/edit', Controller\TransactionController::class.':simpanEditPembayaran');
$app->get('/pembayaran/{id}/hapus', Controller\TransactionController::class.':hapusPembayaran');

$app->get('/pelanggan', Controller\PelangganController::class.':getPelanggan');
$app->get('/pelanggan/baru', Controller\PelangganController::class.':insert');
$app->post('/pelanggan/baru', Controller\PelangganController::class.':simpan');
$app->get('/pelanggan/{id}/edit', Controller\PelangganController::class.':edit');
$app->post('/pelanggan/{id}/edit', Controller\PelangganController::class.':simpan');

$app->get('/harga', Controller\HargaController::class.':getHarga');
$app->get('/harga/{id}/edit', Controller\HargaController::class.':editHarga');
$app->post('/harga/{id}/edit', Controller\HargaController::class.':updateHarga');
$app->get('/harga/baru', Controller\HargaController::class.':getHargaForm');
$app->post('/harga/baru', Controller\HargaController::class.':simpanBaru');

$app->get('/sewa', Controller\SewaController::class.':showList');
$app->get('/sewa/baru', Controller\SewaController::class.':save');
$app->get('/sewa/{id}/edit', Controller\SewaController::class.':save');
