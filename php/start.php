<?php
//======================================================================
// CREATE THE DATABASE
//======================================================================

error_reporting(-1);
ini_set('display_errors', 'On');

/* the first connection to the database */
DEFINE('DB_HOST', "localhost");
DEFINE('DB_USER', "root");
DEFINE('DB_PASSWORD', "snuggle"); //Note: this should be your root password


try {
  $db_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD)
    OR die("Connection failed: " . $db_connection->connect_error);
} catch (Exception $e) {
  echo 'Caught exception: ',  $e->getMessage(), nl2br("\r\n");
}

/* Check if database is there or will create it */
$create_stmt = "CREATE DATABASE IF NOT EXISTS ugadvisor_db";
/* Check if database drop was sucessful */
if(mysqli_query($db_connection, $create_stmt)) {
	echo nl2br("Database was successfully created.\r\n");
} else {
	echo "Error dropping database: " . mysqli_error() . nl2br("\r\n");
}
$prep_stmt = $db_connection -> prepare($create_stmt);
$prep_stmt->execute();
$prep_stmt->close();

/* Change to the created database */
$db_connection->select_db("ugadvisor_db");

/* Drop all tables for clean install */
$db_connection->query('SET foreign_key_checks = 0');
if ($result = $db_connection->query("SHOW TABLES")) {
  while($row = $result->fetch_array(MYSQLI_NUM)) {
    $db_connection->query('DROP TABLE IF EXISTS '.$row[0]);
	}
	echo "Tables removed successfully." . nl2br("\r\n");
} else {
	echo "No tables were removed." . nl2br("\r\n");
}
$db_connection->query('SET foreign_key_checks = 1');


/* Salt used for seasoning */
$salt = 'graduate';

//-----------------------------------------------------
// Create Database Tables
//-----------------------------------------------------
echo "Table creation started." . nl2br("\r\n");

