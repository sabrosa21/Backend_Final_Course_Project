<?php

/* -------------------------------------------------------------------------- */
/*                        PDO MYSQL DATABASE CONNECTION                       */
/* -------------------------------------------------------------------------- */
function conn()
{
    /* --------------------------------- DEV USE -------------------------------- */
    $host = 'localhost:3306';
    $db   = 'exptracker';
    $user = 'root';
    //--- Once I'm using Mamp the password is root. Set your password
    $pass = 'root';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
        $pdo = null;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}



/* -------------------------------------------------------------------------- */
/*                                 PHP MAILER                                 */
/* -------------------------------------------------------------------------- */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function email($to, $subject, $message, $output)
{

    require '../vendor/autoload.php';
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        // $mail->Host       =  Place you email host here ;
        $mail->SMTPAuth   = true;
        // $mail->Username   = Place your email user here ;
        // $mail->Password   = Place your email pass here ;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('fullstackgalileu@euricocorreia.pt', 'Staff Expense Tracker');
        $mail->addAddress($to);
        $mail->addReplyTo('fullstackgalileu@euricocorreia.pt', 'Information');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo $output;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

/* -------------------------------------------------------------------------- */
/*   FUNCTION TO GENERETE RANDONM PASSWORDS WHEN YOU RESET YOU ACCOUNT PASS   */
/* -------------------------------------------------------------------------- */
function generateStrongPassword($length = 10, $add_dashes = false, $available_sets = 'luds')
{
    $sets = array();
    if (strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if (strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if (strpos($available_sets, 'd') !== false)
        $sets[] = '23456789';
    if (strpos($available_sets, 's') !== false)
        $sets[] = '!@#$%&*?';

    $all = '';
    $password = '';
    foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
    }

    $all = str_split($all);
    for ($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];

    $password = str_shuffle($password);

    if (!$add_dashes)
        return $password;

    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while (strlen($password) > $dash_len) {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
}
