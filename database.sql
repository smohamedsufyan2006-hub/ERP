-- =========================================
-- Create Database
-- =========================================

-- =========================================
-- Delete Old Database (Optional)
-- =========================================

-- =========================================
-- STUDENT MANAGEMENT SYSTEM DATABASE
-- =========================================

-- =========================================
-- DELETE OLD DATABASE
-- =========================================

-- =========================================
-- DATABASE
-- ========================================
DROP DATABASE IF EXISTS student_management;
DROP DATABASE IF EXISTS student_management_system;

CREATE DATABASE student_management_system;
USE student_management_system;

-- =========================================
-- MASTER TABLES
-- =========================================

-- PROGRAMS (UG / PG)
CREATE TABLE programs (
    program_id INT AUTO_INCREMENT PRIMARY KEY,
    program_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO programs (program_name) VALUES
('UG'),
('PG');

-- DEPARTMENTS
CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE,
    program_id INT,

    FOREIGN KEY (program_id) REFERENCES programs(program_id)
);

INSERT INTO departments (department_name, program_id) VALUES
('B.Tech CSE', 1),
('B.Tech IT', 1),
('BCA', 1),
('B.Sc', 1),
('MBA', 2);

-- YEARS
CREATE TABLE years (
    year_id INT AUTO_INCREMENT PRIMARY KEY,
    year_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO years (year_name) VALUES
('1st Year'),
('2nd Year'),
('3rd Year'),
('4th Year');

-- GENDERS
CREATE TABLE genders (
    gender_id INT AUTO_INCREMENT PRIMARY KEY,
    gender_name VARCHAR(20) NOT NULL UNIQUE
);

INSERT INTO genders (gender_name) VALUES
('Male'),
('Female'),
('Other');

-- BOARDS
CREATE TABLE boards (
    board_id INT AUTO_INCREMENT PRIMARY KEY,
    board_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO boards (board_name) VALUES
('State Board'),
('CBSE'),
('ICSE'),
('IB');

-- RELATIONSHIPS
CREATE TABLE relationships (
    relationship_id INT AUTO_INCREMENT PRIMARY KEY,
    relationship_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO relationships (relationship_name) VALUES
('Father'),
('Mother'),
('Guardian'),
('Brother'),
('Sister'),
('Relative'),
('Uncle'),
('Aunt'),
('Grandfather'),
('Grandmother'),
('Other');

-- DOCUMENT TYPES
CREATE TABLE document_types (
    document_type_id INT AUTO_INCREMENT PRIMARY KEY,
    document_name VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO document_types (document_name) VALUES
('Photo'),
('10th Marksheet'),
('12th Marksheet'),
('Aadhaar'),
('Transfer Certificate'),
('Community Certificate');

-- =========================================
-- ROLES TABLE
-- =========================================
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (role_name) VALUES
('student'),
('teacher'),
('admin');

-- =========================================
-- USER TABLE (LOGIN SYSTEM)
-- =========================================
CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    register_number VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,

    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- =========================================
-- STUDENTS TABLE
-- =========================================
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    gender_id INT,
    date_of_birth DATE,
    department_id INT,
    year_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
    FOREIGN KEY (gender_id) REFERENCES genders(gender_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (year_id) REFERENCES years(year_id)
);

-- =========================================
-- STUDENT ADDRESS
-- =========================================
CREATE TABLE student_addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    permanent_address TEXT NOT NULL,
    temporary_address TEXT,

    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- =========================================
-- FAMILY DETAILS
-- =========================================
CREATE TABLE family_details (
    family_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    relationship_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    occupation VARCHAR(100),
    phone VARCHAR(15),

    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (relationship_id) REFERENCES relationships(relationship_id)
);

-- =========================================
-- SIBLINGS TABLE
-- =========================================
CREATE TABLE siblings (
    sibling_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    sibling_name VARCHAR(100) NOT NULL,
    relationship_id INT NOT NULL,

    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (relationship_id) REFERENCES relationships(relationship_id)
);

-- =========================================
-- EMERGENCY CONTACTS
-- =========================================
CREATE TABLE emergency_contacts (
    emergency_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    emergency_name VARCHAR(100) NOT NULL,
    relationship_id INT NOT NULL,
    emergency_phone VARCHAR(15) NOT NULL,

    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (relationship_id) REFERENCES relationships(relationship_id)
);

-- =========================================
-- ACADEMIC DETAILS
-- =========================================
CREATE TABLE academic_details (
    academic_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    qualification VARCHAR(20) NOT NULL,
    school_name VARCHAR(150),
    percentage DECIMAL(5,2),
    board_id INT,

    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (board_id) REFERENCES boards(board_id)
);

-- =========================================
-- DOCUMENTS TABLE
-- =========================================
CREATE TABLE student_documents (
    document_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    document_type_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,

    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (document_type_id) REFERENCES document_types(document_type_id)
);
-- =========================================
-- STAFF CATEGORIES
-- =========================================

CREATE TABLE staff_categories (

    staff_category_id INT AUTO_INCREMENT PRIMARY KEY,

    staff_category_name VARCHAR(100) NOT NULL UNIQUE

);

INSERT INTO staff_categories (staff_category_name)
VALUES

('Teaching Staff'),
('Administrative Staff'),
('Laboratory Staff'),
('Library Staff'),
('Office Staff'),
('Accounts Staff'),
('IT Staff'),
('Hostel Staff'),
('Sports Staff'),
('Maintenance Staff'),
('Security Staff');



-- =========================================
-- DESIGNATIONS
-- =========================================

CREATE TABLE designations (

    designation_id INT AUTO_INCREMENT PRIMARY KEY,

    staff_category_id INT NOT NULL,

    designation_name VARCHAR(100) NOT NULL,

    FOREIGN KEY (staff_category_id)
    REFERENCES staff_categories(staff_category_id)

);

INSERT INTO designations
(
    staff_category_id,
    designation_name
)
VALUES

-- =========================================
-- TEACHING STAFF
-- =========================================

(1,'Professor'),
(1,'Associate Professor'),
(1,'Assistant Professor'),
(1,'Senior Professor'),
(1,'Senior Lecturer'),
(1,'Lecturer'),
(1,'Teaching Fellow'),
(1,'Guest Faculty'),
(1,'Visiting Faculty'),
(1,'Adjunct Professor'),

-- =========================================
-- ADMINISTRATIVE STAFF
-- =========================================

(2,'Principal'),
(2,'Vice Principal'),
(2,'Dean'),
(2,'Registrar'),
(2,'Controller of Examinations'),
(2,'Head of Department'),
(2,'Academic Coordinator'),

-- =========================================
-- LABORATORY STAFF
-- =========================================

(3,'Lab Incharge'),
(3,'Senior Lab Assistant'),
(3,'Lab Assistant'),
(3,'Lab Technician'),
(3,'Workshop Instructor'),

-- =========================================
-- LIBRARY STAFF
-- =========================================

(4,'Chief Librarian'),
(4,'Librarian'),
(4,'Assistant Librarian'),
(4,'Library Assistant'),

-- =========================================
-- OFFICE STAFF
-- =========================================

(5,'Administrative Officer'),
(5,'Office Superintendent'),
(5,'Office Assistant'),
(5,'Receptionist'),
(5,'Clerk'),
(5,'Data Entry Operator'),

-- =========================================
-- ACCOUNTS STAFF
-- =========================================

(6,'Finance Officer'),
(6,'Senior Accountant'),
(6,'Accountant'),
(6,'Cashier'),

-- =========================================
-- IT STAFF
-- =========================================

(7,'System Administrator'),
(7,'Network Administrator'),
(7,'Software Developer'),
(7,'Web Developer'),
(7,'Technical Support Engineer'),

-- =========================================
-- HOSTEL STAFF
-- =========================================

(8,'Chief Warden'),
(8,'Hostel Warden'),
(8,'Assistant Warden'),

-- =========================================
-- SPORTS STAFF
-- =========================================

(9,'Physical Director'),
(9,'Sports Coach'),

-- =========================================
-- MAINTENANCE STAFF
-- =========================================

(10,'Maintenance Supervisor'),
(10,'Electrician'),
(10,'Plumber'),
(10,'Carpenter'),
(10,'Mechanic'),
(10,'Housekeeping Supervisor'),
(10,'Housekeeping Staff'),
(10,'Gardener'),
(10,'Store Keeper'),
(10,'Purchase Officer'),

-- =========================================
-- SECURITY STAFF
-- =========================================

(11,'Chief Security Officer'),
(11,'Security Officer'),
(11,'Security Guard');



-- =========================================
-- TEACHER STATUS
-- =========================================

CREATE TABLE teacher_status (

    status_id INT AUTO_INCREMENT PRIMARY KEY,

    status_name VARCHAR(50) UNIQUE

);

INSERT INTO teacher_status(status_name)
VALUES

('Active'),
('On Leave'),
('Suspended'),
('Transferred'),
('Retired'),
('Resigned');



-- =========================================
-- QUALIFICATIONS
-- =========================================

CREATE TABLE qualifications (

    qualification_id INT AUTO_INCREMENT PRIMARY KEY,

    qualification_name VARCHAR(100) UNIQUE

);

INSERT INTO qualifications(qualification_name)
VALUES

('SSLC'),
('HSC'),
('Diploma'),
('B.Sc'),
('B.Com'),
('B.A'),
('BCA'),
('B.E'),
('B.Tech'),
('M.Sc'),
('M.Com'),
('M.A'),
('MCA'),
('M.E'),
('M.Tech'),
('MBA'),
('M.Phil'),
('Ph.D'),
('Post Doctorate'),
('Other');



-- =========================================
-- DOCUMENT TYPES
-- =========================================

CREATE TABLE document_types (

    document_type_id INT AUTO_INCREMENT PRIMARY KEY,

    document_name VARCHAR(100) UNIQUE

);

INSERT INTO document_types(document_name)
VALUES

('Passport Photo'),
('Aadhaar Card'),
('PAN Card'),
('Driving License'),
('Voter ID'),
('Resume'),
('Degree Certificate'),
('PG Degree Certificate'),
('Experience Certificate'),
('Community Certificate'),
('Transfer Certificate'),
('Appointment Order'),
('Salary Certificate'),
('Bank Passbook'),
('Other');
-- =========================================
-- TEACHERS
-- =========================================

CREATE TABLE teachers (

    teacher_id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    teacher_name VARCHAR(100) NOT NULL,

    gender_id INT NOT NULL,

    date_of_birth DATE,

    phone VARCHAR(15),

    email VARCHAR(100),

    department_id INT NOT NULL,

    designation_id INT NOT NULL,

    joining_date DATE,

    experience INT DEFAULT 0,

    status_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES user(user_id),

    FOREIGN KEY (gender_id)
        REFERENCES genders(gender_id),

    FOREIGN KEY (department_id)
        REFERENCES departments(department_id),

    FOREIGN KEY (designation_id)
        REFERENCES designations(designation_id),

    FOREIGN KEY (status_id)
        REFERENCES teacher_status(status_id)

);




-- =========================================
-- TEACHER ADDRESSES
-- =========================================

CREATE TABLE teacher_addresses (

    teacher_address_id INT AUTO_INCREMENT PRIMARY KEY,

    teacher_id INT NOT NULL,

    permanent_address TEXT,

    temporary_address TEXT,

    FOREIGN KEY (teacher_id)
        REFERENCES teachers(teacher_id)
        ON DELETE CASCADE

);




-- =========================================
-- TEACHER QUALIFICATIONS
-- =========================================

CREATE TABLE teacher_qualifications (

    teacher_qualification_id INT AUTO_INCREMENT PRIMARY KEY,

    teacher_id INT NOT NULL,

    qualification_id INT NOT NULL,

    specialization VARCHAR(100),

    institution VARCHAR(150),

    university VARCHAR(150),

    year_of_passing YEAR,

    percentage_or_cgpa DECIMAL(5,2),

    FOREIGN KEY (teacher_id)
        REFERENCES teachers(teacher_id)
        ON DELETE CASCADE,

    FOREIGN KEY (qualification_id)
        REFERENCES qualifications(qualification_id)

);




-- =========================================
-- TEACHER EXPERIENCE
-- =========================================

CREATE TABLE teacher_experience (

    experience_id INT AUTO_INCREMENT PRIMARY KEY,

    teacher_id INT NOT NULL,

    organization VARCHAR(150),

    designation VARCHAR(100),

    from_date DATE,

    to_date DATE,

    experience_type VARCHAR(50),

    FOREIGN KEY (teacher_id)
        REFERENCES teachers(teacher_id)
        ON DELETE CASCADE

);




-- =========================================
-- TEACHER EMERGENCY CONTACTS
-- =========================================

CREATE TABLE teacher_emergency_contacts (

    emergency_contact_id INT AUTO_INCREMENT PRIMARY KEY,

    teacher_id INT NOT NULL,

    emergency_name VARCHAR(100),

    relationship_id INT,

    emergency_phone VARCHAR(15),

    FOREIGN KEY (teacher_id)
        REFERENCES teachers(teacher_id)
        ON DELETE CASCADE,

    FOREIGN KEY (relationship_id)
        REFERENCES relationships(relationship_id)

);




-- =========================================
-- TEACHER DOCUMENTS
-- =========================================

CREATE TABLE teacher_documents (

    document_id INT AUTO_INCREMENT PRIMARY KEY,

    teacher_id INT NOT NULL,

    document_type_id INT NOT NULL,

    file_name VARCHAR(255),

    uploaded_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (teacher_id)
        REFERENCES teachers(teacher_id)
        ON DELETE CASCADE,

    FOREIGN KEY (document_type_id)
        REFERENCES document_types(document_type_id)

);
CREATE TABLE classes (

    class_id INT AUTO_INCREMENT PRIMARY KEY,

    department_id INT NOT NULL,

    year_of_study TINYINT NOT NULL,

    section CHAR(1) NOT NULL,

    semester_id INT NOT NULL,

    academic_year_id INT NOT NULL,

    advisor_teacher_id INT DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_class_department
        FOREIGN KEY (department_id)
        REFERENCES departments(department_id),

    CONSTRAINT fk_class_semester
        FOREIGN KEY (semester_id)
        REFERENCES semesters(semester_id),

    CONSTRAINT fk_class_academic_year
        FOREIGN KEY (academic_year_id)
        REFERENCES academic_years(academic_year_id),

    CONSTRAINT fk_class_teacher
        FOREIGN KEY (advisor_teacher_id)
        REFERENCES teachers(teacher_id),

    UNIQUE (
        department_id,
        year_of_study,
        section,
        academic_year_id
    )

);
ALTER TABLE students
ADD class_id INT AFTER department_id;
ALTER TABLE students

ADD CONSTRAINT fk_student_class

FOREIGN KEY (class_id)

REFERENCES classes(class_id);
CREATE TABLE academic_years (

    academic_year_id INT AUTO_INCREMENT PRIMARY KEY,

    academic_year_name VARCHAR(20) NOT NULL UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
INSERT INTO academic_years (academic_year_name)
VALUES
('2025-2026'),
('2026-2027'),
('2027-2028'),
('2028-2029'),
('2029-2030');
CREATE TABLE semesters (

    semester_id INT AUTO_INCREMENT PRIMARY KEY,

    semester_name VARCHAR(30) NOT NULL UNIQUE

);
INSERT INTO semesters (semester_name)
VALUES
('Semester 1'),
('Semester 2'),
('Semester 3'),
('Semester 4'),
('Semester 5'),
('Semester 6'),
('Semester 7'),
('Semester 8');
ALTER TABLE classes
DROP COLUMN semester,
DROP COLUMN academic_year;
ALTER TABLE classes

ADD semester_id INT NOT NULL AFTER advisor_teacher_id,

ADD academic_year_id INT NOT NULL AFTER semester_id;
ALTER TABLE classes

ADD CONSTRAINT fk_class_semester

FOREIGN KEY (semester_id)

REFERENCES semesters(semester_id),

ADD CONSTRAINT fk_class_academic_year

FOREIGN KEY (academic_year_id)

REFERENCES academic_years(academic_year_id);
ALTER TABLE students
ADD COLUMN class_id INT NULL AFTER year_id;
ALTER TABLE students
ADD CONSTRAINT fk_students_class
FOREIGN KEY (class_id)
REFERENCES classes(class_id)
ON UPDATE CASCADE
ON DELETE SET NULL;
CREATE TABLE subjects (

    subject_id INT AUTO_INCREMENT PRIMARY KEY,

    subject_code VARCHAR(20) NOT NULL UNIQUE,

    subject_name VARCHAR(150) NOT NULL,

    credits INT NOT NULL DEFAULT 3,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
CREATE TABLE class_subjects (

    class_subject_id INT AUTO_INCREMENT PRIMARY KEY,

    class_id INT NOT NULL,

    subject_id INT NOT NULL,

    UNIQUE(class_id, subject_id),

    FOREIGN KEY (class_id)
        REFERENCES classes(class_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(subject_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);
CREATE TABLE teacher_class_subjects (

    teacher_class_subject_id INT AUTO_INCREMENT PRIMARY KEY,

    class_subject_id INT NOT NULL,

    teacher_id INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_teacher_class_subject
        FOREIGN KEY (class_subject_id)
        REFERENCES class_subjects(class_subject_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_teacher
        FOREIGN KEY (teacher_id)
        REFERENCES teachers(teacher_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_class_subject
        UNIQUE(class_subject_id)

);
CREATE TABLE attendance_sessions (

    attendance_session_id INT AUTO_INCREMENT PRIMARY KEY,

    teacher_class_subject_id INT NOT NULL,

    attendance_date DATE NOT NULL,

    period_number INT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_teacher_subject
        FOREIGN KEY (teacher_class_subject_id)
        REFERENCES teacher_class_subjects(teacher_class_subject_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_attendance_session
        UNIQUE (
            teacher_class_subject_id,
            attendance_date,
            period_number
        )

);
CREATE TABLE attendance (

    attendance_id INT AUTO_INCREMENT PRIMARY KEY,

    attendance_session_id INT NOT NULL,

    student_id INT NOT NULL,

    attendance_status ENUM('Present','Absent') NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_attendance_session
        FOREIGN KEY (attendance_session_id)
        REFERENCES attendance_sessions(attendance_session_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_attendance_student
        FOREIGN KEY (student_id)
        REFERENCES students(student_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_student_attendance
        UNIQUE (
            attendance_session_id,
            student_id
        )

);
CREATE TABLE mark_types(

    mark_type_id INT AUTO_INCREMENT PRIMARY KEY,

    mark_type_name VARCHAR(50) NOT NULL

);
INSERT INTO mark_types(mark_type_name)

VALUES

('Internal 1'),
('Internal 2'),
('Assignment'),
('Model Exam');
CREATE TABLE student_marks(

    mark_id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    teacher_class_subject_id INT NOT NULL,

    mark_type_id INT NOT NULL,

    marks_obtained INT NOT NULL,

    max_marks INT NOT NULL DEFAULT 100,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


    FOREIGN KEY(student_id)

    REFERENCES students(student_id),


    FOREIGN KEY(teacher_class_subject_id)

    REFERENCES teacher_class_subjects(teacher_class_subject_id),


    FOREIGN KEY(mark_type_id)

    REFERENCES mark_types(mark_type_id)

);
CREATE TABLE assignments (

assignment_id INT AUTO_INCREMENT PRIMARY KEY,

teacher_class_subject_id INT NOT NULL,

title VARCHAR(150) NOT NULL,

description TEXT,

due_date DATE NOT NULL,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


FOREIGN KEY (teacher_class_subject_id)

REFERENCES teacher_class_subjects(teacher_class_subject_id)

);
CREATE TABLE assignment_submissions (

submission_id INT AUTO_INCREMENT PRIMARY KEY,

assignment_id INT NOT NULL,

student_id INT NOT NULL,

file_name VARCHAR(255),

submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

status ENUM('Submitted','Pending') DEFAULT 'Pending',


FOREIGN KEY (assignment_id)

REFERENCES assignments(assignment_id),


FOREIGN KEY (student_id)

REFERENCES students(student_id)

);