# 🎓 Student Portal System (PHP + MySQL)

A web-based Student Portal built with **PHP**, **MySQL**, and **Bootstrap**.  
It supports multiple roles: **Admin, Student, Teacher, and Program Coordinator**.

---

## 🚀 Features

### 🔐 Unified Login
- Single login page for all roles (Admin, Student, Teacher, Coordinator).
- Role decides which dashboard user is redirected to.

### 👨‍💼 Admin Module
- Upload Students (via CSV)
- Upload Courses (via CSV)
- Upload Academic Records (via CSV)
- Upload Teacher Courses (via CSV)
- View Students (with pagination, delete, filter by semester)
- View Courses (search, filter, prerequisites)
- View Enrollments (all students’ enrollments)
- View Academic Records
- View Teacher Courses

### 🎓 Student Module
- View Available Courses (filtered by semester)
- Enroll in Courses
- View My Enrollments
- View My Academic Record

### 👨‍🏫 Teacher Module
- Dashboard for teachers
- View Assigned Courses
- Manage Grades (update student academic records for their courses)

### 🧑‍💼 Coordinator Module
- Dashboard for coordinators
- (Extendable for program/course management)

---

## 🛠️ Installation

### 1. Clone Repository
```bash
cd C:\xampp\htdocs\
git clone https://github.com/bhatti59-dev/Student.git student_portal
```

Or if you already created repo:
```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/bhatti59-dev/Student.git
git push -u origin main
```

---

### 2. Setup Database
1. Open **phpMyAdmin** → Create a new database:
   ```sql
   CREATE DATABASE student_portal_db;
   ```
2. Import the SQL schema from `database.sql` (provided in this repo).  
   It creates tables:
   - `users` (login table)
   - `student`
   - `course`
   - `enrollment`
   - `academic_record`
   - `teacher`
   - `teacher_courses`
   - `program`

---

### 3. Configure Database Connection
Edit file:  
```
student_portal/includes/db_connection.php
```
Set your MySQL details:
```php
<?php
$host = "localhost";
$user = "root";   // default XAMPP user
$pass = "";       // default XAMPP password is empty
$db   = "student_portal_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

### 4. Run Project
1. Start **Apache** and **MySQL** in XAMPP.
2. Open browser:
   ```
   http://localhost/student_portal/
   ```
3. Login using credentials stored in the `users` table. Example:
   - **Admin** → username: `admin1` | password: `admin123`
   - **Student** → username: `S001` | password: `pass123`
   - **Teacher** → username: `teacher1` | password: `teach123`
   - **Coordinator** → username: `coord1` | password: `coord123`

---

## 📂 Project Structure

```
student_portal/
│── admin/
│   ├── dashboard.php
│   ├── upload_students.php
│   ├── upload_courses.php
│   ├── upload_academic_record.php
│   ├── upload_teacher_courses.php
│   ├── view_students.php
│   ├── view_courses.php
│   ├── view_enrollments.php
│   ├── view_academic_records.php
│   └── view_teacher_courses.php
│
│── student/
│   ├── dashboard.php
│   ├── enroll.php
│   ├── my_courses.php
│   ├── view_courses.php
│   └── view_academic_records.php
│
│── teacher/
│   ├── dashboard.php
│   ├── view_courses.php
│   └── manage_grades.php
│
│── coordinator/
│   ├── dashboard.php
│   └── manage_program.php
│
│── includes/
│   └── db_connection.php
│
│── index.php   # Unified Login
│── logout.php
│── README.md
│── database.sql
```

---

## 🧪 Testing
1. Login as **Admin** → Upload students & courses via CSV.
2. Login as **Student** → Enroll in courses → Check in Admin view.
3. Login as **Teacher** → See assigned courses → Manage grades.
4. Login as **Coordinator** → Manage programs.

---

## 📌 Notes
- Default passwords are stored in plain text for testing. Use `password_hash()` + `password_verify()` in production.
- All CSV upload formats are included in `/samples/`.
