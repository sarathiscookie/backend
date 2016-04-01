<?php
require_once "dbconnection-class.php";

class ManageCourses
{
    /**
     * List all courses in edit mode of the selected school
     * @return string
     */
    public function listCourses()
    {
        global $mysqli, $_GET;
        $school_id = $_GET['sid'];
        $courses = "SELECT CR.*,CT.title FROM `courses` CR INNER JOIN course_types CT ON CR.course_type_id = CT.id   WHERE CR.school_id=".$school_id." ORDER BY CR.id DESC ";
        $rows = $mysqli->query($courses);
        $count =  $rows->num_rows;
        if ($count > 0) {
            $course_row = '';
            while ($course = $rows->fetch_array()) {
                $course_id = $course['id'];
                $course_options = $this->getCourseOptions($course_id);

                $course_row .= '<div class="col-md-6"><div class="panel panel-default">
<div class="panel-heading">'.$course["title"].'
 <button type="button" class="btn btn-primary btn-sm pull-right optmodalBtn" id="optCrs_'.$course_id.'">Preis hinzufügen</button>
<div class="clearfix"></div>
</div>
<div class="panel-body">
                                <form id="courseFrm_'.$course_id.'" onsubmit="return false">
<div class="form-group form-inline"><label>Von Monat </label> <input type="number" placeholder="Monat Start" class="form-control" name="month_begin-'.$course_id.'" value="'.$course["month_begin"].'" min="1" max="12">
<label>bis Monat </label> <input type="number" placeholder="Monat Ende" class="form-control" name="month_end-'.$course_id.'" value="'.$course["month_end"].'" min="1" max="12"></div>
<div class="form-group"><textarea name="services-'.$course_id.'" class="form-control textarea" rows="10">'.$course["services"].'</textarea></div>
<div id="optionContainer_'.$course_id.'">'.$course_options.'</div>
                                <input type="hidden" name="course" value="'.$course_id.'">
                                <button class="btn btn-primary btn-lg btn-block btnUpdate" id="updateBtn-'.$course_id.'" type="button">Aktualisieren</button>
                                </form>
                                </div>
                                </div></div>';
            }
        }
        return $course_row;
    }

    /**
     * get Course options in -edit mode
     * @param $course_id
     * @return string
     */
    protected function getCourseOptions($course_id)
    {
        global $mysqli;
        $options = "SELECT CO.*,CD.title,CD.duration,CD.period FROM `course_options` CO INNER JOIN course_durations CD ON CO.course_duration_id = CD.id   WHERE CO.course_id=".$course_id;
        $rows = $mysqli->query($options);
        $list ='';
        while ($option = $rows->fetch_array()) {
            $option_id = $option["id"];

            $list .= '<div class="well"><label>' . $option["title"] . ' - ' . $option["duration"] . ' Tage - ' . $option["period"] . '  </label>
                <div class="form-group form-inline">
                <label>Preis:</label>
                <input type="text" placeholder="Preis" class="form-control" name="price-' . $option_id . '" value="' . $option["price"] . '">
                <input type="text" placeholder="Pseudo Preis" class="form-control" name="pseudo_price-' . $option_id . '" value="' . $option["pseudo_price"] . '">
                </div>
                <div class="form-group form-inline">
                <label>Preis EZ:</label>
                <input type="text" placeholder="Preis 1 Person (optional)" class="form-control" name="price_solo-' . $option_id . '" value="' . $option["price_solo"] . '">
                <input type="text" placeholder="Pseudo Preis 1 Person (optional)" class="form-control" name="pseudo_price_solo-' . $option_id . '" value="' . $option["pseudo_price_solo"] . '">
                </div>
                <div class="form-group form-inline">
                <label>Rabatt:</label>
                <input type="text" placeholder="Rabatt" class="form-control" name="discount-' . $option_id . '" value="' . $option["discount"] . '">
                </div>
                </div>';
        }
        return $list;
    }

