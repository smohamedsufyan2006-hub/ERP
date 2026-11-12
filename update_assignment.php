<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];



// CHECK DATA


if(

!isset($_POST["assignment_id"]) ||

!isset($_POST["title"]) ||

!isset($_POST["due_date"])

){

    header("Location:manage_assignments.php");

    exit();

}



$assignment_id=$_POST["assignment_id"];

$title=$_POST["title"];

$description=$_POST["description"];

$due_date=$_POST["due_date"];





// UPDATE ASSIGNMENT


$sql="

UPDATE assignments a


INNER JOIN teacher_class_subjects tcs


ON a.teacher_class_subject_id=tcs.teacher_class_subject_id



SET

a.title=?,

a.description=?,

a.due_date=?



WHERE

a.assignment_id=?

AND

tcs.teacher_id=?


";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"sssii",

$title,

$description,

$due_date,

$assignment_id,

$teacher_id

);



mysqli_stmt_execute($stmt);





// REDIRECT


header(

"Location:view_assignments.php?assignment_id=".$assignment_id

);


exit();


?>