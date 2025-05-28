<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Lấy danh sách liên hệ sắp xếp theo ngày mới nhất
$select_contact = $conn->query("SELECT * FROM contact ORDER BY date DESC");
$contacts = $select_contact->fetchAll(PDO::FETCH_ASSOC);

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

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   







<section class="contact-table">

   <h1 class="heading">Danh sách liên hệ gần đây</h1>

   <div class="table-container">
      <table class="contact-table-style">
         <thead>
            <tr>
               <th>Tên</th>
               <th>Email</th>
               <th>Nội dung</th>
               <th>Ngày gửi</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach($contacts as $contact): ?>
            <tr>
               <td><?= htmlspecialchars($contact['name']); ?></td>
               <td><?= htmlspecialchars($contact['email']); ?></td>
               <td><?= nl2br(htmlspecialchars($contact['message'])); ?></td>
               <td><?= htmlspecialchars($contact['date']); ?></td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>

</section>


<style>
 .contact-table {
   padding: 3rem 2rem;
   background-color: #f9f9f9;
   min-height: 100vh;
}

.contact-table .heading {
   font-size: 2.8rem;
   font-weight: 600;
   color: #333;
   margin-bottom: 2rem;
}

.table-container {
   overflow-x: auto;
   background: #fff;
   border-radius: .8rem;
   box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
   padding: 1.5rem;
}

.contact-table-style {
   width: 100%;
   border-collapse: collapse;
   font-size: 1.6rem;
   min-width: 700px;
}

.contact-table-style thead {
   background-color: #f1f1f1;
}

.contact-table-style th {
   font-weight: 700; /* Chữ đậm */
   text-align: center; /* Căn giữa */
   background-color: #f1f1f1;
   padding: 1.2rem 1.5rem;
   color: #222;
}
.contact-table-style td {
   text-align: left;
   padding: 1.2rem 1.5rem;
   border-bottom: 1px solid #e0e0e0;
   color: #444;
}

.contact-table-style th {
   font-weight: 600;
   color: #222;
}

.contact-table-style tr:hover {
   background-color: #f9f9f9;
   transition: 0.2s ease-in-out;
}

@media (max-width: 768px) {
   .contact-table-style {
      font-size: 1.4rem;
   }
}

.contact-table-style th:nth-child(1), 
.contact-table-style td:nth-child(1) {
   width: 20%; /* Cột Name: 20% */
}

.contact-table-style th:nth-child(2), 
.contact-table-style td:nth-child(2) {
   width: 30%; /* Cột Email: 25% */
}

.contact-table-style th:nth-child(4), 
.contact-table-style td:nth-child(4) {
   width: 12%; /* Cột Email: 25% */
}

.contact-table-style th:nth-child(3), 
.contact-table-style td:nth-child(3) {
   width: auto; /* Cột Nội dung: phần còn lại */
}
</style>



<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>