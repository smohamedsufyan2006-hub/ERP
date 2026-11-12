<?php

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Teacher Registration</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="container">

<h1>Teacher Registration</h1>

<form action="teacher_process.php"
      method="POST"
      enctype="multipart/form-data">

<!-- ========================================= -->
<!-- PERSONAL INFORMATION -->
<!-- ========================================= -->

<h2 class="form-section">

Personal Information

</h2>

<label>Teacher Name</label>

<input
type="text"
name="teacher_name"
placeholder="Enter Teacher Name"
required>

<label>Employee ID</label>

<input
type="text"
name="employee_id"
placeholder="Enter Employee ID"
required>

<label>Email</label>

<input
type="email"
name="email"
placeholder="Enter Email Address"
required>

<label>Phone Number</label>

<input
type="text"
name="phone"
placeholder="Enter Phone Number"
required>

<label>Gender</label>

<select
name="gender"
required>

<option value="">

Select Gender

</option>

<?php

$result = mysqli_query(

$conn,

"SELECT * FROM genders"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["gender_id"]; ?>">

<?php echo htmlspecialchars($row["gender_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Date of Birth</label>

<input
type="date"
name="date_of_birth"
required>
<!-- ========================================= -->
<!-- PROFESSIONAL INFORMATION -->
<!-- ========================================= -->

<h2 class="form-section">

Professional Information

</h2>

<label>Staff Category</label>

<select
name="staff_category"
required>

<option value="">

Select Staff Category

</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM staff_categories ORDER BY staff_category_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["staff_category_id"]; ?>">

<?php echo htmlspecialchars($row["staff_category_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Designation</label>

<select
name="designation"
required>

<option value="">

Select Designation

</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM designations ORDER BY designation_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["designation_id"]; ?>">

<?php echo htmlspecialchars($row["designation_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Department</label>

<select
name="department"
required>

<option value="">

Select Department

</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM departments ORDER BY department_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["department_id"]; ?>">

<?php echo htmlspecialchars($row["department_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Joining Date</label>

<input
type="date"
name="joining_date"
required>

<label>Employment Status</label>

<select
name="status"
required>

<option value="">

Select Status

</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM teacher_status ORDER BY status_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["status_id"]; ?>">

<?php echo htmlspecialchars($row["status_name"]); ?>

</option>

<?php

}

?>

</select>
<!-- ========================================= -->
<!-- ADDRESS DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Address Details

</h2>

<label>Permanent Address</label>

<textarea
name="permanent_address"
rows="4"
placeholder="Enter Permanent Address"
required></textarea>

<label>Temporary Address</label>

<textarea
name="temporary_address"
rows="4"
placeholder="Enter Temporary Address"></textarea>

<!-- ========================================= -->
<!-- EMERGENCY CONTACT -->
<!-- ========================================= -->

<h2 class="form-section">

Emergency Contact

</h2>

<label>Emergency Contact Name</label>

<input
type="text"
name="emergency_name"
placeholder="Enter Emergency Contact Name"
required>

<label>Relationship</label>

<select
name="relationship"
required>

<option value="">

Select Relationship

</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM relationships ORDER BY relationship_name"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["relationship_id"]; ?>">

<?php echo htmlspecialchars($row["relationship_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Emergency Contact Number</label>

<input
type="text"
name="emergency_phone"
placeholder="Enter Emergency Contact Number"
required>
<!-- ========================================= -->
<!-- QUALIFICATIONS -->
<!-- ========================================= -->

<!-- ========================================= -->
<!-- QUALIFICATIONS -->
<!-- ========================================= -->

<h2 class="form-section">

Educational Qualifications

</h2>

<div class="table-container">
<table class="siblings-table">

<thead>

<tr>

<th>#</th>

<th>Qualification</th>

<th>Specialization</th>

<th>Institution</th>

<th>University</th>

<th>Percentage / CGPA</th>

<th>Year</th>

<th>Action</th>

</tr>

</thead>


<tbody id="qualification_container">


<tr>

<td>1</td>


<td>

<select name="qualification[]" required>

<option value="">Select</option>

<?php

$result=mysqli_query(
$conn,
"SELECT * FROM qualifications ORDER BY qualification_name"
);


while($row=mysqli_fetch_assoc($result)){

echo "

<option value='{$row['qualification_id']}'>
{$row['qualification_name']}
</option>

";

}

?>

</select>

</td>


<td>

<input
type="text"
name="specialization[]">

</td>


<td>

<input
type="text"
name="institution[]"
required>

</td>


<td>

<input
type="text"
name="university[]"
required>

</td>


<td>

<input
type="text"
name="percentage[]"
required>

</td>


<td>

<input
type="number"
name="passing_year[]"
min="1950"
max="<?php echo date('Y'); ?>"
required>

</td>


<td>

<button
type="button"
class="remove-btn"
onclick="removeQualification(this)">

<i class="fa-solid fa-trash"></i>

</button>

</td>


</tr>


</tbody>
</table>
</div>

<br>


<button
type="button"
class="add-btn"
onclick="addQualification()">

+ Add Another Qualification

</button>
<!-- ========================================= -->
<!-- WORK EXPERIENCE -->
<!-- ========================================= -->

<!-- ========================================= -->
<!-- EXPERIENCE -->
<!-- ========================================= -->

<h2 class="form-section">

Teaching / Work Experience

</h2>


<div class="table-container">
<table class="siblings-table">

<thead>

<tr>

<th>#</th>

<th>Organization</th>

<th>Designation</th>

<th>From Date</th>

<th>To Date</th>

<th>Experience Type</th>

<th>Action</th>

</tr>

</thead>


<tbody id="experience_container">


<tr>

<td>1</td>


<td>

<input
type="text"
name="experience_institution[]">

</td>


<td>

<input
type="text"
name="experience_designation[]">

</td>


<td>

<input
type="date"
name="experience_from[]">

</td>


<td>

<input
type="date"
name="experience_to[]">

</td>


<td>

<select name="experience_type[]">

<option value="Teaching">
Teaching
</option>

<option value="Industry">
Industry
</option>

<option value="Research">
Research
</option>

<option value="Administrative">
Administrative
</option>

<option value="Other">
Other
</option>


</select>

</td>


<td>

<button
type="button"
class="remove-btn"
onclick="removeExperience(this)">

<i class="fa-solid fa-trash"></i>

</button>

</td>


</tr>


</tbody>

</table>
</div>


<br>


<button
type="button"
class="add-btn"
onclick="addExperience()">

+ Add Another Experience

</button>
<!-- ========================================= -->
<!-- DOCUMENTS -->
<!-- ========================================= -->

<h2 class="form-section">

Documents

</h2>

<label>Passport Size Photo</label>

<input
type="file"
name="photo"
accept=".jpg,.jpeg,.png">

<label>Aadhaar Card</label>

<input
type="file"
name="aadhaar"
accept=".pdf,.jpg,.jpeg,.png">

<label>PAN Card</label>

<input
type="file"
name="pan"
accept=".pdf,.jpg,.jpeg,.png">

<label>Highest Degree Certificate</label>

<input
type="file"
name="degree_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<label>Experience Certificate</label>

<input
type="file"
name="experience_certificate"
accept=".pdf,.jpg,.jpeg,.png">

<label>Other Supporting Document</label>

<input
type="file"
name="other_document"
accept=".pdf,.jpg,.jpeg,.png">

<!-- ========================================= -->
<!-- ACCOUNT DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Account Details

</h2>

<label>staff ID</label>

<input
type="text"
name="register_number"
placeholder="Enter Register Number"
required>

<label>Password</label>

<div class="password-box">

<input
type="password"
id="password"
name="password"
placeholder="Enter Password"
required>

<i
class="fa-solid fa-eye"
id="togglePassword">

</i>

</div>

<label>Confirm Password</label>

<div class="password-box">

<input
type="password"
id="confirm_password"
name="confirm_password"
placeholder="Confirm Password"
required>

<i
class="fa-solid fa-eye"
id="toggleConfirmPassword">

</i>

</div>

<!-- ========================================= -->
<!-- BUTTONS -->
<!-- ========================================= -->

<div class="button-group">

<button
type="submit"
class="btn register-btn">

Register Teacher

</button>

<button
type="reset"
class="btn login-btn">

Reset

</button>

</div>

</form>

</div>

<!-- ========================================= -->
<!-- PASSWORD SHOW / HIDE -->
<!-- ========================================= -->

<script>

function addQualification(){

let tbody=document.getElementById("qualification_container");

let rowNumber=tbody.rows.length+1;

let row=tbody.insertRow();


row.innerHTML=`

<td>${rowNumber}</td>


<td>

<select name="qualification[]" required>

<option value="">Select</option>

<?php

$result=mysqli_query(
$conn,
"SELECT * FROM qualifications ORDER BY qualification_name"
);

while($row=mysqli_fetch_assoc($result)){

echo "

<option value='{$row['qualification_id']}'>
{$row['qualification_name']}
</option>";

}

?>

</select>

</td>


<td>
<input type="text" name="specialization[]">
</td>


<td>
<input type="text" name="institution[]" required>
</td>


<td>
<input type="text" name="university[]" required>
</td>


<td>
<input type="text" name="percentage[]" required>
</td>


<td>
<input type="number" name="passing_year[]" required>
</td>


<td>

<button
type="button"
class="remove-btn"
onclick="removeQualification(this)">

<i class="fa-solid fa-trash"></i>

</button>

</td>

`;

}


function removeQualification(button){

button.closest("tr").remove();

updateQualificationNumbers();

}


function updateQualificationNumbers(){

let rows=document.querySelectorAll("#qualification_container tr");


rows.forEach(function(row,index){

row.cells[0].innerHTML=index+1;

});

}

function addExperience(){

let tbody=document.getElementById("experience_container");

let rowNumber=tbody.rows.length+1;

let row=tbody.insertRow();


row.innerHTML=`

<td>${rowNumber}</td>


<td>
<input type="text" name="experience_institution[]">
</td>


<td>
<input type="text" name="experience_designation[]">
</td>


<td>
<input type="date" name="experience_from[]">
</td>


<td>
<input type="date" name="experience_to[]">
</td>


<td>

<select name="experience_type[]">

<option>Teaching</option>

<option>Industry</option>

<option>Research</option>

<option>Administrative</option>

<option>Other</option>

</select>

</td>


<td>

<button
type="button"
class="remove-btn"
onclick="removeExperience(this)">

<i class="fa-solid fa-trash"></i>

</button>

</td>

`;

}



function removeExperience(button){

button.closest("tr").remove();

updateExperienceNumbers();

}



function updateExperienceNumbers(){

let rows=document.querySelectorAll("#experience_container tr");


rows.forEach(function(row,index){

row.cells[0].innerHTML=index+1;

});

}
const togglePassword =
document.getElementById("togglePassword");

const password =
document.getElementById("password");

togglePassword.addEventListener("click",function(){

    if(password.type==="password"){

        password.type="text";

        this.classList.remove("fa-eye");

        this.classList.add("fa-eye-slash");

    }

    else{

        password.type="password";

        this.classList.remove("fa-eye-slash");

        this.classList.add("fa-eye");

    }

});

const toggleConfirmPassword =
document.getElementById("toggleConfirmPassword");

const confirmPassword =
document.getElementById("confirm_password");

toggleConfirmPassword.addEventListener("click",function(){

    if(confirmPassword.type==="password"){

        confirmPassword.type="text";

        this.classList.remove("fa-eye");

        this.classList.add("fa-eye-slash");

    }

    else{

        confirmPassword.type="password";

        this.classList.remove("fa-eye-slash");

        this.classList.add("fa-eye");

    }

});

</script>

</body>

</html>