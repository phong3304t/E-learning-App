<?php

include 'components/connect.php';

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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>QuachEdu</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses-header">
   <h1>Khám Phá Kho Khóa Học</h1>
   <p>Nâng cao kỹ năng của bạn với hàng trăm khóa học chất lượng từ các chuyên gia hàng đầu</p>
</section>
<!-- courses header ends -->


<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">Khóa học</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC");
         $select_courses->execute(['Mở']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">Xem khóa học</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Chưa có khóa học nào!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->
<style>
      .inline-btn {
      background-color:rgb(142, 107, 255);
   }
   :root {
         --main-color:#6B7CFF;
         --secondary-color: #6B7CFF;
      }
   .courses-header {
         background: linear-gradient(135deg, var(--main-color), var(--secondary-color));
         padding: 3rem 2rem;
         border-radius: 1rem;
         margin-bottom: 3rem;
         text-align: center;
         color: white;
         position: relative;
         overflow: hidden;
      }

      .courses-header::before {
         content: '';
         position: absolute;
         width: 300px;
         height: 300px;
         background: rgba(255, 255, 255, 0.05);
         border-radius: 50%;
         top: -100px;
         right: -100px;
      }

      .courses-header::after {
         content: '';
         position: absolute;
         width: 200px;
         height: 200px;
         background: rgba(255, 255, 255, 0.05);
         border-radius: 50%;
         bottom: -70px;
         left: -70px;
      }

      .courses-header h1 {
         font-size: 3rem;
         margin-bottom: 1rem;
         text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         background: none;
         -webkit-text-fill-color: white;
      }

      .courses-header p {
         font-size: 1.6rem;
         max-width: 800px;
         margin: 0 auto;
         line-height: 1.6;
      }

</style>









<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>