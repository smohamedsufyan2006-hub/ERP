
<?php

// =========================================
// SHOW ERRORS
// =========================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";


// =========================================
// CHECK LOGIN
// =========================================

// =========================================
// CHECK LOGIN
// =========================================

// =========================================
// CHECK LOGIN
// =========================================

if (isset($_SESSION["admin_id"]) && isset($_GET["student_id"])) {

    // Admin editing selected student
    $student_id = (int)$_GET["student_id"];

} elseif (isset($_SESSION["student_id"])) {

    // Student editing own profile
    $student_id = $_SESSION["student_id"];

} else {

    header("Location: login.php");
    exit();

}


// =========================================
// LOAD STUDENT + USER DETAILS
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT
        s.*,
        u.user_id,
        u.register_number,
        u.email,
        u.password
     FROM students s
     INNER JOIN user u
        ON s.user_id = u.user_id
     WHERE s.student_id=?"

);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Store user id
$user_id = $student["user_id"];

// =========================================
// LOAD ADDRESS
// =========================================

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM student_addresses
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$address = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// LOAD FAMILY DETAILS
// =========================================

$family = [];

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM family_details
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    $family[$row["relationship_id"]] = $row;

}

mysqli_stmt_close($stmt);

// =========================================
// LOAD EMERGENCY CONTACT
// =========================================

// =========================================
// LOAD EMERGENCY CONTACT
// =========================================

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM emergency_contacts
     WHERE student_id=?"
);

mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$emergency = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// =========================================
// LOAD ACADEMIC DETAILS
// =========================================

$academic = [];

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM academic_details
     WHERE student_id=?"
);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    // qualification contains '10th' or '12th'
    $academic[$row["qualification"]] = $row;

}

mysqli_stmt_close($stmt);

// =========================================
// LOAD SIBLINGS
// =========================================

$siblings = [];

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM siblings
     WHERE student_id=?"
);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    $siblings[] = $row;

}

mysqli_stmt_close($stmt);
// =========================================
// LOAD ACADEMIC DETAILS
// =========================================

$academic = [];

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM academic_details
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    $academic[$row["qualification"]] = $row;

}

mysqli_stmt_close($stmt);

// =========================================
// LOAD SIBLINGS
// =========================================

$siblings = [];

$stmt = mysqli_prepare(

    $conn,

    "SELECT *
     FROM siblings
     WHERE student_id=?"

);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    $siblings[] = $row;

}

mysqli_stmt_close($stmt);