    /**
     * update Course details - Ajax
     */
    public function saveCourse()
    {
        global $mysqli, $_POST;
        $course_id      = $_POST['course'];
        $month_begin    =  $_POST['month_begin-'.$course_id];
        $month_end      =  $_POST['month_end-'.$course_id];
        $services       =  $mysqli->real_escape_string($_POST['services-'.$course_id]);
        $update_qry     = "UPDATE `courses` SET `month_begin`='".$month_begin."', `month_end`='".$month_end."',`services`='".$services."' WHERE id=".$course_id;
        $mysqli->query($update_qry);
        $mes = ($mysqli->affected_rows>=0)?1:0;

        $options = "SELECT id FROM `course_options` WHERE course_id=".$course_id;
        $rows = $mysqli->query($options);
        while ($option = $rows->fetch_array()) {
            $opt_id = $option['id'];
            $price             = $mysqli->real_escape_string($_POST['price-'.$opt_id]);
            $pseudo_price      = $mysqli->real_escape_string($_POST['pseudo_price-'.$opt_id]);
            $discount          = $mysqli->real_escape_string($_POST['discount-'.$opt_id]);
            $price_solo        = $mysqli->real_escape_string($_POST['price_solo-'.$opt_id]);
            $pseudo_price_solo = $mysqli->real_escape_string($_POST['pseudo_price_solo-'.$opt_id]);

            $update_opt     = "UPDATE `course_options` SET `price`=".$price.", `pseudo_price`=".$pseudo_price.",`discount`='".$discount."',`price_solo`='".$price_solo."',`pseudo_price_solo`='".$pseudo_price_solo."' WHERE id=".$opt_id;
            $mysqli->query($update_opt);
        }

        print json_encode(['success'=>$mes]);
    }

    /**
     * Show Arrival options - Monday to Sunday
     * @param string $option
     * @return string
     */
    public function getArrivalOptions($option='')
    {
        $html ='<option value="">Wochentag der Ankunft im Hotel</option>';
        $options = array('1'=>'Montag','2'=>'Dienstag','3'=>'Mittwoch','4'=>'Donnerstag','5'=>'Freitag','6'=>'Samstag','7'=>'Sonntag');
        foreach($options as $val => $text) {
            $selected = ($val==$option)?'selected="selected"':'';
            $html .= '<option value="'.$val.'" '.$selected.'>'.$text.'</option>';
        }
        return $html;
    }

    /**
     * get All course types - options input
     * @return string
     */
    public function getCourseTypes()
    {
        global $mysqli;

        $rows  = $mysqli->query("SELECT id, title FROM `course_types` ORDER BY title");
        $inputs ='<option value="">Kursart wählen</option>';
        while ($type = $rows->fetch_array()) {
            $inputs .= '<option value="'.$type['id'].'">'.$type['title'].'</option>';
        }
        return $inputs;
    }

    /**
     * create new course - Ajax
     */
    public function createCourse()
    {
        global $mysqli, $_GET, $_POST;
        $course_row ='';

        $school_id   = $_POST['school_id'];
        $course_type = $_POST['course_type_id'];
        $month_begin = $mysqli->real_escape_string($_POST['month_begin']);
        $month_end   = $mysqli->real_escape_string($_POST['month_end']);
        $services    = $mysqli->real_escape_string($_POST['services']);

        $insert_course = "INSERT INTO courses (`school_id`, `course_type_id`,`month_begin`,`month_end`,`services`)
VALUES('".$school_id."','".$course_type."','".$month_begin."','".$month_end."','".$services."')";
        $mysqli->query($insert_course);
        $id = $mysqli->insert_id;

        if($id>0) {
            $courses = "SELECT CR.*,CT.title FROM `courses` CR INNER JOIN course_types CT ON CR.course_type_id = CT.id   WHERE CR.id=" . $id;
            $rows = $mysqli->query($courses);
            $course = $rows->fetch_array();
            $course_options = $this->getCourseOptions($id);

            $course_row .= '<div class="col-md-6"><div class="panel panel-default">
<div class="panel-heading">' . $course["title"] . '
<button type="button" class="btn btn-primary btn-sm pull-right optmodalBtn" id="optCrs_'.$id.'">Preis hinzufügen</button>
<div class="clearfix"></div>
</div>
<div class="panel-body">
                                <form id="courseFrm_' . $id . '" onsubmit="return false">
<div class="form-group form-inline"><label>Von Monat </label> <input type="number" placeholder="Monat Start" class="form-control" name="month_begin-' . $id . '" value="' . $course["month_begin"] . '" min="1" max="12">
<label>bis Monat</label> <input type="number" placeholder="Monat Ende" class="form-control" name="month_end-' . $id . '" value="' . $course["month_end"] . '" min="1" max="12"></div>
<div class="form-group"><textarea name="services-' . $id . '" class="form-control textarea" rows="10">' . $course["services"] . '</textarea></div>
<div id="optionContainer_'.$id.'"> ' . $course_options . '</div>
                                <input type="hidden" name="course" value="' . $id . '">
                                <button class="btn btn-primary btn-lg btn-block btnUpdate" id="updateBtn-' . $id . '" type="button">Aktualisieren</button>
                                </form>
                                </div>
                                </div></div>';

            print json_encode(['success'=>1,'row'=>$course_row]);
        }
        else {
            print json_encode(['success'=>0,'row'=>'']);
        }
    }

