<?php

// =========================================
// DATABASE CONNECTION
// =========================================

require "database.php";

// =========================================
// CHECK DEPARTMENT
// =========================================

if (!isset($_GET["department_id"])) {

    echo '<option value="">Select Department First</option>';
    exit();

}

$department_id = $_GET["department_id"];

// =========================================
// FETCH TEACHERS
// =========================================

$sql = "SELECT

            teacher_id,
            teacher_name

        FROM teachers

        WHERE department_id = ?

        ORDER BY teacher_name ASC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $department_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// =========================================
// DISPLAY OPTIONS
// =========================================

echo '<option value="">Select Class Advisor</option>';

if (mysqli_num_rows($result) > 0) {

    while ($teacher = mysqli_fetch_assoc($result)) {

        ?>

        <option value="<?php echo $teacher["teacher_id"]; ?>">

            <?php echo htmlspecialchars($teacher["teacher_name"]); ?>

        </option>

        <?php

    }

}
else {

    echo '<option value="">No Teachers Available</option>';

}

mysqli_stmt_close($stmt);

mysqli_close($conn);

?>