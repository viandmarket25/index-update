var dropped_link_memory=[false,null,null];
var data_binding=[null,null];
var trap_Target=null;
var result_Data=null;
var all_results=[];
var process_check_progress=0;
function create_recent_download(param1,param2,param3){
    var html_Append="";
    var file_name="<div style='float:left; left:5%; color:#404040; text-align:left; line-height:20px; font-size:12px; width:85%; font-family:sans-serif; letter-spacing:0.0712em; word-wrap:break-word; white-space:pre-line; position:relative; margin-top:10px; '>"
    +"<img src='/report_wizard/assets/feather_gray/file.svg' style='top:5px; margin-right:8px; width:20px; height:20px; position:relative;' >"+param2+"</div><br/>";
    var download_action="<a href='http://"+self.location.hostname+"/report_wizard/include_helper_builders/download_muscle.php?download_file="+param1+"'><div style='width:90%; position:relative; border-bottom:1px solid #f1f1f1; padding-bottom:20px; margin-bottom:30px; top:20px; float:left;' align='center' ><img src='http://127.0.0.1/report_wizard/assets/feather_green/download.svg' style='width:20px; height:20px; left:40%; float:left; text-decoration:none; position:relative; ' /></div></a>";
    html_Append=file_name+download_action;
    $(".recent-download-banner").prepend(html_Append);

}
function expand_major_sets_Helper(ex_array_memory,element_absorb,element_Target,element_change_style){
    $("#"+element_absorb).click(function(){  
        if(ex_array_memory[0]==false && ex_array_memory[2]!=element_absorb && ex_array_memory[2]==null){
            $("#"+element_Target).slideDown(200);
            $("#"+element_Target).show(200);
            $("#"+element_change_style).addClass("link-target-dynamic").removeClass("link-target-container");
            ex_array_memory[0]=true;
            ex_array_memory[1]=element_Target;
            ex_array_memory[2]=element_absorb;
        }else if( ex_array_memory[0]!=false && ex_array_memory[2]==element_absorb && ex_array_memory[2]!=null){                      
            ex_array_memory[0]=false;
            ex_array_memory[1]=null;
            ex_array_memory[2]=null;
            $("#"+element_Target).slideUp(200);
            $("#"+element_Target).hide(200);
            $("#"+ex_array_memory[1]).show();
            $(".link-target-dynamic").addClass("link-target-container").removeClass("link-target-dynamic");
            $(".link-target-container").addClass("link-target-container").removeClass("link-target-dynamic");
        }
    });
}
function select_major_set(element_absorb,element_change_style){
    $("#"+element_absorb).click(function(){  
        $("."+element_change_style+"-dynamic").addClass(element_change_style).removeClass(element_change_style+"-dynamic");  
        $("#"+element_absorb).addClass(element_change_style+"-dynamic").removeClass(element_change_style);
    });
}
function switch_render_view(element_reporter,param2){
    if(element_reporter=="students-category-focus-switch"){
        $(".student-list-report-container").show();
        $(".programs-list-report-container").hide();
        $("#students-category-focus-switch").css("border-top","2px solid #f1f1f1");
        $("#students-category-focus-switch").css("border-right","2px solid #f1f1f1");
        $("#students-category-focus-switch").css("border-bottom","2px solid white");
        $("#programs-category-focus-switch").css("border-top","2px solid white");
        $("#programs-category-focus-switch").css("border-bottom","2px solid #f1f1f1");
        $("#programs-category-focus-switch").css("border-left","2px solid white");
    }else if(element_reporter=="programs-category-focus-switch"){
        $(".student-list-report-container").hide();
        $(".programs-list-report-container").show();
        $("#programs-category-focus-switch").css("border-top","2px solid #f1f1f1");
        $("#programs-category-focus-switch").css("border-left","2px solid #f1f1f1");
        $("#programs-category-focus-switch").css("border-bottom","2px solid white");
        $("#students-category-focus-switch").css("border-top","2px solid white");
        $("#students-category-focus-switch").css("border-bottom","2px solid #f1f1f1");
        $("#students-category-focus-switch").css("border-right","2px solid white");
    }
}
//-------
class data_Container{
    target_url="http://"+self.location.hostname+"/report_wizard/include_helper_builders/category_query_engine.php";
    response_Target="";
    student_list_Target="";
    programs_list_Target="";
    constructor(){
    }
    fetch_student_associates(data_Value,param1,param2){
        function build_student_List(_tourist_){
            console.log(_tourist_);
            var elx ="<div align='center' class='no-record-holder' ><label class='no-records-label'>No records found</label><br/><img src='/report_wizard/assets/icons_off/notfound.gif' class='no-record-img'/>";
            var total_record=[0,0]; //---index 1 total student, 0 total programs       
            var prepare_for_Build=JSON.parse(_tourist_);
            if(prepare_for_Build[0].length!=0){
                for(var i=0; i<prepare_for_Build[0].length;i++){
                    if(prepare_for_Build[0][i]!=undefined){
                        if(prepare_for_Build[0][i][3] =="program_Mode"){
                            total_record[0]+=1;
                            each_program_template_(prepare_for_Build[0][i][1]+"ipcs"+prepare_for_Build[0][i][2],prepare_for_Build[0][i][0],prepare_for_Build[0][i][1],prepare_for_Build[0][i][3]);
                        }else if(prepare_for_Build[0][i][3] =="student_Mode"){
                            total_record[1]+=1;
                            all_results.push(prepare_for_Build[0][i][2]);
                            each_student_template_(prepare_for_Build[0][i][1]+"ixs"+prepare_for_Build[0][i][2],prepare_for_Build[0][i][0],prepare_for_Build[0][i][1],prepare_for_Build[0][i][3]);
                        }
                    }
                }
                $("#programs-label").html("Programs "+"("+total_record[0]+")");
                $("#student-label").html("Students "+"("+total_record[1]+")");
                //var elx ="<div align='center' class='no-record-holder' ><label class='no-records-label'>No records found</label><br/><img src='/report_wizard/assets/icons_off/notfound.gif' class='no-record-img'/>";
                if(total_record[1]==0){ //---------if not student records found
                    $(".student-list-report-container").html(elx);
                }
                if(total_record[0]==0){ //---------if no program records found
                    $(".programs-list-report-container").html(elx);
                }
            }else{
                $(".student-list-report-container").html(elx);
                $("#student-label").html("Students "+"(0)");
            }
            param1=null;
            param2=null;
        }
        $.post(this.target_url,{_major:data_Value[0],_program:data_Value[1],_request_Mode:"neutral"}, function(data){
            build_student_List(data); //-----build students list 
            result_Data=data;
        });       
    }
}


