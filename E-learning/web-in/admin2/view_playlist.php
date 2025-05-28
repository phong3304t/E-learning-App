<?php

include '../components/connect.php';

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    echo '<p class="empty">Không tìm thấy khóa học!</p>';
    exit;
}

if (isset($_POST['toggle_status'])) {
    $current_status = $_POST['current_status'];
    $new_status = ($current_status == 'Mở') ? 'Đóng' : 'Mở';
 
    if (isset($_POST['video_id'])) {
       $video_id = $_POST['video_id'];
       $update_status = $conn->prepare("UPDATE `content` SET `status` = ? WHERE `id` = ?");
       $update_status->execute([$new_status, $video_id]);
       $message[] = 'Trạng thái video đã được cập nhật!';
    }
 
    if (isset($_POST['playlist_id'])) {
       $playlist_id = $_POST['playlist_id'];
       $update_status = $conn->prepare("UPDATE `playlist` SET `status` = ? WHERE `id` = ?");
       $update_status->execute([$new_status, $playlist_id]);
       $message[] = 'Trạng thái khóa học đã được cập nhật!';
    }
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
   
<section class="playlist-details">

   <h1 class="heading">Khóa học</h1>

   <?php
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
      $select_playlist->execute([$get_id]);
      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
   ?>
   <div class="row">
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
      </div>
      <div class="details">
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         <div class="description"><?= $fetch_playlist['description']; ?></div>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Không tìm thấy khóa học!</p>';
      }
   ?>

</section>

<section class="contents">

   <h1 class="heading">Danh sách video</h1>

   <div class="box-container">

   <?php
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
      $select_videos->execute([$playlist_id]);
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'Mở'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_videos['status'] == 'Mở'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <input type="hidden" name="current_status" value="<?= $fecth_videos['status']; ?>">
            <button type="submit" name="toggle_status" class="option-btn" status="<?= $fetch_videos['status']; ?>" onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái video này?');">
            <?= ($fecth_videos['status'] == 'Mở') ? 'Khóa' : 'Mở'; ?>
            </button>
            <input type="submit" value="Xóa" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">Xem video</a>
      </div>
   <?php
         }
      }else{
        echo '<p class="empty">Không tìm thấy video nào!</p>';
      }
   ?>

   </div>
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
</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>