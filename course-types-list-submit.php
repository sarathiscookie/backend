<?php
require_once "config/debug.php";
require_once "classes/course-types-list-class.php";
$service                 = new courseTypesService();
if(isset($_POST['edit_course_type'])){
    $courseType_id       = $_POST['id'];
    $courseType_title    = htmlspecialchars_decode($_POST['firstname']);
    $service->updateCourseType($courseType_title, $courseType_id);
}
if(isset($_POST['add_course_type'])){
    $title     = htmlspecialchars_decode($_POST['title']);
    $service->addCourseType($title);
    echo '<div class="alert alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
            Kurstyp wurde erfolgreich hinzugefügt!
            </div>';
}
