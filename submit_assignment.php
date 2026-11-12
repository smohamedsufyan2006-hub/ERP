<?php

session_start();


if(!isset($_SESSION["student_id"])){

    header("Location: student_login.php");
    exit();

}


require "database.php";


$student_id=$_SESSION["student_id"];



if(!isset($_GET["assignment_id"])){

    header("Location:student_assignments.php");

    exit();

}


$assignment_id=$_GET["assignment_id"];


// FETCH ASSIGNMENT DETAILS


$sql="

SELECT


a.assignment_id,

a.title,

a.description,

a.due_date,


sub.subject_name



FROM assignments a



INNER JOIN teacher_class_subjects tcs

ON a.teacher_class_subject_id=tcs.teacher_class_subject_id



INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id



INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id



WHERE

a.assignment_id=?



";



$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$assignment_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$assignment=mysqli_fetch_assoc($result);



if(!$assignment){

    die("Assignment not found");

}


?>


<!DOCTYPE html>

<html>

<head>


<title>

Submit Assignment

</title>


<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="student.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>


<body>


<div class="student-assignment-container">


<div class="student-assignment-card">

<div class="page-header">


<a href="student_assignments.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Submit Assignment

</h1>


</div>




<div class="assignment-details-card">


<h2>

<?php echo htmlspecialchars($assignment["title"]); ?>

</h2>


<p>

<b>Subject:</b>

<?php echo htmlspecialchars($assignment["subject_name"]); ?>

</p>


<p>

<b>Description:</b>

<br>

<?php

echo nl2br(htmlspecialchars($assignment["description"]));

?>

</p>


<p>

<b>Due Date:</b>

<?php echo $assignment["due_date"]; ?>

</p>


<div class="assignment-upload-box">

<form action="save_submission.php"

method="POST"

enctype="multipart/form-data">


<input type="hidden"

name="assignment_id"

value="<?php echo $assignment_id; ?>">



<label>

Upload File

</label>


<input

type="file"

name="assignment_file"

accept=".pdf,.doc,.docx"

required>



<br>



<button class="save-btn">


<i class="fa-solid fa-upload"></i>

Submit Assignment


</button>


</form>
</div>



</div>



</div>


</div>


</body>


</html>