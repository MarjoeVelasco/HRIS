/* */


const leave_forms = [
    "special_leave_privilege_form",
    "vacation_leave_form",
    "sick_leave_form",
    "maternity_leave_form",
    "cto_leave_form",
    "others_leave_form"
];

const leave_titles = [
    "special leave privilege",
    "vacation leave",
    "sick leave",
    "maternity leave",
    "cto",
    "others"
];

const leave_id = [
    "slp_title",
    "vacation_leave_title",
    "sick_leave_title",
    "maternity_leave_title",
    "cto_leave_title",
    "others_leave_title",
];

const details_of_leave = [
    "slp_vacay_details",
    "sick_details",
    "study_details",
    "other_details",
    "slbfw_details",
];

//ILS Public IP
const ils_public_ip =   [     "119.92.225.0","119.92.225.1","119.92.225.2","119.92.225.3","119.92.225.4",
                              "119.92.225.5","119.92.225.6","119.92.225.7","119.92.225.8","119.92.225.9",
                              "119.92.225.10","119.92.225.11","119.92.225.12","119.92.225.13","119.92.225.14", 
                              "116.50.187.160","116.50.187.161","116.50.187.162","116.50.187.163","116.50.187.164",
                              "116.50.187.165","116.50.187.166","116.50.187.167","116.50.187.168","116.50.187.169",
                              "116.50.187.170","116.50.187.171","116.50.187.172","116.50.187.173","116.50.187.174",
                              "116.50.187.175","116.50.187.176","116.50.187.177","116.50.187.178","116.50.187.179",
                              "116.50.187.180","116.50.187.181","116.50.187.182","116.50.187.183","116.50.187.184",
                              "116.50.187.185","116.50.187.186","116.50.187.187","116.50.187.188","116.50.187.189",
                              "116.50.187.190"];





