<?php

include '../web-in/components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['tutor_fetch'])){

   $tutor_email = $_POST['tutor_email'];
   $tutor_email = filter_var($tutor_email, FILTER_SANITIZE_STRING);
   $select_tutor = $conn->prepare('SELECT * FROM `tutors` WHERE email = ?');
   $select_tutor->execute([$tutor_email]);

   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
   $tutor_id = $fetch_tutor['id'];

   $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
   $count_playlists->execute([$tutor_id]);
   $total_playlists = $count_playlists->rowCount();

   $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
   $count_contents->execute([$tutor_id]);
   $total_contents = $count_contents->rowCount();

   $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
   $count_likes->execute([$tutor_id]);
   $total_likes = $count_likes->rowCount();

   $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
   $count_comments->execute([$tutor_id]);
   $total_comments = $count_comments->rowCount();

}else{
   header('location:teacher.php');
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


<h1 class="heading"> THông tin giảng viên </h1>

<!-- teachers profile section starts  -->

<section class="tutor-profile">

   <div class="details">
      <div class="tutor">
         <img src="../web-in/uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <h3><?= $fetch_tutor['name']; ?></h3>
         <span><?= $fetch_tutor['profession']; ?></span>
      </div>
      <div class="flex">
         <p>Các khóa học : <span><?= $total_playlists; ?></span></p>
         <p>Video : <span><?= $total_contents; ?></span></p>
         <p>Lượt thích : <span><?= $total_likes; ?></span></p>
         <p>Bình luận : <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- teachers profile section ends -->

<section class="courses">

   <h1 class="heading">Các khóa học</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND status = ?");
         $select_courses->execute([$tutor_id, 'Mở']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <img src="../web-in/uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3><?= $fetch_course['title']; ?></h3>
         <p><?= $fetch_course['description']; ?></p> <!-- Hiển thị mô tả -->
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">Xem thêm</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Không tìm thấy khóa học</p>';
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
    .tutor-profile .details{
   background-color: var(--white);
   border-radius: .5rem;
   padding: 2rem;
   text-align: center;
}

.tutor-profile .details .tutor{
   margin-bottom: 2rem;
}

.tutor-profile .details .tutor img{
   height: 20rem;
   width: 20rem;
   border-radius: 50%;
   object-fit: cover;
   margin-bottom: .5rem;
}

.tutor-profile .details .tutor h3{
   font-size: 2rem;
   color:rgb(167, 35, 188);
   margin: .5rem 0;
}

.tutor-profile .details .tutor span{
   font-size: 1.5rem;
   color: var(--light-color);
}

.tutor-profile .details .flex{
   display: flex;
   gap: 1.5rem;
   align-items: center;
   flex-wrap: wrap;
}

.tutor-profile .details .flex p{
   flex: 1 1 25rem;
   border-radius: .5rem;
   background-color: var(--light-bg);
   padding: 1rem 3rem;
   font-size: 2rem;
   color: var(--light-color);
}

.tutor-profile .details .flex p span{
   color: var(--main-color);
}






.courses .box-container{
    display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr)); /* Đặt độ rộng mỗi box là 27rem */
   gap: 2rem; /* Khoảng cách giữa các box */
   justify-content: center; /* Căn giữa các box */
}

.courses .box-container .box{
    border-radius: .5rem;
   background-color: var(--white);
   padding: 2rem;
   border: 2px solid var(--main-color); /* Thêm viền cho box */
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Thêm bóng mờ */
   width: 100%; /* Đảm bảo box chiếm toàn bộ chiều rộng cột */
   text-align: center; /* Căn giữa nội dung trong box */
}
.courses .box-container .box h3{
    font-size: 2rem;
    color:rgb(167, 35, 188);
   margin: .5rem 0;
}
.courses .box-container .box p {
   font-size: 1.8rem; /* Tăng kích thước chữ của thẻ <p> */
   color: var(--light-color);
   margin: 1rem 0;
   line-height: 1.6; /* Tăng khoảng cách giữa các dòng */
}

.courses .box-container .box .thumb{
   width: 100%;
   border-radius: .5rem;
   height: 20rem;
   object-fit: cover;
   margin-bottom: .3rem;
}

.courses .box-container .box .title{
   font-size: 2rem;
   color: var(--black);
   margin-top: .5rem;
   padding: .5rem 0;
}

.courses .more-btn{
   margin-top: 2rem;
   text-align: center;
}
.courses .box-container .box .inline-btn {
   display: inline-block;
   padding: 1rem 2rem;
   font-size: 1.4rem;
   color: #fff;
   background-color: #6a0dad; /* Màu tím */
   border: none;
   border-radius: 5px;
   text-decoration: none;
   cursor: pointer;
   transition: background-color 0.3s ease;
}

.courses .box-container .box .inline-btn:hover {
   background-color: #8a2be2; /* Màu tím sáng hơn khi hover */
}
 </style>

</div>



<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>