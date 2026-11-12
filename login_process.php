<?php

// =============================
// START SESSION
// =============================

session_start();

// =============================
// DATABASE CONNECTION
// =============================

require "database.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// =============================
// CHECK REQUEST METHOD
// =============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $register_number = trim($_POST["register_number"]);
    $password = $_POST["password"];

    // =============================
    // FIND USER
    // =============================

    $stmt = mysqli_prepare(

        $conn,

        "SELECT
            user_id,
            register_number,
            email,
            password,
            role_id
         FROM user
         WHERE register_number=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $register_number

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        // =============================
        // VERIFY PASSWORD
        // =============================

        if (password_verify($password, $user["password"])) {

            // =============================
            // COMMON SESSION
            // =============================

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["register_number"] = $user["register_number"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role_id"] = $user["role_id"];

            // =====================================================
            // STUDENT LOGIN
            // =====================================================

            if ($user["role_id"] == 1) {

                $stmt2 = mysqli_prepare(

                    $conn,

                    "SELECT
                        student_id,
                        student_name
                     FROM students
                     WHERE user_id=?"

                );

                mysqli_stmt_bind_param(

                    $stmt2,

                    "i",

                    $user["user_id"]

                );

                mysqli_stmt_execute($stmt2);

                $studentResult = mysqli_stmt_get_result($stmt2);

                if ($student = mysqli_fetch_assoc($studentResult)) {

                    $_SESSION["student_id"] = $student["student_id"];
                    $_SESSION["student_name"] = $student["student_name"];

                }

                mysqli_stmt_close($stmt2);

                header("Location: dashboard.php");
                exit();

            }

            // =====================================================
            // TEACHER LOGIN
            // =====================================================
// =====================================================
// TEACHER LOGIN
// =====================================================

else if ($user["role_id"] == 2) {

    $stmt2 = mysqli_prepare(

        $conn,

        "SELECT
            teacher_id,
            teacher_name
         FROM teachers
         WHERE user_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt2,

        "i",

        $user["user_id"]

    );

    mysqli_stmt_execute($stmt2);

    $teacherResult = mysqli_stmt_get_result($stmt2);

    if ($teacher = mysqli_fetch_assoc($teacherResult)) {

        $_SESSION["teacher_id"] = $teacher["teacher_id"];
        $_SESSION["teacher_name"] = $teacher["teacher_name"];

    } else {

        echo "<script>
                alert('Teacher record not found.');
                window.location='login.php';
              </script>";
        exit();

    }

    mysqli_stmt_close($stmt2);

    header("Location: teacher_dashboard.php");
    exit();

}
            // =====================================================
            // ADMIN LOGIN
            // =====================================================

            else if ($user["role_id"] == 3) {

                $_SESSION["admin_id"] = $user["user_id"];

                header("Location: admin_dashboard.php");
                exit();

            }

            else {

                echo "<script>

                    alert('Invalid User Role');

                    window.location='login.php';

                </script>";

            }

        } else {

            echo "<script>

                alert('Incorrect Password');

                window.location='login.php';

            </script>";

        }

    } else {

        echo "<script>

            alert('Register Number Not Found');

            window.location='login.php';

        </script>";

    }

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

}

?>