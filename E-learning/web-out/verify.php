<?php
include '../web-in/components/connect.php';

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Kiểm tra mã xác nhận trong bảng email_verify
    $select_verify = $conn->prepare("SELECT * FROM `email_verify` WHERE `verification_code` = ?");
    $select_verify->execute([$verification_code]);

    if ($select_verify->rowCount() > 0) {
        // Lấy email, name và password người dùng từ bảng email_verify
        $row = $select_verify->fetch();
        $email = $row['email'];
        $name = $row['name'];
        $password = $row['password'];  // Lấy mật khẩu đã mã hóa

        // Kiểm tra xem người dùng đã có trong bảng users chưa
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $select_user->execute([$email]);

        if ($select_user->rowCount() == 0) {
            // Mã xác nhận hợp lệ, tạo tài khoản mới trong bảng users
            $id = unique_id();  // Giả sử bạn có hàm unique_id để tạo id người dùng
            $default_picture = 'images/teacher-4.png';
            $insert_user = $conn->prepare("INSERT INTO `users` (`id`, `name`, `email`, `password`, `state`) 
                                          VALUES (?, ?, ?, ?, ?)");
            $insert_user->execute([$id, $name, $email, $password, 'unpaid']);

            // Xóa mã xác nhận đã sử dụng
            $delete_verify = $conn->prepare("DELETE FROM `email_verify` WHERE `verification_code` = ?");
            $delete_verify->execute([$verification_code]);

            // Sau khi xác nhận thành công, chuyển hướng đến price.html
            header("Location: price.html");
            exit();  // Đảm bảo mã phía sau không được thực thi sau khi chuyển hướng
        } else {
            $error_message = "Tài khoản đã được xác nhận trước đó!";
        }
    } else {
        $error_message = "Mã xác nhận không hợp lệ hoặc đã hết hạn.";
    }
} else {
    $error_message = "Không có mã xác nhận!";
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

<!-- home section  -->

<section class="home">

    <div class="content">
        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?= $error_message; ?></p>
        <?php } ?> 
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
    .section-divider {
        width: 80%;
        margin: 4rem auto;
        border: 1px solid #ddd;
    }
</style>

<style>
    .section-footer{
        width: 100%;
        margin: 2rem auto;
        border: .1rem solid rgba(0,0,0,.1);
    }

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