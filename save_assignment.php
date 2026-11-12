<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];


// CHECK FORM DATA

if(

!isset($_POST["teacher_class_subject_id"]) ||

!isset($_POST["title"]) ||

!isset($_POST["due_date"])

){

    header("Location:create_assignment.php");

    exit();

}



$teacher_class_subject_id=$_POST["teacher_class_subject_id"];

$title=$_POST["title"];

$description=$_POST["description"];

$due_date=$_POST["due_date"];




// =====================================
// CHECK TEACHER ACCESS
// =====================================


$check_sql="

SELECT teacher_class_subject_id

FROM teacher_class_subjects

WHERE

teacher_class_subject_id=?

AND

teacher_id=?

";


$check_stmt=mysqli_prepare($conn,$check_sql);


mysqli_stmt_bind_param(

$check_stmt,

"ii",

$teacher_class_subject_id,

$teacher_id

);


mysqli_stmt_execute($check_stmt);


$check_result=mysqli_stmt_get_result($check_stmt);



if(mysqli_num_rows($check_result)==0){


    die("Unauthorized Access");


}




// =====================================
// INSERT ASSIGNMENT
// =====================================


$sql="

INSERT INTO assignments

(

teacher_class_subject_id,

title,

description,

due_date

)

VALUES

(?,?,?,?)

";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"isss",

$teacher_class_subject_id,

$title,

$description,

$due_date

);



mysqli_stmt_execute($stmt);




// =====================================
// REDIRECT
// =====================================


header("Location:manage_assignments.php");

exit();


?>