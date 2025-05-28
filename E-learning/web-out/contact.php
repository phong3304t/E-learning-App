<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'autoload.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = nl2br(htmlspecialchars($_POST['message']));

    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'quachedu@gmail.com'; // thay bằng email trung tâm
        $mail->Password   = 'eygy mmnb tbsv oxir';               // dùng App Password nếu Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Gửi từ
        $mail->setFrom('quachedu@gmail.com', 'QuachEdu Contact');

        // Gửi đến trung tâm
        $mail->addAddress('quachedu@gmail.com'); // hoặc admin@trungtam.com

        // Nội dung email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "$subject";
        $mail->Body    = "
            <b>Họ tên:</b> $name<br>
            <b>Email:</b> $email<br>
            <b>Nội dung:</b><br>$message
        ";

        $mail->send();
        $success_message = "Phản hồi của bạn đã được gửi. Cảm ơn bạn!";
    } catch (Exception $e) {
        $error_message = "Gửi email thất bại. Lỗi: {$mail->ErrorInfo}";
    }
} else {
    //$error_message = "Truy cập không hợp lệ.";
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

<h1 class="heading">Liên hệ với chúng tôi</h1>

<!-- contact section  -->

<section class="contact">

    <div class="image">
        <img src="images/contact-img.svg" alt="">
    </div>

    <form action="" method="POST">

        <div class="inputBox">
            <input type="text" name="name" placeholder="Tên" required>
            <input type="email" name="email" placeholder="email" required>
        </div>

        <input type="text" name="subject" placeholder="Tiêu đề" class="box" required>

        <textarea name="message" placeholder="Nội dung" cols="30" rows="10" required></textarea>

        <input type="submit" class="btn" value="Gửi">

        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?= $error_message; ?></p>
        <?php } ?> 
        <?php if (!empty($success_message)) { ?>
            <p class="success-message"><?= $success_message; ?></p>
        <?php } ?> 

    </form>

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

    <div class="credit"> <span>Nhóm 2</span> - Nhập môn công nghệ phần mềm </span></div>

</section>
<style>
    .section-footer{
        width: 100%;
        margin: 2rem auto;
        border: .1rem solid rgba(0,0,0,.1);
    }
    .error-message {
        color: red; /* Màu chữ đỏ để nổi bật */
        font-size: 1.7rem; /* Kích thước chữ vừa phải */
        margin: 0.5rem 0; /* Khoảng cách trên và dưới */
        text-align: left; /* Căn giữa thông báo */
    }
    .success-message {
        color: green; /* Màu chữ đỏ để nổi bật */
        font-size: 1.7rem; /* Kích thước chữ vừa phải */
        margin: 0.5rem 0; /* Khoảng cách trên và dưới */
        text-align: left; /* Căn giữa thông báo */
    }
</style>
</div>















<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>