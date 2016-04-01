<?php
require_once "dbconnection-class.php";

class ManageHotels
{
    /**
     * view selected hotel details - to edit
     * @return mixed
     */
     public function viewHotel()
     {
         global $mysqli, $_GET;

         $hotel = "SELECT * FROM `hotels` WHERE id=" . $_GET['hid'];
         if (!$results = $mysqli->query($hotel))
         {
             echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
         }
         return $results->fetch_array();
     }

    /**
     * get status options
     * @param string $option
     * @return string
     */
     public function getStatus($option='')
     {
         $status = array("online" => "Online", "offline" => "Offline", "offer" => "Nur für Angebote", "hidden" => "Versteckt");
         $options = '';
         foreach ($status AS $key => $value) {
             $selected = "";
             if ($key == $option)
                 $selected = ' selected="selected"';

             $options .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
         }
         return $options;
     }

    /**
     * get Hotel Group - options
     * @param string $option
     * @return string
     */
    public function getGroup($option='')
    {
        global $mysqli;

        $groups ='';
        $select_group = $mysqli->query("SELECT * FROM groups");
        while ($fetch_group = $select_group->fetch_assoc()) {
            $selected = "";
            if ($fetch_group["id"] == $option)
                $selected = ' selected="selected"';

            $groups.= '<option value="' . $fetch_group["id"] . '"' . $selected . '>' . $fetch_group["title"] . '</option>';
        }
        return $groups;
    }

    /**
     * Ajax - select2 - return list of all schools matching query
     */
    public function getSchoolList()
    {
        global $mysqli, $_GET;
        $results = '';
        $select_school = $mysqli->query("SELECT title,id FROM schools WHERE status<>'deleted' AND (title<>'' OR title<>null) AND `title` LIKE '%" . $_GET['q'] . "%' ORDER BY title");
        while ($row = $select_school->fetch_assoc()) {
                $results[] = array("text" => $row["title"], "id" => $row["id"]);
        }
        if($results!='')
            echo json_encode($results);
        else
            echo json_encode(array());
    }

    /**
     * create Hotel with all posted data
     */
    public function createHotel()
    {
        global $mysqli, $_POST;

        $title          = $mysqli->real_escape_string($_POST['title']);
        $alias          = $mysqli->real_escape_string($_POST['alias']);
        $subtitle       = $mysqli->real_escape_string($_POST['subtitle']);
        $group_id       = $_POST['group_id'];
        $school_id      = ($_POST['school_id']>0)?$_POST['school_id']:null;
        $stars          = $_POST['stars'];
        $golfdistance   = $_POST['golfdistance'];
        $address        = $mysqli->real_escape_string($_POST['address']);
        $coords         = $mysqli->real_escape_string($_POST['coords']);
        $city           = $mysqli->real_escape_string($_POST['city']);
        $location       = $mysqli->real_escape_string($_POST['location']);
        $country        = $mysqli->real_escape_string($_POST['country']);
        $country_short  = $mysqli->real_escape_string($_POST['country_short']);
        $description    = $mysqli->real_escape_string($_POST['description']);
        $terms          = $mysqli->real_escape_string($_POST['terms']);
        $services       = $mysqli->real_escape_string($_POST['services']);
        $new            = $_POST['new'];

        $insert_qry = "INSERT INTO `hotels` (`group_id`, `school_id`, `title`, `alias`, `subtitle`, `description`, `terms`, `services`,`golfdistance`, `stars`,`address`, `coords`, `city`, `location`, `country`, `country_short`, `additional_nights`,`status`,`new`)
                        VALUES (".$group_id.",'".$school_id."','".$title."','".$alias."','".$subtitle."','".$description."','".$terms."','".$services."','".$golfdistance."','".$stars."','".$address."', '".$coords."', '".$city."', '".$location."', '".$country."', '".$country_short."', '".$_POST['additional_nights']."','hidden','".$new."')";

        $mysqli->query($insert_qry);
        $id = $mysqli->insert_id;
        $mes = ($id>0)?1:0;

        header("location:create_hotel.php?r=".$mes); // to avoid resubmitting data
    }

