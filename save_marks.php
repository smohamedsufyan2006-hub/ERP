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


// =========================================
// CHECK FORM DATA
// =========================================

if(
    !isset($_POST["teacher_class_subject_id"]) ||
    !isset($_POST["mark_type_id"]) ||
    !isset($_POST["marks"])
){

    header("Location: manage_marks.php");
    exit();

}


// =========================================
// GET DATA
// =========================================


$teacher_class_subject_id = $_POST["teacher_class_subject_id"];

$mark_type_id = $_POST["mark_type_id"];

$max_marks = $_POST["max_marks"];

$marks = $_POST["marks"];




// =========================================
// INSERT MARKS
// =========================================


$sql = "

INSERT INTO student_marks

(

student_id,

teacher_class_subject_id,

mark_type_id,

marks_obtained,

max_marks

)

VALUES

(?,?,?,?,?)

";


$stmt=mysqli_prepare($conn,$sql);



foreach($marks as $student_id=>$mark){


    mysqli_stmt_bind_param(

        $stmt,

        "iiiii",

        $student_id,

        $teacher_class_subject_id,

        $mark_type_id,

        $mark,

        $max_marks

    );


    mysqli_stmt_execute($stmt);


}



// =========================================
// REDIRECT
// =========================================


header(

"Location: view_marks.php?teacher_class_subject_id=".$teacher_class_subject_id

);


exit();


?>