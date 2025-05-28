<?php
include '../web-in/components/connect.php';

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE `status` = 'Mở' ORDER BY date DESC");
$select_playlists->execute();
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

<h1 class="heading"> Khóa học </h1>

<!-- course section  -->

<section class="course">
    <?php
    if ($select_playlists->rowCount() > 0) {
        while ($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) {
            $course_id = $fetch_playlist['id'];

            // Truy vấn thông tin giáo viên
            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_tutor->execute([$fetch_playlist['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="box">
        <span class="amount">QuachEdu.</span>
        <img src="../web-in/uploaded_files/<?= $fetch_playlist['thumb']; ?>" class="thumb" alt="Playlist Thumbnail">
        <h3><?= $fetch_playlist['title']; ?></h3>
        <p class="tutor-name">Giáo viên: <?= $fetch_tutor['name']; ?></p>
        <p><?= $fetch_playlist['description']; ?></p>
        <hr class="section-footer">
        <a href="playlist.php?get_id=<?= $course_id; ?>" class="btn">Xem thêm</a>
    </div>
    <?php
        }
    } else {
        echo '<p class="empty">Không tìm thấy khóa học nào!</p>';
    }
    ?>
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
        margin: 1re, auto;
        margin-top: 0.5rem ;
        border: .1rem solid rgba(0,0,0,.1);
    }
</style>
</div>














<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>