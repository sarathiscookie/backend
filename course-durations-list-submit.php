<?php
require_once "config/debug.php";
require_once "classes/course-durations-list-class.php";
$service                   = new courseDurationsService();
if(isset($_POST['editcourseduration'])){
    $courseDuration_id       = $_POST['id'];
    $CourseDuration_title    = $_POST['firstname'];
    $CourseDuration_period   = $_POST['secondname'];
    $CourseDuration_duration = $_POST['thirdname'];
    $service->updateCourseDuration($CourseDuration_title, $CourseDuration_period, $CourseDuration_duration, $courseDuration_id);
}
if(isset($_POST['add_course_duration'])){
    $title     = $_POST['title'];
    $period    = $_POST['period'];
    $duration  = $_POST['duration'];
    $service->addCourseDuration($title, $period, $duration);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            Kursdauer wurde erfolgreich hinzugefügt!
            </div>';
}
