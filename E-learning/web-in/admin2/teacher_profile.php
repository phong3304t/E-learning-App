<?php

include '../components/connect.php';

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

}else if(isset($_POST['toggle_status']) || isset($_POST['delete'])){
   // Nếu là toggle hoặc delete thì cần lấy lại tutor_id từ DB
   $playlist_id = $_POST['playlist_id'];
   $get_tutor_id = $conn->prepare("SELECT tutor_id FROM `playlist` WHERE id = ?");
   $get_tutor_id->execute([$playlist_id]);
   $result = $get_tutor_id->fetch(PDO::FETCH_ASSOC);
   $tutor_id = $result['tutor_id'];

   // Truy xuất lại thông tin tutor như ban đầu
   $select_tutor = $conn->prepare('SELECT * FROM `tutors` WHERE id = ?');
   $select_tutor->execute([$tutor_id]);
   $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

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

// Xử lý thay đổi trạng thái playlist
if(isset($_POST['toggle_status'])){
   $playlist_id = $_POST['playlist_id'];
   $current_status = $_POST['current_status'];
   $new_status = ($current_status == 'Mở') ? 'Đóng' : 'Mở';

   $update_status = $conn->prepare("UPDATE `playlist` SET `status` = ? WHERE `id` = ?");
   $update_status->execute([$new_status, $playlist_id]);

   $message[] = 'Trạng thái khóa học đã được cập nhật!';
}

if(isset($_POST['delete'])){
   $delete_id = $_POST['playlist_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
   $verify_playlist->execute([$delete_id]);

   if($verify_playlist->rowCount() > 0){
      // Xóa các video liên quan trong bảng content
      $delete_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
      $delete_videos->execute([$delete_id]);
      while($fetch_video = $delete_videos->fetch(PDO::FETCH_ASSOC)){
         // Xóa file video và thumbnail
         if(file_exists('../uploaded_files/'.$fetch_video['thumb'])){
            unlink('../uploaded_files/'.$fetch_video['thumb']);
         }
         if(file_exists('../uploaded_files/'.$fetch_video['video'])){
            unlink('../uploaded_files/'.$fetch_video['video']);
         }
      }
      // Xóa các bản ghi video trong database
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE playlist_id = ?");
      $delete_content->execute([$delete_id]);
      
      $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
      $delete_playlist_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
      $delete_bookmark->execute([$delete_id]);
      $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
      $delete_playlist->execute([$delete_id]);
      $message[] = 'Đã xóa khóa học!';
   }else{
      $message[] = 'Khóa học đã bị xóa!';
   }
}

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
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include '../components/admin2_header.php'; ?>

<!-- teachers profile section starts  -->

<section class="tutor-profile">

   <h1 class="heading">Thông tin giáo viên</h1>

   <div class="details">
      <div class="tutor">
         <img src="../uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <h3><?= $fetch_tutor['name']; ?></h3>
         <span><?= $fetch_tutor['profession']; ?></span>
      </div>
      <div class="flex">
         <p>Tổng khóa học : <span><?= $total_playlists; ?></span></p>
         <p>Tổng video : <span><?= $total_contents; ?></span></p>
         <p>Tổng lượt thích : <span><?= $total_likes; ?></span></p>
         <p>Tổng bình luận : <span><?= $total_comments; ?></span></p>
      </div>
   </div>

</section>

<!-- teachers profile section ends -->

<section class="playlists">

   <h1 class="heading">Khóa học</h1>

   <div class="box-container">

      <?php
         // Lấy toàn bộ khóa học trong database
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? ORDER BY date DESC");
         $select_playlist->execute([$fetch_tutor['id']]);
         if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_playlist['status'] == 'Mở'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_playlist['status'] == 'Mở'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlist['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <input type="hidden" name="current_status" value="<?= $fetch_playlist['status']; ?>">
            <button type="submit" name="toggle_status" class="option-btn" status="<?= $fetch_playlist['status']; ?>" onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái khóa học này?');">
               <?= ($fetch_playlist['status'] == 'Mở') ? 'Khóa' : 'Mở'; ?>
            </button>
            <input type="submit" value="Xóa" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">
         </form>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">Xem khóa học</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">Chưa có khóa học nào!</p>';
      }
      ?>

   </div>
<style>
.playlists .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 35rem);
   align-items: flex-start;
   justify-content: center;
   gap: 1.5rem;
}

.playlists .box-container .box{
   background-color: var(--white);
   border-radius: .5rem;
   padding: 2rem;
   overflow-x:hidden;
}

.playlists .box-container .box .thumb{
   height: 20rem;
   position: relative;
   margin: 1.5rem 0;
}

.playlists .box-container .box .flex{
   display: flex;
   align-items: center;
   gap: 1.5rem;
   justify-content: space-between;
}

.playlists .box-container .box .flex i{
   font-size: 1.5rem;
   color: var(--main-color);
   margin-right:.7rem;
}

.playlists .box-container .box .flex span{
   color: var(--light-color);
   font-size: 1.7rem;
}

.playlists .box-container .box .thumb img{
   height: 100%;
   width: 100%;
   object-fit: cover;
   border-radius: .5rem;
}

.playlists .box-container .box .thumb span{
   background-color: rgba(0,0,0,.3);
   color: #fff;
   border-radius: .5rem;
   position: absolute;
   top: 1rem; left: 1rem;
   padding: .5rem 1.5rem;
   font-size: 2rem;
}

.playlists .box-container .box .title{
   font-size: 2rem;
   color: var(--black);
   margin-bottom: 1rem;
   text-overflow: ellipsis;
   white-space: nowrap;
   overflow-x:hidden;
}

.playlists .box-container .box .description{
   line-height: 2;
   font-size: 1.7rem;
   color: var(--light-color);
}

.playlists .box-container .box .description::after{
   content: '...';
}
/* Blue for "Mở" */
.option-btn[status="Mở"] {
    background-color: #007bff; /* Màu xanh dương */
}

/* Gray for "Đóng" */
.option-btn[status="Đóng"] {
    background-color:rgb(36, 184, 63); /* Màu xanh */
}
</style>

</section>

<!-- courses section ends -->










<?php include '../components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="../js/script.js"></script>
   
</body>
</html>