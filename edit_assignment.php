<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];



if(!isset($_GET["assignment_id"])){

    header("Location: manage_assignments.php");
    exit();

}



$assignment_id=$_GET["assignment_id"];



// FETCH ASSIGNMENT


$sql="

SELECT

a.assignment_id,

a.title,

a.description,

a.due_date


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


$result=mysqli_stmt_get_result($stmt);


$assignment=mysqli_fetch_assoc($result);



if(!$assignment){

    die("Invalid Access");

}

?>


<!DOCTYPE html>

<html>

<head>


<title>

Edit Assignment

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


<a href="view_assignments.php?assignment_id=<?php echo $assignment_id; ?>"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>

Edit Assignment

</h1>


</div>




<form action="update_assignment.php" method="POST" class="teacher-form">


<input type="hidden"

name="assignment_id"

value="<?php echo $assignment["assignment_id"]; ?>">





<label>

Assignment Title

</label>


<input

type="text"

name="title"

value="<?php echo htmlspecialchars($assignment["title"]); ?>"

required>




<label>

Description

</label>


<textarea

name="description"

rows="5"

><?php echo htmlspecialchars($assignment["description"]); ?></textarea>




<label>

Due Date

</label>


<input

type="date"

name="due_date"

value="<?php echo $assignment["due_date"]; ?>"

required>




<br>


<button class="save-btn">


<i class="fa-solid fa-save"></i>

Update Assignment


</button>



</form>



</div>


</div>


</body>


</html>