<?php
session_start();
$prefix_inc_string=$_SERVER['DOCUMENT_ROOT'];
include $prefix_inc_string."/report_wizard/include_configuration/config.php";
if($connect){
    //echo'connected';
    $major=$_POST['_major'];
    $program=$_POST['_program'];
    $request_Mode=$_POST['_request_Mode'];
    if(isset($_POST['_major']) && isset($_POST['_program'])  ){
        class build_Category{
            //public $student_record_target="user_profile,user";
            public $student_record_target="`user_profile`,`user`,`group`,`group_user`";
            public $programs_record_target="problems_list";
            public $temp_data=array([]);
            public function load_students($connection,$major,$program){
                //-----------------------------------------------------
                $_created_by_=$_SESSION['__id'];
                $_group_id=0;
                $get_group="  SELECT * from `group` where group.name='$major' and group.created_by=$_created_by_";
                $exec_get_group=mysqli_query($connection,$get_group);
                if($rowx_=mysqli_fetch_assoc($exec_get_group)){
                    $_group_id=$rowx_['id'];
                }
                //-----------------------------------------------------
                //$student_query="SELECT  DISTINCT * from $this->student_record_target where  user.role=10 && user_profile.student_number!='NULL' && user_profile.student_number!='' && user.id=user_profile.user_id && user_profile.major='$major' && user_profile.major!=''  && user_profile.major!='NULL' ";
                $student_query="SELECT  DISTINCT * from $this->student_record_target where  user.role=10 && user_profile.student_number!='NULL' && user.id=user_profile.user_id && user.id=group_user.user_id && group.id=$_group_id && group.created_by=$_created_by_  && group_user.group_id=$_group_id "; 
                $exec=mysqli_query($connection, $student_query);
                while($row=mysqli_fetch_assoc($exec)){ 
                    $this->temp_data[0][]=[$row['username']." ".$row['nickname'],$row['student_number'],$row['id'],"student_Mode"];   
                }
            }
            public function load_practices($connection,$major,$program){
                $program_query="SELECT * from  $this->programs_record_target where PROBLEM_LANGUAGE='$program' ";
                $exec=mysqli_query($connection, $program_query);
                while($row=mysqli_fetch_assoc($exec)){
                    $this->temp_data[0][]=[$row['TITLE'],$row['CODE_ID'],$row['ID'],"program_Mode"];     
                }
            }
        }
        $temp_build_Navigation=new build_Category;
        $temp_build_Navigation->load_students($connect,$major,$program);
        $temp_build_Navigation->load_practices($connect,$major,$program);
        echo json_encode( $temp_build_Navigation->temp_data);
    }
}


?>