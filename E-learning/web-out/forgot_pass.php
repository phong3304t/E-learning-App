<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'autoload.php'; // Đường dẫn đến autoload của Composer hoặc PHPMailer

include '../web-in/components/connect.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
    $select_user->execute([$email]);

    if ($select_user->rowCount() > 0) {
        // Tạo mật khẩu mới ngẫu nhiên
        $new_password = bin2hex(random_bytes(4)); // Mật khẩu ngẫu nhiên 8 ký tự
        $hashed_password = sha1($new_password); // Mã hóa mật khẩu mới

        // Cập nhật mật khẩu mới vào cơ sở dữ liệu
        $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE email = ?");
        $update_password->execute([$hashed_password, $email]);

        // Gửi email chứa mật khẩu mới
        $mail = new PHPMailer(true);

        try {
            // Cấu hình SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server (ví dụ: Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'quachedu@gmail.com'; // Địa chỉ email của bạn
            $mail->Password = 'eygy mmnb tbsv oxir'; // Mật khẩu email của bạn
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Cấu hình email
            $mail->setFrom('quachedu@gmail.com', 'QuachEdu'); // Email và tên người gửi
            $mail->addAddress($email); // Email người nhận
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Cấp lại mật khẩu - QuachEdu';
            $mail->Body = "
                <h1>Cấp lại mật khẩu</h1>
                <p>Chào bạn,</p>
                <p>Mật khẩu mới của bạn là: <strong>$new_password</strong></p>
                <p>Vui lòng đăng nhập và đổi mật khẩu ngay lập tức để bảo mật tài khoản của bạn.</p>
                <p>Trân trọng,<br>QuachEdu</p>
            ";

            $mail->send();
            $success_message = 'Mật khẩu mới đã được gửi đến email của bạn!';
        } catch (Exception $e) {
            $error_message = 'Không thể gửi email. Vui lòng thử lại sau.';
        }
    } else {
        $error_message = 'Email không tồn tại trong hệ thống!';
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


<h1 class="heading"> Cấp lại mật khẩu</h1>   

<section class="login-register">

    <div class="image">
        <img src="images/login.png" alt="Login / Register Image">
    </div>

    <div class="form-container">
    <!-- Form cấp lại mật khẩu -->
    <form action="" method="POST" id="reset-form" autocomplete="off">
        <h1>Cấp lại mật khẩu</h1>
        <input type="email" name="email" placeholder="Nhập email của bạn" autocomplete="off" required>
        <input type="submit" name="submit" class="btn" value="Cấp lại mật khẩu">
        
        <!-- Hiển thị thông báo thành công -->
        <?php if (!empty($success_message)) { ?>
            <p class="success-message"><?= $success_message; ?></p>
        <?php } ?>

        <!-- Hiển thị thông báo lỗi -->
        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?= $error_message; ?></p>
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
    .error-message p{
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