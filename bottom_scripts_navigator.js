console.log("bottom script reporting");
$("#students-category-focus-switch").click(function(){
    switch_render_view("students-category-focus-switch",null);
    $(".print-all-reports-icon").show(200);
    default_printAll(true);
});
$("#programs-category-focus-switch").click(function(){
    switch_render_view("programs-category-focus-switch",null);
    $(".print-all-reports-icon").hide(200);
    default_printAll(true);
});
function create_pdf_Helper(file_publish_Name,param2,param3){
    var element_base_Target = document.getElementById(file_publish_Name);
    var options = {
    margin:0.3,
    pagebreak: { mode: 'avoid-all',before:'css' },
    filename:     param2,
    image:        { type: 'jpeg', quality: 0.98 },
    html2canvas:  { scale:2},
    jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    }; //--options have been set
    var prepare_save = html2pdf().set(options).from(element_base_Target);
    return prepare_save; //------return built value, and await save command-------------
}
function element_Creator(element_type,element_id,element_class,content_data){
	let element_temp= document.createElement(element_type);
	element_temp.setAttribute("id",element_id);
	element_temp.setAttribute("class",element_class);
	if(content_data!=null && (element_type=="video" || element_type=="img")){
		element_temp.setAttribute("src",content_data);
	}else if(content_data!=null && element_type=="label"){
		$(element_temp).html(content_data);
	}
	return element_temp;
}
var h_tracker=false;
var pending="";
function handlerIn_hand_obj(target_id,action_rec,param1){
    $(param1).hide();
    $(action_rec).show();
    /*
    if(h_tracker==false && pending==""){
        $(".object-to-print-box").hide();
        $(param1).hide();
        $(action_rec).show(200);
        $(target_id).css("background","white");
        $(action_rec).animate({
            width:"0%",width:"35%",width:"40%"    
        },);
        h_tracker=true;
        pending=target_id;
    }
    */
}
function handlerOut_hand_obj(target_id,action_rec,param1){
    $(param1).show();
    $(action_rec).hide();
    /*
    $(".object-to-print-box").hide();
    if(h_tracker==true && target_id==pending){
        $(target_id).css("background","#fcfcfc");
        $(param1).show();
        $(action_rec).hide();
        h_tracker=false;
        pending="";
    }
    */
}
function build_id_result_(id_appender,name,student_id,param1){ //------student list each template builder
    var student_div_container_=element_Creator("div","each-search-result"+id_appender,"each-search-result",null);
    var student_image_icon_=element_Creator("img","student-icon"+id_appender,"student-icon","/report_wizard/assets/icons_off/student_icon.png");
    var student_label_name_=element_Creator("label","student-name"+id_appender,"student-name",student_id);
    var student_label_id_=element_Creator("label","student-id"+id_appender,"student-id",name);
    var pdf_function_container_=element_Creator("div","pdf-function-button"+id_appender+student_id,"pdf-function-button",null);
    pdf_function_container_.setAttribute("align","center");
    var pdf_label_=element_Creator("label","generate-pdf-action-label"+id_appender,"generate-pdf-action-label","Generate");
    var pdf_icon_image_=element_Creator("img","pdf-icon"+id_appender,"pdf-icon","/report_wizard/assets/icons_off/pdf_icon.jpeg");
    $(".search-result-part").append(student_div_container_);
    $(student_div_container_).append(student_image_icon_);
    $(student_div_container_).append(student_label_name_);
    $(student_div_container_).append(student_label_id_);
    $(student_div_container_).append(pdf_function_container_);
    $(pdf_function_container_).append(pdf_label_);
    $(pdf_function_container_).append(pdf_icon_image_);
    //-----------lets add the other elements for pdf generation--------
    var object_pdf_to_print=element_Creator("div","object-to-print-box"+id_appender,"object-to-print-box",null);
    var print_obj1_=element_Creator("img","program-object-icon1java"+id_appender+student_id,"object-icon","/report_wizard/assets/icons_off/java.jpeg");
    var print_obj2_=element_Creator("img","program-object-icon2c"+id_appender+student_id,"object-icon","/report_wizard/assets/icons_off/ccprog.png");
    var print_obj3_=element_Creator("img","program-object-icon3cplus"+id_appender+student_id,"object-icon","/report_wizard/assets/icons_off/cplusus.png");
    var print_obj4_=element_Creator("img","program-object-icon4python"+id_appender+student_id,"object-icon","/report_wizard/assets/icons_off/python.jpeg");
    object_pdf_to_print.setAttribute("align","left");
    $(object_pdf_to_print).append(print_obj1_);
    $(object_pdf_to_print).append(print_obj2_);
    $(object_pdf_to_print).append(print_obj3_);
    $(object_pdf_to_print).append(print_obj4_);
    $(student_div_container_).append(object_pdf_to_print);
    $("#each-search-result"+id_appender).on({
        mouseenter:function(){
            handlerIn_hand_obj("#each-search-result"+id_appender,"#object-to-print-box"+id_appender,"#pdf-function-button"+id_appender+student_id);
        },
        mouseleave:function(){
            handlerOut_hand_obj("#each-search-result"+id_appender,"#object-to-print-box"+id_appender,"#pdf-function-button"+id_appender+student_id);   
        }
    });
    $("#program-object-icon1java"+id_appender+student_id).click(function(){
        clicked_program__="#program-object-icon1java"+id_appender+student_id;
        load_post_Search(name,student_id,"Java Programming",param1);
    });
    $("#program-object-icon2c"+id_appender+student_id).click(function(){
        clicked_program__="#program-object-icon2c"+id_appender+student_id;
        load_post_Search(name,student_id,"C Programming",param1);
    });
    $("#program-object-icon3cplus"+id_appender+student_id).click(function(){
        clicked_program__="#program-object-icon3cplus"+id_appender+student_id;
        load_post_Search(name,student_id,"C++ Programming",param1);
    });
    $("#program-object-icon4python"+id_appender+student_id).click(function(){
        clicked_program__="#program-object-icon4python"+id_appender+student_id;
        load_post_Search(name,student_id,"Python Programming",param1);
    });
}

