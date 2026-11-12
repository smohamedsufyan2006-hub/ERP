<?php

// =========================================
// START SESSION
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



$teacher_id=$_SESSION["teacher_id"];



// =========================================
// CHECK DATA
// =========================================


if(

!isset($_POST["mark_id"]) ||

!isset($_POST["marks_obtained"]) ||

!isset($_POST["max_marks"])

){

    header("Location: manage_marks.php");

    exit();

}




$mark_id=$_POST["mark_id"];

$marks_obtained=$_POST["marks_obtained"];

$max_marks=$_POST["max_marks"];




// =========================================
// UPDATE MARKS
// =========================================


$sql="

UPDATE student_marks sm


INNER JOIN teacher_class_subjects tcs

ON sm.teacher_class_subject_id=tcs.teacher_class_subject_id


SET

sm.marks_obtained=?,

sm.max_marks=?


WHERE

sm.mark_id=?

AND

tcs.teacher_id=?


";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"iiii",

$marks_obtained,

$max_marks,

$mark_id,

$teacher_id

);



mysqli_stmt_execute($stmt);




// =========================================
// REDIRECT
// =========================================


header(

"Location:view_marks.php?teacher_class_subject_id=".$_POST["teacher_class_subject_id"]

);


exit();


?>