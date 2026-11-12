<?php

// =========================================
// SESSION START
// =========================================

session_start();


// =========================================
// CHECK TEACHER LOGIN
// =========================================

if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";


// =========================================
// LOGGED TEACHER
// =========================================

$teacher_id = $_SESSION["teacher_id"];


// =========================================
// FETCH ASSIGNED CLASS SUBJECTS
// =========================================


$sql = "

SELECT

tcs.teacher_class_subject_id,

c.class_id,

c.year_of_study,

c.section,

sub.subject_name


FROM teacher_class_subjects tcs


INNER JOIN class_subjects cs

ON tcs.class_subject_id = cs.class_subject_id


INNER JOIN classes c

ON cs.class_id = c.class_id


INNER JOIN subjects sub

ON cs.subject_id = sub.subject_id


WHERE tcs.teacher_id = ?


ORDER BY

c.year_of_study,
c.section

";


$stmt = mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$teacher_id

);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


?>


<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>
Manage Marks
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


<a href="teacher_dashboard.php"
class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Manage Marks

</h1>


</div>



<div class="student-table-wrapper">


<table class="student-table">


<thead>

<tr>

<th>S.No</th>

<th>Class</th>

<th>Subject</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php


$count=1;


if(mysqli_num_rows($result)>0){


while($row=mysqli_fetch_assoc($result)){


?>


<tr>


<td>

<?php echo $count++; ?>

</td>



<td>

<?php

echo "Year ".$row["year_of_study"].
" - ".$row["section"];

?>

</td>



<td>

<?php

echo htmlspecialchars($row["subject_name"]);

?>

</td>



<td>


<a

href="enter_marks.php?teacher_class_subject_id=<?php echo $row['teacher_class_subject_id']; ?>"

class="teacher-btn">


<i class="fa-solid fa-pen"></i>

Enter Marks


</a>


</td>



</tr>


<?php


}

}

else{


?>


<tr>

<td colspan="4">

No subjects assigned.

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