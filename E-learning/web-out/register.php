<?php
include '../web-in/components/connect.php';

// Bao gồm autoloader của Composer để tự động tải các thư viện
require 'autoload.php';

// Sử dụng các lớp của PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}

// Khi người dùng đăng ký và gửi form
if (isset($_POST['submit'])) {
    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);  // Mã hóa mật khẩu
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);  // Mã hóa mật khẩu xác nhận
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);

    if ($select_user->rowCount() > 0) {
        $error_message = 'Email đã tồn tại!';
    } else {
        if ($pass != $cpass) {
            $error_message = 'Mật khẩu xác nhận không chính xác!';
        } else {
            $verification_code = md5(uniqid(rand(), true)); // Tạo mã xác nhận duy nhất

            // Lưu thông tin vào bảng email_verify trước khi gửi email
            $insert_verify = $conn->prepare("INSERT INTO `email_verify` (`email`, `verification_code`, `name`, `password`) 
                                             VALUES (?, ?, ?, ?)");
            $insert_verify->execute([$email, $verification_code, $name, $pass]);

            // Gửi email xác nhận
            $mail = new PHPMailer(true);
            try {
                // Cấu hình máy chủ SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'quachedu@gmail.com'; // Địa chỉ email gửi
                $mail->Password = 'eygy mmnb tbsv oxir'; // Mật khẩu tài khoản email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Người gửi và người nhận
                $mail->setFrom('quachedu@gmail.com', 'QuachEdu');
                $mail->addAddress($email, $name);

                // Nội dung email
                $mail->isHTML(true);
                $mail->Subject = 'Verify your account';
                $mail->Body    = 'Chào ' . $name . ', hãy nhấp vào liên kết sau để xác nhận tài khoản của bạn: <a href="http://localhost/E-learning/web-out/verify.php?code=' . $verification_code . '">Xác nhận tài khoản</a>';

                // Gửi email
                $mail->send();
                $success_message = 'Email xác nhận đã được gửi.';
            } catch (Exception $e) {
                $error_message = "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuachEdu</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style-out.css">

</head>
<body>

<div class="container">

    <header>
    
        <a href="#" class="logo">Quach<span>Edu.</span></a>
    
        <div id="menu" class="fas fa-bars"></div>
    
        <nav class="navbar">
            <a href="home.html">Trang chủ</a>
            <a href="course.php">Khóa học</a>
            <a href="teacher.php">Giáo viên</a>
            <a href="review.html">Đánh giá</a>
            <a href="contact.php">Liên hệ</a>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        </nav>
    
    </header>


<h1 class="heading"> Đăng Ký</h1>   

<section class="login-register">

    <div class="image">
        <img src="images/register.png" alt="Login / Register Image">
    </div>

    <div class="form-container">
        <!-- register Form -->
        <form action="" method="POST" id="register-form" autocomplete="off">
            <h2>Đăng ký</h2>
            <input type="text" name="name" placeholder="Nhập tên của bạn" required>
            <input type="email" name="email" placeholder="Nhập email của bạn" required>
            <input type="password" name="pass" placeholder="Nhập mật khẩu của bạn" 
            autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly'); this.type='password';" required>
            <input type="password" name="cpass" placeholder="Xác nhận lại mật khẩu" required>
            <input type="submit" name="submit" class="btn" value="Đăng ký"> 
            <p>Đã có tài khoản <a href="login.php">Đăng nhập tại đây</a></p>
            <?php if (!empty($error_message)) { ?>
                <p class="error-message"><?= $error_message; ?></p>
            <?php } ?> 
            <?php if (!empty($success_message)) { ?>
                <p class="success-message"><?= $success_message; ?></p>
            <?php } ?>
        </form>
    </div>
</section>

<!-- footer section  -->

<section class="footer">

    <hr class="section-footer">

    <div class="box-container">

        <div class="box">
            <h3>Về chúng tôi</h3>
            <p>Trung tâm giáo dục QuachEdu. - đào tạo, ôn thi đại học hàng đầu Việt Nam.</p>
        </div>

        <div class="box">
            <h3>Liên kết</h3>
            <a href="home.html">Trang chủ</a>
            <a href="course.php">Khóa học</a>
            <a href="teacher.php">Giáo viên</a>
            <a href="review.html">Đánh giá</a>
            <a href="contact.php">Liên hệ</a>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        </div>

        <div class="box">
            <h3>Theo dõi</h3>
            <a href="https://www.facebook.com/profile.php?id=61575079702285">facebook</a>
            <a href="#">twitter</a>
            <a href="#">instagram</a>
            <a href="#">linkedin</a>
        </div>

        <div class="box">
            <h3>Liên hệ</h3>
           <p> <i class="sđt"></i> 098-686-4461 </p>
           <p> <i class="email"></i> quachEdu@gmail.com </p>
           <p> <i class="địa chỉ"></i>144 Xuan Thuy, Hanoi, Vietnam </p>
        </div>

    </div>

    <div class="credit"> <span>Nhóm 2</span> - Nhập môn công nghệ phần mềm</div>

</section>
<style>
    .section-footer{
        width: 100%;
        margin: 2rem auto;
        border: .1rem solid rgba(0,0,0,.1);
    }
</style>

<style>
    .error-message {
        color: red; /* Màu chữ đỏ để nổi bật */
        font-size: 1rem; /* Kích thước chữ vừa phải */
        margin: 0.5rem 0; /* Khoảng cách trên và dưới */
        text-align: left; /* Căn giữa thông báo */
    }
</style>
</div>















<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>