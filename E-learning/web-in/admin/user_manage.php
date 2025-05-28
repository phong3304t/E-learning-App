<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
    exit;
}
// Xử lý cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $user_id = $_POST['user_id'];
    $state = isset($_POST['state']) ? 'paid' : 'unpaid'; // Kiểm tra trạng thái của checkbox
    $update_status = $conn->prepare("UPDATE users SET state = ? WHERE id = ?");
    $update_status->execute([$state, $user_id]);
    if ($state === 'paid') {
        $message[] = 'Cập nhật trạng thái tài khoản thành: Kích hoạt!';
    } else {
        $message[] = 'Cập nhật trạng thái tài khoản thành: Chưa kích hoạt!';
    }
}

// Xử lý tìm kiếm
$search_email = '';
if (isset($_POST['search'])) {
    $search_email = $_POST['search_email'];
    $search_email = filter_var($search_email, FILTER_SANITIZE_STRING);

    // Truy vấn tìm kiếm user theo email
    $select_users = $conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
    $select_users->execute([$search_email]);
} else {
    // Lấy danh sách user theo bộ lọc
    $filter = '';
    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    }

    if ($filter === 'paid') {
        $select_users = $conn->prepare("SELECT * FROM `users` WHERE `state` = 'paid'");
    } elseif ($filter === 'unpaid') {
        $select_users = $conn->prepare("SELECT * FROM `users` WHERE `state` = 'unpaid'");
    } else {
        $select_users = $conn->prepare("SELECT * FROM `users`");
    }

    $select_users->execute();
}

// Xử lý xóa user
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_user->execute([$user_id]);
    $message[] = 'Người dùng đã được xóa thành công!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>QuachEdu.Teacher</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="user-management">

    <h1 class="heading">Quản lý học sinh</h1>

    <!-- Thanh tìm kiếm -->
    <form action="" method="post" class="search-form">
        <input type="text" name="search_email" placeholder="Nhập email cần tìm" value="<?= $search_email; ?>" required>
        <button type="submit" name="search" class="btn">Tìm kiếm</button>
    </form>

    <!-- Bộ lọc -->
    <div class="filter-buttons">
        <a href="user_manage.php" class="btn">Toàn bộ học sinh</a>
        <a href="user_manage.php?filter=paid" class="btn">Đã kích hoạt</a>
        <a href="user_manage.php?filter=unpaid" class="btn">Chưa kích hoạt</a>
    </div>

    <!-- Bảng hiển thị user -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($select_users->rowCount() > 0) {
                    while ($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $fetch_user['name']; ?></td>
                            <td><?= $fetch_user['email']; ?></td>
                            <td>
                                <form action="" method="post" style="display: flex; align-items: center; justify-content: center; gap: 3rem;">
                                    <input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
                                    <input type="checkbox" name="state" <?= $fetch_user['state'] === 'paid' ? 'checked' : ''; ?>>
                                    <button type="submit" name="update_status" class="btn">Cập nhật</button>
                                </form>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
                                    <button type="submit" name="delete_user" class="delete-btn" >Xóa</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="4" class="empty">Không tìm thấy học sinh</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</section>
<style>
.user-management {
   padding: 3rem 2rem;
   background-color: #f9f9f9;
   min-height: 100vh;
}

.user-management .filter-buttons {
   display: flex;
   flex-wrap: wrap;
   gap: 1rem;
   margin-bottom: 2rem;
   justify-content: flex-start; /* Căn sát bên trái */
}

.user-management .filter-buttons .btn {
   flex: 0 0 15%; /* Đặt độ rộng khoảng 15% */
   padding: 0.6rem 1.2rem;
   font-size: 1.4rem;
   background:rgb(59, 116, 195);
   color:  #fefefe;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   transition: 0.3s;
   text-align: center; /* Căn giữa nội dung trong nút */
   height: 35px;
}

.user-management .filter-buttons .btn:hover {
   background:  #8a2be2;
}

/* Căn chỉnh bảng để chiếm toàn bộ chiều rộng màn hình */
.user-management .table-container {
   width: 100%;
   overflow-x: 100%;
   margin-top: 2rem;
}

.user-management table {
   width: 100%;
   border-collapse: collapse;
   text-align: left;
   background-color: #fff;
   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.user-management table th, table td {
   padding: 1.5rem;
   border: 1px solid #ddd;
   font-size: 1.6rem;
}

.user-management table th {
   background-color: #f4f4f4;
   font-weight: bold;
   text-align: center;
}

.user-management table td {
   text-align: left;
}
.user-management table th:nth-child(1),
.user-management table td:nth-child(1) {
   width: 20%;
}

.user-management table th:nth-child(2),
.user-management table td:nth-child(2) {
   width: 40%;
}

.user-management table th:nth-child(3),
.user-management table td:nth-child(3) {
   width: 25%;
}

.user-management table th:nth-child(4),
.user-management table td:nth-child(4) {
   width: 10%;
}
/* Căn giữa nút Update */
.user-management .btn {
   width: 60%;
   display: inline-block;
   padding: 0.8rem 2rem;
   font-size: 1.4rem;
   color: #fff;
   background-color:rgb(40, 115, 213);
   border: none;
   border-radius: 5px;
   cursor: pointer;
   text-align: center;
   align-items: center;
}

.user-management .btn:hover {
   background-color: #284ed5;
}

/* Nút Delete */
.user-management .delete-btn {
    width: 80%;
   background-color: #f44336;
   color: #fff;
   padding: 0.8rem 2rem;
   font-size: 1.4rem;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   align-items: center;
   margin-left: 1rem;
}

.user-management .delete-btn:hover {
   background-color: #d32f2f;
}

/* Thanh tìm kiếm */
.user-management .search-form {
   display: flex;
   flex-direction: column; /* Chuyển sang xếp dọc */
   align-items: center;     /* Căn giữa theo chiều ngang */
   gap: 1rem;
   width: 100%;
   max-width: 800px;
   margin: 0 auto 2rem;
}

.user-management .search-form input {
   width: 100%;              /* Đảm bảo input đầy chiều ngang container */
   height: 50px;
   padding: 1rem;
   font-size: 1.6rem;
   border: 1px solid #ccc;
   border-radius: 8px;
   box-sizing: border-box;
}

.user-management .search-form .btn {
   height: 50px;
   width: 20%;               /* Tùy chỉnh chiều rộng của nút */
   padding: 1rem;
   font-size: 1.6rem;
   background: rgb(41, 200, 54);
   color: #fff;
   border: 1px solid #ccc;
   border-radius: 5px;
   cursor: pointer;
}

.user-management .search-form .btn:hover {
   background: #3f32ed;
}

</style>
<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Khi click vào nút Update
    const updateForms = document.querySelectorAll('form[action=""][method="post"]');
    updateForms.forEach(form => {
        const updateBtn = form.querySelector('button[name="update_status"]');
        if (updateBtn) {
            form.addEventListener('submit', function (e) {
                const confirmed = confirm('Are you sure you want to update the user status?');
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        }
    });

    // Khi click vào nút Delete
    const deleteButtons = document.querySelectorAll('button[name="delete_user"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const confirmed = confirm('Are you sure you want to delete this user?');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });
/*
    // Khi người dùng thay đổi checkbox trạng thái (tick/untick)
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="state"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function (e) {
            const confirmed = confirm('Are you sure you want to change this user\'s payment status?');
            if (!confirmed) {
                checkbox.checked = !checkbox.checked; // hoàn tác nếu không đồng ý
            }
        });
    });
*/
});
</script>
</body>
</html>