/* Role */
$create_role = $db_connection->prepare(
	"CREATE OR REPLACE TABLE role
  (role_id int NOT NULL AUTO_INCREMENT,
	role_type varchar(255) NOT NULL,
	PRIMARY KEY(role_id));");
$create_role->execute();
$create_role->close();

/* Status  */
$create_status = $db_connection->prepare(
	"CREATE OR REPLACE TABLE status
  (status_id int NOT NULL AUTO_INCREMENT,
  status_type varchar(255) NOT NULL,
	PRIMARY KEY(status_id));");
$create_status->execute();
$create_status->close();

/* State In Time */
$create_status = $db_connection->prepare(
	"CREATE OR REPLACE TABLE state
  (state_id int NOT NULL AUTO_INCREMENT,
  state_type varchar(255) NOT NULL,
	PRIMARY KEY(state_id));");
$create_status->execute();
$create_status->close();

/* Permission */
$create_permission = $db_connection->prepare(
	"CREATE OR REPLACE TABLE permission
	(permission_id int NOT NULL AUTO_INCREMENT,
	permission_type varchar(255) NOT NULL,
	PRIMARY KEY(permission_id));");
$create_permission->execute();
$create_permission->close();

/* Grade */
$create_grade = $db_connection->prepare(
	"CREATE OR REPLACE TABLE grade
  (grade_id int NOT NULL AUTO_INCREMENT,
  grade_type varchar(2) NOT NULL,
	PRIMARY KEY(grade_id));");
$create_grade->execute();
$create_grade->close();

/* Year */
$create_year = $db_connection->prepare(
	"CREATE OR REPLACE TABLE years
  (year_id int NOT NULL AUTO_INCREMENT,
  year_type int NOT NULL,
	PRIMARY KEY(year_id));");
$create_year->execute();
$create_year->close();

/* Semester */
$create_semester = $db_connection->prepare(
	"CREATE OR REPLACE TABLE semester
  (semester_id int NOT NULL AUTO_INCREMENT,
  semester_type varchar(255) NOT NULL,
	PRIMARY KEY(semester_id));");
$create_semester->execute();
$create_semester->close();

/* Take Status */
$create_semester = $db_connection->prepare(
	"CREATE OR REPLACE TABLE take_status
  (take_status_id int NOT NULL AUTO_INCREMENT,
  take_status_type varchar(255) NOT NULL,
	PRIMARY KEY(take_status_id));");
$create_semester->execute();
$create_semester->close();

/* Below Are Tables That Have Forigen Keys */
/* ------------------------------------------ */

/* Department */
$create_dept = $db_connection->prepare(
	"CREATE OR REPLACE TABLE department(
		dept_id varchar(4) NOT NULL,
		dept_name varchar(255) NOT NULL,
		creation_date timestamp,
		status_id int NOT NULL,
		PRIMARY KEY(dept_id),
		FOREIGN KEY(status_id) REFERENCES status(status_id));");
$create_dept->execute();
$create_dept->close();

/* Program */
$create_program = $db_connection->prepare(
	"CREATE OR REPLACE TABLE program
		(program_id int NOT NULL AUTO_INCREMENT,
		program_name varchar(255) NOT NULL,
		creation_date timestamp,
		dept_id varchar(4) NOT NULL,
		status_id int NOT NULL,
		PRIMARY KEY(program_id),
		FOREIGN KEY(dept_id) REFERENCES department(dept_id),
		FOREIGN KEY(status_id) REFERENCES status(status_id));");
	$create_program->execute();
	$create_program->close();

/* Below Are Tables Describe Users */
/* ------------------------------------------ */

/* User */
$create_user = $db_connection->prepare(
	"CREATE OR REPLACE TABLE user
	(user_id int NOT NULL AUTO_INCREMENT,
	username varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	email varchar(255),
	first_name varchar(255),
	last_name varchar(255),
	role_id int NOT NULL,
	creation_date timestamp,
	PRIMARY KEY(user_id),
	FOREIGN KEY(role_id) REFERENCES role(role_id));");
$create_user->execute();
$create_user->close();

/* Administrator */
$create_sysadmin = $db_connection->prepare(
	"CREATE OR REPLACE TABLE administrator
	(admin_id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
	PRIMARY KEY(admin_id),
  FOREIGN KEY(user_id) REFERENCES user(user_id));");
$create_sysadmin->execute();
$create_sysadmin->close();

/* Faculty */
$create_faculty = $db_connection->prepare(
	"CREATE OR REPLACE TABLE faculty
	(faculty_id int NOT NULL AUTO_INCREMENT,
	user_id int NOT NULL,
	dept_id varchar(4) NOT NULL,
  status_id int NOT NULL,
	PRIMARY KEY(faculty_id),
	FOREIGN KEY(user_id) REFERENCES user(user_id),
	FOREIGN KEY(dept_id) REFERENCES department(dept_id),
  FOREIGN KEY(status_id) REFERENCES status(status_id));");
$create_faculty->execute();
$create_faculty->close();

/* Student */
$create_student = $db_connection->prepare(
	"CREATE OR REPLACE TABLE student
	(student_id int NOT NULL AUTO_INCREMENT,
	user_id int NOT NULL,
	program_id int NOT NULL,
  status_id int NOT NULL,
	PRIMARY KEY(student_id),
	FOREIGN KEY(user_id) REFERENCES user(user_id),
	FOREIGN KEY(program_id) REFERENCES program(program_id),
  FOREIGN KEY(status_id) REFERENCES status(status_id));");
$create_student->execute();
$create_student->close();

/* Advisor */
$create_advisor = $db_connection->prepare(
	"CREATE OR REPLACE TABLE advisor
	(student_id int NOT NULL,
	faculty_id int NOT NULL,
	CONSTRAINT pk_advisor PRIMARY KEY(student_id, faculty_id),
	FOREIGN KEY(student_id) REFERENCES student(student_id),
	FOREIGN KEY(faculty_id) REFERENCES faculty(faculty_id));");
$create_advisor->execute();
$create_advisor->close();

/* ------------------------------------------ */
/* Courses and graduation maps */

/* Course */
$create_course = $db_connection->prepare(
	"CREATE OR REPLACE TABLE course
		(course_id int(6) NOT NULL,
    course_num int(4) NOT NULL,
		course_name varchar(255) NOT NULL,
		credits int,
		dept_id varchar(4) NOT NULL,
		status_id int NOT NULL,
		creation_date timestamp,
		PRIMARY KEY(course_id),
		FOREIGN KEY(dept_id) REFERENCES department(dept_id),
		FOREIGN KEY(status_id) REFERENCES status(status_id));");
$create_course->execute();
$create_course->close();

/* Prerequisite */
$create_prerequisite = $db_connection->prepare(
	"CREATE OR REPLACE TABLE prerequisite
		(prerequisite_id int NOT NULL AUTO_INCREMENT,
		course_id int NOT NULL,
    course_prerequisite_id int NOT NULL,
		PRIMARY KEY(prerequisite_id),
		FOREIGN KEY(course_id) REFERENCES course(course_id),
    FOREIGN KEY(course_prerequisite_id) REFERENCES course(course_id));");
$create_prerequisite->execute();
$create_prerequisite->close();

/* Take */
$create_taken = $db_connection->prepare(
	"CREATE OR REPLACE TABLE take
		(take_id int NOT NULL AUTO_INCREMENT,
		course_id int NOT NULL,
		grade_id int NOT NULL,
		semester_id int NOT NULL,
		year_id int NOT NULL,
		state_id int NOT NULL,
		take_status_id int NOT NULL,
		student_id int NOT NULL,
		PRIMARY KEY(take_id),
		FOREIGN KEY(course_id) REFERENCES course(course_id),
		FOREIGN KEY(grade_id) REFERENCES grade(grade_id),
		FOREIGN KEY(semester_id) REFERENCES semester(semester_id),
		FOREIGN KEY(year_id) REFERENCES years(year_id),
		FOREIGN KEY(state_id) REFERENCES state(state_id),
		FOREIGN KEY(take_status_id) REFERENCES take_status(take_status_id),
		FOREIGN KEY(student_id) REFERENCES student(student_id));");
$create_taken->execute();
$create_taken->close();

/* Graduation Map */
$create_graduation = $db_connection->prepare(
	"CREATE OR REPLACE TABLE graduation
		(graduation_id int NOT NULL AUTO_INCREMENT,
		program_id int NOT NULL,
		student_id int NOT NULL,
		enrolled timestamp,
		PRIMARY KEY(graduation_id),
		FOREIGN KEY(program_id) REFERENCES program(program_id),
		FOREIGN KEY(student_id) REFERENCES student(student_id));");
$create_graduation->execute();
$create_graduation->close();

/* Status Display */
echo nl2br("The database tables were successfully created.\r\n");


//-----------------------------------------------------
// Populate Tables of Database
//-----------------------------------------------------

/* Role */
$insert_role = $db_connection->prepare(
	"INSERT INTO role
		(role_id, role_type) VALUES(?,?);");
$insert_role->bind_param("is", $role_id, $role_title);

$role_id = 1;
$role_title = "administrator";
$insert_role->execute();

$role_id = 2;
$role_title = "faculty";
$insert_role->execute();

$role_id = 3;
$role_title = "student";
$insert_role->execute();

$role_id = 4;
$role_title = "guest";
$insert_role->execute();

$insert_role->close();

/* Status */
$insert_status = $db_connection->prepare(
	"INSERT INTO status
		(status_id, status_type) VALUES(?,?);");
$insert_status->bind_param("is", $status_id, $status_title);
$status_id = 1;
$status_title = "active";
$insert_status->execute();

$status_id = 2;
$status_title = "dormant";
$insert_status->execute();

$insert_status->close();

/* Take Status */
$insert_take_status = $db_connection->prepare(
	"INSERT INTO take_status
		(take_status_id, take_status_type) VALUES(?,?);");
$insert_take_status->bind_param("is", $take_status_id, $take_status_title);
$take_status_id = 1;
$take_status_title = "default";
$insert_take_status->execute();

$take_status_id = 2;
$take_status_title = "changed";
$insert_take_status->execute();

$take_status_id = 3;
$take_status_title = "request";
$insert_take_status->execute();

$take_status_id = 4;
$take_status_title = "approved";
$insert_take_status->execute();

$take_status_id = 5;
$take_status_title = "denied";
$insert_take_status->execute();

$insert_take_status->close();

/* State */
$insert_state = $db_connection->prepare(
	"INSERT INTO state
		(state_id, state_type) VALUES(?,?);");
$insert_state->bind_param("is", $state_id, $state_title);
$state_id = 1;
$state_title = "past";
$insert_state->execute();

$state_id = 2;
$state_title = "present";
$insert_state->execute();

$state_id = 3;
$state_title = "future";
$insert_state->execute();

$insert_state->close();

/* Grade */
$insert_grade = $db_connection->prepare(
	"INSERT INTO grade
		(grade_id, grade_type) VALUES(?,?);");
$insert_grade->bind_param("is", $grade_id, $grade_type);
$grade_id = 1;
$grade_type = "A+";
$insert_grade->execute();

$grade_id = 2;
$grade_type = "A";
$insert_grade->execute();

$grade_id = 3;
$grade_type = "A-";
$insert_grade->execute();

$grade_id = 4;
$grade_type = "B+";
$insert_grade->execute();

$grade_id = 5;
$grade_type = "B";
$insert_grade->execute();

$grade_id = 6;
$grade_type = "B-";
$insert_grade->execute();

$grade_id = 7;
$grade_type = "C+";
$insert_grade->execute();

$grade_id = 8;
$grade_type = "C";
$insert_grade->execute();

$grade_id = 9;
$grade_type = "C-";
$insert_grade->execute();

$grade_id = 10;
$grade_type = "D+";
$insert_grade->execute();

$grade_id = 11;
$grade_type = "D";
$insert_grade->execute();

$grade_id = 12;
$grade_type = "D-";
$insert_grade->execute();

$grade_id = 13;
$grade_type = "F";
$insert_grade->execute();

$grade_id = 14;
$grade_type = "I";
$insert_grade->execute();

$grade_id = 15;
$grade_type = "P";
$insert_grade->execute();

$insert_grade->close();

/* Department */
$insert_dept = $db_connection->prepare(
	"INSERT INTO department
		(dept_id,
		dept_name,
		status_id) VALUES(?,?,?);");
$insert_dept->bind_param("ssi",
$dept_id,
$dept_name,
$status_id);

$dept_id = "CSC";
$dept_name = "Computer Science";
$status_id = 1;
$insert_dept->execute();

$dept_id = "MAT";
$dept_name = "Mathematics";
$status_id = 1;
$insert_dept->execute();

$dept_id = "INQ";
$dept_name = "Inquiry";
$status_id = 1;
$insert_dept->execute();

$dept_id = "ENG";
$dept_name = "English";
$status_id = 1;
$insert_dept->execute();

$dept_id = "CHE";
$dept_name = "Chemistry";
$status_id = 1;
$insert_dept->execute();

$dept_id = "ECS";
$dept_name = "Earth science";
$status_id = 1;
$insert_dept->execute();

$dept_id = "PHY";
$dept_name = "Physics";
$status_id = 1;
$insert_dept->execute();

$dept_id = "BIO";
$dept_name = "Biology";
$status_id = 1;
$insert_dept->execute();

$dept_id = "MIS";
$dept_name = "Management";
$status_id = 1;
$insert_dept->execute();

$dept_id = "ACC";
$dept_name = "Accounting";
$status_id = 1;
$insert_dept->execute();

$dept_id = "PHI";
$dept_name = "Philosophy";
$status_id = 1;
$insert_dept->execute();

$dept_id = "SPA";
$dept_name = "Spanish";
$status_id = 1;
$insert_dept->execute();

$dept_id = "PCH";
$dept_name = "Public Health";
$status_id = 1;
$insert_dept->execute();

$dept_id = "ANT";
$dept_name = "Anthropology";
$status_id = 1;
$insert_dept->execute();

$dept_id = "MKT";
$dept_name = "Marketing";
$status_id = 1;
$insert_dept->execute();

$dept_id = "PSC";
$dept_name = "Political Science";
$status_id = 1;
$insert_dept->execute();

$dept_id = "GEO";
$dept_name = "Geography";
$status_id = 1;
$insert_dept->execute();

$dept_id = "AAA";
$dept_name = "Department Not Specified";
$status_id = 1;
$insert_dept->execute();

$insert_dept->close();

/* Program */
$insert_program = $db_connection->prepare(
	"INSERT INTO program
		(program_id,
		program_name,
		dept_id,
		status_id) VALUES(?,?,?,?);");
$insert_program->bind_param("issi",
$program_id,
$program_name,
$dept_id,
$status_id);

$program_id = 1;
$program_name = "Computer Science General";
$dept_id = "CSC";
$status_id = 1;
$insert_program->execute();

$program_id = 2;
$program_name = "Computer Information Systems";
$dept_id = "CSC";
$status_id = 1;
$insert_program->execute();

$insert_program->close();

/* User */
$insert_user = $db_connection->prepare(
	"INSERT INTO user
		(user_id,
		username,
		password,
		email,
		first_name,
		last_name,
		role_id) VALUES(?,?,?,?,?,?,?);");
$insert_user->bind_param("isssssi",
$user_id,
$username,
$password,
$email,
$first_name,
$last_name,
$role_id);

$user_id = 1;
$username = "ken";
$password = crypt("SCSU2019", $salt);
$email = "ken@smith.edu";
$first_name = "ken";
$last_name = "smith";
$role_id = 3;
$insert_user->execute();

$user_id = 2;
$username = "snow";
$password = crypt("winter", $salt);
$email = "snow@winter.edu";
$first_name = "john";
$last_name = "snow";
$role_id = 1;
$insert_user->execute();

$user_id = 3;
$username = "prof";
$password = crypt("sugar", $salt);
$email = "prof@place.edu";
$first_name = "mike";
$last_name = "ike";
$role_id = 2;
$insert_user->execute();

$user_id = 4;
$username = "flowers";
$password = crypt("spring", $salt);
$email = "flowers@spring.edu";
$first_name = "jeff";
$last_name = "flowers";
$role_id = 3;
$insert_user->execute();

$user_id = 5;
$username = "bugs";
$password = crypt("summer", $salt);
$email = "bugs@summer.edu";
$first_name = "tim";
$last_name = "summer";
$role_id = 3;
$insert_user->execute();

$user_id = 6;
$username = "leaf";
$password = crypt("tree", $salt);
$email = "leaf@fall.edu";
$first_name = "kelly";
$last_name = "tree";
$role_id = 3;
$insert_user->execute();

$user_id = 7;
$username = "john";
$password = crypt("smith1", $salt);
$email = "john@smith.edu";
$first_name = "john";
$last_name = "smith";
$role_id = 3;
$insert_user->execute();

$user_id = 8;
$username = "north";
$password = crypt("west", $salt);
$email = "north@west.edu";
$first_name = "north";
$last_name = "compass";
$role_id = 3;
$insert_user->execute();

$user_id = 9;
$username = "prof_james";
$password = crypt("sugar1", $salt);
$email = "prof_james@place.edu";
$first_name = "james";
$last_name = "ike";
$role_id = 2;
$insert_user->execute();

$user_id = 10;
$username = "prof_jess";
$password = crypt("sugar2", $salt);
$email = "prof_jess@place.edu";
$first_name = "jess";
$last_name = "ike";
$role_id = 2;
$insert_user->execute();

$user_id = 11;
$username = "prof_pam";
$password = crypt("sugar3", $salt);
$email = "prof_pam@place.edu";
$first_name = "pam";
$last_name = "east";
$role_id = 2;
$insert_user->execute();

$insert_user->close();

/* Administrator */
$insert_admin = $db_connection->prepare(
	"INSERT INTO administrator
		(admin_id,
		user_id) VALUES(?,?);");
$insert_admin->bind_param("ii",
$admin_id,
$user_id);

$admin_id = 1;
$user_id = 2;
$insert_admin->execute();

$insert_admin->close();

/* Faculty */
$insert_faculty = $db_connection->prepare(
	"INSERT INTO faculty
		(faculty_id,
		user_id,
		dept_id,
		status_id) VALUES(?,?,?,?);");
$insert_faculty->bind_param("iisi",
$faculty_id,
$user_id,
$dept_id,
$status_id);

$faculty_id = 1;
$user_id = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_faculty->execute();

$faculty_id = 2;
$user_id = 9;
$dept_id = "CSC";
$status_id = 1;
$insert_faculty->execute();

$insert_faculty->close();

/* Year */
$insert_year = $db_connection->prepare(
	"INSERT INTO years
		(year_id,
		year_type) VALUES(?,?);");
$insert_year->bind_param("ii",
$year_id,
$year_type);

$year_id = 1;
$year_type= 2015;
$insert_year->execute();

$year_id = 2;
$year_type= 2016;
$insert_year->execute();

$year_id = 3;
$year_type= 2017;
$insert_year->execute();

$year_id = 4;
$year_type= 2018;
$insert_year->execute();

$year_id = 5;
$year_type= 2019;
$insert_year->execute();

$year_id = 6;
$year_type= 2020;
$insert_year->execute();

$year_id = 7;
$year_type= 2021;
$insert_year->execute();

$year_id = 8;
$year_type= 2022;
$insert_year->execute();

$year_id = 9;
$year_type= 2023;
$insert_year->execute();

$insert_year->close();

/* Semester */
$insert_semester = $db_connection->prepare(
	"INSERT INTO semester
		(semester_id,
		semester_type) VALUES(?,?);");
$insert_semester->bind_param("is",
$semester_id,
$semester_type);

$semester_id = 1;
$semester_type= "Fall";
$insert_semester->execute();

$semester_id = 2;
$semester_type= "Fall 1st 8 weeks";
$insert_semester->execute();

$semester_id = 3;
$semester_type= "Fall 2nd 8 weeks";
$insert_semester->execute();

$semester_id = 4;
$semester_type= "Spring 1st 8 weeks";
$insert_semester->execute();

$semester_id = 5;
$semester_type= "Spring 2nd 8 weeks";
$insert_semester->execute();

$semester_id = 6;
$semester_type= "Spring";
$insert_semester->execute();

$semester_id = 7;
$semester_type= "Winter";
$insert_semester->execute();

$semester_id = 8;
$semester_type= "Summer A";
$insert_semester->execute();

$semester_id = 9;
$semester_type= "Summer B";
$insert_semester->execute();

$semester_id = 10;
$semester_type= "Summer C";
$insert_semester->execute();

$insert_semester->close();

/* Permission */
$insert_permission = $db_connection->prepare(
	"INSERT INTO permission
		(permission_id,
		permission_type) VALUES(?,?);");
$insert_permission->bind_param("is",
$permission_id,
$permission_type);

$permission_id = 1;
$permission_type= "Approved";
$insert_permission->execute();

$permission_id = 2;
$permission_type= "Denied";
$insert_permission->execute();

$insert_permission->close();

/* Course */
$insert_course = $db_connection->prepare(
	"INSERT INTO course
		(course_id,
    course_num,
		course_name,
		credits,
		dept_id,
		status_id) VALUES(?,?,?,?,?,?);");

$insert_course->bind_param("iisisi",
$course_id,
$course_num,
$course_name,
$credits,
$dept_id,
$status_id);

/* CSC Courses */
$course_id = 11625;
$course_num = 101;
$course_name= "Intro to Computers & Applications";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11629;
$course_num = 104;
$course_name= "Web Technology";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11630;
$course_num = 152;
$course_name= "Fundamentals of Programming";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11636;
$course_num = 200;
$course_name= "Info Mgmt/Productivity Software";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11649;
$course_num = 207;
$course_name= "Computer Systems";
$credits = 4;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11651;
$course_num = 212;
$course_name= "CS2: Data Structures";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11654;
$course_num = 229;
$course_name= "Object-Oriented Programming";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11656;
$course_num = 235;
$course_name= "Web and Database Development";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11659;
$course_num = 265;
$course_name= "Computer Networking and Security I";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 42003;
$course_num = 305;
$course_name= "Computer Organization";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 40260;
$course_num = 310;
$course_name= "Multimedia Systems";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11661;
$course_num = 321;
$course_name= "Algorithm Design and Analysis";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11662;
$course_num = 324;
$course_name= "Computer Ethics";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11664;
$course_num = 330;
$course_name= "Software Design and Development";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 41872;
$course_num = 334;
$course_name= "Human-Computer Interactions";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 41873;
$course_num = 335;
$course_name= "Database System";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11666;
$course_num = 341;
$course_name= "Digital Imaging";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 50092;
$course_num = 380;
$course_name= "Network Technology";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 10501;
$course_num = 398;
$course_name= "Deep Learning";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11667;
$course_num = 400;
$course_name= "Capstone";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11668;
$course_num = 424;
$course_name= "System Administration";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11669;
$course_num = 425;
$course_name= "Operating Systems";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 41879;
$course_num = 431;
$course_name= "Computer Graphics";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11670;
$course_num = 443;
$course_name= "Internet Programming";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 41797;
$course_num = 453;
$course_name= "Information Security";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 41878;
$course_num = 463;
$course_name= "Development of Distributed and E-Commerce Applications";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11671;
$course_num = 465;
$course_name= "Computer Networking and Security II";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 42430;
$course_num = 476;
$course_name= "Fundamentals of Data Warehousing";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11672;
$course_num = 477;
$course_name= "Data Mining";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 42004;
$course_num = 481;
$course_name= "Artificial Intelligence";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11673;
$course_num = 505;
$course_name= "Comp. Pgrm & Data Structures";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 41883;
$course_num = 535;
$course_name= "Software Engineering";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11674;
$course_num = 540;
$course_name= "Database Systems";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11675;
$course_num = 550;
$course_name= "Fund. of Moble App. Development";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11677;
$course_num = 558;
$course_name= "Network Security";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11676;
$course_num = 563;
$course_name= "Multithreaded Dist. Programing";
$credits = 3;
$dept_id = "CSC";
$status_id = 1;
$insert_course->execute();

/* Math */
$course_id = 40114;
$course_num = 112;
$course_name= "Math for Natural Sciences";
$credits = 3;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 40816;
$course_num = 120;
$course_name= "College Algebra";
$credits = 3;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11062;
$course_num = 122;
$course_name= "Pre Calculus";
$credits = 4;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11073;
$course_num = 139;
$course_name= "Short Course in Calculus";
$credits = 3;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11075;
$course_num = 150;
$course_name= "Calculus I";
$credits = 4;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11078;
$course_num = 151;
$course_name= "Calculus II";
$credits = 4;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11080;
$course_num = 178;
$course_name= "Elementary Discrete Mathematics";
$credits = 3;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11082;
$course_num = 221;
$course_name= "Intermediate Applied Statistics";
$credits = 4;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11088;
$course_num = 252;
$course_name= "Calculus III";
$credits = 4;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 11093;
$course_num = 322;
$course_name= "Numerical Analysis";
$credits = 4;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

$course_id = 00001;
$course_num = 370;
$course_name= "Business Information Systems";
$credits = 3;
$dept_id = "MAT";
$status_id = 1;
$insert_course->execute();

/* Physics */
$course_id = 10400;
$course_num = 200;
$course_name= "General Physics I";
$credits = 4;
$dept_id = "PHY";
$status_id = 1;
$insert_course->execute();

$course_id = 10404;
$course_num = 201;
$course_name= "General Physics II";
$credits = 4;
$dept_id = "PHY";
$status_id = 1;
$insert_course->execute();

$course_id = 10406;
$course_num = 230;
$course_name= "Physics for Scientists and Engineers I";
$credits = 4;
$dept_id = "PHY";
$status_id = 1;
$insert_course->execute();

$course_id = 10408;
$course_num = 231;
$course_name= "Physics for Scientists and Engineers II";
$credits = 4;
$dept_id = "PHY";
$status_id = 1;
$insert_course->execute();

$course_id = 10410;
$course_num = 355;
$course_name= "Electricity and Electronics";
$credits = 4;
$dept_id = "PHY";
$status_id = 1;
$insert_course->execute();

/* MIS */
$course_id = 12100;
$course_num = 365;
$course_name= "Systems Thinking for MIS";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 40714;
$course_num = 370;
$course_name= "Business Information Systems";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 12109;
$course_num = 371;
$course_name= "Information System Analysis and Design Techniques";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 42080;
$course_num = 375;
$course_name= "Decision Support Systems";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 12111;
$course_num = 400;
$course_name= "Global Information Systems";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 42084;
$course_num = 430;
$course_name= "Project Management";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 42085;
$course_num = 460;
$course_name= "MIS Security Management";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

$course_id = 42086;
$course_num = 470;
$course_name= "Management of Information Systems Design";
$credits = 3;
$dept_id = "MIS";
$status_id = 1;
$insert_course->execute();

/* Accounting */
$course_id = 40863;
$course_num = 200;
$course_name= "Principles of Financial Accounting";
$credits = 3;
$dept_id = "ACC";
$status_id = 1;
$insert_course->execute();

$course_id = 40874;
$course_num = 210;
$course_name= "Managerial Accounting";
$credits = 3;
$dept_id = "ACC";
$status_id = 1;
$insert_course->execute();

/* Chemistry */
$course_id = 11979;
$course_num = 120;
$course_name= "General Chemistry I";
$credits = 4;
$dept_id = "CHE";
$status_id = 1;
$insert_course->execute();

$course_id = 40908;
$course_num = 121;
$course_name= "General Chemistry II";
$credits = 4;
$dept_id = "CHE";
$status_id = 1;
$insert_course->execute();

/* ESC */
$course_id = 00002;
$course_num = 200;
$course_name= "Physical Geology";
$credits = 4;
$dept_id = "ESC";
$status_id = 1;
$insert_course->execute();

$course_id = 00003;
$course_num = 201;
$course_name= "Historical Geology";
$credits = 4;
$dept_id = "ESC";
$status_id = 1;
$insert_course->execute();

/* Biology */
$course_id = 40120;
$course_num = 100;
$course_name= "General Zoology";
$credits = 3;
$dept_id = "BIOCHE";
$status_id = 1;
$insert_course->execute();

$course_id = 40132;
$course_num = 101;
$course_name= "General Botany";
$credits = 3;
$dept_id = "BIOCHE";
$status_id = 1;
$insert_course->execute();

$course_id = 40141;
$course_num = 103;
$course_name= "Biology I";
$credits = 3;
$dept_id = "BIOCHE";
$status_id = 1;
$insert_course->execute();

$course_id = 401153;
$course_num = 120;
$course_name= "Microbiology";
$credits = 4;
$dept_id = "BIOCHE";
$status_id = 1;
$insert_course->execute();

$course_id = 40155;
$course_num = 200;
$course_name= "Human Biology I";
$credits = 4;
$dept_id = "BIOCHE";
$status_id = 1;
$insert_course->execute();

$course_id = 40160;
$course_num = 201;
$course_name= "Human Biology I";
$credits = 4;
$dept_id = "BIOCHE";
$status_id = 1;
$insert_course->execute();

$course_id = 11295;
$course_num = 101;
$course_name= "Intellectual Inquiry";
$credits = 3;
$dept_id = "INQ";
$status_id = 1;
$insert_course->execute();

/* English */
$course_id = 40423;
$course_num = 110;
$course_name= "Composition Writing Lab";
$credits = 3;
$dept_id = "ENG";
$status_id = 1;
$insert_course->execute();

$course_id = 40326;
$course_num = 112;
$course_name= "Writing Arguments";
$credits = 3;
$dept_id = "ENG";
$status_id = 1;
$insert_course->execute();

$course_id = 10484;
$course_num = 201;
$course_name= "Creative Writing";
$credits = 3;
$dept_id = "ENG";
$status_id = 1;
$insert_course->execute();

/* LEP TEIR II Course */

$course_id = 10842;
$course_num = 100;
$course_name= "Introduction to Philosophy";
$credits = 3;
$dept_id = "PHI";
$status_id = 1;
$insert_course->execute();

$course_id = 11338;
$course_num = 201;
$course_name= "Wellness";
$credits = 3;
$dept_id = "PCH";
$status_id = 1;
$insert_course->execute();

$course_id = 10027;
$course_num = 102;
$course_name= "Biological Anthropology";
$credits = 3;
$dept_id = "ANT";
$status_id = 1;
$insert_course->execute();

$course_id = 10796;
$course_num = 101;
$course_name= "Spanish II";
$credits = 3;
$dept_id = "SPA";
$status_id = 1;
$insert_course->execute();

$course_id = 10807;
$course_num = 200;
$course_name= "Spanish III";
$credits = 3;
$dept_id = "SPA";
$status_id = 1;
$insert_course->execute();

$course_id = 10579;
$course_num = 350;
$course_name= "Product and Market Planning";
$credits = 3;
$dept_id = "MKT";
$status_id = 1;
$insert_course->execute();

$course_id = 10031;
$course_num = 201;
$course_name= "The Global Community";
$credits = 3;
$dept_id = "ANT";
$status_id = 1;
$insert_course->execute();

$course_id = 10617;
$course_num = 200;
$course_name= "Political Change and Conflict";
$credits = 3;
$dept_id = "PSC";
$status_id = 1;
$insert_course->execute();

$course_id = 11235;
$course_num = 100;
$course_name= "People, Places, and Environments";
$credits = 3;
$dept_id = "GEO";
$status_id = 1;
$insert_course->execute();


$insert_course->close();

/* Student */
$insert_student = $db_connection->prepare(
	"INSERT INTO student
		(student_id,
		user_id,
		program_id,
		status_id) VALUES(?,?,?,?);");
$insert_student->bind_param("iiii",
$student_id,
$user_id,
$program_id,
$status_id);

$student_id = 1;
$user_id = 1;
$status_id = 1;
$program_id = 1;
$insert_student->execute();

$student_id = 2;
$user_id = 4;
$status_id = 1;
$program_id = 2;
$insert_student->execute();

$student_id = 3;
$user_id = 5;
$status_id = 1;
$program_id = 1;
$insert_student->execute();

$student_id = 3;
$user_id = 6;
$status_id = 1;
$program_id = 1;
$insert_student->execute();

$student_id = 4;
$user_id = 7;
$status_id = 1;
$program_id = 1;
$insert_student->execute();

$student_id = 5;
$user_id = 8;
$status_id = 2;
$program_id = 1;
$insert_student->execute();

$insert_student->close();

/* Take   JOshC updating...*/
$insert_take = $db_connection->prepare(
	"INSERT INTO take
		(take_id,
		course_id,
		grade_id,
		semester_id,
		year_id,
		state_id,
		take_status_id,
		student_id) VALUES(?,?,?,?,?,?,?,?);");
$insert_take->bind_param("iiiiiiii",
$take_id,
$course_id,
$grade_id,
$semester_id,
$year_id,
$state_id,
$take_status_id,
$student_id);

$take_id = 1;
$course_id = 11630;
$grade_id = 14;
$semester_id = 1;
$year_id = 5;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 2;
$course_id = 40114;
$grade_id = 14;
$semester_id = 1;
$year_id = 5;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 3;
$course_id = 11295;
$grade_id = 14;
$semester_id = 1;
$year_id = 5;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 4;
$course_id = 11636;
$grade_id = 14;
$semester_id = 1;
$year_id = 5;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 5;
$course_id = 10842;
$grade_id = 14;
$semester_id = 1;
$year_id = 5;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 6;
$course_id = 11651;
$grade_id = 14;
$semester_id = 6;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 7;
$course_id = 11062;
$grade_id = 14;
$semester_id = 6;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 8;
$course_id = 10796;
$grade_id = 14;
$semester_id = 6;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 9;
$course_id = 40326;
$grade_id = 14;
$semester_id = 6;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 10;
$course_id = 11338;
$grade_id = 14;
$semester_id = 6;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 11;
$course_id = 11649;
$grade_id = 14;
$semester_id = 1;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 12;
$course_id = 11659;
$grade_id = 14;
$semester_id = 1;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 13;
$course_id = 11075;
$grade_id = 14;
$semester_id = 1;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 14;
$course_id = 10027;
$grade_id = 14;
$semester_id = 1;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 15;
$course_id = 10807;
$grade_id = 14;
$semester_id = 1;
$year_id = 6;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 16;
$course_id = 11659;
$grade_id = 14;
$semester_id = 6;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 17;
$course_id = 11654;
$grade_id = 14;
$semester_id = 6;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 18;
$course_id = 11080;
$grade_id = 14;
$semester_id = 6;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 19;
$course_id = 11078;
$grade_id = 14;
$semester_id = 6;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 20;
$course_id = 11661;
$grade_id = 14;
$semester_id = 1;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 21;
$course_id = 11662;
$grade_id = 14;
$semester_id = 1;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 22;
$course_id = 42003;
$grade_id = 14;
$semester_id = 1;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 23;
$course_id = 10400;
$grade_id = 14;
$semester_id = 1;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 24;
$course_id = 10579;
$grade_id = 14;
$semester_id = 1;
$year_id = 7;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 25;
$course_id = 11664;
$grade_id = 14;
$semester_id = 6;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 26;
$course_id = 41873;
$grade_id = 14;
$semester_id = 6;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 27;
$course_id = 10404;
$grade_id = 14;
$semester_id = 6;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 28;
$course_id = 10484;
$grade_id = 14;
$semester_id = 6;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 29;
$course_id = 10031;
$grade_id = 14;
$semester_id = 6;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 30;
$course_id = 11669;
$grade_id = 14;
$semester_id = 1;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 31;
$course_id = 11672;
$grade_id = 14;
$semester_id = 1;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 32;
$course_id = 10617;
$grade_id = 14;
$semester_id = 1;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 33;
$course_id = 11082;
$grade_id = 14;
$semester_id = 1;
$year_id = 8;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 34;
$course_id = 10501;
$grade_id = 14;
$semester_id = 6;
$year_id = 9;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 35;
$course_id = 11667;
$grade_id = 14;
$semester_id = 6;
$year_id = 9;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 36;
$course_id = 11088;
$grade_id = 14;
$semester_id = 6;
$year_id = 9;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$take_id = 37;
$course_id = 11235;
$grade_id = 14;
$semester_id = 6;
$year_id = 9;
$state_id = 3;
$take_status_id = 1;
$student_id = 1;
$insert_take->execute();

$insert_take->close();

/* Graduation Map */
$insert_graduation = $db_connection->prepare(
	"INSERT INTO graduation
		(graduation_id,
		student_id,
		program_id) VALUES(?,?,?);");
$insert_graduation->bind_param("iii",
$graduation_id,
$student_id,
$program_id);

$graduation_id = 1;
$student_id = 1;
$program_id = 1;
$insert_graduation->execute();

$insert_graduation->close();

/* Advisor */
$insert_advisor = $db_connection->prepare(
	"INSERT INTO advisor
		(student_id,
		faculty_id) VALUES(?,?);");
$insert_advisor->bind_param("ii",
$student_id,
$faculty_id);

$student_id = 1;
$faculty_id = 1;
$insert_advisor->execute();

$student_id = 2;
$faculty_id = 1;
$insert_advisor->execute();

$student_id = 3;
$faculty_id = 1;
$insert_advisor->execute();

$student_id = 5;
$faculty_id = 2;
$insert_advisor->execute();

$insert_advisor->close();

/* Prerequisite */
$insert_prerequisite = $db_connection->prepare(
	"INSERT INTO prerequisite
		(prerequisite_id,
      course_prerequisite_id,
      course_id) VALUES(?,?,?);");
$insert_prerequisite->bind_param("iii",
$prerequisite_id,
$course_prerequisite_id,
$course_id);

$prerequisite_id = 1;
$course_id = 11630;
$course_prerequisite_id = 40114;
$insert_prerequisite->execute();

$prerequisite_id = 2;
$course_id = 11649;
$course_prerequisite_id = 11630;
$insert_prerequisite->execute();

$prerequisite_id = 3;
$course_id = 11649;
$course_prerequisite_id = 40816;
$insert_prerequisite->execute();

$prerequisite_id = 4;
$course_id = 11651;
$course_prerequisite_id = 40114;
$insert_prerequisite->execute();

$prerequisite_id = 5;
$course_id = 11651;
$course_prerequisite_id = 11630;
$insert_prerequisite->execute();

$prerequisite_id = 6;
$course_id =11656;
$course_prerequisite_id = 11651;
$insert_prerequisite->execute();

$prerequisite_id = 7;
$course_id = 11654;
$course_prerequisite_id = 11651;
$insert_prerequisite->execute();

$prerequisite_id = 8;
$course_id = 11659;
$course_prerequisite_id = 11649;
$insert_prerequisite->execute();

$prerequisite_id = 9;
$course_id = 42003;
$course_prerequisite_id = 11649;
$insert_prerequisite->execute();


$insert_prerequisite->close();

/* Status Display */
echo nl2br("The database tables were successfully populated.\r\n");
/* Return to homepage after 5 seconds */
header( "refresh:10;url=/ug-advisor" );

/* ALWAYS CLOSE THE DB CONNECTION */
$db_connection->close();

?>
