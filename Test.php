<?php
include "Advisor.php";
include "Student.php";
include "Appointment.php";
include_once "CommonMethods.php";
$COMMON = new Common(true);
$advisor = new Advisor($COMMON, 2);
var_dump($advisor);
$student = new Student($COMMON, 'AA12345');
var_dump($student);
$appointments = Appointment::searchAppointments($COMMON, date('Y-m-d'), 'I', 'Computer Science');
var_dump($appointments);
?>