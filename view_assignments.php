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


// FETCH ASSIGNMENT DETAILS


$sql="

SELECT


a.assignment_id,

a.title,

a.description,

a.due_date,


c.year_of_study,

c.section,


sub.subject_name



FROM assignments a



INNER JOIN teacher_class_subjects tcs

ON a.teacher_class_subject_id=tcs.teacher_class_subject_id



INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id



INNER JOIN classes c

ON cs.class_id=c.class_id



INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id



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

Assignment Details

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


<a href="manage_assignments.php"

class="back-btn">


<i class="fa-solid fa-arrow-left"></i>


</a>



<h1>

Assignment Details

</h1>


</div>




<div class="teacher-form">



<h2>

<?php echo htmlspecialchars($assignment["title"]); ?>

</h2>



<p>

<b>Class:</b>

<?php

echo "Year "

.$assignment["year_of_study"]

." - "

.$assignment["section"];

?>

</p>




<p>

<b>Subject:</b>

<?php echo htmlspecialchars($assignment["subject_name"]); ?>

</p>



<p>

<b>Description:</b>

<br>

<?php echo nl2br(htmlspecialchars($assignment["description"])); ?>

</p>



<p>

<b>Due Date:</b>

<?php echo $assignment["due_date"]; ?>

</p>



<br>
<a

href="view_submissions.php?assignment_id=<?php echo $assignment["assignment_id"]; ?>"

class="teacher-btn">


<i class="fa-solid fa-users"></i>

Submissions


</a>



<a

href="edit_assignment.php?assignment_id=<?php echo $assignment["assignment_id"]; ?>"

class="edit-btn">


<i class="fa-solid fa-pen"></i>

Edit


</a>




<a

href="delete_assignment.php?assignment_id=<?php echo $assignment["assignment_id"]; ?>"

class="delete-btn"

onclick="return confirm('Delete this assignment?');">


<i class="fa-solid fa-trash"></i>

Delete


</a>



</div>



</div>


</div>


</body>


</html>