    /**
     * Update selected Hotel details
     */
    public function updateHotel()
    {
        global $mysqli, $_POST, $_GET;

        $id            = $_GET['hid'];
        $title         = $mysqli->real_escape_string($_POST['title']);
        $alias         = $mysqli->real_escape_string($_POST['alias']);
        $subtitle      = $mysqli->real_escape_string($_POST['subtitle']);
        $status        = $mysqli->real_escape_string($_POST['status']);
        $group_id      = $_POST['group_id'];
        $school_id     = ($_POST['school_id']>0)?$_POST['school_id']:null;
        $stars         = $_POST['stars'];
        $golfdistance  = $_POST['golfdistance'];
        $address       = $mysqli->real_escape_string($_POST['address']);
        $coords        = $mysqli->real_escape_string($_POST['coords']);
        $city          = $mysqli->real_escape_string($_POST['city']);
        $location      = $mysqli->real_escape_string($_POST['location']);
        $country       = $mysqli->real_escape_string($_POST['country']);
        $country_short = $mysqli->real_escape_string($_POST['country_short']);
        $description   = $mysqli->real_escape_string($_POST['description']);
        $terms         = $mysqli->real_escape_string($_POST['terms']);
        $services      = $mysqli->real_escape_string($_POST['services']);
        $new           = $_POST['new'];

        $update_qry = "UPDATE `hotels` SET `group_id`=".$group_id.",
        `school_id`='".$school_id."',
        `title`='".$title."',
        `alias`='".$alias."',
        `subtitle`='".$subtitle."',
        `description`='".$description."',
        `terms`='".$terms."',
        `services`='".$services."',
        `golfdistance`='".$golfdistance."',
        `stars`='".$stars."',
        `address`='".$address."',
        `coords`='".$coords."',
        `city`='".$city."',
        `location`='".$location."',
        `country`='".$country."',
        `country_short`='".$country_short."',
        `additional_nights`='".$_POST['additional_nights']."',
        `status`='".$status."',
        `new`='".$new."'  WHERE id=".$id;

        $mysqli->query($update_qry);
        $mes = ($mysqli->affected_rows>=0)?1:0;
        header("location:edit_hotel.php?hid=".$id."&r=".$mes); // to avoid resubmitting data

    }

    /**
     * get title of selected school
     * @param $id
     * @return mixed
     */
    public function getSelectedSchool($id)
    {
        global $mysqli;
        $hotel = $mysqli->query("SELECT title FROM `schools` WHERE id=" . $id);
        $row = $hotel->fetch_array();

        return $row['title'];
    }

    /**
     * show message after create/update
     * @param string $page - C -create, U - update
     * @return string
     */
    public function showMessage($page='C')
    {
        global $_GET;
        $alert_mes = '';
        $action = $page == 'C' ? 'Created' : 'Updated';
        if(isset($_GET['r'])) {
            $alert_mes = $_GET['r'] == 1 ? '<div class="alert alert-success" id="alert_div">
  Successfully ' . $action . '!
</div>' : '<div class="alert alert-danger" id="alert_div">
  <strong>Oops!</strong> Some error occurred.
</div>';

        }
        return $alert_mes;
    }

