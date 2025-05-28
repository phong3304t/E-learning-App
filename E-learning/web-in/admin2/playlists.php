<?php

include '../components/connect.php';

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
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin2_header.php'; ?>

<section class="playlists">

   <h1 class="heading">Khóa học</h1>

   <div class="box-container">

      <?php
         // Lấy toàn bộ khóa học trong database
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` ORDER BY date DESC");
         $select_playlist->execute();
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

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });


</script>

</body>
</html>