<?php
include "includes/header.php";
require_once "classes/course-durations-list-class.php";
$service                              = new courseDurationsService();
?>
    <div class="container-fluid">
        <div class="row">
            <?php require_once 'includes/sidebar.php';
            ?>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <h2 class="sub-header">Kursdauern</h2>

                <div class="form-group text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Kursdauer hinzufügen</button>
                </div>

                <div class="panel panel-primary">
                    <!-- Default panel contents -->
                    <div class="panel-heading">Kursdauern</div>
                    <div class="table-responsive">
                        <!-- Table -->
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Titel</th>
                                <th>Dauer (Tage)</th>
                                <th>Zeitraum (Beschreibung)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            // How many adjacent pages should be shown on each side?
                            $adjacents                         = 3;
                            /*
                                 First get total number of rows in data table.
                                 If you have a WHERE clause in your query, make sure you mirror it here.
                            */
                            $countCourseDurations              = $service->countCourseDurations();
                            if($countCourseDurations){
                                $countCourseDurations_result   = $mysqli->use_result();
                                $total_items                   = $countCourseDurations_result->fetch_array();
                            }
                            $countCourseDurations_result->free();/* free result set */

                            /* Setup vars for query. */
                            $targetpage                        = "course-durations-list.php";   //your file name  (the name of this file)
                            $limit                             = 15;                 //how many items to show per page
                            if(isset($_GET['page'])) {
                                $page   = $_GET['page'];
                                $start  = ($page - 1) * $limit;      //first item to display on this page
                            } else {
                                $page  = 0;
                                $start = 0;               //if no page var is given, set start to 0
                            }
                            /* Setup page vars for display. */
                            if ($page == 0) $page = 1;      //if no page var is given, default to 1.
                            $prev = $page - 1;              //previous page is page - 1
                            $next = $page + 1;              //next page is page + 1
                            $lastpage = ceil($total_items[0]/$limit);//lastpage is = total pages / items per page, rounded up.
                            $lpm1 = $lastpage - 1;            //last page minus 1
                            /*
                               Now we apply our rules and draw the pagination object.
                               We're actually saving the code to a variable in case we want to draw it more than once.
                             */
                            $pagination = "";
                            if($lastpage > 1)
                            {
                                $pagination .= "<nav><ul class=\"pagination\">";
                                //previous button
                                if ($page > 1)
                                    $pagination.= "<li><a href=\"$targetpage?page=$prev\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
                                else
                                    $pagination.= "<li class=\"disabled\"><a href=\"#\">previous</a></li>";
                                //pages
                                if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
                                {
                                    for ($counter = 1; $counter <= $lastpage; $counter++)
                                    {
                                        if ($counter == $page)
                                            $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                        else
                                            $pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";
                                    }
                                }
                                elseif($lastpage > 5 + ($adjacents * 2))  //enough pages to hide some
                                {
                                    //close to beginning; only hide later pages
                                    if($page < 1 + ($adjacents * 2))
                                    {
                                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                            else
                                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                        }
                                        $pagination.= "<a href=\"#\">...</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                                    }
                                    //in middle; hide some front and some back
                                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                    {
                                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                                        $pagination.= "<a href=\"#\">...</a>";
                                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                            else
                                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                        }
                                        $pagination.= "<a href=\"#\">...</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                                    }
                                    //close to end; only hide early pages
                                    else
                                    {
                                        $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                                        $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                                        $pagination.= "<a href=\"#\">...</a>";
                                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
                                            else
                                                $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                                        }
                                    }
                                }
                                //next button
                                if ($page < $counter - 1)
                                    $pagination.= "<li><a href=\"$targetpage?page=$next\"><span aria-hidden=\"true\">&raquo;</span></a></li>";
                                else
                                    $pagination.= "<li class=\"disabled\"><a href=\"#\">Next</a></li>";
                                $pagination.= "</ul></nav>\n";
                            }
                            $courseDurationsDetails     = $service->listCourseDuration($start, $limit);
                            if($courseDurationsDetails) // View course duration begin
                            {
                                $courseDurations_result  = $mysqli->use_result();
                                while($courseDurations_array = $courseDurations_result->fetch_array())
                                {
                                    ?>
                                    <tr>
                                        <td class="edit_td" id="<?php echo $courseDurations_array['id']; ?>">
                                            <span data-toggle="tooltip" data-placement="top" title="Click here for edit <?php echo $courseDurations_array['title']; ?>" id="first_<?php echo $courseDurations_array['id']; ?>" class="text" ><?php echo $courseDurations_array['title']; ?></span>
                                            <input type="text" value="<?php echo $courseDurations_array['title']; ?>" id="first_input_<?php echo $courseDurations_array['id']; ?>" class="form-control editbox" maxlength="255">
                                        </td>

                                        <td class="edit_td" id="<?php echo $courseDurations_array['id']; ?>">
                                            <span data-toggle="tooltip" data-placement="top" title="Click here for edit <?php echo $courseDurations_array['duration']; ?>" id="third_<?php echo $courseDurations_array['id']; ?>" class="text" ><?php echo $courseDurations_array['duration']; ?></span>
                                            <select class="form-control editbox" name="duration" id="third_input_<?php echo $courseDurations_array['id']; ?>">
                                                <option value="1"<?=$courseDurations_array['duration'] == '1' ? ' selected="selected"' : '';?>>1</option>
                                                <option value="2"<?=$courseDurations_array['duration'] == '2' ? ' selected="selected"' : '';?>>2</option>
                                                <option value="3"<?=$courseDurations_array['duration'] == '3' ? ' selected="selected"' : '';?>>3</option>
                                                <option value="4"<?=$courseDurations_array['duration'] == '4' ? ' selected="selected"' : '';?>>4</option>
                                                <option value="5"<?=$courseDurations_array['duration'] == '5' ? ' selected="selected"' : '';?>>5</option>
                                            </select>
                                        </td>

                                        <td class="edit_td" id="<?php echo $courseDurations_array['id']; ?>">
                                            <span data-toggle="tooltip" data-placement="top" title="Click here for edit <?php echo $courseDurations_array['period']; ?>" id="second_<?php echo $courseDurations_array['id']; ?>" class="text" ><?php echo $courseDurations_array['period']; ?></span>
                                            <input type="text" value="<?php echo $courseDurations_array['period']; ?>" id="second_input_<?php echo $courseDurations_array['id']; ?>" class="form-control editbox" maxlength="255">
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $courseDurations_result->free();
                            } //View course duration end
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center"><?php echo $pagination; ?></div>

                <!-- Modal Div Add course duration Open-->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close closebutton" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel">Kursdauer</h4>
                            </div>
                            <!-- Alert Success Div Open-->
                            <div id="alert_success"></div>
                            <!-- Alert Success Div Close-->
                            <div class="modal-body">
                                <form name="form">
                                    <div class="form-group">
                                        <label for="titleshort">Titel</label>
                                        <input type="text" class="form-control" id="title" name="title" maxlength="255">
                                    </div><!-- /form-group -->

                                    <div class="form-group">
                                        <label for="type">Dauer</label>
                                        <select class="form-control" name="duration" id="duration">
                                            <option>--- Kursdauer wählen ---</option>
                                            <option value="1">1 Tag</option>
                                            <option value="2">2 Tage</option>
                                            <option value="3">3 Tage</option>
                                            <option value="4">4 Tage</option>
                                            <option value="5">5 Tage</option>
                                        </select>
                                    </div><!-- /form-group -->

                                    <div class="form-group">
                                        <label for="title">Zeitraum Beschreibung</label>
                                        <input type="text" class="form-control" id="period" name="period" maxlength="255">
                                    </div><!-- /form-group -->

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default closebutton" data-dismiss="modal">Schließen</button>
                                        <button type="button" class="btn btn-primary" id="add_course_duration">Speichern</button>
                                        <span id="message_ta"></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Div Add course duration Close-->

            </div>
        </div>
    </div>
<?php include "includes/scripts.php"; ?>

    <!-- Check All -->
    <script>
        $(function() {
            /* Tooltip */
            $('[data-toggle="tooltip"]').tooltip();

            /* Clear modal*/
            $(".modal").on("hidden.bs.modal", function(){
                $(this).find('form')[0].reset();
            });

            /* Add course duration */
            $("#add_course_duration").click(function(){
                var title               = $("#title").val();
                var period              = $("#period").val();
                var duration            = $("#duration").val();
                var add_course_duration = 'add_course_duration';
                $.post('course-durations-list-submit.php', {title: title, period: period, duration: duration, add_course_duration: add_course_duration},
                    function(data){
                        $("#alert_success").html(data);
                        setInterval('location.reload(true)', 1000);
                    });
            });

            /* Edit course duration Details */
            $(".editbox").hide();
            $(".text").show();
            $(".edit_td").click(function()
            {
                var ID   = $(this).attr('id');
                $("#first_"+ID).hide();
                $("#second_"+ID).hide();
                $("#third_"+ID).hide();
                $("#first_input_"+ID).show();
                $("#second_input_"+ID).show();
                $("#third_input_"+ID).show();
            }).change(function()
            {
                var ID                 = $(this).attr('id');
                var editcourseduration = 'editcourseduration';
                var first              = $("#first_input_"+ID).val();
                var second             = $("#second_input_"+ID).val();
                var third              = $("#third_input_"+ID).val();

                var dataString = 'id='+ ID +'&firstname='+first+'&secondname='+second+'&thirdname='+third+'&editcourseduration='+editcourseduration;
                if(first.length>0 && second.length>0 && third.length>0)
                {
                    $.ajax({
                        type: "POST",
                        url: "course-durations-list-submit.php",
                        data: dataString,
                        cache: false,
                        success: function(html)
                        {
                            $("#first_"+ID).html(first);
                            $("#second_"+ID).html(second);
                            $("#third_"+ID).html(third);
                        }
                    });
                }
                else
                {
                    alert('Enter something.');
                }
            });
            // Edit input box click action
            $(".editbox").mouseup(function()
            {
                return false;
            });
            // Outside click action
            $(document).mouseup(function()
            {
                $(".editbox").hide();
                $(".text").show();
            });
        });

    </script>

<?php include "includes/footer.php"; ?>