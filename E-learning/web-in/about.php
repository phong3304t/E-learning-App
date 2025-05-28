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

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>Tại sao chọn QuachEdu?</h3>
         <p>Chúng tôi cung cấp các khóa học chất lượng cao được thiết kế bởi những chuyên gia hàng đầu trong lĩnh vực. Với phương pháp giảng dạy hiện đại và nội dung cập nhật, QuachEdu cam kết đem đến trải nghiệm học tập tốt nhất cho học viên mọi lứa tuổi.</p>
         <p>Hơn 25,000 học viên đã thành công sau khi tham gia các khóa học trên nền tảng của chúng tôi. Bạn có thể học bất kỳ lúc nào, bất kỳ nơi đâu với đa dạng các khóa học từ cơ bản đến nâng cao.</p>
         <a href="courses.php" class="inline-btn">Khám phá khóa học</a>
      </div>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-graduation-cap"></i>
         <div>
            <h3>+5</h3>
            <span>Khóa học online</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            <h3>+2k</h3>
            <span>Học sinh xuất sắc</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-chalkboard-user"></i>
         <div>
            <h3>4</h3>
            <span>Giáo viên chuyên gia</span>
         </div>
      </div>

 

   </div>

</section>

<!-- about section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="heading">Đánh giá từ học sinh</h1>

   <div class="box-container">

      <div class="box">
         <p>"Tôi đã học được rất nhiều từ các khóa học tại QuachEdu. Giao diện thân thiện và nội dung chất lượng đã giúp tôi dễ dàng tiếp thu kiến thức mới. Giáo viên nhiệt tình và luôn sẵn sàng giải đáp thắc mắc."</p>
         <div class="user">
            <img src="images/pic-2.jpg" alt="">
            <div>
               <h3>Nguyễn Văn An</h3>
            </div>
         </div>
      </div>

      <div class="box">
         <p>"Các khóa học với chất lượng tuyệt vời, đặc biệt là khóa học Toán của thầy Quách, rất dễ vào giấc ngủ."</p>
         <div class="user">
            <img src="images/pic-3.jpg" alt="">
            <div>
               <h3>Trần Thị Bình</h3>
            </div>
         </div>
      </div>

      <div class="box">
         <p>"Tôi thích cách họ cập nhật các khóa học thường xuyên với công nghệ mới nhất. Điều này giúp tôi luôn bắt kịp với các xu hướng công nghệ. Giao diện học tập dễ sử dụng và tôi có thể học mọi lúc, mọi nơi."</p>
         <div class="user">
            <img src="images/pic-4.jpg" alt="">
            <div>
               <h3>Lê Văn Công</h3>
            </div>
         </div>
      </div>

      <div class="box">
         <p>"Không có từ nào để chê chất lượng của trung tâm này. Thật tuyệt với, thật ko thể tin nổi !!!"</p>
         <div class="user">
            <img src="images/pic-5.jpg" alt="">
            <div>
               <h3>Phạm Minh Dũng</h3>
            </div>
         </div>
      </div>

      <div class="box">
         <p>"Tôi đã thử nhiều nền tảng học trực tuyến khác nhau nhưng QuachEdu là tốt nhất. Chất lượng video rất cao và nội dung được cấu trúc một cách logic, từ cơ bản đến nâng cao. Đội ngũ hỗ trợ luôn phản hồi nhanh chóng."</p>
         <div class="user">
            <img src="images/pic-6.jpg" alt="">
            <div>
               <h3>Ngô Thị Hoa</h3>
            </div>
         </div>
      </div>

      <div class="box">
         <p>"Tôi thích tính năng thảo luận trong mỗi bài học, nơi chúng tôi có thể trao đổi ý kiến và giải quyết vấn đề cùng nhau. Giáo viên rất chuyên nghiệp và có kinh nghiệm thực tế trong lĩnh vực công nghệ."</p>
         <div class="user">
            <img src="images/pic-7.jpg" alt="">
            <div>
               <h3>Hoàng Văn Giang</h3>
            </div>
         </div>
      </div>

   </div>

</section>

<!-- reviews section ends -->




<style>
      .inline-btn {
      background-color:rgb(142, 107, 255);
   }
</style>





<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>