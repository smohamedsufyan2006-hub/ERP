<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];


// FETCH TEACHER ASSIGNED SUBJECTS

$sql="

SELECT


tcs.teacher_class_subject_id,


c.year_of_study,

c.section,


sub.subject_name



FROM teacher_class_subjects tcs



INNER JOIN class_subjects cs

ON tcs.class_subject_id=cs.class_subject_id



INNER JOIN classes c

ON cs.class_id=c.class_id



INNER JOIN subjects sub

ON cs.subject_id=sub.subject_id



WHERE

tcs.teacher_id=?



ORDER BY

c.year_of_study,
c.section


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"i",

$teacher_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


?>


<!DOCTYPE html>

<html>

<head>


<title>
Create Assignment
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

Create Assignment

</h1>


</div>



<form action="save_assignment.php" method="POST" class="teacher-form">



<label>

Select Class & Subject

</label>



<select name="teacher_class_subject_id" required>


<option value="">

Select

</option>



<?php while($row=mysqli_fetch_assoc($result)){ ?>


<option value="<?php echo $row["teacher_class_subject_id"]; ?>">


<?php

echo "Year "

.$row["year_of_study"]

." - "

.$row["section"]

." | "

.$row["subject_name"];

?>


</option>


<?php } ?>


</select>




<label>

Assignment Title

</label>


<input

type="text"

name="title"

placeholder="Enter assignment title"

required>




<label>

Description

</label>


<textarea

name="description"

rows="5"

placeholder="Enter assignment details">

</textarea>




<label>

Due Date

</label>


<input

type="date"

name="due_date"

required>




<br>


<button class="save-btn">


<i class="fa-solid fa-save"></i>

Create Assignment


</button>



</form>



</div>


</div>



</body>


</html>