<?php
session_start();
//------at landing verify all data, get values set from pusher---
/***
 ************** Report Generation Application
    * Developed by: Emetuche Winner Chidiuto
    * for Use with: jnoj
    * At: Wenzhou University
    * Tech Stack: php, javascript,mysql db nginx server, 
    * libraries and tools: jquery, dompdf, jsPDF,
    * 
    * ***/
//---run db_query find if user admin or student----
echo'
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head>';

class verific_Details{  
    public $user_="";
    public $display_name="";
    public $user_db_id_="";
}
$dec_Details__=new verific_Details;
function reduc_name($value_str){
    $name="";
    if(strlen($value_str)>40){
        $name_head=substr($value_str,0,30);
        $name_tail=substr($value_str,strlen($value_str)-6,strlen($value_str));
        $name=$name_head."...".$name_tail;
    }else{
        $name=$value_str;
    }
    return $name;
}
include "include_configuration/config.php";  
    if(!isset($_SESSION['__id'])){
        //-----refere to start page, refuse entrance
        header("Location:http://".$_SERVER['SERVER_ADDR']."/index.php");	
    }else{
        $_SESSION['subject_name']="";
        $_SESSION['subject_id']="";//---if subject is student we give an id
        $id=$_SESSION['__id'];
        $_SESSION['user_category']="";
        $dec_Details__->user_db_id_=$_SESSION['__id'];
        $major="";
        $student_id="";
        $student_name="";
        mysqli_set_charset($connect,'UTF8');
        $my_query="SELECT user.role,user.username,user.nickname,user_profile.student_number,user_profile.major from user,user_profile where user.id=$id && user_profile.user_id=$id ";
        $exec=mysqli_query($connect,$my_query);
        if($row=mysqli_fetch_assoc($exec)){
            $u_=$row['role'];
            if($u_==30){
                //$user_="admin";
                $dec_Details__->user_="admin";
                $dec_Details__->display_name=$row['nickname'];
                $_SESSION['user_category']="admin";
            }else{
                //$user_="student";
                $dec_Details__->user_="student";
                $_SESSION['user_category']="student";
                $student_id=$row['student_number'];
                $student_name=$row['username']." ".$row['nickname'];
                $major=$row['major'];
                $dec_Details__->display_name=$row['nickname'];
            }
            $_SESSION['subject_id']=$row['student_number'];
            $_SESSION['subject_name']=$row['username']." ".$row['nickname'];
        }
        $_SESSION['subject_type']=$dec_Details__->user_;
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Wizard</title>
</head>
<body>
<script type="text/javascript" src="/report_wizard/js_scripts/jquery-3.3.1.min.js" ></script>
<script type="text/javascript" src="/report_wizard/js_scripts/html2pdf/dist/html2pdf.bundle.min.js" ></script>
<script type="text/javascript" src="/report_wizard/js_scripts/top_scripts_builder.js"></script>
<link rel="stylesheet" href="/report_wizard/css_styles/report-wizard-style.css" >
<link rel="stylesheet" href="/report_wizard/css_styles/report-template-style.css" >
<link rel="stylesheet" href="/report_wizard/css_styles/fonts.css" >
<script> 
    var search_event_on=false;
    var catch_input=null;
</script>
<div class="main-container-shield" id="main-container-shield">
    <div class="left-side-section">
        <div class="left-top-detail" >
            <label class="app-mini-label">Report Wizard</label>
        </div>
        <div class="left-link-border" >
            <div class="action-list-container">
                <?php 
                    if($dec_Details__->user_=="admin"){
                        include "include_helper_builders/wizard_navigation_builder.php"; 
                    }else if($dec_Details__->user_=="student"){
                        echo'
                            <div style="width:100%; float:left;" align="center">
                                <img class="student-profile-icon" src="/report_wizard/assets/icons_off/fine-student.jpg" />
                                <div class="profile-text-welcome">Hi, <span style="font-weight:normal;">'.$dec_Details__->display_name.'</span></div>
                                <div class="profile-text">Lets Generate your Reports </div>
                            </div>
                        ';
                    }else{
                        header("Location:http://".$_SERVER['SERVER_ADDR']."/index.php");	
                    }     
                ?>
            </div>
        </div>
        <div class="hold-docs" align="center">
            <img class="pdf-report-anim-img" src="/report_wizard/assets/icons_off/docs.gif" />
        </div>  
    </div>
    <div class="right-side-section">
        <!-----center area---------->
    <?php
        if($dec_Details__->user_=="admin"){
        echo '
        <div class="main-information-pane">
            <div class="app-label" > </div> 
            <div class="search-box-container" align="center">
                <div class="close-search-part" align="center">
                    <img src="/report_wizard/assets/feather_default/x.svg" id="close-search-icon" class="close-search-icon" />
                </div>
                <div class="search-input-part-" align="center">
                    <img src="/report_wizard/assets/feather_green/search.svg" class="search-input-icon" />
                    <img src="/report_wizard/assets/feather_gray/x.svg" class="empty-input-icon" />
                    <input type="text" class="input-elem" id="input_value" autofocus="true" placeholder="Search here eg: 13511160022">
                </div>
                <div class="search-result-part" align="left" ></div>

                <div class="major-list-part" align="left" ><br/>
                <div class="guide-text" style="font-size:15px; font-family:sans-serif; line-height:20px; text-align:left; color:#00E2D3; margin-top:20px; margin-bottom:60px; word-wrap:break-word;">Choose a Group from below <b style="color:#505050;"></b></div> 
                <br/><br/>
                ';


                $colors_list=array("#576574","#778beb","#786fa6","#574b90","#ff6b6b","#ff9f43","#01a3a4","#54a0ff","#e66767","#576574","#10ac84","#e15f41","#d1d8e0","#18dcff","#4b4b4b","#ffb8b8");
                /**
                 * select all the groups which the admin created that are presently visible
                 * **/
                $group_query="SELECT DISTINCT * from `group` where group.created_by=1 && group.status=1 ";
                $exec_group=mysqli_query($connect,$group_query);
                while($group_row=mysqli_fetch_assoc($exec_group)){
                    $random_color=rand(0,14);
                    $group_name=$group_row['name'];
                    if($group_row['name']==$major){       
                        echo '<span id="major-list-target'.$group_row['id'].'" class="each-group-list" style="font-size:16px; font-weight:bold; opacity:1; border-left:5px solid '.$colors_list[$random_color].';">'.$group_row['name'].'</span>';
                        echo'
                        <script>
                            $("#major-list-target'.$group_row['id'].'").click(function(){
                                major_group="'.$group_name.'";
                                $(".each-group-list").addClass("each-group-post-click").removeClass("each-group-list");                                
                                $(".each-group-list").css("font-size","14px");
                                $(".each-group-list").css("font-weight","normal");
                                $(".each-group-list").css("opacity","0.5");
                                $(".each-group-post-click").css("font-size","14px");
                                $(".each-group-post-click").css("font-weight","normal");
                                $(".each-group-post-click").css("opacity","0.5");                                      
                                $("#major-list-target'.$group_row['id'].'").css("font-size","16px");
                                $("#major-list-target'.$group_row['id'].'").css("font-weight","bold");
                                $("#major-list-target'.$group_row['id'].'").css("opacity","1");  
                                $(clicked_program__).click();       
                                $(".search-result-part").animate({"height":"500px"});      
                                major_group="";            
                            });                                
                        </script>
                        ';
                    }else{
                        echo '<span id="major-list-target'.$group_row['id'].'" class="each-group-list" style="border-left:5px solid '.$colors_list[$random_color].';">'.$group_row['name'].'</span>';
                        echo'
                        <script>
                            $("#major-list-target'.$group_row['id'].'").click(function(){
                                major_group="'.$group_name.'";
                                $(".each-group-list").addClass("each-group-post-click").removeClass("each-group-list");                  
                                $(".each-group-list").css("font-size","14px");
                                $(".each-group-list").css("font-weight","normal");
                                $(".each-group-list").css("opacity","0.5");
                                $(".each-group-post-click").css("font-size","14px");
                                $(".each-group-post-click").css("font-weight","normal");
                                $(".each-group-post-click").css("opacity","0.5");                                      
                                $("#major-list-target'.$group_row['id'].'").css("font-size","16px");
                                $("#major-list-target'.$group_row['id'].'").css("font-weight","bold");
                                $("#major-list-target'.$group_row['id'].'").css("opacity","1");
                                $(clicked_program__).click();     
                                $(".search-result-part").animate({"height":"500px"});
                                major_group="";
                            });
                        </script>       
                        ';
                    }
                }
                
                
                
                
                echo'
                </div>

                
            </div>
            <div class="main-body-target" id="main-body-target">
                <div class="report-category-border">
                    <div class="switch-category-list" id="students-category-focus-switch">
                        <img src="/report_wizard/assets/icons_off/coding.png" class="report-navigation-icon" />
                        <label class="report-navigation-label" id="student-label" >Students</label>
                    </div>
<!----
                    <div class="switch-category-list" id="programs-category-focus-switch" >
                        <img src="/report_wizard/assets/icons_off/progs.jpeg" class="report-navigation-icon" />
                        <label class="report-navigation-label" id="programs-label">Programs</label>
                    </div>
------>
                    <div class="" id=""  style="float:right; ">
                        <img src="/report_wizard/assets/feather_default/x.svg" class="close-list-box" />
                    </div>
                </div>
                <div class="category-query-result-container" >           
                    <div class="student-list-report-container" id="student-list-report-container" >                   
                    </div>
                    <div class="programs-list-report-container" id="programs-list-report-container" >
                    </div>
                    <div class="print-all-reports-icon" id="print-all-reports-icon">
                        <label class="print-all-label" id="print-all-label">Print All</label>
                        <img id="print-all-icon" class="print-all-icon" src="/report_wizard/assets/icons_off/pdf_icon.jpeg" />
                        <img id="print-all-progress" class="print-all-progress" src="/report_wizard/assets/icons_off/loading-block.gif" />
                    </div>
                </div>
            </div>
            <!--------user sees this message at landing----------->
            <div class="landing-message-container" id="landing-message-container" align="center">
                <div class="position-message" align="left" >
                    <div class="welcome-message" >Hi, '.$dec_Details__->display_name.'</div>
                    <div class="my-introduction" >I am Report Wizard,  </div>
                    <div class="teaching-you" >There are so many things I can do for you:
                        1. I can generate report of students Algorithm
                        2. I can show you what your students are doing
                        3. Feel free to ask me anything like: Generate Reports, etc I am here to help.
                    </div>      
                    <div class="search-input-part" align="center">                
                        <input type="text" class="input-elem" id="input_" placeholder="Search ">
                        <img src="/report_wizard/assets/feather_gray/search.svg" class="search-input-icon" />
                    </div>    
                    <div style=" height:80px; margin-top:30px;" align="center">
                        <div style="overflow:hidden; height:100%; " id="clear-record-front">
                            <img src="/report_wizard/assets/feather_gray/database.svg" style="width:18px; height:18px; position:relative; top:3px; margin-right:6px; " />
                            <label id="clear-record-init" style="font-family:sans-serif; line-height:20px; font-size:15px; letter-spacing:0.0712em;">Clear all downloads</label>
                        </div>
                        <div style="display:none; height:100%; " id="clear-record-back">
                            <label style="font-family:sans-serif; font-size:14px; line-height:20px; letter-spacing:0.0712em; ">Are you sure?</label><br/><br/>
                            <label>
                                <span id="cancel-record-clear" style="font-family:sans-serif; font-size:13px; line-height:24px; letter-spacing:0.0712em; height:20px; width:100px; border-radius:8px; letter-spacing:0.0712em; border:1px solid #ddd; margin:4px; padding:8px;">No </span>
                                <span id="confirm-record-clear" style="font-family:sans-serif; font-weight:bold; font-size:13px; line-height:24px; letter-spacing:0.0712em; height:20px; width:100px; border-radius:8px; letter-spacing:0.0712em; border:1px solid #3CACAE; margin:4px; padding:8px;">Yes Proceed </span>                         
                            </label>

                        </div>
                    </div>
                    <script>
                    var target_url_="http://"+self.location.hostname+"/report_wizard/include_helper_builders/reset_downloads_record.php";
                        $("#clear-record-init").click(function(){
                            $("#clear-record-front").hide();
                            $("#clear-record-back").show(200);
                        });
                        $("#cancel-record-clear").click(function(){
                            $("#clear-record-front").show(200);
                            $("#clear-record-back").hide(400);
                        });
                        $("#confirm-record-clear").click(function(){
                            $.post(target_url_,{
                                _major:"ixpx1208apolo",
                                _program:"",
                                category:"",
                                id:null,
                                name:"global",
                                _request_Mode:"global"
                            }, function(data){
                                if(JSON.stringify(data).includes("done")){
                                    $("#clear-record-init").html("Successful");
                                    $(".recent-download-banner").empty();
                                    $("#clear-record-front").show(200);
                                    $("#clear-record-back").hide(400);
                                    setTimeout(function(){
                                        $("#clear-record-init").html("Clear all downloads");
                                    },1500);
                                }else{
                                    $("#clear-record-init").html("Operation Failed");
                                    $("#clear-record-front").show(200);
                                    $("#clear-record-back").hide(400);
                                    setTimeout(function(){
                                        $("#clear-record-init").html("Clear all downloads");
                                    },1500);
                                }
                            });
                        });

                    </script>
                </div>
            </div>
            <div class="load-delay-pane" align="center" >
                <label class="load-delay-label" >Please Wait ...</label><br/>
                <img class="load-delay-img" src="/report_wizard/assets/icons_off/loading.gif" />
            </div>




            
        </div>
        <!-----right side will have little stats and details------>
        <div class="minor-details-pane" align="center" >
                        <!---
            <div class="mini-stats-box-top-label">
                <label class="app-version-label" >Version 0.01</label><br/>
                <img class="app-version-img" src="/report_wizard/assets/icons_off/comwizard.jpg" />
            </div>--->
            <div class="mini-stats-box">
                <label class="responsive-label" >Happy Day</label><br/>
                <img class="responsive-img" src="/report_wizard/assets/icons_off/teacherz.gif" />
            </div>
            <div class="mini-stats-box" id="recent-report-box">
                <div class="activity-label"><label class="activity-label-box">Recent </label></div>
                <div class="recent-download-banner">
                ';         
                $temp_cc=$dec_Details__->user_db_id_;
                $downloads_query_="SELECT * from `complete_reports` where complete_reports.GENERATED_BY='$temp_cc' ";
                $exec_download_=mysqli_query($connect,$downloads_query_);
                while($row=mysqli_fetch_assoc($exec_download_)){
                    echo ' <script> create_recent_download("'.$row['FILE_NAME_'].'","'.reduc_name($row['FILE_NAME_']).'",null); </script> ';          
                }
                echo'
                </div>
            </div>
        </div>
        ';
        }else if($dec_Details__->user_=="student"){
            echo'  
                <div class="student-main-pane" align="center" >
                    <div class="single-report-block" id="j-s">
                        <img src="/report_wizard/assets/icons_off/java.jpeg" class="programs-rectangle" id="close-report-wizard" /><br/>
                        <label class="language-label" >Java Programming</label>         
                    </div>
                    <div class="single-report-block" id="c-ss">
                        <img src="/report_wizard/assets/icons_off/ccprog.png" class="programs-rectangle" id="close-report-wizard" /><br/>
                        <label class="language-label" >C Programming</label>           
                    </div>
                    <div class="single-report-block" id="cpp-s">
                        <img src="/report_wizard/assets/icons_off/cplusus.png" class="programs-rectangle" id="close-report-wizard" /><br/>
                        <label class="language-label" >C++ Programming</label>       
                    </div>
                    <div class="single-report-block" id="py-s">
                        <img src="/report_wizard/assets/icons_off/python.jpeg" class="programs-rectangle" id="close-report-wizard" /><br/>
                        <label class="language-label" >Python Programming</label>       
                    </div>

                    <div class="guide-text" style="font-size:18px; font-weight:bold; text-align:center; ">Choose your Group, Click a box to print Report</div> 
                    <div class="guide-text" style="font-size:15px; font-family:sans-serif; line-height:20px; text-align:left; color:gray; margin-top:20px; word-wrap:break-word;">Your Main Group <b style="color:#505050;">'.$major.'</b> has been automatically detected, <br/>to Print  Report of a different Group Choose group from below</div> 

                    <div align="left" class="group-listing-box" ><br/>';
                        $colors_list=array("#576574","#778beb","#786fa6","#574b90","#ff6b6b","#ff9f43","#01a3a4","#54a0ff","#e66767","#576574","#10ac84","#e15f41","#d1d8e0","#18dcff","#4b4b4b","#ffb8b8");
                        /**
                         * select all the groups which the admin created that are presently visible
                         * **/
                        $group_query="SELECT DISTINCT * from `group` where group.created_by=1 && group.status=1 ";
                        $exec_group=mysqli_query($connect,$group_query);
                        while($group_row=mysqli_fetch_assoc($exec_group)){
                            $random_color=rand(0,14);
                            $group_name=$group_row['name'];
                            if($group_row['name']==$major){       
                                echo '<span id="major-list-target'.$group_row['id'].'" class="each-group-list" style="font-size:16px; font-weight:bold; opacity:1; border-left:5px solid '.$colors_list[$random_color].';">'.$group_row['name'].'</span>';
                                echo'
                                <script>
                                    $("#major-list-target'.$group_row['id'].'").click(function(){
                                        major_group="'.$group_name.'";
                                        $(".each-group-list").addClass("each-group-post-click").removeClass("each-group-list");                                
                                        $(".each-group-list").css("font-size","14px");
                                        $(".each-group-list").css("font-weight","normal");
                                        $(".each-group-list").css("opacity","0.5");
                                        $(".each-group-post-click").css("font-size","14px");
                                        $(".each-group-post-click").css("font-weight","normal");
                                        $(".each-group-post-click").css("opacity","0.5");                                      
                                        $("#major-list-target'.$group_row['id'].'").css("font-size","16px");
                                        $("#major-list-target'.$group_row['id'].'").css("font-weight","bold");
                                        $("#major-list-target'.$group_row['id'].'").css("opacity","1");                           
                                    });                                
                                </script>
                                ';
                            }else{
                                echo '<span id="major-list-target'.$group_row['id'].'" class="each-group-list" style="border-left:5px solid '.$colors_list[$random_color].';">'.$group_row['name'].'</span>';
                                echo'
                                <script>
                                    $("#major-list-target'.$group_row['id'].'").click(function(){
                                        major_group="'.$group_name.'";
                                        $(".each-group-list").addClass("each-group-post-click").removeClass("each-group-list");                  
                                        $(".each-group-list").css("font-size","14px");
                                        $(".each-group-list").css("font-weight","normal");
                                        $(".each-group-list").css("opacity","0.5");
                                        $(".each-group-post-click").css("font-size","14px");
                                        $(".each-group-post-click").css("font-weight","normal");
                                        $(".each-group-post-click").css("opacity","0.5");                                      
                                        $("#major-list-target'.$group_row['id'].'").css("font-size","16px");
                                        $("#major-list-target'.$group_row['id'].'").css("font-weight","bold");
                                        $("#major-list-target'.$group_row['id'].'").css("opacity","1");
                                    });
                                </script>       
                                ';
                            }
                        }
                    echo'
                    </div>
                </div>





                <div class="" style="width:25%; top:10%; position:fixed; right:0%; float:right; height:80%; overflow-y:scroll; ">
                    <div class="activity-label-2" style="width:80%; position:relative; " >
                        <label class="activity-label-box-2" style="font-size:15px; font-weight:bold; letter-spacing:0.0712em; font-family:sans-serif;">Recent </label>
                    </div>
                    <div class="recent-download-banner">
                    ';
                    $temp_xx=$dec_Details__->user_db_id_;
                    $downloads_query="select * from complete_reports where complete_reports.GENERATED_BY='$temp_xx' ";
                    $exec_download=mysqli_query($connect,$downloads_query);
                    while($row=mysqli_fetch_assoc($exec_download)){                      
                        echo ' <script>create_recent_download("'.$row['FILE_NAME_'].'","'.reduc_name($row['FILE_NAME_']).'",null); </script>';                                                                     
                    }
                    echo'             
                    </div>
                </div>    
            ';
        }
        ?>
        <!---right side section ends here-->
    </div>
</div>
<div class="transparent-effect"></div>
<div class="report-result-data-and-control" id="report-result-data-and-control">
    <div class="report-result-control">
        <img src="/report_wizard/assets/feather_default/x.svg" class="close-report-wizard" id="close-report-wizard" />
        <label class="report-wizard-label">Report Wizard</label>
        <div class="pdf-save-button" align="center">
            <label class="save-pdf-action-label">Save </label>
            <img src="/report_wizard/assets/icons_off/pdf_icon.jpeg" class="pdf-save-icon" />
        </div>
    </div>
    <div class="report-result-data-target-container" id="report-result-data-target-container" align="center"  ></div>
</div>
<div class="load-effect" align="center">
    <label class="load-effect-label" >Generating Report</label><br/>
    <label class="load-effect-label-small" >Please wait...</label><br/>
    <img class="load-effect-img" src="/report_wizard/assets/icons_off/loading.gif" />
</div>
<div id="hidden-pdf-result-container"></div>
<div id="reports-progressing-container-box" align="center">
    <img class="close-download-pane" src="/report_wizard/assets/feather_green/x.svg" />
    <div id="reports-progressing-container">    
        <div class="report-process-show" >
            <img class="wizard-acting-notice" src="/report_wizard/assets/icons_off/loading-block.gif" />
            <label class="wizard-acting-text" >Preparing Reports, please wait...</label><br/>     
        </div>
        <div class="report-process-complete" style="display:none; ">
            <img class="wizard-ready-notice" src="/report_wizard/assets/icons_off/com1.png" />
            <label class="wizard-ready-text" >Report is Ready</label><br/>
            <div class="file-name-download">
                <img src="/report_wizard/assets/icons_off/zipz.jpeg" style="top:10px; margin-right:8px; width:30px; height:30px; position:relative;" />
                <span class="file-name-target">report-wzu.zip</span></div>
            <a  id="download_init" style="text-decoration:none;" ><div class="report-ready-download">Download Now</div></a>
        </div>
    </div>
</div>
<?php
if($dec_Details__->user_=="admin"){
    echo '<script type="text/javascript" src="/report_wizard/js_scripts/bottom_scripts_navigator.js" ></script>
        <script>
        var major_group="";
        var clicked_program__="";
        </script>
    ';
}else{
    echo'<script>
        var major_group="'.$major.'";
        var param1="";
        var student_id="'.$student_id.'";
        var _reporter_="'.$student_name.'";
        $("#j-s").click(function(){
        param1="Java Programming"
        $(".transparent-effect").show();
        $("#reports-progressing-container-box").show();
        var target_url="/report_wizard/include_helper_builders/server_pdf_Model.php";
        $.post(target_url,{
            _major:major_group,
            _program:param1,
            category:"single",
            id:student_id,
            name:_reporter_,
            _request_Mode:""
        }, function(data){
            console.log(data);
            function downloadURI_(uri, name,__target) {
                var link = document.getElementById(__target);
                link.href=uri+name;
            }   
            var download_tar=data;
            var shortened="";
            var len_str=download_tar.length;
            var start_str=len_str-4;
            var original=download_tar;
            if(download_tar.length>35){
                shortened=download_tar.substring(0,30)+"..."+original.substring(start_str,len_str);
            }else{
                shortened=download_tar;
            }
            if(JSON.stringify(data).includes("failed")){
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/ooopz.jpg");
                $(".wizard-ready-text").html("Sorry, No reports Found ");
                $(".file-name-download").hide();
                $("#download_init").hide();
           }else{
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/com1.png");
                $(".wizard-ready-text").html("Report is Ready");
                $(".file-name-download").show();
                $("#download_init").show();
                downloadURI_("http://"+self.location.hostname+"/report_wizard/include_helper_builders/download_muscle.php?download_file=",download_tar,"download_init");
                create_recent_download(download_tar,shortened,null);
                $(".file-name-target").html(shortened);
           }
        });  
    });
    $("#c-ss").click(function(){
        param1="C Programming"
        $(".transparent-effect").show();
        $("#reports-progressing-container-box").show();
        var target_url="/report_wizard/include_helper_builders/server_pdf_Model.php";
        $.post(target_url,{
            _major:major_group,
            _program:param1,
            category:"single",
            id:student_id,
            name:_reporter_,
            _request_Mode:""
        }, function(data){
            console.log(data);
            function downloadURI_(uri, name,__target) {
                var link = document.getElementById(__target);
                link.href=uri+name;
            }   
            var download_tar=data;
            var shortened="";
            var len_str=download_tar.length;
            var start_str=len_str-4;
            var original=download_tar;
            if(download_tar.length>35){
                shortened=download_tar.substring(0,30)+"..."+original.substring(start_str,len_str);
            }else{
                shortened=download_tar;
            }
            if(JSON.stringify(data).includes("failed")){
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/ooopz.jpg");
                $(".wizard-ready-text").html("Sorry, No reports Found ");
                $(".file-name-download").hide();
                $("#download_init").hide();
           }else{
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/com1.png");
                $(".wizard-ready-text").html("Report is Ready");
                $(".file-name-download").show();
                $("#download_init").show();
                downloadURI_("http://"+self.location.hostname+"/report_wizard/include_helper_builders/download_muscle.php?download_file=",download_tar,"download_init");
                create_recent_download(download_tar,shortened,null);
                $(".file-name-target").html(shortened);
           }
        });  
    });
    $("#cpp-s").click(function(){
        param1="C++ Programming"
        $(".transparent-effect").show();
        $("#reports-progressing-container-box").show();
        var target_url="/report_wizard/include_helper_builders/server_pdf_Model.php";
        $.post(target_url,{
            _major:major_group,
            _program:param1,
            category:"single",
            id:student_id,
            name:_reporter_,
            _request_Mode:""
        }, function(data){
            console.log(data);
            function downloadURI_(uri, name,__target) {
                var link = document.getElementById(__target);
                link.href=uri+name;
            }   
            var download_tar=data;
            var shortened="";
            var len_str=download_tar.length;
            var start_str=len_str-4;
            var original=download_tar;
            if(download_tar.length>35){
                shortened=download_tar.substring(0,30)+"..."+original.substring(start_str,len_str);
            }else{
                shortened=download_tar;
            }
            if(JSON.stringify(data).includes("failed")){
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/ooopz.jpg");
                $(".wizard-ready-text").html("Sorry, No reports Found ");
                $(".file-name-download").hide();
                $("#download_init").hide();
           }else{
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/com1.png");
                $(".wizard-ready-text").html("Report is Ready");
                $(".file-name-download").show();
                $("#download_init").show();
                downloadURI_("http://"+self.location.hostname+"/report_wizard/include_helper_builders/download_muscle.php?download_file=",download_tar,"download_init");
                create_recent_download(download_tar,shortened,null);
                $(".file-name-target").html(shortened);
           }
        });  
    });
    $("#py-s").click(function(){
        param1="Python Programming"
        $(".transparent-effect").show();
        $("#reports-progressing-container-box").show();
        var target_url="/report_wizard/include_helper_builders/server_pdf_Model.php";
        $.post(target_url,{
            _major:major_group,
            _program:param1,
            category:"single",
            id:student_id,
            name:_reporter_,
            _request_Mode:""
        }, function(data){
            console.log(data);
            function downloadURI_(uri, name,__target) {
                var link = document.getElementById(__target);
                link.href=uri+name;
            }   
            var download_tar=data;
            var shortened="";
            var len_str=download_tar.length;
            var start_str=len_str-4;
            var original=download_tar;
            if(download_tar.length>35){
                shortened=download_tar.substring(0,30)+"..."+original.substring(start_str,len_str);
            }else{
                shortened=download_tar;
            }
            if(JSON.stringify(data).includes("failed")){
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/ooopz.jpg");
                $(".wizard-ready-text").html("Sorry, No reports Found ");
                $(".file-name-download").hide();
                $("#download_init").hide();
           }else{
                $(".report-process-show").hide();
                $(".report-process-complete").show(); 
                $(".wizard-ready-notice").attr("src","/report_wizard/assets/icons_off/com1.png");
                $(".wizard-ready-text").html("Report is Ready");
                $(".file-name-download").show();
                $("#download_init").show();
                downloadURI_("http://"+self.location.hostname+"/report_wizard/include_helper_builders/download_muscle.php?download_file=",download_tar,"download_init");
                create_recent_download(download_tar,shortened,null);
                $(".file-name-target").html(shortened);
           }
        });  
    });
    $(".close-download-pane").click(function(){
        $(".transparent-effect").hide();
        $("#reports-progressing-container-box").hide();
        $(".report-process-show").show();
        $(".report-process-complete").hide();
    });
    </script>     
    ';
}
?>
</body>
</html>

