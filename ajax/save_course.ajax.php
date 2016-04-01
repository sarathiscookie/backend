<?php
require_once "../classes/manage_courses.class.php";

$objCourse = new ManageCourses();
if(isset($_GET['mode']) && $_GET['mode']=='C') {
    $objCourse -> createCourse();
}
elseif(isset($_GET['mode']) && $_GET['mode']=='Opt') {
    $objCourse -> createCourseOptions();
}
else {
    $objCourse -> saveCourse();
}
