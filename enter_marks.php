<?php

session_start();


if(!isset($_SESSION["teacher_id"])){

    header("Location: teacher_login.php");
    exit();

}


require "database.php";


$teacher_id=$_SESSION["teacher_id"];


if(!isset($_GET["teacher_class_subject_id"])){

    header("Location: manage_marks.php");
    exit();

}


$teacher_class_subject_id=$_GET["teacher_class_subject_id"];


// ===============================
// FETCH CLASS SUBJECT DETAILS
// ===============================


$sql="

SELECT

c.class_id,

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

tcs.teacher_class_subject_id=?

AND

tcs.teacher_id=?


";


$stmt=mysqli_prepare($conn,$sql);


mysqli_stmt_bind_param(

$stmt,

"ii",

$teacher_class_subject_id,

$teacher_id

);


mysqli_stmt_execute($stmt);


$result=mysqli_stmt_get_result($stmt);


$class=mysqli_fetch_assoc($result);



if(!$class){

    die("Invalid Access");

}




$class_id=$class["class_id"];


// ===============================
// FETCH STUDENTS
// ===============================


$sql2="

SELECT

s.student_id,

s.student_name,

u.register_number


FROM students s


INNER JOIN user u

ON s.user_id=u.user_id


WHERE s.class_id=?


ORDER BY u.register_number


";


$stmt2=mysqli_prepare($conn,$sql2);


mysqli_stmt_bind_param(

$stmt2,

"i",

$class_id

);


mysqli_stmt_execute($stmt2);


$students=mysqli_stmt_get_result($stmt2);



// ===============================
// MARK TYPES
// ===============================


$types=mysqli_query(

$conn,

"SELECT * FROM mark_types"

);


?>


<!DOCTYPE html>

<html>

<head>


<title>
Enter Marks
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


<a href="manage_marks.php"
class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h1>
Enter Marks
</h1>


</div>




<h3>

Class :

<?php echo "Year ".$class["year_of_study"]." - ".$class["section"]; ?>

<br>

Subject :

<?php echo htmlspecialchars($class["subject_name"]); ?>

</h3>



<form action="save_marks.php" method="POST">


<input type="hidden"
name="teacher_class_subject_id"
value="<?php echo $teacher_class_subject_id; ?>">



<label>

Mark Type

</label>


<select name="mark_type_id" required>


<option value="">
Select Type
</option>


<?php while($type=mysqli_fetch_assoc($types)){ ?>


<option value="<?php echo $type["mark_type_id"]; ?>">

<?php echo $type["mark_type_name"]; ?>

</option>


<?php } ?>


</select>



<label>

Maximum Marks

</label>


<input

type="number"

name="max_marks"

value="100"

required>



<div class="student-table-wrapper">


<table class="student-table">


<thead>

<tr>

<th>S.No</th>

<th>Register Number</th>

<th>Student Name</th>

<th>Marks</th>

</tr>

</thead>


<tbody>


<?php

$i=1;


while($row=mysqli_fetch_assoc($students)){


?>


<tr>


<td>

<?php echo $i++; ?>

</td>


<td>

<?php echo htmlspecialchars($row["register_number"]); ?>

</td>


<td>

<?php echo htmlspecialchars($row["student_name"]); ?>

</td>


<td>


<input

type="number"

name="marks[<?php echo $row['student_id']; ?>]"

min="0"

required>


</td>


</tr>


<?php } ?>


</tbody>


</table>


</div>



<br>


<button class="save-btn">

<i class="fa-solid fa-save"></i>

Save Marks

</button>



</form>



</div>


</div>


</body>


</html>