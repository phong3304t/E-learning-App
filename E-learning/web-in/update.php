<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_user['password'];
   $prev_image = $fetch_user['image'];

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

  if(!empty($name)){
   $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
   $update_name->execute([$name, $user_id]);
   $message[] = 'Cập nhật tên thành công!';
  }

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT email FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $message[] = 'Email đã được sử dụng!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
         $message[] = 'Cập nhật email thành công!';
      }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Kích thước ảnh quá lớn!';
      }else{
         $update_image = $conn->prepare("UPDATE `users` SET `image` = ? WHERE id = ?");
         $update_image->execute([$rename, $user_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($prev_image != '' AND $prev_image != $rename){
            unlink('uploaded_files/'.$prev_image);
         }
         $message[] = 'Cập nhật ảnh thành công!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'Mật khẩu cũ không khớp!';
      }elseif($new_pass != $cpass){
         $message[] = 'Mật khẩu xác nhận không khớp!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$cpass, $user_id]);
            $message[] = 'Cập nhật mật khẩu thành công!';
         }else{
            $message[] = 'Chưa nhập mật khẩu mới!';
         }
      }
   }

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

<section class="form-container" style="min-height: calc(100vh - 19rem);">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Cập nhật thông tin</h3>
      <div class="flex">
         <div class="col">
            <p>Tên</p>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="100" class="box">
            <p>Email</p>
            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="100" class="box">
            <p>Ảnh đại diện</p>
            <input type="file" name="image" accept="image/*" class="box">
         </div>
         <div class="col">
               <p>Mật khẩu cũ</p>
               <input type="password" name="old_pass" placeholder="Nhập mật khẩu cũ" maxlength="50" class="box"
               autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
               <p>Mật khẩu mới</p>
               <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới" maxlength="50" class="box"
               autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
               <p>Xác nhận mật khẩu mới</p>
               <input type="password" name="cpass" placeholder="Xác nhận lại mật khẩu" maxlength="50" class="box"
               autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
         </div>
      </div>
      <input type="submit" name="submit" value="Cập nhật thông tin" class="btn">
   </form>

</section>

<!-- update profile section ends -->




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