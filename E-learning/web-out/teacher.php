<?php

include '../web-in/components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
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


<h1 class="heading"> Giáo viên </h1>

<!-- teachers section starts  -->

<section class="teachers">

   <div class="box-container">

      <?php
         $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
         $select_tutors->execute();
         if($select_tutors->rowCount() > 0){
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){

               $tutor_id = $fetch_tutor['id'];

               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND status = 'Mở'");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND status = 'Mở'");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
      ?>
      <div class="box">
         <div class="tutor">
            <img src="../web-in/uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3>Thầy <?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
         </div>
         <p>Khóa học : <span><?= $total_playlists; ?></span></p>
         <p>Video : <span><?= $total_contents ?></span></p>
         <p>Lượt thích : <span><?= $total_likes ?></span></p>
         <hr class="section-footer">
         <form action="teacher_dt.php" method="post">
            <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
            <input type="submit" value="Xem chi tiết" name="tutor_fetch" class="inline-btn">
         </form>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">Không tìm thấy giáo viên nào!</p>';
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
.teachers .box-container{
  display: grid;
  grid-template-columns: repeat(auto-fit, 45rem);
  gap: 1.5rem;
  align-items: flex-start;
  justify-content: center;
}

.teachers .box-container .box{
  border-radius: .5rem;
  padding: 2rem;
  background-color: var(--white);
  border: 2px solid rgba(0, 0, 0, 0.1); /* Thêm viền đậm hơn */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Thêm bóng mờ để nổi bật */
}


.teachers .box-container .box .tutor{
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.teachers .box-container .box .tutor img{
  height: 12rem;
  width: 12rem;
  object-fit: cover;
  border-radius: 50%;
}

.teachers .box-container .box .tutor h3{
  color:rgb(167, 35, 188);
  font-size: 2rem;
  margin-bottom: .2rem;
}

.teachers .box-container .box .tutor span{
  color: var(--main-color);
  font-size: 1.5rem;
}

.teachers .box-container .box p{
  padding-top: 1rem;
  font-size: 1.7rem;
  color: var(--light-color);
}

.teachers .box-container .box p span{
  color: var(--main-color);
}

.teachers .box-container .offer{
  text-align: center;
}

.teachers .box-container .offer h3{
  font-size: 2rem;
  color: var(--black);
}

.teachers .box-container .offer p{
  line-height: 2;
  padding-bottom: .5rem;
}

.teachers .box-container .box .inline-btn {
  display: inline-block;
  padding: 1rem 2rem; /* Tăng kích thước nút */
  font-size: 1.6rem; /* Tăng kích thước chữ */
  color: #fff; /* Màu chữ trắng */
  background-color:rgb(143, 15, 212); /* Màu nền tím */
  border: none;
  border-radius: 5px; /* Bo góc nút */
  cursor: pointer;
  text-align: center;
  transition: background-color 0.3s ease; /* Hiệu ứng hover */
}

.teachers .box-container .box .inline-btn:hover {
  background-color: #8a2be2; /* Màu tím sáng hơn khi hover */
}
</style>
</div>



<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>