$(document).ready(function() {

var pathname = window.location.pathname;    

if(window.location.pathname=="/home")
{

    $(window).on('resize', function() {
        var win = $(this);
        if (win.width() < 555) {
      
          $('.btn').addClass('btn-block');
      
        } else {
          $('.btn').removeClass('btn-block');
        }
      });



      var time_in_value=document.getElementById("user_time_in").textContent;
      var work_envi_value=document.getElementById("work_place_input").value;

    if(work_envi_value=="work from home")
    {
        document.getElementById('in_office_span').style.visibility="hidden";
        document.getElementById('wfh_span').style.visibility="visible";
        document.getElementById("work_place_checkbox").checked = false;

    }
    else if(work_envi_value=="in office")
    {
        document.getElementById('in_office_span').style.visibility="visible";
        document.getElementById('wfh_span').style.visibility="hidden";
        document.getElementById("work_place_checkbox").checked = true;


        $.ajax('https://api.ipify.org/?callback=getIP',
        {
            success:function(data) {
                document.getElementById('public_ip_timeout_text').value=data;

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown+ "Execution Error, Press OK to reload");
                location.reload();
            }
        });

    }
    
    if(time_in_value.includes("No Entry")){
        document.getElementById('work_environment_container').style.display="none";

        //$.ajax('https://testingapi123.free.beeceptor.com/',
        $.ajax('https://api.ipify.org/?callback=getIP',
        {
    
            success:function(data) {
                //show loading gif
                document.getElementById('work_environment_container').style.display="block";
                document.getElementById('loading_container').style.display="none";

                //ILS public IP detected, in office
                if(ils_public_ip.includes(data)) {
                    document.getElementById('in_office_span').style.visibility="visible";
                    document.getElementById('wfh_span').style.visibility="hidden";
                    document.getElementById("work_place_checkbox").checked = true;
                    document.getElementById('work_place_checkbox').disabled=true;
                    $('#network_detection_modal_in_office').modal('toggle');
                    document.getElementById("work_place_input").value = "in office";
                }

                //wfh
                else {
                    document.getElementById('in_office_span').style.visibility="hidden";
                    document.getElementById('wfh_span').style.visibility="visible";
                    document.getElementById("work_place_checkbox").checked = false;
                    document.getElementById('work_place_checkbox').disabled=true;
                    $('#network_detection_modal_wfh').modal('toggle');
                    document.getElementById("work_place_input").value = "work from home";
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown+ "Execution Error, Press OK to reload");
                location.reload();
            }

        });



        /*
        $.ajax('https://api.ipify.org/?callback=getIP')
        .done(function (result) {
            if(result=="119.92.225.2" || result=="116.50.187.190" ){
                document.getElementById('in_office_span').style.visibility="visible";
                document.getElementById('wfh_span').style.visibility="hidden";
                document.getElementById("work_place_checkbox").checked = true;
                document.getElementById("work_place_input").value = "in office";
                document.getElementById('work_place_checkbox').disabled=true;
            }
        })

        .fail(function (xhr) {
            // error notification, etc.
            console.log("failed");
            document.getElementById('work_place_checkbox').disabled=false;
        });
        */

    }
    else{
        document.getElementById('loading_container').style.display="none";
        document.getElementById('work_place_checkbox').disabled=true;
    }

    

    //console.log(time_in_value);
    if (document.body.contains(document.getElementById('time_in_val'))) 
    {
        document.getElementById("time_in_val").value = time_in_value;

    }

    document.getElementById("btn_multipleTimeOut").addEventListener("click", function(){
        $('#modalConfirmationDoubleEntry').modal('hide');
        $('#modalAccomplishment').modal('show');
    });


        /*
        const checkbox = document.getElementById('work_place_checkbox')

        checkbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
            document.getElementById('in_office_span').style.visibility="visible";
            document.getElementById('wfh_span').style.visibility="hidden";
            document.getElementById("work_place_input").value = "in office";
        } else {
            document.getElementById('in_office_span').style.visibility="hidden";
            document.getElementById('wfh_span').style.visibility="visible";
            document.getElementById("work_place_input").value = "work from home";
        }
        })
        */

        function showTime(){
            var date = new Date();
            var h = date.getHours(); // 0 - 23
            var m = date.getMinutes(); // 0 - 59
            var s = date.getSeconds(); // 0 - 59
            var session = "AM";
            
            if(h == 0){
                h = 12;
            }
            
            if(h > 12){
                h = h - 12;
                session = "PM";
            }
            
            h = (h < 10) ? "0" + h : h;
            m = (m < 10) ? "0" + m : m;
            s = (s < 10) ? "0" + s : s;
            
            var time = h + ":" + m + ":" + s + " " + session;
            document.getElementById("live_time_span").innerText = time;
            document.getElementById("live_time_span").textContent = time;
            
            setTimeout(showTime, 1000);
            
        }
        
        showTime();    
}

if(window.location.pathname=="/ilsea") {
   
    document.getElementById("vote_proceed_btn").style.display="none";

    $('body').on('click', '#checkbox_resolution', function(event) {
        var checkBox = document.getElementById("checkbox_resolution");
        var text = document.getElementById("vote_proceed_btn");
        if (checkBox.checked == true){
          text.style.display = "block";
        } else {
           text.style.display = "none";
        }
    });

}


if (pathname.indexOf("/live-results", 0) != -1) {
    console.log('test');

    function showTime(){
        var date = new Date();
        var h = date.getHours(); // 0 - 23
        var m = date.getMinutes(); // 0 - 59
        var s = date.getSeconds(); // 0 - 59
        var session = "AM";
        
        if(h == 0){
            h = 12;
        }
        
        if(h > 12){
            h = h - 12;
            session = "PM";
        }
        
        h = (h < 10) ? "0" + h : h;
        m = (m < 10) ? "0" + m : m;
        s = (s < 10) ? "0" + s : s;
        
        var time = h + ":" + m + ":" + s + " " + session;

        document.getElementById("live_time_results").textContent = time;
        
        setTimeout(showTime, 1000);
        
    }
    
    showTime();  
}

if (pathname.indexOf("/ilsea/elections/", 0) != -1) {

    document.getElementById('spinner_icon').style.display="none"; 

    $('body').on('click', '#submit_vote_btn', function(event) {
        document.getElementById('submit_vote_btn').style.display="none";
        document.getElementById('spinner_icon').style.display="block"; 

    });

    $('body').on('click', '#cancel_vote_confirm_btn', function(event) {
        document.getElementById('submit_vote_btn').style.display="block";
        document.getElementById('spinner_icon').style.display="none"; 

    });

    

    $('body').on('click', '#confirm_vote_btn', function(event) {

        var select = document.getElementById('treasurer_candidate_dropdown');
        document.getElementById('treasurer_text').value=select.options[select.selectedIndex].text;


    });

}












if (pathname.indexOf("/recommendation/details/", 0) != -1) {

    var leave_type = document.getElementById("value_leave_type").value;
    var leave_details = document.getElementById("value_leave_details").value;
    var supervisor_note = document.getElementById("value_supervisor_note").value;
    var hr_note = document.getElementById("value_hr_note").value;
    

    if (document.getElementById("value_commutation").value == "Requested") {
        document.getElementById("radio_requested_commutation").checked = true;
    } else {
        document.getElementById("radio_not_requested_commutation").checked = true;
    }

    //check of leave type

    if (leave_type == "vacation leave") {
        document.getElementById("radio_vacation").checked = true;

        leave_details = leave_details.split("-");

        if (leave_details[0] == "To seek employment") {
            document.getElementById("radio_to_seek_employment").checked = true;
        } else {
            var vl_others = leave_details[0].split("/");
            document.getElementById("radio_others_vl").checked = true;
            document.getElementById("v_vacation_others").textContent = vl_others[1];
        }


        var vl_case = leave_details[1].split("/");
        if (vl_case[0] == "In the country") {
            document.getElementById("radio_in_country").checked = true;
        } else {
            document.getElementById("radio_abroad").checked = true;
            document.getElementById("v_vacation_case_others").textContent = vl_case[1];
        }

    } else if (leave_type == "special leave privilege") {
        document.getElementById("radio_slp").checked = true;
    } else if (leave_type == "sick leave") {
        document.getElementById("radio_sick").checked = true;
        leave_details = leave_details.split("/");
        if (leave_details[0] == "In hospital (Specify)") {
            document.getElementById("radio_in_hospital").checked = true;
            document.getElementById("v_in_hospital").textContent = leave_details[1];
        } else {
            document.getElementById("radio_out_patient").checked = true;
            document.getElementById("v_out_patient").textContent = leave_details[1];
        }

    } else if (leave_type == "maternity leave") {
        document.getElementById("radio_maternity").checked = true;
    } else if (leave_type == "others") {
        document.getElementById("radio_others").checked = true;
        document.getElementById("v_others").textContent = leave_details;
    }


    supervisor_note=supervisor_note.split("-");
    if(supervisor_note[0]!="Waiting for approval")
    {
        if(supervisor_note[0]=="Disapproved")
        {
            document.getElementById("radio_supervisor_disapproved").checked = true;
            document.getElementById("v_supervisor_reason").textContent = supervisor_note[1];
        }
        else
        {
            document.getElementById("radio_supervisor_approved").checked = true;
        }
    }
    

    hr_note=hr_note.split(",");

    if(hr_note[0]!="Waiting for approval")
    {
        if(hr_note.length==7)
    {
        document.getElementById("v_vl").textContent = hr_note[0];
        document.getElementById("v_sl").textContent = hr_note[1];
        document.getElementById("v_slp").textContent = hr_note[2];
        document.getElementById("v_total").textContent = hr_note[3];
        document.getElementById("dwp").textContent = hr_note[4];
        document.getElementById("dwop").textContent = hr_note[5];
        document.getElementById("dothers").textContent = hr_note[6];
    }

    else
    {
        document.getElementById("v_vl").textContent = hr_note[0];
        document.getElementById("v_sl").textContent = hr_note[1];
        document.getElementById("v_slp").textContent = hr_note[2];
        document.getElementById("v_total").textContent = hr_note[3];
        document.getElementById("v_reason_disapproved").textContent = hr_note[4];
    }
    }

    if(document.getElementById("value_approver_id").value!="")
    {
        var id=document.getElementById("value_approver_id").value;
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        
            $.ajax({
                    type: 'GET',
                    url: '/approver/details/'+id,
                    dataType: 'JSON',
        
                    success: function (data) {
                        var name=data.data[0].firstname+' '+data.data[0].middlename+' '+data.data[0].lastname+' '+data.data[0].extname;
                        name=name.replaceAll(" null","");
                        var position= data.data[0].position;
                        document.getElementById("approver_name").textContent = name;
                        document.getElementById("approver_position").textContent = position;
                        //console.log(name);
        
           
                    },
                });
    }

    if(document.getElementById("value_signatory_id").value!="")
    {
        var id=document.getElementById("value_signatory_id").value;
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        
            $.ajax({
                    type: 'GET',
                    url: '/approver/details/'+id,
                    dataType: 'JSON',
        
                    success: function (data) {
                        var name=data.data[0].firstname+' '+data.data[0].middlename+' '+data.data[0].lastname+' '+data.data[0].extname;
                        name=name.replaceAll(" null","");
                        var position= data.data[0].position;
                        document.getElementById("signatory_name").textContent = name.toUpperCase();
                        document.getElementById("signatory_position").textContent = position;
                        //console.log(name);
        
           
                    },
                });
    }
    




}


//for leave request    
if(window.location.pathname=="/recommendation")
{
    $('body').on('click', '#decline_btn', function(event) {
        var id = $(this).data('id');
        console.log(id);
        var curDate = currentDate();
        $('.modal-content .modal-body').html("");
        html = '<form method="PATCH " action="/decline">';
        html += '<p>Please provide a reason for declining this leave request</p>';
        html += '<textarea rows="10" name="decline_reason" class="form-control" required></textarea> ';
        html += '<input type="hidden" value=' + id + ' name="id"><br>';
        html += '<input type="hidden" value=' + curDate + ' name="curDate"><br>';
    
        html += '<button type="submit" class="btn btn-danger">Decline</button>';
        html += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
        html += '	</form>';
        $('.modal-content .modal-body').append(html)


    });

    $('body').on('click', '#approve_btn', function(event) {
        var id = $(this).data('id');
        console.log(id);
        var curDate = currentDate();
        $('.modal-content .modal-body').html("");
        html = '<form method="PATCH " action="/approve">';
        html += '<p>Are you sure you want to approve this request? This action cannot be undone upon submission.</p>';

        html += '<input type="hidden" value=' + id + ' name="id"><br>';
        html += '<input type="hidden" value=' + curDate + ' name="curDate">';

        html += '<button type="submit" class="btn btn-danger">Approve</button>';
        html += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
        html += '	</form>';
        $('.modal-content .modal-body').append(html)


    });


}

/*
if(window.location.pathname=="/myleaves")
{
    $('body').on('click', '#deleteLeave', function(event) {
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/archiveleave">';
        html += '<input type="text" value=' + id + ' name="delete_leave_id" hidden>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
        $('#delete_modal_body').append(html)
    });

}
*/

if(window.location.pathname=="/myleaves")
{

    /*
    $('body').on('click', '#ilc_tab_a', function(event) {
        const url = new URL(location);
        url.searchParams.delete('page');
        history.replaceState(null, null, url)

      


    });*/
    
    $('body').on('click', '#leave_survey_btn', function(event) {

        var page = 'https://forms.office.com/Pages/ResponsePage.aspx?id=guZUuEHcEUWp_nB9SS795tvJpJ1962NGjBMrki0c_zRUNE1OMDNOM1hQVjBNU1NKQkNUUVVVTUxLTyQlQCN0PWcu';
        
        var params = [
            'height='+screen.height,
            'width='+screen.width,
            'fullscreen=yes' // only works in IE, but here for completeness
        ].join(',');

        var myWindow = window.open(page, "_blank", "popup_window", params);
         
         // focus on the popup //
         myWindow.focus();
         myWindow.moveTo(0,0);

    });

    $('body').on('click', '#cancelLeave', function(event) {
        var id = $(this).data('id');
        //console.log(id);
        $('#cancel_modal_body').html("");
        
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to cancel this leave request? <br>This process cannot be undone.</p><center>';
        html += '<form method="get" action="/cancel-filed-leave">';
        html += '<input type="text" value=' + id + ' name="cancel_leave_id" hidden>';
        html += '<input type="text" class="col-6 form-control input-sm" name="cancel_reason" placeholder="Reason (optional)"><br>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
        $('#cancel_modal_body').append(html)
    });

    $('body').on('click', '#cancelCto', function(event) {
        var id = $(this).data('id');
        //console.log(id);
        $('#cancel_modal_body_cto').html("");
        
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to cancel this CTO request? <br>This process cannot be undone.</p><center>';
        html += '<form method="get" action="/cancel-filed-cto">';
        html += '<input type="text" value=' + id + ' name="cancel_leavecto_id" hidden>';
        html += '<input type="text" class="col-6 form-control input-sm" name="cancel_reason" placeholder="Reason (optional)"><br>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
        $('#cancel_modal_body_cto').append(html)
    });


    $('body').on('click', '.showLeaveStatus', function(event) {
  
        var id = $(this).data('id');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: 'GET',
          url: '/leave/details/' + id+'/filed_leaves',
          dataType: 'JSON',

          success: function(data) {
            $('#leave_modal_body').html("");
            //console.log(data);
            html ='<span class="h3 text-dark font-weight-bold">Document Log</span>'
            html += '<textarea rows="12" style="width:100%;border-style:none; border-color:Transparent; overflow:auto;" readonly>';
            html += data.data[0].remarks;
            html += '</textarea>';
            html +='<button class="btn btn-dark float-right mt-2" data-dismiss="modal">Close</button>'
            $('#leave_modal_body').append(html)
            $('#leave_modal').modal('show');

          },
        });
      });

      $('body').on('click', '.showCtoStatus', function(event) {
  
        var id = $(this).data('id');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: 'GET',
          url: '/leave/details/' + id+'/filed_cto',
          dataType: 'JSON',

          success: function(data) {
            $('#leave_modal_body').html("");
            //console.log(data);
            html ='<span class="h3 text-dark font-weight-bold">Document Log</span>'
            html += '<textarea rows="12" style="width:100%;border-style:none; border-color:Transparent; overflow:auto;" readonly>';
            html += data.data[0].remarks;
            html += '</textarea>';
            html +='<button class="btn btn-dark float-right mt-2" data-dismiss="modal">Close</button>'
            $('#leave_modal_body').append(html)
            $('#leave_modal').modal('show');

          },
        });
      });

}






