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

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>

Add Subject

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

       class="back-btn"

       title="Back">

        <i class="fa-solid fa-arrow-left"></i>

    </a>

    <h1>

        Add Subject

    </h1>

</div>

<!-- ========================================= -->
<!-- FORM -->
<!-- ========================================= -->

<form

action="add_subject_process.php"

method="POST"

class="student-form">

<div class="form-grid">

<!-- SUBJECT CODE -->

<div class="form-group">

<label>

Subject Code 

</label>

<input

type="text"

name="subject_code"

maxlength="20"

placeholder="Enter Subject Code"

required>

</div>

<!-- SUBJECT NAME -->

<div class="form-group">

<label>

Subject Name 

</label>

<input

type="text"

name="subject_name"

maxlength="150"

placeholder="Enter Subject Name"

required>

</div>

<!-- CREDITS -->

<div class="form-group">

<label>

Credits 

</label>

<input

type="number"

name="credits"

min="1"

max="10"

value="3"

required>

</div>

</div>

<!-- BUTTONS -->

<div class="form-buttons">

<button

type="submit"

class="save-btn">

Save

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