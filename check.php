<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

if (!$link) {
    die("無法打開數據庫!<br/>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'login') {
        $user_login = mysqli_real_escape_string($link, $_POST['user_login']);
        $user_pass = mysqli_real_escape_string($link, $_POST['user_pass']);

        // 如果帳號和密碼都是root，重定向到manager.php
        if ($user_login === 'root' && $user_pass === 'root') {
            $_SESSION['manager_loggedin'] = true;
            $_SESSION['username'] = 'Manager';
            header("Location: manager.php");
            exit;
        }

        // 檢查帳號和密碼是否正確
        $sql = "SELECT id, username, verified FROM member WHERE user_login = ? AND user_pass = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $user_login, $user_pass);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $verified);
                mysqli_stmt_fetch($stmt);

                if ($verified == 1) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['user_login'] = $user_login;
                    $_SESSION['username'] = $username;

                    header("Location: member.php");
                    exit;
                } else {
                    $_SESSION['error'] = "帳號未驗證，請檢查您的郵箱。";
                    header("Location: login.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = "帳號或密碼不正確，請重新輸入。";
                header("Location: login.php");
                exit;
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "資料庫查詢失敗：" . mysqli_error($link);
            header("Location: login.php");
            exit;
        }
    }

    if ($action == 'register') {
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $user_login = mysqli_real_escape_string($link, $_POST['user_login']);
        $user_pass = mysqli_real_escape_string($link, $_POST['user_pass']);
        $confirm_pass = mysqli_real_escape_string($link, $_POST['confirm_pass']);

        if ($user_pass !== $confirm_pass) {
            $_SESSION['error'] = "兩次輸入的密碼不相同，請重新輸入。";
            header("Location: register.php");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "無效的電子郵件格式，請重新輸入。";
            header("Location: register.php");
            exit;
        }

        $sql = "SELECT id FROM member WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $_SESSION['error'] = "該電子郵件已經註冊過，請使用不同的電子郵件。";
                mysqli_stmt_close($stmt);
                header("Location: register.php");
                exit;
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "資料庫查詢失敗：" . mysqli_error($link);
            header("Location: register.php");
            exit;
        }

        $verification_code = rand(100000, 999999);

        $sql = "INSERT INTO member (username, user_login, user_pass, email, verification_code) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $username, $user_login, $user_pass, $email, $verification_code);
            if (mysqli_stmt_execute($stmt)) {
                if (send_verification_email($email, $verification_code)) {
                    $_SESSION['success'] = "註冊成功，請檢查您的郵箱以驗證您的電子郵件。";
                    $_SESSION['id'] = mysqli_insert_id($link);
                    mysqli_stmt_close($stmt);
                    header("Location: verify.php");
                    exit;
                } else {
                    $_SESSION['error'] = "註冊成功，但驗證郵件發送失敗。";
                    header("Location: register.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = "註冊失敗：" . mysqli_stmt_error($stmt);
                header("Location: register.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "資料庫查詢失敗：" . mysqli_error($link);
            header("Location: register.php");
            exit;
        }
    }

    if ($action == 'verify') {
        $verification_code = mysqli_real_escape_string($link, $_POST['verification_code']);
        $user_id = $_SESSION['id'];

        $sql = "SELECT verification_code FROM member WHERE id = ? AND verification_code = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $verification_code);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                $sql_update = "UPDATE member SET verified = 1 WHERE id = ?";
                if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                    mysqli_stmt_bind_param($stmt_update, "i", $user_id);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);
                }

                $_SESSION['success'] = "驗證成功，請登入。";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['error'] = "驗證碼錯誤，請重新輸入。";
                header("Location: verify.php");
                exit;
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "資料庫查詢失敗：" . mysqli_error($link);
            header("Location: verify.php");
            exit;
        }
    }

    mysqli_close($link);
}

function send_verification_email($email, $code) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 's536s10309@gmail.com';
        $mail->Password   = 'rkwx watr hwsu kdop';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->CharSet    = 'utf-8';

        $mail->setFrom('s536s10309@gmail.com', 'Fitness Hub');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Fitness Hub - 驗證碼';
        $mail->Body    = '您的驗證碼是: ' . $code;

        $mail->send();
        return true;
    } catch (Exception $e) {
        $_SESSION['error'] = "驗證郵件發送失敗。 Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
?>
