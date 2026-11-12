<?php

session_start();

if(!isset($_SESSION["admin_id"])){

    header("Location: login.php");
    exit();

}

require "database.php";


// Check student id

if(!isset($_GET['student_id'])){

    header("Location: manage_student_class.php");
    exit();

}


$student_id = $_GET['student_id'];



// Fetch student details

$student_sql = "

SELECT

s.student_id,
s.student_name,
u.register_number,
d.department_name

FROM students s

JOIN user u

ON s.user_id = u.user_id

LEFT JOIN departments d

ON s.department_id = d.department_id

WHERE s.student_id = ?

";


$stmt = mysqli_prepare($conn,$student_sql);


if(!$stmt){

    die("Student SQL Error: ".mysqli_error($conn));

}


mysqli_stmt_bind_param($stmt,"i",$student_id);

mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


$student = mysqli_fetch_assoc($result);




// Fetch classes

$class_sql = "

SELECT

c.class_id,

d.department_name,

c.year_of_study,

c.section,

ay.academic_year_name,

sem.semester_name


FROM classes c


JOIN departments d

ON c.department_id = d.department_id


JOIN academic_years ay

ON c.academic_year_id = ay.academic_year_id


JOIN semesters sem

ON c.semester_id = sem.semester_id


ORDER BY c.class_id DESC

";


$class_result = mysqli_query($conn,$class_sql);



?>



<!DOCTYPE html>

<html>

<head>

<title>Change Student Class</title>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>


body{

font-family:Arial;

background:#f4f6f9;

}


.container{

width:600px;

margin:40px auto;

}


.card{

background:white;

padding:25px;

border-radius:10px;

box-shadow:0 0 10px #ddd;

}


.back{

font-size:22px;

color:#333;

text-decoration:none;

}


input,select{

width:100%;

padding:10px;

margin:10px 0 20px;

border:1px solid #ccc;

border-radius:5px;

}


button{

background:#ffc107;

border:none;

padding:10px 20px;

border-radius:5px;

cursor:pointer;

}


</style>


</head>


<body>


<div class="container">


<div class="card">


<a href="manage_student_class.php" class="back">

<i class="fa-solid fa-arrow-left"></i>

</a>



<h2>

Change Student Class

</h2>



<label>

Student

</label>


<input type="text"

value="<?php echo $student['register_number']." - ".$student['student_name']; ?>"

readonly>




<form action="update_student_class_process.php" method="POST">


<input type="hidden"

name="student_id"

value="<?php echo $student_id; ?>">



<label>

Select New Class

</label>



<select name="class_id" required>


<option value="">

Select Class

</option>


<?php


while($class=mysqli_fetch_assoc($class_result))

{


?>


<option value="<?php echo $class['class_id']; ?>">


<?php echo

$class['department_name'].

" - Year ".

$class['year_of_study'].

" - Section ".

$class['section'].

" (".$class['academic_year_name'].

" / ".

$class['semester_name'].")";


?>


</option>


<?php

}

?>


</select>



<button type="submit">

Update Class

</button>


</form>



</div>


</div>


</body>


</html>