    /**
     * Show all bundle prices of hotel - edit mode
     * @return string
     */
    public function editPrices()
    {
        global $mysqli, $_GET;

        $prices_arr  = array();
        $prices_list = '';
        if (isset($_GET["hid"]) && $_GET["hid"]>0) {
            $hotel_id = $_GET["hid"];
            $query = "SELECT
                            hb.id,
                            ct.title AS course_title,
                            co.course_duration_id,
                            co.id as co_id,
                            cd.title AS cd_title,
                            cd.duration,
                            hb.date_begin,
                            hb.date_end,
                            hb.price,
                            hb.price_additional_night,
                            hb.pseudo_price,
                            rm.id AS rm_id,
                            rm.title AS room_title
                        FROM hotel_bundles hb
                        INNER JOIN course_options co ON co.id = hb.course_option_id
                        INNER JOIN course_durations cd ON cd.id = co.course_duration_id
                        INNER JOIN courses c ON c.id = co.course_id
                        INNER JOIN course_types ct ON ct.id = c.course_type_id
                        INNER JOIN rooms rm ON rm.id = hb.room_id
                        WHERE hb.hotel_id = '" . $hotel_id . "' AND  hb.date_end > NOW()
                        ORDER BY hb.date_begin DESC, course_title ASC, cd.duration ASC, hb.price ASC
                        ";
            $select_prices = $mysqli->query($query);
            if ($select_prices->num_rows == 0) {
                return "PREISUPDATE ERFORDERLICH!";
                exit;
            }
            //Grouping results in to array based on date,course_id ,room_id
            while ($fetch_prices = $select_prices->fetch_assoc()) {
                $prices_arr[$fetch_prices["date_begin"]."_".$fetch_prices["date_end"]][$fetch_prices["co_id"]][$fetch_prices["rm_id"]] =$fetch_prices;
            }
            $i=1;
            //processing each date range
            foreach ($prices_arr as $date => $courses) {
                $header_date = explode("_",$date);
                $header      = date("d.m.Y", strtotime($header_date[0])) . " bis " . date("d.m.Y", strtotime($header_date[1]));
                $date_input  = '<div class="form-group form-inline">
                                          <input size="15" class="form-control prcDate" name="date_begin" value="' . date("d.m.Y", strtotime($header_date[0])) . '" readonly> &nbsp; bis &nbsp;
                                          <input size="15" class="form-control prcDate" name="date_end" value="' . date("d.m.Y", strtotime($header_date[1])) . '" readonly>
                                      </div>';
                $price_input = "";
                //processing each courses
                foreach($courses as $course_id => $rooms) {
                    $course          = "";
                    $course_duration = "";
                    //processing each rooms
                    foreach($rooms as $room_id => $prices) {
                        if ($course != $prices["course_title"]) {
                            $course = $prices["course_title"];
                            $price_input .= '<h4>' . $course . '</h4>';
                        }
                        if ($course_duration != $prices["course_duration_id"]) {
                            $course_duration = $prices["course_duration_id"];
                            $price_input .= "<h5>" . $prices["duration"] . " Tage " . $prices["cd_title"] . "</h5>";
                        }
                        $price_input .= '<label for="">' . $prices["room_title"] . ' </label>
                                            <div class="form-group form-inline">
                                            <strong><input size="5" class="form-control" name="price[' . $prices["id"] . ']" value="' . $prices["price"] . '"> EUR</strong> &nbsp;
                                            statt <strong><input size="5" class="form-control" name="pseudo_price[' . $prices["id"] . ']" value="' . $prices["pseudo_price"] . '"> EUR</strong> &nbsp;
                                            Zusatznacht <input class="form-control" size="5" name="price_additional_night[' . $prices["id"] . ']" value="' . $prices["price_additional_night"] . '"> <strong>EUR</strong>
                                         </div>';
                    }
                    $additional_inputs = $this->getNewRooms($hotel_id,$course_id,$header_date[0],$header_date[1]);
                    $price_input .= $additional_inputs;
                }
                if($i==1) {
                    $collapse  = "in";
                    $a_class   = '';
                    $expanded  = "true";
                }
                else {
                    $collapse  = "";
                    $a_class   ='class="collapsed"';
                    $expanded  = "false";
                }
                $prices_list .= '<div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading'.$i.'"><a '.$a_class.' role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" aria-expanded="'.$expanded.'" aria-controls="collapse'.$i.'"><h3 id="panelHead'.$i.'">' . $header . '</h3></a></div>
                                    <div id="collapse'.$i.'" class="panel-collapse collapse '.$collapse.'" role="tabpanel" aria-labelledby="heading'.$i.'">
                                    <div class="panel-body">
                                        <form method="POST" id="pricesFrm_'.$i.'" onsubmit="return false">
                                            <div class="well">
                                            '. $date_input .'
                                            '. $price_input .'
                                            </div>
                                            <input type="hidden" name="hotelid" value="'.$hotel_id.'">
                                            <button type="button" id="pricesBtn_'.$i.'" class="btn btn-block btn-primary btn-lg prcUpdate">Preise aktualisieren</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>';
                $i++;

            }
        }

        return $prices_list;
    }

