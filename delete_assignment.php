<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];



if(!isset($_GET["assignment_id"])){

    header("Location:manage_assignments.php");

    exit();

}



$assignment_id=$_GET["assignment_id"];




// =====================================
// DELETE ASSIGNMENT
// =====================================


$sql="

DELETE a

FROM assignments a


INNER JOIN teacher_class_subjects tcs

ON a.teacher_class_subject_id=tcs.teacher_class_subject_id


WHERE

a.assignment_id=?

AND

tcs.teacher_id=?


";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"ii",

$assignment_id,

$teacher_id

);



mysqli_stmt_execute($stmt);




// =====================================
// REDIRECT
// =====================================


header("Location:manage_assignments.php");


exit();


?>