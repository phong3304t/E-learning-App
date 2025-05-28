<?php

include '../web-in/components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        if ($row['state'] === 'unpaid') {
            $error_message = 'Tài khoản chưa được cấp phép!';
        } else if ($row['state'] === 'paid') {
            setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
            header('location:../web-in/home.php');
            exit;
        }
    } else {

            $error_message = 'Sai tài khoản hoặc mật khẩu!';

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


<h1 class="heading"> Đăng nhập</h1>   

<section class="login-register">

    <div class="image">
        <img src="images/login.png" alt="Login / Register Image">
    </div>

    <div class="form-container">
        <!-- Login Form -->
        <form action="" method="POST" id="login-form" autocomplete="off">
            <h1>Đăng nhập </h1>
            <input type="email" name="email" placeholder="Nhập email của bạn"
            autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
            <input type="password" name="pass" placeholder="Nhập mật khẩu của bạn"    
            autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly'); this.type='password';" required>  
              
            <!-- Hiển thị thông báo lỗi -->  
            <input type="submit" name="submit" class="btn" value="Đăng nhập">
           <p>Quên mật khẩu? <a href="forgot_pass.php">Khôi phục mật khẩu</a></p>
            <p>Chưa có tài khoản <a href="register.php">Đăng ký tại đây</a></p>
            <p>Giáo viên đăng nhập: <a href="admin_login.php">Giáo viên đăng nhập</a></p> 

            
            <?php if (!empty($error_message)) { ?>
                <p class="error-message"><?= $error_message; ?></p>
            <?php } ?>  
        </form>
    </div>

 

</section>

    <!-- Nút Thanh toán -->
    <div class="payment-button">
        <p> Nếu chưa thanh toán, thanh toán tại đây để có thể đăng nhập: </p>
        <a href="price.html" class="btn">Thanh toán</a>
    </div>
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
<style>
.payment-button {

    text-align: left;
    margin-top: 2rem;
    margin-left: 10rem;
}

.payment-button .btn {
    padding: 1rem 2rem;
    font-size: 1.4rem;
    color: #fff;
    background-color:rgb(51, 86, 209);
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;

}

.payment-button .btn:hover {
    background-color:rgb(55, 89, 225); 
}

.payment-button p {
    color: #000;
    font-size: 1.7rem;
    padding:.5rem 0;
    width: 40%;
}
</style>
</div>















<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>