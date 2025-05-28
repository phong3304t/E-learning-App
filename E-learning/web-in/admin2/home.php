<?php

include '../components/connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>QuachEdu.Admin</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin2_header.php'; ?>
   

<section class="welcome">
   <h1>Chào mừng quản trị viên</h1>
</section>



<section class="home">
   <a href="user_manage.php" class="box">
      <i class="fas fa-user-graduate"></i>
      <h3>Học sinh</h3>
</a>
   <a href="teacher.php" class="box">
      <i class="fas fa-chalkboard-teacher"></i>
      <h3>Giáo viên</h3>
</a>
   <a href="playlists.php" class="box">
      <i class="fas fa-book"></i>
      <h3>Các khóa học</h3>
</a>
</section>

<section class="intro">
   <p>
      Chào mừng bạn đến với trang quản trị của hệ thống QuachEdu. Tại đây, bạn có thể dễ dàng quản lý các khóa học, video, và danh sách học sinh. 
      Với giao diện thân thiện và các tính năng mạnh mẽ, bạn có thể:
   </p>
   <ul>
      <li>Quản lý danh sách học sinh, xác nhận quyền truy cập khóa học của từng học sinh.</li>
      <li>Quản lý khóa học, xóa hoặc chỉnh sửa quyền truy cập khóa học đó.</li>
      <li>Quản lý video bài giảng, quản lý quyền truy cập cũng như bình luận của học sinh với mỗi video.</li>
   </ul>
   <p>
      Hãy sử dụng các tính năng này để đảm bảo hệ thống hoạt động hiệu quả và hỗ trợ tốt nhất cho học viên và giáo viên.
   </p>
</section>

<style>
      /* Phần chào mừng */
      .welcome {
         background: linear-gradient(135deg, #6a11cb, #6B7CFF); /* Màu tím xanh */
         color: #fff;
         text-align: center;
         padding: 30px;
         border-radius: 10px;
         margin: 20px auto;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         position: relative;
         overflow: hidden;
      }

      .welcome::before, .welcome::after {
         content: '';
         position: absolute;
         width: 200px;
         height: 200px;
         background: rgba(255, 255, 255, 0.2);
         border-radius: 50%;
         z-index: 1;
      }

      .welcome::before {
         top: -50px;
         left: -50px;
      }

      .welcome::after {
         bottom: -50px;
         right: -50px;
      }

      .welcome h1 {
         font-size: 2.5rem;
         z-index: 2;
         position: relative;
      }
      /* Phần giới thiệu */
   .intro {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      font-size: 2rem;
      line-height: 2;
      color: #333;
   }

   .intro ul {
      margin: 10px 0;
      padding-left: 20px;
   }

   .intro ul li {
      margin-bottom: 10px;
   }
      /* Các ô tùy chọn */
      .home {
         display: grid;
         grid-template-columns: repeat(3, 1fr);
         gap: 20px;
         margin: 20px auto;
         max-width: 1200px;
      }

      .home .box {
         background: #fff;
         border-radius: 10px;
         padding: 10px;
         text-align: center;
         box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .home .box:hover {
         transform: translateY(-10px);
         box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
      }

      .home .box i {
         font-size: 3rem;
         color: #6a11cb;
         margin-bottom: 10px;
      }

      .home .box h3 {
         font-size: 1.5rem;
         margin-bottom: 10px;
         color: #333;
      }

      .home .box p {
         color: #666;
      }
   </style>


<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>