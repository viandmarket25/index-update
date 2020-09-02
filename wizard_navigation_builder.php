<?php
session_start();

class build_navigation{
    public $program_courses=array();
    public $program_id_handler=array();
    public $program_source=array();
    public function load_program_values($connection,$param1,$param2){
        $program_query="select * from programming_languages";
        $exec=mysqli_query($connection, $program_query);
        while($row=mysqli_fetch_assoc($exec)){
            $this->program_courses[]="".$row['LANGUAGE_TITLE'];  
            $this->program_id_handler[]="target".$row['ID'];  
            $this->program_source[]=$row['SOURCE'];
        }
    }
    public function load_major_sets($connection,$param1,$param2){
        for($i=0; $i<count($this->program_courses);$i++){
            echo '
            <li class="action-list-arrange" id="action-list-arrange'.$this->program_id_handler[$i].'" data-progam_value_="'.$this->program_courses[$i].'" >
                <div class="action-list-zone" id="action-list-zone'.$this->program_id_handler[$i].'" >
                    <div class="link-target-container" id="link-target-container'.$this->program_id_handler[$i].'" >
                        <img src="'.$this->program_source[$i].'" class="icon-images" />
                        <label class="course-style-label">'.$this->program_courses[$i].'</label>
                        <img src="/report_wizard/assets/feather_default/chevron-down.svg" class="drop-down-show-academic-sets" id="drop-down-show-academic-sets'.$this->program_id_handler[$i].'" />
                    </div>
                </div>
            </li>                                                
            <div class="academic-sets-container" id="academic-sets-container'.$this->program_id_handler[$i].'" align="left" >
            ';
            $using_val= $this->program_courses[$i];
            $created_by__=$_SESSION['__id'];
            $run_sets_query="SELECT DISTINCT * from `group` where group.created_by=$created_by__ ORDER BY group.id ASC ";
            $exec_sets=mysqli_query($connection,$run_sets_query);
            while($row=mysqli_fetch_assoc($exec_sets)){
                echo'       
                <div class="academic-set-label" id="academic-set-label'.$this->program_id_handler[$i].$row['id'].'" >'.$row['name'].'</div>
                <script type="text/javascript">
                    $("#academic-set-label'.$this->program_id_handler[$i].$row['id'].'").click(function(){                                 
                        data_binding[0]="'.$row['name'].'";
                        data_binding[1]="'.$this->program_courses[$i].'";
                        process_.fetch_student_associates(data_binding,null,null);
                        $("#student-list-report-container").empty(); //clear target div off any data--------    
                        $("#programs-list-report-container").empty(); //clear target div off any data--------                                 
                        cook_Process();
                        $(".search-box-container").hide();
                        search_event_on=false;
                        $("#input_value").val("");
                        $(".search-result-part").empty();
                    });
                    select_major_set("academic-set-label'.$this->program_id_handler[$i].$row['id'].'","academic-set-label");                                                          
                </script>                                            
                ';
            } 
           echo'
                <script type="text/javascript">
                    expand_major_sets_Helper(dropped_link_memory,"drop-down-show-academic-sets'.$this->program_id_handler[$i].'","academic-sets-container'.$this->program_id_handler[$i].'","link-target-container'.$this->program_id_handler[$i].'");                                                          
                </script>     
                </div>
            ';
        }
    }
}
$temp_build_Navigation=new build_navigation;
$temp_build_Navigation->load_program_values($connect,null,null);
$temp_build_Navigation->load_major_sets($connect,null,null);
?>