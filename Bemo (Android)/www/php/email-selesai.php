<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require_once 'config.php';
require_once 'phpmailer/classes/class.phpmailer.php';

$data = array();

if ($_POST) {
  $fullName = $_POST['nama'];
  $kode = $_POST['kode'];
  $tgl_keluar = $_POST['tgl_keluar'];
  $wkt_mulai = $_POST['wkt_mulai'];
  $wkt_selesai = $_POST['wkt_selesai'];
  $keterangan = $_POST['keterangan'];
  $query = "SELECT id,nama,email FROM pengguna WHERE nama = '$fullName' ";
  $result = mysqli_query($db, $query);

  if (mysqli_num_rows($result) == 1) {
    $userData = mysqli_fetch_assoc($result);

    $id = $userData["id"];
    $userEmail = $userData["email"];

    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->SMTPSecure = ‘tls’;
    $mail->Host = "ssl://smtp.gmail.com"; //hostname masing-masing provider email
    $mail->SMTPDebug = 2;
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = "noreply.bemo25@gmail.com"; //user email
    $mail->Password = "bemo12345"; //password email
    $mail->SetFrom("noreply.bemo25@gmail.com", "Admin Bemo"); //set email pengirim
    $mail->Subject = "Verifikasi Email"; //subyek email
    $mail->AddAddress($userEmail, $fullName); //tujuan email
    $mail->MsgHTML("Dear $fullName ,<br/><br/>Berikut kami informasikankan bahwa mobil anda telah selesai di servis:<br/><br/><table style='text-align: left;'><tr><th>Kode Servis</th><td style='text-align: right;'>:</td><td> ' . $kode . '</td></tr><tr><th>Nama Montir</th><td style='text-align: right;'>:</td><td> ' . $nama_montir . '</td></tr><tr><th>Tanggal </th><td style='text-align: right;'>:</td><td> ' . $tgl_keluar . '</td></tr><tr><th>Waktu </th><td style='text-align: right;'>:</td><td> ' . $wkt_mulai . ' - ' . $wkt_selesai . '</td></tr><tr><th>Keterangan</th><td style='text-align: right;'>:</td><td> ' . $keterangan . '</td></tr></table><br/><br/>Bila mobil anda tidak enak, jangan lupa untuk datang kembali...<br/>Terimakasih");
    if ($mail->Send()) $data['email'] = "Message has been sent";
    else $data['email'] = "Failed to sending message";

    $data = $userData;
  } else {
    $data = 'tidak berhasil';
  }
  //returns data as JSON format
  echo json_encode($data);
} else {
  $obj = (object)[
    'error' => 'user tidak ditemukan!'
  ];
  http_response_code(400);
  echo json_encode($obj);
}