    /**
     * update all prices of a selected hotel bundle - Ajax
     */
    public function updatePrices()
    {
        global $mysqli, $_POST;

        $mes =0;
        $heading ='';
        $ids = array();
        foreach($_POST["price"] AS $key => $value) {
            if ($value != "" AND $value != 0) {
                $mysqli->query("UPDATE hotel_bundles SET price = '" . $value . "', price_additional_night = '" . $_POST["price_additional_night"][$key] . "', pseudo_price = '" . $_POST["pseudo_price"][$key] . "', date_begin='".date("Y-m-d", strtotime($_POST["date_begin"]))."', date_end='".date("Y-m-d", strtotime($_POST["date_end"]))."' WHERE id = '" . $key . "'");
                $mes = ($mysqli->affected_rows>=0)?1:0;
            }
        }
        if(isset($_POST["add_price"])) {
            foreach ($_POST["add_price"] AS $index => $value) {
                foreach ($value AS $co_id => $fields) {
                    foreach ($fields AS $room => $price) {
                        if ($price != "") {
                            $additional_price = $_POST["add_price_additional_night"][$index][$co_id][$room];
                            $pseudo_price     = $_POST["add_pseudo_price"][$index][$co_id][$room];
                            if ($additional_price == "")
                                $additional_price = 0;
                            if ($pseudo_price == "")
                                $pseudo_price = 0;

                            $mysqli->query("INSERT INTO hotel_bundles (`hotel_id`, `room_id`, `course_option_id`, `price`, `price_additional_night`, `pseudo_price`, `date_begin`, `date_end`, `updated_at`) VALUES('" . $_POST["hotelid"] . "', '" . $room . "', '" . $co_id . "', '" . $price . "', '" . $additional_price . "','" . $pseudo_price . "', '" . date("Y-m-d", strtotime($_POST["date_begin"])) . "', '" . date("Y-m-d", strtotime($_POST["date_end"])) . "', NOW())");
                            $ids[] = $mysqli->insert_id;
                        }
                    }
                }
            }
        }
        if($_POST["date_begin"]!='' && $_POST["date_end"]!='')
            $heading = $_POST["date_begin"] . " bis " . $_POST["date_end"];

        print json_encode(['success'=>$mes,'heading' =>$heading]);
    }