    /**
     * get course durations as input options
     * @return string
     */
    public function getCourseDurations()
    {
        global $mysqli;

        $rows  = $mysqli->query("SELECT id, title, duration FROM `course_durations` ORDER BY title");
        $inputs ='<option value="">Course duration</option>';
        while ($duration = $rows->fetch_array()) {
            $inputs .= '<option value="'.$duration['id'].'">'.$duration['duration'].' Tage '.$duration['title'].'</option>';
        }
        return $inputs;
    }

    /**
     * Create course options- Ajax
     */
    public function createCourseOptions()
    {
        global $mysqli, $_GET, $_POST;

        $list ='';
        $course_id           = $_POST['optcourse_id'];
        $course_duration_id  = $mysqli->real_escape_string($_POST['course_duration_id']);
        $price               = $mysqli->real_escape_string($_POST['price']);
        $pseudo_price        = $mysqli->real_escape_string($_POST['pseudo_price']);
        $price_solo          = $mysqli->real_escape_string($_POST['price_solo']);
        $pseudo_price_solo   = $mysqli->real_escape_string($_POST['pseudo_price_solo']);
        $discount            = $mysqli->real_escape_string($_POST['discount']);

        $insert_option = "INSERT INTO `course_options`(`course_id`, `course_duration_id`, `price`, `pseudo_price`, `price_solo`, `pseudo_price_solo`, `discount`) VALUES ('".$course_id."','".$course_duration_id."','".$price."','".$pseudo_price."','".$price_solo."','".$pseudo_price_solo."','".$discount."')";
        $mysqli->query($insert_option);
        $id = $mysqli->insert_id;

        if($id>0)
        {
            $options = "SELECT CO.*,CD.title,CD.duration,CD.period FROM `course_options` CO INNER JOIN course_durations CD ON CO.course_duration_id = CD.id   WHERE CO.id=".$id;
            $rows = $mysqli->query($options);
            $option = $rows->fetch_array();
                $option_id = $option["id"];

                $list .= '<div class="well"><label>' . $option["title"] . ' - ' . $option["duration"] . ' Tage - ' . $option["period"] . '  </label>
                <div class="form-group form-inline">
                <label>Preis:</label>
                <input type="text" placeholder="Preis" class="form-control" name="price-' . $option_id . '" value="' . $option["price"] . '">
                <input type="text" placeholder="Pseudo Preis" class="form-control" name="pseudo_price-' . $option_id . '" value="' . $option["pseudo_price"] . '">
                </div>
                <div class="form-group form-inline">
                <label>Preis EZ:</label>
                <input type="text" placeholder="Preis 1 Person (optional)" class="form-control" name="price_solo-' . $option_id . '" value="' . $option["price_solo"] . '">
                <input type="text" placeholder="Pseudo Preis 1 Person (optional)" class="form-control" name="pseudo_price_solo-' . $option_id . '" value="' . $option["pseudo_price_solo"] . '">
                </div>
                <div class="form-group form-inline">
                <label>Rabatt:</label>
                <input type="text" placeholder="Rabatt" class="form-control" name="discount-' . $option_id . '" value="' . $option["discount"] . '">
                </div>
                </div>';

            print json_encode(['success'=>1,'parent'=>$course_id,'options'=>$list]);
        }
        else {
            print json_encode(['success'=>0,'parent'=>'','options'=>'']);
        }

    }

    /**
     * Get Hotel Name
     */
    public function getSchoolName()
    {
        global $mysqli;
        $school = $mysqli->query("SELECT title FROM schools WHERE id=".$_GET["sid"]);
        $row  = $school->fetch_array();
        return $row["title"];
    }

}