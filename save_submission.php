<?php

session_start();


if(!isset($_SESSION["student_id"])){

    header("Location: student_login.php");
    exit();

}


require "database.php";


$student_id=$_SESSION["student_id"];



// CHECK DATA


if(

!isset($_POST["assignment_id"]) ||

!isset($_FILES["assignment_file"])

){

    header("Location:student_assignments.php");

    exit();

}



$assignment_id=$_POST["assignment_id"];




// FILE DETAILS


$file=$_FILES["assignment_file"];


$file_name=$file["name"];

$file_tmp=$file["tmp_name"];

$file_error=$file["error"];





if($file_error!=0){

    die("File upload failed");

}





// ALLOWED FILE TYPES


$file_ext=strtolower(

pathinfo($file_name,PATHINFO_EXTENSION)

);



$allowed=array(

"pdf",

"doc",

"docx"

);



if(!in_array($file_ext,$allowed)){


    die("Only PDF and DOC files allowed");


}





// CREATE UNIQUE FILE NAME


$new_file_name=

time()

."_"

.$file_name;





// UPLOAD LOCATION


$upload_path="uploads/assignments/".$new_file_name;





if(!move_uploaded_file(

$file_tmp,

$upload_path

)){


    die("Unable to upload file");


}






// INSERT SUBMISSION


$sql="

INSERT INTO assignment_submissions

(

assignment_id,

student_id,

file_name,

status

)

VALUES

(?,?,?,'Submitted')


";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(

$stmt,

"iis",

$assignment_id,

$student_id,

$new_file_name

);



mysqli_stmt_execute($stmt);




// REDIRECT


header("Location:student_assignments.php");


exit();


?>