<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo">QuachEduAdmin.</a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="Tìm kiếm tại đây..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
         <a href="../components/admin_logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
            <div id="logout-btn" class="fas fa-right-from-bracket"></div>
         </a>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
        <img src="../images/admin.png" alt="Admin">
        <h3>Admin</h3>
    </div>

   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Trang chủ</span></a>
      <a href="teacher.php"><i class="fas fa-chalkboard-teacher"></i><span>Giáo viên</span></a>
      <a href="playlists.php"><i class="fa-solid fa-bars-staggered"></i><span>Khóa học</span></a>
      <a href="contents.php"><i class="fas fa-graduation-cap"></i><span>Video</span></a>
      <a href="user_manage.php"><i class="fas fa-user-graduate"></i><span>Học sinh</span></a>
      <a href="comments.php"><i class="fas fa-comment"></i><span>Bình luận</span></a>
      <a href="response.php"><i class="fas fa-reply"></i><span>Phản hồi</span></a>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Đăng xuất</span></a>
   </nav>

</div>

<!-- side bar section ends -->

<style>
   :root{
   --main-color:rgb(142, 107, 255);
}
.header {
   background-color: var(--white);
   border-bottom: var(--border);
   position: sticky;
   top: 0; left: 0; right: 0;
   z-index: 1000;
   height: 8rem; /* Chiều cao cố định */
}

.header .flex {
   height: 100%;
   padding: 0 2rem;
   position: relative;
   display: flex;
   align-items: center;
   justify-content: space-between;
}

.header .flex .logo{
   font-size: 2.5rem;
   color: var(--black);
   font-weight: bolder;
}

.header .flex .search-form{
   width: 50rem;
   border-radius: .5rem;
   display: flex;
   align-items: center;
   gap: 2rem;
   padding: 1.5rem 2rem;
   background-color: var(--light-bg);
   padding: 1rem 1.5rem;
   height: 5rem; /* đồng bộ với icon */
   margin-top: 1.5rem;
   margin-bottom: 1.5rem;
}

.header .flex .search-form input{
   width: 100%;
   background:none;
   font-size: 2rem;   color: var(--black);
   margin-top: 1.5rem;
   margin-bottom: 1.5rem;
}

.header .flex .search-form button{
   font-size: 2rem;
   color: var(--black);
   cursor: pointer;
   background: none;
}

.header .flex .search-form button:hover{
   color: var(--main-color);
}

.header .flex .icons div{
   font-size: 2rem;
   color: var(--black);
   border-radius: .5rem;
   height: 4.5rem;
   cursor: pointer;
   width: 4.5rem;
   line-height: 4.4rem;
   background-color: var(--light-bg);
   text-align: center;
   margin-left: .5rem;
}

.header .flex .icons div:hover{
   background-color: var(--black);
   color:var(--white);
}

#search-btn{
   display: none;
}

.header .flex .profile{
   position: absolute;
   top: 120%; right: 2rem;
   background-color: var(--white);
   border-radius: .5rem;
   padding: 2rem;
   text-align: center;
   width: 30rem;
   transform: scale(0);
   transform-origin: top right;
}

.header .flex .profile.active{
   transform: scale(1);
   transition: .2s linear;
}

.header .flex .profile img{
   height: 10rem;
   width: 10rem;
   border-radius: 50%;
   object-fit: cover;
   margin-bottom: .5rem;
}

.header .flex .profile h3{
   font-size: 2rem;
   color: var(--black);
}

.header .flex .profile span{
   color: var(--light-color);
   font-size: 1.6rem;
}
</style>