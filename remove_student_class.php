<?php

session_start();


if(!isset($_SESSION["admin_id"])){

    header("Location: login.php");
    exit();

}


require "database.php";



// Check student id

if(!isset($_GET["student_id"])){

    header("Location: manage_student_class.php");
    exit();

}



$student_id = $_GET["student_id"];




// Remove class assignment

$sql = "

UPDATE students

SET class_id = NULL

WHERE student_id = ?

";



$stmt = mysqli_prepare($conn,$sql);



if(!$stmt){

    die("SQL Error: ".mysqli_error($conn));

}



mysqli_stmt_bind_param(

    $stmt,

    "i",

    $student_id

);



if(mysqli_stmt_execute($stmt)){


    header("Location: manage_student_class.php");

    exit();


}

else{


    echo "Remove Failed: ".mysqli_error($conn);


}



?>