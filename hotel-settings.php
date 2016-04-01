<?php
include "includes/header.php";
require_once "classes/hotel-settings-class.php";
$service     = new hotelSettingsService();
$hotelname = $service->getHotelName();
$hotel_id  = $_GET['editID'];
?>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-lightness/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery.multiselect.css" />
    <link rel="stylesheet" type="text/css" href="css/prettify.css" />
    <style>
        .ui-multiselect {
            width: 100% !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <?php require_once 'includes/hotel_sidebar.php'; ?>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <h1 class="page-header">Einstellungen <small>(<?=$hotelname?>)</small></h1>
                <h3 class="sub-header">Zimmer und Piktogramme hinzufügen</h3>
                <div class="row">
                    <form method="post">
                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Zimmer hinzufügen</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select id="hotelRoomsSelect" class="form-control" multiple="multiple" size="5">
                                            <?php
                                            $select     = $service->listRooms();
                                            while($row = $select->fetch_array())
                                            {
                                                ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Piktogramme hinzufügen</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select id="hotelPictogramsSelect" class="form-control" multiple="multiple" size="5">
                                            <?php
                                            $selectpicto  = $service->listPictograms();
                                            while ($pictogram_array = $selectpicto->fetch_array()) {
                                                ?>
                                                <option value="<?php echo $pictogram_array['id']; ?>"><?php echo $pictogram_array['alt']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div><!-- col-md-6 -->
                    </form>
                </div><!-- Row End -->

            </div>

            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <h3 class="sub-header">Hotel & Kurs Details</h3>
                <div class="row">
                    <form method="post">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Hotel & Kurs Details hinzufügen</div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <!-- Table -->
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Kursdauer</th>
                                                <th>Titel</th>
                                                <th>Nächte</th>
                                                <th>Ankunft</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $hotel_id = $_GET['editID'];
                                            $hotel_courses_details = $service->listHotelCourses($hotel_id);
                                            while($hotel_courses_array = $hotel_courses_details->fetch_array())
                                            {
                                                $course_duration_id    = $hotel_courses_array['id'];
                                                $hotel_course_execute  = $service->hotelCourseDetails($hotel_id, $course_duration_id);
                                                $num_rows              = $hotel_course_execute->num_rows;
                                                if($num_rows > 0){
                                                    while( $hotel_course_select_array = $hotel_course_execute->fetch_array() )
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $hotel_courses_array['duration']; ?> Tage</td>
                                                            <td><?php echo $hotel_courses_array['title']; ?></td>
                                                            <td>
                                                                <input type="text" class="form-control" id="nights_<?php echo $hotel_course_select_array['id']; ?>" name="nights" value="<?php echo $hotel_course_select_array['nights']; ?>">
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="arrival" id="arrival_<?php echo $hotel_course_select_array['id']; ?>">
                                                                    <?php
                                                                    $html = '<option value="">Wochentag der Ankunft im Hotel</option>';
                                                                    $options = array('1' => 'Montag', '2' => 'Dienstag', '3' => 'Mittwoch', '4' => 'Donnerstag', '5' => 'Freitag', '6' => 'Samstag', '7' => 'Sonntag');
                                                                    foreach($options as $val => $text) {
                                                                        $selected = ($val==$hotel_course_select_array['arrival'])?'selected="selected"':'';
                                                                        $html .= '<option value="'.$val.'" '.$selected.'>'.$text.'</option>';
                                                                    }
                                                                    echo $html;
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-primary update_hotel_courses" id="<?php echo $hotel_course_select_array['id']; ?>">Aktualisieren</button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else{
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $hotel_courses_array['duration']; ?> Tage</td>
                                                        <td><?php echo $hotel_courses_array['title']; ?></td>
                                                        <td>
                                                            <input type="text" class="form-control" id="nights_<?php echo $hotel_courses_array['id']; ?>" name="nights" value="<?php echo $hotel_course_select_array['nights']; ?>">
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="arrival" id="arrival_<?php echo $hotel_courses_array['id']; ?>">
                                                                <?php
                                                                $html = '<option value="">Wochentag der Ankunft im Hotel</option>';
                                                                $options = array('1' => 'Montag', '2' => 'Dienstag', '3' => 'Mittwoch', '4' => 'Donnerstag', '5' => 'Freitag', '6' => 'Samstag', '7' => 'Sonntag');
                                                                foreach($options as $val => $text) {
                                                                    $html .= '<option value="'.$val.'">'.$text.'</option>';
                                                                }
                                                                echo $html;
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary save_hotel_courses" id="<?php echo $hotel_courses_array['id']; ?>">Speichern</button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div><!-- col-md-6 -->
                    </form>
                </div><!-- Row End -->

            </div>

        </div>
    </div>
<?php include "includes/scripts.php"; ?>
    <!-- Multi Select Libraries -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
    <script src="js/prettify.js"></script>
    <script src="js/jquery.multiselect.js"></script>

    <script type="text/javascript">
        $(function(){

            /* save_hotel_courses */
            $(".save_hotel_courses").click(function(){
                var ID                  = $(this).attr('id');
                var nights              = $("#nights_"+ID).val();
                var arrival             = $("#arrival_"+ID).val();
                var save_hotel_course   = 'save_hotel_course';
                var hotelID             = '<?php echo $_GET["editID"];?>';
                $.post('hotel-settings-submit.php', {nights: nights, arrival: arrival, course_duration_id: ID, save_hotel_course: save_hotel_course, hotelID: hotelID},
                    function(response){
                        location.reload();
                    });
            });

            /* update_hotel_courses */
            $(".update_hotel_courses").click(function(){
                var ID                  = $(this).attr('id');
                var nights              = $("#nights_"+ID).val();
                var arrival             = $("#arrival_"+ID).val();
                var update_hotel_course = 'update_hotel_course';
                $.post('hotel-settings-submit.php', {nights: nights, arrival: arrival, update_hotel_course: update_hotel_course, hotel_course_ID: ID},
                    function(response){
                        location.reload();
                    });
            });

            /* Selected hotel rooms values default */
            var hotel_id = '<?php echo $_GET["editID"]?>';
            var selected = 'selected';
            $.post("hotel-settings-submit.php", {selected: selected, hotel_id: hotel_id}, function(data){
                var valArr = data;
                $.each(data,function(index,item)
                {
                    $("#hotelRoomsSelect").multiselect("widget").find(":checkbox[value='"+item+"']").attr("checked","checked");
                    $("#hotelRoomsSelect option[value='" + item + "']").attr("selected", 1);
                    $("#hotelRoomsSelect").multiselect("refresh");
                });
            });

            /* Selected hotel pictograms values default */
            var hotel_id           = '<?php echo $_GET["editID"]?>';
            var selectedPictograms = 'selectedPictograms';
            $.post("hotel-settings-submit.php", {selectedPictograms: selectedPictograms, hotel_id: hotel_id}, function(data){
                var valArray = data;
                i = 0, size = valArray.length;
                $.each(data,function(index,item)
                {
                    $("#hotelPictogramsSelect").multiselect("widget").find(":checkbox[value='"+item+"']").attr("checked","checked");
                    $("#hotelPictogramsSelect  option[value='" + item + "']").attr("selected", 1);
                    $("#hotelPictogramsSelect").multiselect("refresh");
                });
            });

            /* Multiple Select hotel rooms */
            $("#hotelRoomsSelect").multiselect({
                header: "Choose options below",
                click: function(event, ui){
                    var check              = ui.checked;
                    var multihotelroomdata = ui.value;
                    var hotelroomsubmit    = 'hotelroomsubmit';
                    var hotelID            = '<?php echo $_GET["editID"];?>';
                    if(check === true){ //insert
                        $.post('hotel-settings-submit.php', {multihotelroomdata: multihotelroomdata, hotelroomsubmit: hotelroomsubmit, hotelID: hotelID},
                            function(data){
                            });
                    }
                    else{ //delete
                        var multidataDel  = ui.value;
                        var hotelroomsDel = 'hotelroomsDel';
                        $.post('hotel-settings-submit.php', {multidataDel: multidataDel, hotelroomsDel: hotelroomsDel, hotelID: hotelID},
                            function(data){
                            });
                    }
                }
            });

            /* Multiple Select hotel pictograms */
            $("#hotelPictogramsSelect").multiselect({
                header: "Choose options below",
                click: function(event, ui){
                    var check               = ui.checked;
                    var hotelpictodata      = ui.value;
                    var hotelpictogramsubmit= 'hotelpictogramsubmit';
                    var hotelID             = '<?php echo $_GET["editID"];?>';
                    if(check === true){ //insert
                        $.post('hotel-settings-submit.php', {hotelpictodata: hotelpictodata, hotelpictogramsubmit: hotelpictogramsubmit, hotelID: hotelID},
                            function(data){
                            });
                    }
                    else{ //delete
                        var pictodataDel       = ui.value;
                        var hotelpictodataDel  = 'hotelpictodataDel';
                        $.post('hotel-settings-submit.php', {pictodataDel: pictodataDel, hotelpictodataDel: hotelpictodataDel, hotelID: hotelID},
                            function(data){
                            });
                    }
                }
            });

        });
        $('#navHtlSet').addClass('active');
    </script>

<?php include "includes/footer.php"; ?>