    /**
     * show inputs form for adding new price bundle
     * @return string
     */
    public function showAddPriceForm()
    {
        global $mysqli, $_GET;
        $formInputs = '';
        $roomInputs = '';
        if (isset($_GET["hid"]) && $_GET["hid"]>0) {
            $hotel_id = $_GET["hid"];
            $select_courses = $mysqli->query("SELECT
                                            ct.id,
                                            c.id AS id_course,
                                            ct.title AS course_title,
                                            cd.title AS duration_title,
                                            cd.duration,
                                            co.course_duration_id,
                                            co.id AS co_id
                                        FROM courses c
                                        INNER JOIN schools s ON s.id = c.school_id
                                        INNER JOIN hotels h ON h.school_id = s.id
                                        INNER JOIN course_types ct ON ct.id = c.course_type_id
                                        INNER JOIN course_options co ON co.course_id = c.id
                                        INNER JOIN course_durations cd ON cd.id = co.course_duration_id
                                        WHERE h.id = '" . $hotel_id . "'
                                        ");
            $c_course = "";
            $c_course_duration = "";
            $price_input = '';
            while ($fetch_courses = $select_courses->fetch_assoc()) {
                if ($c_course != $fetch_courses["course_title"]) {
                    $c_course = $fetch_courses["course_title"];
                    $price_input .= "<h4>" . $c_course . "</h4>";
                }
                if ($c_course_duration != $fetch_courses["course_duration_id"]) {
                    $c_course_duration = $fetch_courses["course_duration_id"];
                    $price_input .= "<h5>" . $fetch_courses["duration"] . " Tage " . $fetch_courses["duration_title"] . "</h5>";
                }

                $select_rooms = $mysqli->query("SELECT RM.title, HR.room_id AS id_room
                                            FROM hotel_rooms HR
                                            INNER JOIN rooms RM ON RM.id = HR.room_id
                                            WHERE HR.hotel_id = '" . $hotel_id . "'
                                            GROUP BY HR.room_id");
                if ($select_rooms->num_rows > 0) {
                    while ($fetch_rooms = $select_rooms->fetch_assoc()) {
                        $price_input .= '<label for="">' . $fetch_rooms["title"] . '</label>
                                     <div class="form-group form-inline">
                                         <input type="text" class="form-control" id="" name="data_price[][' . $fetch_courses["co_id"] . '][' . $fetch_rooms["id_room"] . ']" placeholder="Preis pro Person">
                                         <input type="text" class="form-control" id="" name="data_pseudoprice[][' . $fetch_courses["co_id"] . '][' . $fetch_rooms["id_room"] . ']" placeholder="Statt">
                                         <input type="text" class="form-control" id="" name="data_addprice[][' . $fetch_courses["co_id"] . '][' . $fetch_rooms["id_room"] . ']" placeholder="Preis pro Zusatznacht">
                                     </div>';
                    }
                }
                else {
                    return '<label>No Rooms</label>&nbsp;<a id="settingNavA" href="hotel-settings.php?editID='.$hotel_id.'">Settings</a>';
                    exit;
                }
            }
            if ($price_input != '') {
                $formInputs = '<form method="POST" id="addPriceFrm">
                                <div class="form-group form-inline">
                                    <input type="text" class="form-control prcDate" id="date_begin" name="date_begin" placeholder="Date begin" readonly> &nbsp;bis&nbsp;
                                    <input type="text" class="form-control prcDate" id="date_end" name="date_end" placeholder="Date end" readonly>
                                </div>' . $price_input . '
                                <input type="hidden" name="hotel_id" value="'.$hotel_id.'">
                               </form>';
            }
        }
        return $formInputs;
    }


    /**
     * Add new price bundle - Ajax
     */
    public function addHotelPrices()
    {
        global $mysqli, $_POST;
        $ids =array();
        foreach($_POST["data_price"] AS $index => $value) {
            foreach($value AS $co_id => $fields) {
                foreach($fields AS $room => $price) {
                    if ($price != "") {
                        $additional_price = $_POST["data_addprice"][$index][$co_id][$room];
                        $pseudo_price     = $_POST["data_pseudoprice"][$index][$co_id][$room];
                        if ($additional_price == "")
                            $additional_price = 0;
                        if ($pseudo_price == "")
                            $pseudo_price = 0;

                        $mysqli->query("INSERT INTO hotel_bundles (`hotel_id`, `room_id`, `course_option_id`, `price`, `price_additional_night`, `pseudo_price`, `date_begin`, `date_end`, `created_at`) VALUES('" . $_POST["hotel_id"] . "', '" . $room . "', '" . $co_id . "', '" . $price . "', '" . $additional_price . "','".$pseudo_price."', '" . date("Y-m-d", strtotime($_POST["date_begin"])) . "', '" . date("Y-m-d", strtotime($_POST["date_end"])) . "', NOW())");
                        $ids[] = $mysqli->insert_id;
                    }
                }
            }
        }
        if(count($ids)>0) {
            print json_encode(['success'=>1]);
        }
        else {
            print json_encode(['success'=>0]);
        }
    }

    /**
     * get All new rooms which are not present at the time of adding a bundle - to update
     * @param $hotel_id
     * @param $co_id
     * @param $begin
     * @param $end
     * @return string
     */
    protected function getNewRooms($hotel_id,$co_id,$begin,$end)
    {
        global $mysqli;
        $additional_input  = '';
        $select_rooms = $mysqli->query("SELECT RM.title, HR.room_id AS id_room
                                            FROM hotel_rooms HR
                                            INNER JOIN rooms RM ON RM.id = HR.room_id
                                            WHERE HR.hotel_id = '" . $hotel_id . "' AND HR.room_id NOT IN(select room_id from hotel_bundles where hotel_id='".$hotel_id."' AND course_option_id='".$co_id."' AND date_begin='".$begin."' AND date_end='".$end."')
                                            GROUP BY HR.room_id ORDER BY RM.id");

        if ($select_rooms->num_rows > 0) {
            while ($fetch_rooms = $select_rooms->fetch_assoc()) {
                $additional_input .= '<label for="">' . $fetch_rooms["title"] . ' </label>
                    <div class="form-group form-inline">
                    <strong><input size="5" class="form-control" name="add_price[][' . $co_id . '][' . $fetch_rooms["id_room"] . ']" value=""> EUR</strong> &nbsp;
                    statt <strong><input size="5" class="form-control" name="add_pseudo_price[][' . $co_id . '][' . $fetch_rooms["id_room"] . ']" value=""> EUR</strong> &nbsp;
                    Zusatznacht <input class="form-control" size="5" name="add_price_additional_night[][' . $co_id . '][' . $fetch_rooms["id_room"] . ']" value=""> <strong>EUR</strong>
                    </div>';
            }
        }
        return $additional_input;
    }

    /**
     * list preciously archived bundle for edit
     * @return string
     */
    public function editArchivedPrices()
    {
        global $mysqli, $_GET;

        $prices_arr   = array();
        $archive_list = '';
        if (isset($_GET["hid"]) && $_GET["hid"]>0) {
            $hotel_id = $_GET["hid"];
            $archived_date = $this->getPreviousArchiveDate($hotel_id);
            if($archived_date=="") {
                return "";
                exit;
            }
            $query = "SELECT
                            hb.id,
                            ct.title AS course_title,
                            co.course_duration_id,
                            co.id as co_id,
                            cd.title AS cd_title,
                            cd.duration,
                            hb.date_begin,
                            hb.date_end,
                            hb.price,
                            hb.price_additional_night,
                            hb.pseudo_price,
                            rm.id AS rm_id,
                            rm.title AS room_title
                        FROM hotel_bundles hb
                        INNER JOIN course_options co ON co.id = hb.course_option_id
                        INNER JOIN course_durations cd ON cd.id = co.course_duration_id
                        INNER JOIN courses c ON c.id = co.course_id
                        INNER JOIN course_types ct ON ct.id = c.course_type_id
                        INNER JOIN rooms rm ON rm.id = hb.room_id
                        WHERE hb.hotel_id = '" . $hotel_id . "' AND  hb.date_end = '".$archived_date."'
                        ORDER BY hb.date_end DESC, hb.date_begin DESC, course_title ASC, cd.duration ASC, hb.price ASC ;
                        ";
            $select_prices = $mysqli->query($query);
            if ($select_prices->num_rows == 0) {
                return "";
                exit;
            }
            //Grouping results in to array based on date,course_id ,room_id
            while ($fetch_prices = $select_prices->fetch_assoc()) {
                $prices_arr[$fetch_prices["date_begin"]."_".$fetch_prices["date_end"]][$fetch_prices["co_id"]][$fetch_prices["rm_id"]] =$fetch_prices;
            }
            $i='A';
            //processing previously archived date range
            foreach ($prices_arr as $date => $courses) {
                $header_date = explode("_",$date);
                $header      = date("d.m.Y", strtotime($header_date[0])) . " bis " . date("d.m.Y", strtotime($header_date[1]));
                $date_input  = '<div class="form-group form-inline">
                                          <input size="15" class="form-control prcDate" name="date_begin" value="' . date("d.m.Y", strtotime($header_date[0])) . '" readonly> &nbsp; bis &nbsp;
                                          <input size="15" class="form-control prcDate" name="date_end" value="' . date("d.m.Y", strtotime($header_date[1])) . '" readonly>
                                      </div>';
                $price_input = "";
                //processing each courses
                foreach($courses as $course_id => $rooms) {
                    $course          = "";
                    $course_duration = "";
                    //processing each rooms
                    foreach($rooms as $room_id => $prices) {
                        if ($course != $prices["course_title"]) {
                            $course = $prices["course_title"];
                            $price_input .= '<h4>' . $course . '</h4>';
                        }
                        if ($course_duration != $prices["course_duration_id"]) {
                            $course_duration = $prices["course_duration_id"];
                            $price_input .= "<h5>" . $prices["duration"] . " Tage " . $prices["cd_title"] . "</h5>";
                        }
                        $price_input .= '<label for="">' . $prices["room_title"] . ' </label>
                                            <div class="form-group form-inline">
                                            <strong><input size="5" class="form-control" name="price[' . $prices["id"] . ']" value="' . $prices["price"] . '"> EUR</strong> &nbsp;
                                            statt <strong><input size="5" class="form-control" name="pseudo_price[' . $prices["id"] . ']" value="' . $prices["pseudo_price"] . '"> EUR</strong> &nbsp;
                                            Zusatznacht <input class="form-control" size="5" name="price_additional_night[' . $prices["id"] . ']" value="' . $prices["price_additional_night"] . '"> <strong>EUR</strong>
                                         </div>';
                    }
                    $additional_inputs = $this->getNewRooms($hotel_id,$course_id,$header_date[0],$header_date[1]);
                    $price_input .= $additional_inputs;
                }
                $archive_list .= '<h2>Archive</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading"><h3 id="panelHead'.$i.'">' . $header . '</h3></div>
                                    <div class="panel-body">
                                        <form method="POST" id="pricesFrm_'.$i.'" onsubmit="return false">
                                            <div class="well">
                                            '. $date_input .'
                                            '. $price_input .'
                                            </div>
                                            <input type="hidden" name="hotelid" value="'.$hotel_id.'">
                                            <button type="button" id="pricesBtn_'.$i.'" class="btn btn-block btn-primary btn-lg prcUpdate">Preise aktualisieren</button>
                                        </form>
                                    </div>
                                </div>';

            }

        }
        return $archive_list;
    }

    /**
     * get last Archived date
     * @param $hotel_id
     * @return string
     */
    protected function getPreviousArchiveDate($hotel_id)
    {
        global $mysqli;
        $date =$mysqli->query("SELECT date_end FROM `hotel_bundles` WHERE `date_end`<=NOW() AND hotel_id ='".$hotel_id."' ORDER BY date_end DESC, date_begin DESC, price ASC LIMIT 0,1");

        if($date->num_rows>0) {
            $row = $date->fetch_object();
            return $row->date_end;
        }
        else{
            return "";
        }
    }

    /**
     * Get Hotel Name
     */
    public function getHotelName()
    {
        global $mysqli;
        $hotel = $mysqli->query("SELECT title FROM hotels WHERE id=".$_GET["hid"]);
        $row  = $hotel->fetch_array();
        return $row["title"];
    }

}