if(window.location.pathname=="/file-leave")
{
    document.getElementById("input_monetization_no_days").style.display="none";

    //show modal consent
    $('#staticBackdrop').modal({backdrop: 'static', keyboard: false});


    //get all holiday dates
    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $.ajax({
            type: 'GET',
            url: '/holidayDates',
            dataType: 'JSON',
            success: function (data) {
                var conso_dates="";
                for(var i=0;i<data.data.length;i++){
                    conso_dates+=data.data[i].inclusive_dates+",";
                }

                var dates= conso_dates.split(",");;
                $('.date').datepicker({  
                format: 'yyyy-mm-dd',
                //daysOfWeekDisabled: [0,6],
                multidate: true,
                clearBtn: true,
                orientation:'auto',
                datesDisabled: dates,
                });
                
                var selectedDates=[]
                    cto_datepicker=$('.date_cto').datepicker({  
                    format: 'yyyy-mm-dd',
                    daysOfWeekDisabled: [0,6],
                    multidate: false,
                    clearBtn: true,
                    orientation:'auto',
                    datesDisabled: dates,
                    }); 
   
                },
            });


var leave_type = document.getElementById('file_leave_type');
//hide cto container
$( "#cto_container" ).hide();
//hide all details of leave
for (var i in details_of_leave) {
    document.getElementById(details_of_leave[i]).style.display="none";
}

//On change of leave type dropdown
if (document.body.contains(document.getElementById('file_leave_type'))) {
    leave_type.addEventListener('change', (event) => {
        const selected_leave = `${event.target.value}`;
       
        if(selected_leave=="special privilege leave"||selected_leave=="vacation leave" ){
            document.getElementById("input_general_inclusive_dates").style.display="block";
            document.getElementById("input_monetization_no_days").style.display="none";
            //hide cto container
            $( "#cto_container" ).hide();
            $( "#details_of_leave_container" ).show();
            for (var i in details_of_leave) {
                if(details_of_leave[i]=="slp_vacay_details"){
                    document.getElementById(details_of_leave[i]).style.display="block";
                    //clear radiobutton group
                    uncheckRadioButtons();
                }
                else{
                    document.getElementById(details_of_leave[i]).style.display="none";          
        }}}

        else if(selected_leave=="sick leave"){
            document.getElementById("input_general_inclusive_dates").style.display="block";
            document.getElementById("input_monetization_no_days").style.display="none";
            //hide cto container
            $( "#cto_container" ).hide();
            $( "#details_of_leave_container" ).show();
            for (var i in details_of_leave) {
                if(details_of_leave[i]=="sick_details"){
                    console.log("show: "+ details_of_leave[i]);
                    document.getElementById(details_of_leave[i]).style.display="block";
                    //clear radiobutton group
                    uncheckRadioButtons();
                }
                else{
                    console.log("hide: "+ details_of_leave[i]);
                    document.getElementById(details_of_leave[i]).style.display="none";
        }}}

        else if(selected_leave=="study leave"){
            document.getElementById("input_general_inclusive_dates").style.display="block";
            document.getElementById("input_monetization_no_days").style.display="none";
            //hide cto container
            $( "#cto_container" ).hide();
            $( "#details_of_leave_container" ).show();
            for (var i in details_of_leave) {
                if(details_of_leave[i]=="study_details"){
                    document.getElementById(details_of_leave[i]).style.display="block";
                    //clear radiobutton group
                    uncheckRadioButtons();
                }
                else{
                    document.getElementById(details_of_leave[i]).style.display="none";
        }}}

        else if(selected_leave=="others"){
            document.getElementById("input_general_inclusive_dates").style.display="block";
            document.getElementById("input_monetization_no_days").style.display="none";
            //hide cto container
            $( "#cto_container" ).hide();
            $( "#details_of_leave_container" ).show();
            for (var i in details_of_leave) {
                if(details_of_leave[i]=="other_details"){
                    document.getElementById(details_of_leave[i]).style.display="block";
                }
                else{
                    document.getElementById(details_of_leave[i]).style.display="none";
                    document.getElementById('slbfw_details').style.display="none";
        }}
    
    
            $(document).ready(function(){
                $('input[name=other_leave_details]').click(function(){
                    if(this.value=="monetization_leave"){
                        document.getElementById("input_monetization_no_days").style.display="block";
                        document.getElementById("input_general_inclusive_dates").style.display="none";
                    }
                    else{
                        document.getElementById("input_monetization_no_days").style.display="none";
                        document.getElementById("input_general_inclusive_dates").style.display="block";
                    }
                });
            });

        }

        else if(selected_leave=="special leave benefits for women"){
            //hide cto container
            $( "#cto_container" ).hide();
            $( "#details_of_leave_container" ).show();
            for (var i in details_of_leave) {
                if(details_of_leave[i]=="slbfw_details"){
                    document.getElementById(details_of_leave[i]).style.display="block";
                }
                else{
                    document.getElementById(details_of_leave[i]).style.display="none";
        }}}

        else if(selected_leave=="cto"){
            for (var i in details_of_leave) {
                document.getElementById(details_of_leave[i]).style.display="none";
             }

            //hide cto container
            $( "#cto_container" ).show();
            $( "#details_of_leave_container" ).hide();
            $( ".half_day_group" ).hide();
            $( "#half_day_message" ).hide();
            //check if user selected a half day or not
            if (document.body.contains(document.getElementById('hours_days_cto'))) {
                hours_days_cto.addEventListener('change', (event) => {
                    const selected_value = `${event.target.value}`;
                    document.getElementById("start_date_cto_group").style.display="block";
                    document.getElementById("generate_dates_cto_btn").disabled=false;
            
                    const flag_halfday = selected_value.includes(".");
            
                    $( ".half_day_group" ).hide();
                    $( "#half_day_message" ).hide();
            
                    if(flag_halfday)
                    {
                        $( ".half_day_group" ).show();
                        $( "#half_day_message" ).show();
                    }        
                });
            }

            if (document.body.contains(document.getElementById('fromDate'))) {
                fromDate.addEventListener('change', (event) => {
                    const selected_leave = `${event.target.value}`;
            
                    var endDate = addDays(selected_leave, 105);
                    endDate = formatDate(endDate);
                    toDate.value = endDate;
            
                });
            }

            if (document.body.contains(document.getElementById('generate_dates_cto_btn'))) {
                generate_dates_cto_btn.addEventListener('click', (event) => {
            
                    var e = document.getElementById("hours_days_cto");
                    var selected_hour_days = e.options[e.selectedIndex].value;
                    
                    const flag_halfday = selected_hour_days.includes(".");
            
                    $("#select_half_day").empty();
            
                    //set number of days
                    if(flag_halfday)
                    {
                        selected_hour_days=+selected_hour_days+0.5;
                    }
            
                    var start_date=document.getElementById("start_date_cto").value;
                    var employee_id=$("#cto_employee_id").val();
                    //console.log(selected_hour_days)
                    //console.log(employee_id)
            
                    var date_cto_range = [];
                    date_cto_range.push(start_date);
                    var temp_date_range=start_date;
            
            
                    while(date_cto_range.length<selected_hour_days)
                    {
            
                       
                        start_date=+addDays(start_date,1);
                        var date = new Date(start_date);
                        var date_final = formatDate(date);
            
                        var working_day_flag=checkWorkingDay(date_final,employee_id);
                        if(working_day_flag)
                        {
                            temp_date_range=temp_date_range+','+date_final;
                            date_cto_range.push(date_final);
                            
                        }  
                            //console.log(date_final);
                            //console.log(employee_id);
                            //console.log(working_day_flag);       
                    }
                    
            
                   // console.log(date_cto_range)
                    var select = document.getElementById("select_half_day"); 
            
                    if(date_cto_range.includes("NaN-NaN-NaN")==false)
                    {
                        document.getElementById("inclusive_dates_cto").value=temp_date_range;
                      
                   
                        for(var i = 0; i < date_cto_range.length; i++) 
                        {
                            var opt = date_cto_range[i];
                            var el = document.createElement("option");
                            el.textContent = opt;
                            el.value = opt;
                            select.appendChild(el);
                        }
            
                    }
                    else
                    {
                        document.getElementById("inclusive_dates_cto").value="";
                        $("#select_half_day").empty();
            
                    }        
                   
                });
            }
           
        }

        else
        {
            //hide cto container
            $( "#cto_container" ).hide();
            $( "#details_of_leave_container" ).show();
            for (var i in details_of_leave) {
                document.getElementById(details_of_leave[i]).style.display="none";
        }}
            
    });

    const checkbox = document.getElementById("is_external_checkbox");
    const box = document.getElementById("external_approver_div");

    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            box.style.display = "block"; // Show the div when checkbox is checked
        } else {
            box.style.display = "none"; // Hide the div when checkbox is unchecked
        }
    });

}


