<?php
require_once "dbconnection-class.php";

class ManageSchools
{
    /**
     * view selected school details - to edit
     * @return mixed
     */
    public function viewSchool()
    {
        global $mysqli, $_GET;

        $school = "SELECT * FROM `schools` WHERE id=" . $_GET['sid'];
        if (!$results = $mysqli->query($school))
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
        $status = array("online" => "Online", "offline" => "Offline", "deleted" => "Gelöscht", "hidden" => "Versteckt");
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
     * get School Group - options
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
     * Ajax - select2 - return list of all Hotels matching query
     */
    public function getHotelList()
    {
        global $mysqli, $_GET;
        $results = '';
        $select_htl = $mysqli->query("SELECT title,id FROM hotels WHERE status<>'deleted' AND (title<>'' OR title<>null) AND `title` LIKE '%" . $_GET['q'] . "%' ORDER BY title");
        while ($row = $select_htl->fetch_assoc()) {
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
    public function createSchool()
    {
        global $mysqli, $_POST;

        $title          = $mysqli->real_escape_string($_POST['title']);
        $alias          = $mysqli->real_escape_string($_POST['alias']);
        $subtitle       = $mysqli->real_escape_string($_POST['subtitle']);
        $group_id       = $_POST['group_id'];
        $description    = $mysqli->real_escape_string($_POST['description']);
        $address        = $mysqli->real_escape_string($_POST['address']);
        $coords         = $mysqli->real_escape_string($_POST['coords']);
        $city           = $mysqli->real_escape_string($_POST['city']);
        $location       = $mysqli->real_escape_string($_POST['location']);
        $country        = $mysqli->real_escape_string($_POST['country']);
        $status         = 'hidden';
        $holes          = $mysqli->real_escape_string($_POST['holes']);
        $duration       = $mysqli->real_escape_string($_POST['duration']);
        $training       = $mysqli->real_escape_string($_POST['training']);
        $flex_booking   = $mysqli->real_escape_string($_POST['flex_booking']);
        $new            = $mysqli->real_escape_string($_POST['new']);

        $insert_qry = "INSERT INTO `schools` (`group_id`, `title`, `alias`, `subtitle`, `description`, `address`, `coords`, `city`, `location`, `country`, `status`, `holes`, `training`,`duration`, `flex_booking`,`new`)
                        VALUES (".$group_id.",'".$title."','".$alias."','".$subtitle."','".$description."','".$address."', '".$coords."', '".$city."', '".$location."', '".$country."', '".$status."','".$holes."','".$training."','".$duration."','".$flex_booking."','".$new."')";

        $mysqli->query($insert_qry);
        $id = $mysqli->insert_id;
        $mes = ($id>0)?1:0;

        header("location:create_school.php?r=".$mes); // to avoid resubmitting data
    }

    public function updateSchool()
    {
        global $mysqli, $_POST, $_GET;

        $id             = $_GET['sid'];
        $title          = $mysqli->real_escape_string($_POST['title']);
        $alias          = $mysqli->real_escape_string($_POST['alias']);
        $subtitle       = $mysqli->real_escape_string($_POST['subtitle']);
        $status         = $mysqli->real_escape_string($_POST['status']);
        $group_id       = $_POST['group_id'];
        $description    = $mysqli->real_escape_string($_POST['description']);
        $address        = $mysqli->real_escape_string($_POST['address']);
        $coords         = $mysqli->real_escape_string($_POST['coords']);
        $city           = $mysqli->real_escape_string($_POST['city']);
        $location       = $mysqli->real_escape_string($_POST['location']);
        $country        = $mysqli->real_escape_string($_POST['country']);
        $holes          = $mysqli->real_escape_string($_POST['holes']);
        $duration       = $mysqli->real_escape_string($_POST['duration']);
        $training       = $mysqli->real_escape_string($_POST['training']);
        $flex_booking   = $mysqli->real_escape_string($_POST['flex_booking']);
        $new            = $mysqli->real_escape_string($_POST['new']);

        $update_qry = "UPDATE `schools` SET `group_id`=".$group_id.",
        `title`='".$title."',
        `alias`='".$alias."',
        `subtitle`='".$subtitle."',
        `description`='".$description."',
        `address`='".$address."',
        `coords`='".$coords."',
        `city`='".$city."',
        `location`='".$location."',
        `country`='".$country."',
        `holes`='".$holes."',
        `duration`='".$duration."',
        `training`='".$training."',
        `flex_booking`='".$flex_booking."',
        `status`='".$status."',
        `new`='".$new."' WHERE id=".$id;

        $mysqli->query($update_qry);
        $mes = ($mysqli->affected_rows>=0)?1:0;
        header("location:edit_school.php?sid=".$id."&r=".$mes); // to avoid resubmitting data
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
  Erfolgreich ' . $action . '!
</div>' : '<div class="alert alert-danger" id="alert_div">
  <strong>Oops!</strong> Some error occurred.
</div>';

        }
        return $alert_mes;
    }
}