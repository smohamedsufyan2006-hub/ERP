<?php

session_start();


if(!isset($_SESSION["admin_id"])){

    header("Location: login.php");
    exit();

}


require "database.php";



if($_SERVER["REQUEST_METHOD"] == "POST"){



    $student_id = $_POST["student_id"];

    $class_id = $_POST["class_id"];




    $sql = "

    UPDATE students

    SET class_id = ?

    WHERE student_id = ?

    ";




    $stmt = mysqli_prepare($conn,$sql);



    if(!$stmt){

        die("SQL Error: ".mysqli_error($conn));

    }



    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $class_id,

        $student_id

    );




    if(mysqli_stmt_execute($stmt)){



        header("Location: manage_student_class.php");

        exit();



    }

    else{


        echo "Update Failed: ".mysqli_error($conn);


    }



}

else{


    header("Location: manage_student_class.php");

    exit();

}


?>