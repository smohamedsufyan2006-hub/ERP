<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];



if(!isset($_GET["mark_id"])){

    header("Location:view_marks.php");

    exit();

}


$mark_id=$_GET["mark_id"];


// =====================================
// FETCH MARK DETAILS
// =====================================


$sql="

SELECT

sm.mark_id,

sm.student_id,

sm.teacher_class_subject_id,

sm.marks_obtained,

sm.max_marks,

s.student_name,

u.register_number,

mt.mark_type_name,

sub.subject_name


FROM student_marks sm


INNER JOIN students s

ON sm.student_id=s.student_id


INNER JOIN user u

ON s.user_id=u.user_id


INNER JOIN mark_types mt

ON sm.mark_type_id=mt.mark_type_id


INNER JOIN teacher_class_subjects tcs

ON sm.teacher_class_subject_id=tcs.teacher_class_subject_id


INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id


INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id


WHERE

sm.mark_id=? 

AND

tcs.teacher_id=?


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"ii",

$mark_id,

$teacher_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);



$mark=mysqli_fetch_assoc($result);



if(!$mark){

    die("Invalid Access");

}


?>


<!DOCTYPE html>

<html>

<head>


<title>

Edit Marks

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


<a href="javascript:history.back()"
class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Edit Marks

</h1>


</div>




<div class="teacher-form">



<h3>

Student Details

</h3>



<p>

<b>Register Number:</b>

<?php echo htmlspecialchars($mark["register_number"]); ?>

</p>


<p>

<b>Student Name:</b>

<?php echo htmlspecialchars($mark["student_name"]); ?>

</p>


<p>

<b>Subject:</b>

<?php echo htmlspecialchars($mark["subject_name"]); ?>

</p>


<p>

<b>Exam:</b>

<?php echo htmlspecialchars($mark["mark_type_name"]); ?>

</p>



<form action="update_marks.php" method="POST">



<input type="hidden"

name="mark_id"

value="<?php echo $mark["mark_id"]; ?>">
<input type="hidden"

name="teacher_class_subject_id"

value="<?php echo $mark["teacher_class_subject_id"]; ?>">



<label>

Marks Obtained

</label>


<input

type="number"

name="marks_obtained"

value="<?php echo $mark["marks_obtained"]; ?>"

required>



<label>

Maximum Marks

</label>


<input

type="number"

name="max_marks"

value="<?php echo $mark["max_marks"]; ?>"

required>



<br>


<button class="save-btn">


<i class="fa-solid fa-check"></i>

Update Marks


</button>



</form>



</div>


</div>


</div>


</body>


</html>


<?php

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>