//hide number of days
document.getElementById("input_monetization_no_days").style.display="none";













/*
//On change of leave type dropdown
if (document.body.contains(document.getElementById('file_leave_type'))) {
    leave_type.addEventListener('change', (event) => {
        const selected_leave = `${event.target.value}`;
        console.log(selected_leave);
        $('#leave_instruction_modal').modal('toggle'); 

        //

        var root = document.getElementById('leave_type_desc');
        var div = document.createElement('div');
        div.append(selected_leave);
        div.style.fontSize = '1.3em';
        div.style.color = '#000000';
        div.style.textTransform = 'uppercase';

        var br = document.createElement('br');
        var p = document.createElement('p');
        var desc = replaceElement(selected_leave);
        p.style.textAlign='justify';
        p.append(desc);

        root.replaceChildren(div,br,p);



    });
}

*/



}

//for leave request    
if(window.location.pathname=="/requestleave")
{

    
//get all holiday dates
$.ajaxSetup({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
            type: 'GET',
            url: '/holidayDates',
            dataType: 'JSON',

            success: function (data) {

                var conso_dates="";
                for(var i=0;i<data.data.length;i++)
                {
                    conso_dates+=data.data[i].inclusive_dates+",";
                }

                var dates= conso_dates.split(",");;
                
                $('.date').datepicker({  
                format: 'yyyy-mm-dd',
                //daysOfWeekDisabled: [0,6],
                multidate: true,
                clearBtn: true,
                orientation:'auto',
                datesDisabled: dates,
                }); 

                    var selectedDates=[]
                    cto_datepicker=$('.date_cto').datepicker({  
                    format: 'yyyy-mm-dd',
                    daysOfWeekDisabled: [0,6],
                    multidate: false,
                    clearBtn: true,
                    orientation:'auto',
                    datesDisabled: dates,
                    }); 

                   

   
            },
        });



$( ".half_day_group" ).hide();
$( "#half_day_message" ).hide();

//Call to hide all forms
hideForm(leave_forms);

const leave_type = document.querySelector('#leave_type');
const fromDate = document.querySelector('#fromDate');
const toDate = document.querySelector('#toDate');

//On change of leave type dropdown
if (document.body.contains(document.getElementById('leave_type'))) {
    leave_type.addEventListener('change', (event) => {
        const selected_leave = `${event.target.value}`;

        for (var i in leave_titles) {
            if (selected_leave == leave_titles[i]) {
                //clear form
                document.getElementById(leave_forms[i]).reset();
                //update leave title  
                updateLeaveTypeTitle(document.getElementById(leave_id[i]), selected_leave);
                //show selected form and hide others
                showOneForm(leave_forms, leave_forms[i]);
            }
        }
    });
}

if (document.body.contains(document.getElementById('hours_days_cto'))) {
    hours_days_cto.addEventListener('change', (event) => {
        const selected_value = `${event.target.value}`;
        document.getElementById("start_date_cto_group").style.display="block";
        document.getElementById("generate_dates_cto_btn").disabled=false;

        const flag_halfday = selected_value.includes(".");

        $( ".half_day_group" ).hide();
        $( "#half_day_message" ).hide();

        if(flag_halfday)
        {
            $( ".half_day_group" ).show();
            $( "#half_day_message" ).show();
        }        
    });
}



if (document.body.contains(document.getElementById('fromDate'))) {
    fromDate.addEventListener('change', (event) => {
        const selected_leave = `${event.target.value}`;

        var endDate = addDays(selected_leave, 105);
        endDate = formatDate(endDate);
        toDate.value = endDate;

    });
}




if (document.body.contains(document.getElementById('reason_vacation_leave'))) {
    reason_vacation_leave = document.getElementById("reason_vacation_leave");
    reason_vacation_leave.addEventListener('change', (event) => {
        const selected_leave = `${event.target.value}`;
        if (selected_leave == "Others (Specify)") {
            document.getElementById("reason_vacation_leave_others").style.display = "block";
        } else {
            document.getElementById("reason_vacation_leave_others").style.display = "none";
            document.getElementById("input_reason_vacation_leave_others").value = "";
        }
    });
}

if (document.body.contains(document.getElementById('case_vacation_leave'))) {
    case_vacation_leave = document.getElementById("case_vacation_leave");
    case_vacation_leave.addEventListener('change', (event) => {
        const selected_leave = `${event.target.value}`;
        if (selected_leave == "Abroad (Specify)") {
            document.getElementById("case_vacation_leave_others").style.display = "block";
        } else {
            document.getElementById("case_vacation_leave_others").style.display = "none";
            document.getElementById("input_case_vacation_leave_others").value = "";
        }
    });
}


if (document.body.contains(document.getElementById('generate_dates_cto_btn'))) {
    generate_dates_cto_btn.addEventListener('click', (event) => {

        var e = document.getElementById("hours_days_cto");
        var selected_hour_days = e.options[e.selectedIndex].value;
        
        const flag_halfday = selected_hour_days.includes(".");

        $("#select_half_day").empty();

        //set number of days
        if(flag_halfday)
        {
            selected_hour_days=+selected_hour_days+0.5;
        }

        var start_date=document.getElementById("start_date_cto").value;
        var employee_id=$("#cto_employee_id").val();
        console.log(selected_hour_days)
        console.log(employee_id)

        var date_cto_range = [];
        date_cto_range.push(start_date);
        var temp_date_range=start_date;


        while(date_cto_range.length<selected_hour_days)
        {

           
            start_date=+addDays(start_date,1);
            var date = new Date(start_date);
            var date_final = formatDate(date);

            var working_day_flag=checkWorkingDay(date_final,employee_id);
            if(working_day_flag)
            {
                temp_date_range=temp_date_range+','+date_final;
                date_cto_range.push(date_final);
                
            }  
                console.log(date_final);
                console.log(employee_id);
                console.log(working_day_flag);       
        }
        

       // console.log(date_cto_range)
        var select = document.getElementById("select_half_day"); 

        if(date_cto_range.includes("NaN-NaN-NaN")==false)
        {
            document.getElementById("inclusive_dates_cto").value=temp_date_range;
          
       
            for(var i = 0; i < date_cto_range.length; i++) 
            {
                var opt = date_cto_range[i];
                var el = document.createElement("option");
                el.textContent = opt;
                el.value = opt;
                select.appendChild(el);
            }

        }
        else
        {
            document.getElementById("inclusive_dates_cto").value="";
            $("#select_half_day").empty();

        }        
       
    });
}

}

});

