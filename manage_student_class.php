<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");
    exit();

}

require "database.php";


// Fetch students with class details

$sql = "

SELECT 

s.student_id,

u.register_number,

s.student_name,

d.department_name,

c.class_id,

c.year_of_study,

c.section


FROM students s


JOIN user u

ON s.user_id = u.user_id


LEFT JOIN departments d

ON s.department_id = d.department_id


LEFT JOIN classes c

ON s.class_id = c.class_id


ORDER BY s.student_id DESC

";


$result = mysqli_query($conn,$sql);


?>


<!DOCTYPE html>

<html>

<head>

<title>Manage Student Class</title>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>


body{

    font-family: Arial, sans-serif;

    background:#f4f6f9;

}


.container{

    width:90%;

    margin:30px auto;

}


.card{

    background:white;

    padding:20px;

    border-radius:10px;

    box-shadow:0 0 10px #ddd;

}


.header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:20px;

}


.header h2{

    margin:0;

}



.back-btn{

    text-decoration:none;

    color:#333;

    font-size:22px;

    margin-right:10px;

}



.add-btn{

    background:#007bff;

    color:white;

    padding:10px 15px;

    border-radius:6px;

    text-decoration:none;

}



table{

    width:100%;

    border-collapse:collapse;

}



th{

    background:#f1f1f1;

}



th,td{

    padding:12px;

    text-align:center;

    border-bottom:1px solid #ddd;

}



.action a{

    text-decoration:none;

    padding:6px 10px;

    border-radius:5px;

    margin:2px;

}



.assign{

    background:#28a745;

    color:white;

}



.edit{

    background:#ffc107;

    color:black;

}



.remove{

    background:#dc3545;

    color:white;

}


</style>


</head>


<body>


<div class="container">


<div class="card">


<div class="header">


<div>

<a href="admin_dashboard.php" class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

</a>


<h2 style="display:inline">

Manage Student Class

</h2>

</div>



<a href="assign_student_class.php" class="add-btn">

<i class="fa-solid fa-plus"></i>

Assign Class

</a>


</div>




<table>


<tr>

<th>S.No</th>

<th>Register No</th>

<th>Student Name</th>

<th>Department</th>

<th>Current Class</th>

<th>Action</th>

</tr>



<?php


$i=1;


while($row=mysqli_fetch_assoc($result))

{


?>


<tr>


<td>

<?php echo $i++; ?>

</td>


<td>

<?php echo $row['register_number']; ?>

</td>


<td>

<?php echo $row['student_name']; ?>

</td>


<td>

<?php echo $row['department_name']; ?>

</td>



<td>


<?php


if($row['class_id'] == NULL)

{

    echo "Not Assigned";

}

else

{

    echo "Year ".$row['year_of_study']." - ".$row['section'];

}


?>


</td>



<td class="action">


<?php


if($row['class_id']==NULL)

{


?>

<a class="assign"

href="assign_student_class.php?student_id=<?php echo $row['student_id']; ?>">


Assign

</a>


<?php

}

else

{


?>


<a class="edit"

href="update_student_class.php?student_id=<?php echo $row['student_id']; ?>">


Change

</a>



<a class="remove"

onclick="return confirm('Remove class assignment?')"

href="remove_student_class.php?student_id=<?php echo $row['student_id']; ?>">


Remove

</a>



<?php


}


?>


</td>


</tr>


<?php

}


?>


</table>



</div>


</div>


</body>

</html>