// =========================================
// UPDATE PROFILE
// =========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    mysqli_begin_transaction($conn);

    try {

        // =====================================
        // STUDENT DETAILS
        // =====================================

        $student_name = trim($_POST["student_name"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);

        $program_id = intval($_POST["program_level"]);
        $gender_id = intval($_POST["gender"]);
        $department_id = intval($_POST["department"]);
        $year_id = intval($_POST["year_of_study"]);

        $date_of_birth = $_POST["date_of_birth"];

        // =====================================
        // ADDRESS
        // =====================================

        $permanent_address = trim($_POST["address"]);
        $temporary_address = trim($_POST["temporary_address"]);

        // =====================================
        // FAMILY
        // =====================================

        $father_name = trim($_POST["father_name"]);
        $father_occupation = trim($_POST["father_occupation"]);
        $father_phone = trim($_POST["father_phone"]);

        $mother_name = trim($_POST["mother_name"]);
        $mother_occupation = trim($_POST["mother_occupation"]);
        $mother_phone = trim($_POST["mother_phone"]);

        $guardian_name = trim($_POST["guardian_name"]);
        $guardian_phone = trim($_POST["guardian_phone"]);

        // =====================================
        // EMERGENCY
        // =====================================

        $emergency_name = trim($_POST["emergency_name"]);
        $relationship_id = intval($_POST["relationship"]);
        $emergency_phone = trim($_POST["emergency_phone"]);

        // =====================================
        // ACADEMICS
        // =====================================

        $tenth_school = trim($_POST["tenth_school"]);
        $tenth_marks = floatval($_POST["tenth_marks"]);

        $twelfth_school = trim($_POST["twelfth_school"]);
        $twelfth_marks = floatval($_POST["twelfth_marks"]);

        $board_id = intval($_POST["board"]);

        // =====================================
        // SIBLINGS
        // =====================================

        $has_siblings = $_POST["has_siblings"];

        $sibling_names = $_POST["sibling_name"] ?? [];

        $sibling_types = $_POST["sibling_type"] ?? [];
                // =====================================
        // UPDATE USER TABLE
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE user
             SET email=?
             WHERE user_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "si",

            $email,
            $user_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE STUDENTS TABLE
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE students
             SET
                student_name=?,
                phone=?,
                gender_id=?,
                date_of_birth=?,
                department_id=?,
                year_id=?,
                program_id=?
             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssisiiii",

            $student_name,
            $phone,
            $gender_id,
            $date_of_birth,
            $department_id,
            $year_id,
            $program_id,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE ADDRESS
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE student_addresses
             SET
                permanent_address=?,
                temporary_address=?
             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssi",

            $permanent_address,
            $temporary_address,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE FATHER
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE family_details
             SET
                name=?,
                occupation=?,
                phone=?
             WHERE student_id=?
             AND relationship_id=1"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssi",

            $father_name,
            $father_occupation,
            $father_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE MOTHER
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE family_details
             SET
                name=?,
                occupation=?,
                phone=?
             WHERE student_id=?
             AND relationship_id=2"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sssi",

            $mother_name,
            $mother_occupation,
            $mother_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE GUARDIAN
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE family_details
             SET
                name=?,
                occupation=NULL,
                phone=?
             WHERE student_id=?
             AND relationship_id=3"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "ssi",

            $guardian_name,
            $guardian_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE EMERGENCY CONTACT
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE emergency_contacts
             SET
                emergency_name=?,
                relationship_id=?,
                emergency_phone=?
             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sisi",

            $emergency_name,
            $relationship_id,
            $emergency_phone,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
                // =====================================
        // UPDATE 10TH ACADEMIC DETAILS
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE academic_details
             SET
                school_name=?,
                percentage=?,
                board_id=?
             WHERE student_id=?
             AND qualification='10th'"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sdii",

            $tenth_school,
            $tenth_marks,
            $board_id,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // UPDATE 12TH ACADEMIC DETAILS
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "UPDATE academic_details
             SET
                school_name=?,
                percentage=?,
                board_id=?
             WHERE student_id=?
             AND qualification='12th'"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "sdii",

            $twelfth_school,
            $twelfth_marks,
            $board_id,
            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // DELETE OLD SIBLINGS
        // =====================================

        $stmt = mysqli_prepare(

            $conn,

            "DELETE FROM siblings
             WHERE student_id=?"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "i",

            $student_id

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        // =====================================
        // INSERT UPDATED SIBLINGS
        // =====================================

        if ($has_siblings == "Yes") {

            $stmt = mysqli_prepare(

                $conn,

                "INSERT INTO siblings
                (
                    student_id,
                    sibling_name,
                    relationship_id
                )
                VALUES
                (?, ?, ?)"

            );

            for ($i = 0; $i < count($sibling_names); $i++) {

                $name = trim($sibling_names[$i]);

                $type = intval($sibling_types[$i]);

                if ($name != "" && $type != 0) {

                    mysqli_stmt_bind_param(

                        $stmt,

                        "isi",

                        $student_id,
                        $name,
                        $type

                    );

                    mysqli_stmt_execute($stmt);

                }

            }

            mysqli_stmt_close($stmt);

        }

        // =====================================
        // COMMIT TRANSACTION
        // =====================================

        mysqli_commit($conn);

        echo "<script>

            alert('Profile Updated Successfully.');

            window.location='manage_student.php';

        </script>";

        exit();

    }

    catch (Exception $e) {

        mysqli_rollback($conn);

        die(
            "Update Failed : " .
            $e->getMessage()
        );

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Update Student Profile</title>

    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="container">

<h1>Update Student Profile</h1>

<form method="POST">

<!-- ========================================= -->
<!-- PERSONAL INFORMATION -->
<!-- ========================================= -->

<h2 class="form-section">

    Personal Information

</h2>

<label>Student Name</label>

<input
type="text"
name="student_name"
value="<?php echo htmlspecialchars($student["student_name"]); ?>"
required>

<label>Program</label>

<select
name="program_level"
required>

<option value="">Select Program</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM programs"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["program_id"]; ?>"

<?php

if($student["program_id"] == $row["program_id"])
echo "selected";

?>

>

<?php echo htmlspecialchars($row["program_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($student["email"]); ?>"
required>

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?php echo htmlspecialchars($student["phone"]); ?>"
required>
<!-- ========================================= -->
<!-- PERSONAL INFORMATION CONTINUED -->
<!-- ========================================= -->

<label>Gender</label>

<select
name="gender"
required>

<option value="">Select Gender</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM genders"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["gender_id"]; ?>"

<?php

if($student["gender_id"] == $row["gender_id"])
echo "selected";

?>

>

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
value="<?php echo htmlspecialchars($student["date_of_birth"]); ?>"
required>

<label>Department</label>

<select
name="department"
required>

<option value="">Select Department</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM departments"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["department_id"]; ?>"

<?php

if($student["department_id"] == $row["department_id"])
echo "selected";

?>

>

<?php echo htmlspecialchars($row["department_name"]); ?>

</option>

<?php

}

?>

</select>

<label>Year of Study</label>

<select
name="year_of_study"
required>

<option value="">Select Year</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT * FROM years"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["year_id"]; ?>"

<?php

if($student["year_id"] == $row["year_id"])
echo "selected";

?>

>

<?php echo htmlspecialchars($row["year_name"]); ?>

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
name="address"
rows="4"
required><?php echo htmlspecialchars($address["permanent_address"] ?? ""); ?></textarea>

<label>Temporary Address</label>

<textarea
name="temporary_address"
rows="4"><?php echo htmlspecialchars($address["temporary_address"] ?? ""); ?></textarea>
<!-- ========================================= -->
<!-- PARENT / GUARDIAN DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Parent / Guardian Details

</h2>

<label>Father's Name</label>

<input
type="text"
name="father_name"
value="<?php echo htmlspecialchars($family[1]["name"] ?? ""); ?>"
required>

<label>Father's Occupation</label>

<input
type="text"
name="father_occupation"
value="<?php echo htmlspecialchars($family[1]["occupation"] ?? ""); ?>"
required>

<label>Father's Phone Number</label>

<input
type="text"
name="father_phone"
value="<?php echo htmlspecialchars($family[1]["phone"] ?? ""); ?>"
required>

<label>Mother's Name</label>

<input
type="text"
name="mother_name"
value="<?php echo htmlspecialchars($family[2]["name"] ?? ""); ?>"
required>

<label>Mother's Occupation</label>

<input
type="text"
name="mother_occupation"
value="<?php echo htmlspecialchars($family[2]["occupation"] ?? ""); ?>"
required>

<label>Mother's Phone Number</label>

<input
type="text"
name="mother_phone"
value="<?php echo htmlspecialchars($family[2]["phone"] ?? ""); ?>"
required>

<label>Guardian Name</label>

<input
type="text"
name="guardian_name"
value="<?php echo htmlspecialchars($family[3]["name"] ?? ""); ?>">

<label>Guardian Phone Number</label>

<input
type="text"
name="guardian_phone"
value="<?php echo htmlspecialchars($family[3]["phone"] ?? ""); ?>">

<!-- ========================================= -->
<!-- SIBLING DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Sibling Details

</h2>

<label>Do you have siblings?</label>

<div class="gender">

<label>

<input
type="radio"
name="has_siblings"
id="siblings_yes"
value="Yes"

<?php if(count($siblings) > 0) echo "checked"; ?>

onclick="toggleSiblingSection()">

Yes

</label>

<label>

<input
type="radio"
name="has_siblings"
id="siblings_no"
value="No"

<?php if(count($siblings) == 0) echo "checked"; ?>

onclick="toggleSiblingSection()">

No

</label>

</div>

<div
id="siblings_section"

style="<?php if(count($siblings)==0) echo 'display:none;'; ?>">

<div id="siblings_container">

<?php

if(count($siblings) > 0){

foreach($siblings as $sibling){

?>

<div class="sibling-card">

<label>Sibling Name</label>

<input
type="text"
name="sibling_name[]"
value="<?php echo htmlspecialchars($sibling["sibling_name"]); ?>">

<label>Relationship</label>

<select name="sibling_type[]">

<option value="">Select</option>

<?php

$result = mysqli_query(

    $conn,

    "SELECT *
     FROM relationships
     WHERE relationship_name IN ('Brother','Sister')"

);

while($row = mysqli_fetch_assoc($result)){

?>

<option

value="<?php echo $row["relationship_id"]; ?>"

<?php

if($row["relationship_id"] == $sibling["relationship_id"])
echo "selected";

?>

>

<?php echo htmlspecialchars($row["relationship_name"]); ?>

</option>

<?php

}

?>

</select>

<button
type="button"
onclick="this.parentElement.remove();">

Remove

</button>

</div>

<?php

}

}

?>

</div>

<br>

<button
type="button"
onclick="addSibling()">

+ Add Another Sibling

</button>

</div>
<!-- ========================================= -->
<!-- PARENT / GUARDIAN DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Parent / Guardian Details

</h2>

<label>Father's Name</label>

<input
type="text"
name="father_name"
value="<?php echo htmlspecialchars($family[1]["name"] ?? ""); ?>"
required>

<label>Father's Occupation</label>

<input
type="text"
name="father_occupation"
value="<?php echo htmlspecialchars($family[1]["occupation"] ?? ""); ?>"
required>

<label>Father's Phone Number</label>

<input
type="text"
name="father_phone"
value="<?php echo htmlspecialchars($family[1]["phone"] ?? ""); ?>"
required>

<label>Mother's Name</label>

<input
type="text"
name="mother_name"
value="<?php echo htmlspecialchars($family[2]["name"] ?? ""); ?>"
required>

<label>Mother's Occupation</label>

<input
type="text"
name="mother_occupation"
value="<?php echo htmlspecialchars($family[2]["occupation"] ?? ""); ?>"
required>

<label>Mother's Phone Number</label>

<input
type="text"
name="mother_phone"
value="<?php echo htmlspecialchars($family[2]["phone"] ?? ""); ?>"
required>

<label>Guardian Name</label>

<input
type="text"
name="guardian_name"
value="<?php echo htmlspecialchars($family[3]["name"] ?? ""); ?>">

<label>Guardian Phone Number</label>

<input
type="text"
name="guardian_phone"
value="<?php echo htmlspecialchars($family[3]["phone"] ?? ""); ?>">

<!-- ========================================= -->
<!-- SIBLING DETAILS -->
<!-- ========================================= -->

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
value="<?php echo htmlspecialchars($emergency["emergency_name"] ?? ""); ?>"
required>

<label>Relationship</label>

<select
name="relationship"
required>

<option value="">Select Relationship</option>

<?php

$result = mysqli_query(
    $conn,
    "SELECT * FROM relationships"
);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["relationship_id"]; ?>"

<?php

if(($emergency["relationship_id"] ?? "") == $row["relationship_id"])
echo "selected";

?>

>

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
value="<?php echo htmlspecialchars($emergency["emergency_phone"] ?? ""); ?>"
required>

<!-- ========================================= -->
<!-- ACADEMIC DETAILS -->
<!-- ========================================= -->

<h2 class="form-section">

Academic Details

</h2>

<label>10th School Name</label>

<input
type="text"
name="tenth_school"
value="<?php echo htmlspecialchars($academic["10th"]["school_name"] ?? ""); ?>"
required>

<label>10th Percentage</label>

<input
type="number"
step="0.01"
name="tenth_marks"
value="<?php echo htmlspecialchars($academic["10th"]["percentage"] ?? ""); ?>"
required>

<label>12th School Name</label>

<input
type="text"
name="twelfth_school"
value="<?php echo htmlspecialchars($academic["12th"]["school_name"] ?? ""); ?>"
required>

<label>12th Percentage</label>

<input
type="number"
step="0.01"
name="twelfth_marks"
value="<?php echo htmlspecialchars($academic["12th"]["percentage"] ?? ""); ?>"
required>

<label>Board</label>

<select
name="board"
required>

<option value="">Select Board</option>

<?php

$result = mysqli_query(
    $conn,
    "SELECT * FROM boards"
);

while($row = mysqli_fetch_assoc($result)){

?>

<option
value="<?php echo $row["board_id"]; ?>"

<?php

if(($academic["10th"]["board_id"] ?? "") == $row["board_id"])
echo "selected";

?>

>

<?php echo htmlspecialchars($row["board_name"]); ?>

</option>

<?php

}

?>

</select>

<!-- ========================================= -->
<!-- DOCUMENTS -->
<!-- ========================================= -->

<h2 class="form-section">

Documents

</h2>

<p style="color:green;">

Your uploaded documents are preserved.
Document replacement can be added later.

</p>

<!-- ========================================= -->
<!-- ACCOUNT -->
<!-- ========================================= -->

<h2 class="form-section">

Account

</h2>

<p style="color:green;">

Password change can be added later.

</p>

<br>

<div class="button-group">

<button type="submit">

Update Profile

</button>

<a href="manage_student.php">

<button
type="button">

Cancel

</button>

</a>

</div>

</form>

</div>

<script src="script.js"></script>

</body>

</html>