function each_student_template_(id_appender,name,student_id,param1){ //------student list each template builder
    var student_div_container_=element_Creator("div","student-template-list"+id_appender,"student-template-list",null);
    var student_image_icon_=element_Creator("img","student-icon"+id_appender,"student-icon","/report_wizard/assets/icons_off/student_icon.png");
    var student_label_name_=element_Creator("label","student-name"+id_appender,"student-name",student_id);
    var student_label_id_=element_Creator("label","student-id"+id_appender,"student-id",name);
    var pdf_function_container_=element_Creator("div","pdf-function-button"+id_appender+student_id,"pdf-function-button",null);
    pdf_function_container_.setAttribute("align","center");
    var pdf_label_=element_Creator("label","generate-pdf-action-label"+id_appender,"generate-pdf-action-label","Generate");
    var pdf_icon_image_=element_Creator("img","pdf-icon"+id_appender,"pdf-icon","/report_wizard/assets/icons_off/pdf_icon.jpeg");
    $(".student-list-report-container").append(student_div_container_);
    $(student_div_container_).append(student_image_icon_);
    $(student_div_container_).append(student_label_name_);
    $(student_div_container_).append(student_label_id_);
    $(student_div_container_).append(pdf_function_container_);
    $(pdf_function_container_).append(pdf_label_);
    $(pdf_function_container_).append(pdf_icon_image_);
    $("#pdf-function-button"+id_appender+student_id).click(function(){
        load_student_Report(name,student_id,null,"student_Mode");
    });
}
function each_program_template_(id_appender,name,program_id,param1){ //------student list each template builder
    var student_div_container_=element_Creator("div","student-template-list"+id_appender,"student-template-list",null);
    var student_image_icon_=element_Creator("img","student-icon"+id_appender,"student-icon","/report_wizard/assets/icons_off/coode.png");
    var student_label_name_=element_Creator("label","student-name"+id_appender,"student-name",program_id);
    var student_label_id_=element_Creator("label","student-id"+id_appender,"student-id",name);
    var pdf_function_container_=element_Creator("div","pdf-function-button"+id_appender+program_id,"pdf-function-button",null);
    pdf_function_container_.setAttribute("align","center");
    var pdf_label_=element_Creator("label","generate-pdf-action-label"+id_appender,"generate-pdf-action-label","Generate");
    var pdf_icon_image_=element_Creator("img","pdf-icon"+id_appender,"pdf-icon","/report_wizard/assets/icons_off/pdf_icon.jpeg");
    $(".programs-list-report-container").append(student_div_container_);
    $(student_div_container_).append(student_image_icon_);
    $(student_div_container_).append(student_label_name_);
    $(student_div_container_).append(student_label_id_);
    $(student_div_container_).append(pdf_function_container_);
    $(pdf_function_container_).append(pdf_label_);
    $(pdf_function_container_).append(pdf_icon_image_);
    $("#pdf-function-button"+id_appender+program_id).click(function(){
        load_program_Report(name,program_id,null,"program_Mode");
    });
}
function default_printAll(default_act){
    if(default_act==true){
        $("#print-all-progress").hide();
        $("#print-all-icon").show();
        $("#print-all-label").html("Print All");
    }else{
        $("#print-all-progress").show();
        $("#print-all-icon").hide();
        $("#print-all-label").html("0%");
    }
}
function break_report_chunks(_tourist_,param1,param2){
    default_printAll(false);
    var prepare_for_Build=JSON.parse(_tourist_);
    if(prepare_for_Build[0].length!=0){
        var count_student_mode=0;
        var inner_student_counter=0;
        for(var i=0; i<prepare_for_Build[0].length;i++){
            if(prepare_for_Build[0][i]!=undefined && prepare_for_Build[0][i][3]=="student_Mode"){
                count_student_mode+=1;
            }
        }
        var calc=Math.round(100/count_student_mode);
        for(var i=0; i<prepare_for_Build[0].length;i++){
            if(prepare_for_Build[0][i]!=undefined){
                console.log(prepare_for_Build[0][i][3]);
                if(prepare_for_Build[0][i][3]=="student_Mode"){
                    async function timed_act() {
                        let promise = new Promise((resolve, reject) => {
                            setTimeout(() => resolve(1), 1000)
                            inner_student_counter+=1;
                            asynchronous_report_build(prepare_for_Build[0][i][1]+"ixs"+prepare_for_Build[0][i][2],prepare_for_Build[0][i][0],prepare_for_Build[0][i][1],prepare_for_Build[0][i][3],i);
                            process_check_progress+=1; 
                            console.log("progress value: "+process_check_progress);
                            if(inner_student_counter==count_student_mode){
                               
                                $("#print-all-label").html("100%");   
                                setTimeout(function(){
                                    default_printAll(true);                                                  
                                },400);
                            }else{
                                var num=process_check_progress*calc;
                                setTimeout(function(){
                                    $("#print-all-label").html(num+"%");                         
                                },200);
                            }
                        });               
                        let result = await promise; // wait until the promise resolves (*)           
                    }
                    timed_act();           
                }
            }
        }
    }
}
function asynchronous_report_build(id_appender,_reporter_,dataz,param1,user_id){
    /* contain report result template */
    var report_container=element_Creator("div","hidden-report-template-container"+id_appender+user_id,"hidden-report-template-container",null);
    /*  report result target */
    var report_target=element_Creator("div","hidden-report-result-data-target"+id_appender+user_id,"hidden-report-result-data-target",null);
    /*
    $.post("/report_wizard/include_helper_builders/report_construction_wizard.php",{_major:data_binding[0],_program:data_binding[1],id:dataz,name:_reporter_,_request_Mode:"student_Mode"}, function(data){
        $(report_container).append(report_target);
        $(report_target).append(report_target);        
        $(report_target).html(data);
        $('#hidden-pdf-result-container').append(report_container);
        //$('body').append(report_container);
        var trap_Target=create_pdf_Helper("hidden-report-template-container"+id_appender+user_id,_reporter_+"_"+dataz,null); //call to build pdf
        trap_Target.save(); //---save pdf report   
    }); 
    */
   $.post("http://"+self.location.hostname+"/report_wizard/include_helper_builders/server_pdf_Model.php",{_major:data_binding[0],_program:data_binding[1],id:dataz,name:_reporter_,_request_Mode:"student_Mode"}, function(data){
        /*
        $(report_container).append(report_target);
        $(report_target).append(report_target);        
        $(report_target).html(data);
        $('#hidden-pdf-result-container').append(report_container);
        //$('body').append(report_container);
        var trap_Target=create_pdf_Helper("hidden-report-template-container"+id_appender+user_id,_reporter_+"_"+dataz,null); //call to build pdf
        trap_Target.save(); //---save pdf report   
        */
       console.log(data);
    }); 
}
//---------we wont add problem description, just problem id,title, and source code-------
//--------use loop to iterate length of result object, build reports with it, while calling inner methods of buiild wizard
$("#close-report-wizard").click(function(){
    $(".transparent-effect").hide();
    $("#report-result-data-and-control").hide();
});
$(".pdf-save-button").click(function(){
    trap_Target.save();//---save pdf report
});
$("#prepare-search-box").click(function(){
    $(".search-box-container").show(200);
    $(".app-label").hide();
    $(".landing-message-container").hide();
});
function search_view(){
    $(".search-box-container").show(200);
    $(".app-label").hide();
    $(".landing-message-container").hide();
}
$("#close-search-icon").click(function(){    
    $(".search-box-container").hide();
    $(".app-label").show();
    $(".landing-message-container").show();
    $("#input_value").val("");
    $(".search-result-part").empty();
    $(".empty-input-icon").hide();
    $(".input-elem").css("left","2%");
    search_event_on=false;
});
$("#input_").focusin(function(){  
    if(search_event_on==false){ 
        console.log(catch_input);  
        setTimeout(function(){
            $("#input_value").focus();
        });
        search_view();
        search_event_on=true;
    }
});
$(".empty-input-icon").click(function(){
    $("#input_value").val("");
    $(".search-result-part").empty();
    $(".empty-input-icon").hide(100);
});
$( "#input_value" ).keyup(function() {  
    catch_input=$("#input_value").val();
    var url="http://"+self.location.hostname+"/report_wizard/include_helper_builders/search_result_model.php";
    if(catch_input.length!==0){
        $(".empty-input-icon").show();
    }else{
        $(".empty-input-icon").hide();
        $(".input-elem").css("left","2%");
    }
    if(search_event_on==false){      
        $("#input_value").val(catch_input);
        search_view();
        search_event_on=true;
    }else{
        if(catch_input!=="" && catch_input.length>2){
            $.post(url,{student_id:catch_input}, function(data){
                $(".search-result-part").empty();
                if(data!=""){
                    get_structure_.build_structure(data);
                }       
                console.log(data);
            });  
        }else if(catch_input==""&&catch_input.length==0){
            $(".search-result-part").empty();
        }
    }
});
$(".close-list-box").click(function(){
    $(".main-body-target").hide();
    $(".landing-message-container").show();
});
/* print all reports handler, if print all is clicked, make post request to server, then generated data--- */
$(".print-all-reports-icon").click(function(){
    $(".transparent-effect").show();
    $("#reports-progressing-container-box").show();
    //break_report_chunks(result_Data,null,null);
    var target_url="http://"+self.location.hostname+"/report_wizard/include_helper_builders/server_pdf_Model.php";
    $.post(target_url,{
        _major:data_binding[0],
        _program:data_binding[1],
        category:"multiple",
        id:"",
        name:"",
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

})