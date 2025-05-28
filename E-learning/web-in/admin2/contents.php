<?php

include '../components/connect.php';

if(isset($_POST['toggle_status'])){
   $video_id = $_POST['video_id'];
   $current_status = $_POST['current_status'];
   $new_status = ($current_status == 'Mở') ? 'Đóng' : 'Mở';

   $update_status = $conn->prepare("UPDATE `content` SET `status` = ? WHERE `id` = ?");
   $update_status->execute([$new_status, $video_id]);

   $message[] = 'Trạng thái video đã được cập nhật!';
}

if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);
   if($verify_video->rowCount() > 0){
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video->execute([$delete_id]);
      $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_video['video']);
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_content->execute([$delete_id]);
      $message[] = 'Đã xóa video!';
   }else{
      $message[] = 'Video đã được xóa!';
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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin2_header.php'; ?>
   
<section class="contents">

   <h1 class="heading">Video</h1>

   <div class="box-container">

   <?php
      // Lấy toàn bộ video trong database
      $select_videos = $conn->prepare("SELECT * FROM `content` ORDER BY date DESC");
      $select_videos->execute();
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div>
               <i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'Mở'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i>
               <span style="<?php if($fecth_videos['status'] == 'Mở'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span>
            </div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <input type="hidden" name="current_status" value="<?= $fecth_videos['status']; ?>">
            <button type="submit" name="toggle_status" class="option-btn" status="<?= $fecth_videos['status']; ?>" onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái video này?');">
               <?= ($fecth_videos['status'] == 'Mở') ? 'Khóa' : 'Mở'; ?>
            </button>
            <input type="submit" value="Xóa" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">Xem video</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">Chưa có video!</p>';
      }
   ?>

   </div>

</section>


<style>

/* Blue for "Mở" */
.option-btn[status="Mở"] {
    background-color: #007bff; /* Màu xanh dương */
}

/* Gray for "Đóng" */
.option-btn[status="Đóng"] {
    background-color:rgb(36, 184, 63); /* Màu xanh */
}
</style>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>