<?php

$sendto = $_POST['address'];
$content = $_POST['content'];

require_once '../vars.php';

// 設置した場所のパスを指定する
require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/Exception.php');
require('../PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 文字エンコードを指定
mb_language('uni');
mb_internal_encoding('UTF-8');

// インスタンスを生成（true指定で例外を有効化）
$mail = new PHPMailer(true);

// 文字エンコードを指定
$mail->CharSet = 'utf-8';

try {
  // デバッグ設定
  $mail->SMTPDebug = 4; // デバッグ出力を有効化（レベルを指定）
  $mail->Debugoutput = function($str, $level) { sub_logwrite("PHP Mailer:" . $str); };

  // SMTPサーバの設定
  $mail->isSMTP();                          // SMTPの使用宣言
  $mail->Host       = $server;   // SMTPサーバーを指定
  $mail->SMTPAuth   = true;                 // SMTP authenticationを有効化
  $mail->Username   = $user_name;   // SMTPサーバーのユーザ名
  $mail->Password   = $password;           // SMTPサーバーのパスワード
  $mail->SMTPSecure = 'ssl';  // 暗号化を有効（tls or ssl）無効の場合はfalse
  $mail->Port       = 465; // TCPポートを指定（tlsの場合は465や587）

  // 送受信先設定（第二引数は省略可）
  $mail->setFrom($from_address, 'RECITONE by udcxx.'); // 送信者
  $mail->addAddress($sendto);   // 宛先
  $mail->addReplyTo($reply_address, 'udcxx.'); // 返信先
  $mail->Sender = $from_address; // Return-path

  // 送信内容設定
  $mail->Subject = '【RECITONE】AIが考えたレシピをお届けします'; 
  $mail->Body    = $content;  

  // 送信
  $mail->send();
} catch (Exception $e) {
  // エラーの場合
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

function sub_logwrite($log)
{
	syslog(LOG_ERR, $log);
}

?>