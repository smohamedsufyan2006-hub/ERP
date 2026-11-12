<?php

// =========================================
// START SESSION
// =========================================

session_start();

// =========================================
// CHECK ADMIN LOGIN
// =========================================

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");
    exit();

}

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK SUBJECT ID
// =========================================

if (!isset($_GET["subject_id"])) {

    header("Location: manage_subject.php");
    exit();

}

$subject_id = $_GET["subject_id"];

// =========================================
// FETCH SUBJECT
// =========================================

$sql = "SELECT *
        FROM subjects
        WHERE subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $subject_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    die("Subject not found.");

}

$subject = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Update Subject

</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="admin.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="form-card">

<!-- ========================================= -->
<!-- PAGE HEADER -->
<!-- ========================================= -->

<div class="page-header">

    <a href="manage_subject.php"

       class="back-btn">

        <i class="fa-solid fa-arrow-left"></i>

    </a>

    <h1>

        Update Subject

    </h1>

</div>

<!-- ========================================= -->
<!-- FORM -->
<!-- ========================================= -->

<form

action="update_subject_process.php"

method="POST"

class="student-form">

<input
type="hidden"
name="subject_id"
value="<?php echo $subject["subject_id"]; ?>">

<div class="form-grid">

<!-- SUBJECT CODE -->

<div class="form-group">

<label>

Subject Code <span class="required">*</span>

</label>

<input

type="text"

name="subject_code"

value="<?php echo htmlspecialchars($subject["subject_code"]); ?>"

required>

</div>

<!-- SUBJECT NAME -->

<div class="form-group">

<label>

Subject Name <span class="required">*</span>

</label>

<input

type="text"

name="subject_name"

value="<?php echo htmlspecialchars($subject["subject_name"]); ?>"

required>

</div>

<!-- CREDITS -->

<div class="form-group">

<label>

Credits <span class="required">*</span>

</label>

<input

type="number"

name="credits"

min="1"

max="10"

value="<?php echo $subject["credits"]; ?>"

required>

</div>

</div>

<div class="form-buttons">

<button

type="submit"

class="save-btn">

Update

</button>

<a

href="manage_subject.php"

class="cancel-btn">

Cancel

</a>

</div>

</form>

</div>

</body>

</html>

<?php

mysqli_close($conn);

?>