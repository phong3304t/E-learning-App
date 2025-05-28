<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

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

<!-- Hero section đặt ở đầu body -->
<section class="custom-hero">
   <div class="custom-container">
      <div class="custom-hero-content">
         <h2>Khóa Học Online Chuyên Sâu</h2>
         <p class="custom-hero-description">Học mọi lúc, mọi nơi với các chuyên gia hàng đầu. Khóa học được thiết kế để giúp bạn nắm vững kiến thức từ cơ bản đến nâng cao.</p>
         
         <div class="custom-feature-grid">
            <div class="custom-feature-item">
               <div class="custom-feature-icon">
                  <i class="fas fa-graduation-cap"></i>
               </div>
               <div class="custom-feature-label">Lộ trình bài bản</div>
            </div>
            <div class="custom-feature-item">
               <div class="custom-feature-icon">
                  <i class="fas fa-chalkboard-teacher"></i>
               </div>
               <div class="custom-feature-label">Giáo viên chất lượng</div>
            </div>
            <div class="custom-feature-item">
               <div class="custom-feature-icon">
                  <i class="fas fa-book-reader"></i>
               </div>
               <div class="custom-feature-label">Tài liệu trọn đời</div>
            </div>
            <div class="custom-feature-item">
               <div class="custom-feature-icon">
                  <i class="fas fa-headset"></i>
               </div>
               <div class="custom-feature-label">Hỗ trợ 24/7</div>
            </div>
         </div>
         
         <div class="custom-hero-buttons">
            <a href="about.php" class="custom-hero-btn">Khám Phá Về Chúng Tôi</a>
            <a href="courses.php" class="custom-hero-btn">Khám Phá Khóa Học</a>
         </div>
      </div>
   </div>
</section>

<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">Lựa chọn nhanh</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Lượt thích</h3>
         <p>Tổng lượt thích : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">Xem video thích</a>
      </div>
      <?php
      }
      ?>

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Bình luận</h3>
         <p>Tổng bình luận : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">Xem bình luận</a>
      </div>
      <?php
      }
      ?>

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Khóa học đã lưu</h3>
         <p>Khóa học đã lưu : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php" class="inline-btn">Xem khóa học</a>
      </div>
      <?php
      }
      ?>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">Khóa học mới nhất</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
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
         echo '<p class="empty">Chưa có khóa học!</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">Xem thêm</a>
   </div>

</section>

<!-- courses section ends -->

<style>

   .inline-btn {
      background-color:rgb(142, 107, 255);
   }
   .custom-hero {
   width: 100%;
   background: linear-gradient(135deg, #6B7CFF, #9C4DFF);
   display: flex;
   justify-content: center;
   padding: 3rem 0;
   text-align: center;
   color: white;
   position: relative;
   overflow: hidden;
}

.custom-container {
   width: 100%;
   max-width: 1200px;
   padding: 0 2rem;
   margin: 0 auto;
}

.custom-hero-content {
   width: 100%;
   max-width: 100%;
}

.custom-hero-content h2 {
   font-size: 3rem;
   font-weight: 800;
   margin-bottom: 1.5rem;
   color: white;
}

.custom-hero-description {
   font-size: 1.6rem;
   color: rgba(255, 255, 255, 0.9);
   max-width: 800px;
   margin: 0 auto 3rem auto;
   line-height: 1.6;
}

/* FEATURES GRID */
.custom-feature-grid {
   display: grid;
   grid-template-columns: repeat(4, 1fr);
   gap: 1.5rem;
   margin-bottom: 3rem;
   width: 100%;
}

.custom-feature-item {
   background-color: white;
   border-radius: 12px;
   padding: 1.5rem 1rem;
   display: flex;
   flex-direction: column;
   align-items: center;
   justify-content: center;
   box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
   transition: all 0.3s ease;
}

.custom-feature-icon {
   margin-bottom: 1rem;
}

.custom-feature-icon i {
   font-size: 2.5rem;
   color: #6B7CFF;
   transition: all 0.3s ease;
}

.custom-feature-label {
   font-size: 1.4rem;
   color: white;
   background-color: #8A64FF;
   padding: 0.5rem 1rem;
   border-radius: 5px;
   width: 100%;
   font-weight: 500;
}

.custom-feature-item:hover {
   transform: translateY(-5px);
   box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.custom-feature-item:hover .custom-feature-icon i {
   color: #9C4DFF;
   transform: scale(1.1);
}

/* BUTTONS */
.custom-hero-buttons {
   display: flex;
   justify-content: center;
   gap: 2rem;
}

.custom-hero-btn {
   padding: 1.2rem 2.5rem;
   font-size: 1.2rem;
   border-radius: 50px;
   font-weight: 600;
   background-color: white;
   color: #6B7CFF;
   border: none;
   text-transform: uppercase;
   letter-spacing: 1px;
   box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
   transition: all 0.3s ease;
   text-decoration: none;
   display: inline-block;
   position: relative;
   overflow: hidden;
}

.custom-hero-btn:hover {
   transform: translateY(-3px);
   box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
   background: rgba(255, 255, 255, 0.9);
}
</style>










<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>