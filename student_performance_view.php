<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


if(!isset($_GET["class_id"])){

    header("Location: student_performance.php");
    exit();

}


$class_id=$_GET["class_id"];


// FETCH STUDENTS


$sql="

SELECT

s.student_id,

s.student_name,

u.register_number


FROM students s


INNER JOIN user u

ON s.user_id=u.user_id


WHERE s.class_id=?


ORDER BY u.register_number


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$class_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


?>


<!DOCTYPE html>

<html>

<head>

<title>

Students Performance

</title>


<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="teacher.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>


<body>



<div class="manage-student-container">


<div class="teacher-page-card">



<div class="page-header">


<a href="student_performance.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Students Performance

</h1>


</div>




<div class="student-table-wrapper">


<table class="student-table">


<thead>


<tr>


<th>S.No</th>

<th>Register Number</th>

<th>Student Name</th>

<th>Action</th>


</tr>


</thead>



<tbody>



<?php


$i=1;


while($row=mysqli_fetch_assoc($result)){


?>


<tr>


<td>

<?php echo $i++; ?>

</td>


<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>


<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>



<td>


<a

class="teacher-btn"

href="student_report.php?student_id=<?php echo $row['student_id']; ?>">


<i class="fa-solid fa-eye"></i>

View Report


</a>


</td>


</tr>



<?php


}


?>


</tbody>


</table>


</div>



</div>


</div>


</body>


</html>



<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>