function cook_Process(){
    $(".landing-message-container").hide();
    $(".main-body-target").hide();
    $(".load-delay-pane").show();
    setTimeout(function(){
        $(".load-delay-pane").hide(200);
        $(".main-body-target").show();
    },350);
}
function load_post_Search(_reporter_,data,param1,major_){ //----program mode or student mode
    //alert(clicked_program__);
   
    //major_=major_group;
    //alert(major_);

    if(major_group==""){


        $(".search-result-part").animate({"height":"100px"});

    }else{
        /*----if the report is being generated from search results
        */
        major_=major_group;
        $(".transparent-effect").show();
        $("#reports-progressing-container-box").show();
        //break_report_chunks(result_Data,null,null);
        var target_url="http://"+self.location.hostname+"/report_wizard/include_helper_builders/server_pdf_Model.php";
        $.post(target_url,{
            _major:major_,
            _program:param1,
            category:"single",
            id:data,
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
    }

}
function load_program_Report(_reporter_,data,param1,request_Mode){ //----program mode or student mode
    $(".transparent-effect").show();
    $(".load-effect").show();
    $.post("http://"+self.location.hostname+"/report_wizard/include_helper_builders/report_construction_wizard.php",{
        _major:data_binding[0],
        _program:data_binding[1],
        id:data,
        name:_reporter_,
        _request_Mode:"program_Mode"
    }, 
    function(data){
        $(".report-result-data-target-container").html(data); //-----build students list 
    });  
    setTimeout(function(){
        $(".load-effect").hide();
        $(".report-result-data-and-control").show();
        trap_Target=create_pdf_Helper("report-result-data-target",null,null); //call to build pdf
    },1000);
}
function load_student_Report(_reporter_,data,param1,request_Mode){ //----program mode or student mode
    /***  when admin clicks on program and chooses major***/
   $(".transparent-effect").show();
   $("#reports-progressing-container-box").show();
   //break_report_chunks(result_Data,null,null);
   var target_url="http://"+self.location.hostname+"/report_wizard/include_helper_builders/server_pdf_Model.php";
   $.post(target_url,{
       _major:data_binding[0],
       _program:data_binding[1],
       category:"single",
       id:data,
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
                console.log(data);
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
        /*
       if(JSON.stringify(data).includes("failed")){
            $(".report-process-show").hide();
            $(".report-process-complete").show(); 
            $(".wizard-ready-notice").attr("src","http://127.0.0.1/report_wizard/assets/icons_off/failed_op.jpeg");
            $(".wizard-ready-text").html("Sorry, No reports Found ");
            $(".file-name-download").hide();
            $("#download_init").hide();
       }else{
            $(".report-process-show").hide();
            $(".report-process-complete").show(); 
            $(".wizard-ready-notice").attr("src","http://127.0.0.1/report_wizard/assets/icons_off/com1.png");
            $(".wizard-ready-text").html("Report is Ready");
            $(".file-name-download").show();
            $("#download_init").show();
       }
       function downloadURI_(uri, name,__target) {
        var link = document.getElementById(__target);
        link.href="http://127.0.0.1/report_wizard/include_helper_builders/download_muscle.php?download_file="+name;
        }   
        var download_tar=data;
        downloadURI_("http://127.0.0.1/report_wizard/pdf_generations/",download_tar,"download_init");
       var len_str=download_tar.length;
       var start_str=len_str-4;
       var original=download_tar;
       if(download_tar.length>35){
           download_tar=download_tar.substring(0,30)+"..."+original.substring(start_str,len_str);
       }
       $(".file-name-target").html(download_tar);
       */


   });  
}
var process_=new data_Container();
function build_student_search_Result(_tourist_){
    var total_record=[0,0]; //---index 1 total student, 0 total programs     
    $("#programs-label").html("Programs "+"("+total_record[0]+")");
    $("#student-label").html("Students "+"("+total_record[1]+")");  
    console.log(_tourist_);
    var prepare_for_Build=JSON.parse(_tourist_);
    if(prepare_for_Build[0].length!=0){
        for(var i=0; i<prepare_for_Build[0].length;i++){
            if(prepare_for_Build[0][i]!=undefined){
                if(prepare_for_Build[0][i][3] =="program_Mode"){
                    total_record[0]+=1;
                    each_program_template_(prepare_for_Build[0][i][1]+"ipcs"+prepare_for_Build[0][i][2],prepare_for_Build[0][i][0],prepare_for_Build[0][i][1],prepare_for_Build[0][i][3]);
                }else if(prepare_for_Build[0][i][3] =="student_Mode"){
                    console.log("student mode");
                    total_record[1]+=1;
                    each_student_template_(prepare_for_Build[0][i][1]+"ixs"+prepare_for_Build[0][i][2],prepare_for_Build[0][i][0],prepare_for_Build[0][i][1],prepare_for_Build[0][i][3]);
                }
            }
        }
        $("#programs-label").html("Programs "+"("+total_record[0]+")");
        $("#student-label").html("Students "+"("+total_record[1]+")");
        var elx ="<div align='center' class='no-record-holder' ><label class='no-records-label'>No records found</label><br/><img src='/report_wizard/assets/icons_off/notfound.gif' class='no-record-img'/>";
        if(total_record[1]==0){ //---------if not student records found
            $(".student-list-report-container").html(elx);
        }
        if(total_record[0]==0){ //---------if no program records found
            $(".programs-list-report-container").html(elx);
        }
    }else{
      

    }
}
class student_search_Res_{
    constructor(){
    }
    build_structure(_tourist_){
        console.log(_tourist_);
        var prepare_for_Build=JSON.parse(_tourist_);
        if(prepare_for_Build[0].length!=0){
            for(var i=0; i<prepare_for_Build[0].length;i++){
                if(prepare_for_Build[0][i]!=undefined){
                    build_id_result_(prepare_for_Build[0][i][1]+"ipcs"+prepare_for_Build[0][i][2],prepare_for_Build[0][i][0],prepare_for_Build[0][i][1],prepare_for_Build[0][i][3]);                   
                }
            }
        }
        
    }
}
var get_structure_=new student_search_Res_();