function replaceElement(leave_type)
{
    var desc="";
    if(leave_type=="vacation leave")
    {
        desc="It shall be filed five (5) days in advance, whenever possible, of the effective date of such leave. Vacation leave within in the Philippines or abroad shall be indicated in the form for purposes of securing travel authority and completing clearance from money and work accountabilities";
    }

    return desc;
}


function checkWorkingDay(date,id)
{
    var result="";
    $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
    $.ajax({
            'async': false,
            type: 'GET',
            url: '/checkWorkingDay/'+date+'/'+id,
            dataType: 'JSON',
    
            success: function (data) {

                result=data.data;
       
            },
        });

        return result;
}


function updateLeaveTypeTitle(element, str) {
    element.value = str;
}

function uncheckRadioButtons()
{
    if ($('input[name=leave_details]:checked').length > 0) {
        // do something here
        document.querySelector('input[name="leave_details"]:checked').checked = false;
    }
}

function hideForm(forms) {

    document.getElementById("start_date_cto_group").style.display="none";

    for (var i in forms) {
        if (document.getElementById(forms[i])) {
            document.getElementById(forms[i]).style.display = 'none';
        }
    }

}

function showOneForm(forms, selected_form) {
    for (var i in forms) {
        if (document.getElementById(forms[i])) {

            if (forms[i] == selected_form) {
                document.getElementById(forms[i]).style.display = 'block';
            } else {
                document.getElementById(forms[i]).style.display = 'none';
            }
        }
    }
}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function formatDate(date) {
    var yyyy = date.getFullYear();
    var mm = date.getMonth() + 1;
    var dd = date.getDate();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    return (yyyy + '-' + mm + '-' + dd);
}

function currentDate() {
    today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //As January is 0.
    var yyyy = today.getFullYear();

    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;

    return (mm + "/" + dd + "/" + yyyy);
}