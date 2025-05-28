

<?php

include '../web-in/components/connect.php';


if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];

    // Truy vấn dữ liệu từ bảng content với playlist_id tương ứng
    $select_content = $conn->prepare("SELECT * FROM `content` WHERE `playlist_id` = ?");
    $select_content->execute([$get_id]);

} else {
    $get_id = '';
    header('location:home.php');
    exit;
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


<h1 class="heading"> Chi tiết môn học </h1>

<section class="course">


      <?php
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? and status = ? LIMIT 1");
         $select_playlist->execute([$get_id, 'Mở']);
         if($select_playlist->rowCount() > 0){
            $fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC);

            $playlist_id = $fetch_playlist['id'];

            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
            $select_tutor->execute([$fetch_playlist['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
            
            $count_likes = $conn->prepare("SELECT COUNT(*) FROM `likes` WHERE content_id IN (SELECT id FROM `content` WHERE playlist_id = ?)");
            $count_likes->execute([$playlist_id]);
            $total_likes = $count_likes->fetchColumn();
        
            $count_comments = $conn->prepare("SELECT COUNT(*) FROM `comments` WHERE content_id IN (SELECT id FROM `content` WHERE playlist_id = ?)");
            $count_comments->execute([$playlist_id]);
            $total_comments = $count_comments->fetchColumn();
      ?>

      <div class="box">
        <img src="../web-in/uploaded_files/<?= $fetch_playlist['thumb']; ?>" class="thumb" alt="Playlist Thumbnail">
        <div class="icons">
            <p> <i class="fas fa-book"></i> <?= $total_videos; ?> </p>
            <p> <i class="fas fa-thumbs-up"></i> <?= $total_likes; ?></p>
            <p> <i class="fas fa-comments"></i> <?= $total_comments; ?></p>
        </div>

      </div>
      <div class="box">
        <h3><?= $fetch_playlist['title']; ?></h3>
        <h4>Giáo viên: <?= $fetch_tutor['name']; ?></h4>
        <hr class="section-footer">
        <p>Nội dung: <?= $fetch_playlist['description']; ?></p>

      </div>


      <?php
         }else{
            echo '<p class="empty">Không tìm thấy khóa học nào!</p>';
         }  
      ?>


</section>

<!-- videos container section starts  -->

<section class="video">

   <h1 class="heading">Video</h1>

   <div class="box-container">

      <?php
         $select_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? ORDER BY date DESC");
         $select_content->execute([$get_id, 'Mở']);
         if($select_content->rowCount() > 0){
            while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){  
      ?>

      <div class="box">
         <img src="../web-in/uploaded_files/<?= $fetch_content['thumb']; ?>" class="thumb" alt="">
         <h3><?= $fetch_content['title']; ?></h3>
         <p><?= $fetch_content['description']; ?></p> <!-- Hiển thị mô tả -->
      </div>
      <?php
            }
         }else{
            echo '<p class="empty"> Video không có sẵn! </p>';
         }
      ?>

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
</style>
<style>
.video .box-container{
    display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr)); /* Đặt độ rộng mỗi box là 27rem */
   gap: 2rem; /* Khoảng cách giữa các box */
   justify-content: center; /* Căn giữa các box */
}

.video .box-container .box{
    border-radius: .5rem;
   background-color: var(--white);
   padding: 2rem;
   border: 2px solid var(--main-color); /* Thêm viền cho box */
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Thêm bóng mờ */
   width: 100%; /* Đảm bảo box chiếm toàn bộ chiều rộng cột */
   text-align: center; /* Căn giữa nội dung trong box */
}
.video .box-container .box h3{
    font-size: 2rem;
    color:rgb(167, 35, 188);
   margin: .5rem 0;
}
.video .box-container .box p {
   font-size: 1.8rem; /* Tăng kích thước chữ của thẻ <p> */
   color: var(--light-color);
   margin: 1rem 0;
   line-height: 1.6; /* Tăng khoảng cách giữa các dòng */
}

.video .box-container .box .thumb{
   width: 100%;
   border-radius: .5rem;
   height: 30rem;
   object-fit: cover;
   margin-bottom: .3rem;
}

.video .box-container .box .title{
   font-size: 2rem;
   color: var(--black);
   margin-top: .5rem;
   padding: .5rem 0;
}
</style>

</div>















<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>