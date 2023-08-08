$(document).ready(function() {

   

    var pathname = window.location.pathname;
  
    if (window.location.pathname == "/attendancereport") {
  
      
      document.getElementById("month_year").onchange = function() {
        var consolidated_weeks="";
        var selected_month_year = document.getElementById("month_year").value;
  
  
        var res = selected_month_year.split("-");
        var data = getWeeksStartAndEndInMonth(res[1], res[0])
       console.log(data);
  
        $('#week').find('option:not(:first)').remove();
        
        var week = document.getElementById("week");
        for (var i = 0; i < data.length; i++) {
          var option = document.createElement("option");
          label = i + 1;
          option.text = "week " + label;
          option.value = data[i].start + "-" + data[i].end;
          week.appendChild(option);

          consolidated_weeks=consolidated_weeks+data[i].start+"-"+data[i].end+",";
        }
        console.log(consolidated_weeks);
        document.getElementById("weeks").value=consolidated_weeks;
        
      };

     
  
      
    }

    if (pathname.indexOf("/review-leave-supervisor/", 0) != -1) {
      //show modal consent
      //$('#staticBackdrop').modal({backdrop: 'static', keyboard: false});
      document.getElementById("hr_disapprove_reason").style.display="none";
      //onclick of decline show reason
      document.getElementById("decline_leave_btn").onclick = function() {

      document.getElementById("approve_leave_btn").style.display="none";
      document.getElementById("decline_leave_btn").style.display="none";
      document.getElementById("hr_disapprove_reason").style.display="block";
      };
  
      }

      if (pathname.indexOf("/review-cto-supervisor/", 0) != -1) {
        //show modal consent
        //$('#staticBackdrop').modal({backdrop: 'static', keyboard: false});
        document.getElementById("hr_disapprove_reason").style.display="none";
        //onclick of decline show reason
        document.getElementById("decline_cto_btn").onclick = function() {
  
        document.getElementById("approve_cto_btn").style.display="none";
        document.getElementById("decline_cto_btn").style.display="none";
        document.getElementById("hr_disapprove_reason").style.display="block";
        };
    
        }


    if (pathname.indexOf("/review-leave/", 0) != -1) {
    //show modal consent
    $('#staticBackdrop').modal({backdrop: 'static', keyboard: false});
    document.getElementById("hr_disapprove_reason").style.display="none";
    //onclick of decline show reason
    document.getElementById("decline_leave_btn").onclick = function() {
    document.getElementById("approve_leave_form").style.display="none";
    document.getElementById("decline_leave_btn").style.display="none";
    document.getElementById("hr_disapprove_reason").style.display="block";
    };

    document.getElementById("total_earned_vl").onkeyup = function() {calculateLeaveCredits()};
    document.getElementById("total_earned_sl").onkeyup = function() {calculateLeaveCredits()};
    document.getElementById("less_vl").onkeyup = function() {calculateLeaveCredits()};
    document.getElementById("less_sl").onkeyup = function() {calculateLeaveCredits()};

    }

    if (pathname.indexOf("/review-leave/", 0) != -1) {
      //show modal consent
      $('#staticBackdrop').modal({backdrop: 'static', keyboard: false});
      document.getElementById("hr_disapprove_reason").style.display="none";
      //onclick of decline show reason
      document.getElementById("decline_leave_btn").onclick = function() {
      document.getElementById("approve_leave_form").style.display="none";
      document.getElementById("decline_leave_btn").style.display="none";
      document.getElementById("hr_disapprove_reason").style.display="block";
      };
  
      document.getElementById("total_earned_vl").onkeyup = function() {calculateLeaveCredits()};
      document.getElementById("total_earned_sl").onkeyup = function() {calculateLeaveCredits()};
      document.getElementById("less_vl").onkeyup = function() {calculateLeaveCredits()};
      document.getElementById("less_sl").onkeyup = function() {calculateLeaveCredits()};
  
      }

      if (pathname.indexOf("/review-cto/", 0) != -1) {
        //show modal consent
        $('#staticBackdrop').modal({backdrop: 'static', keyboard: false});
        document.getElementById("hr_disapprove_reason").style.display="none";
        //onclick of decline show reason
        document.getElementById("decline_cto_btn").onclick = function() {
        document.getElementById("approve_cto_form").style.display="none";
        document.getElementById("decline_cto_btn").style.display="none";
        document.getElementById("hr_disapprove_reason").style.display="block";
        };
    
        }

    if (pathname.indexOf("/certify-leave/", 0) != -1) {
  
      //console.log("test");

      document.getElementById("total_earned_vl").onkeyup = function() {calculateLeaveCredits()};
      document.getElementById("total_earned_sl").onkeyup = function() {calculateLeaveCredits()};
      document.getElementById("less_vl").onkeyup = function() {calculateLeaveCredits()};
      document.getElementById("less_sl").onkeyup = function() {calculateLeaveCredits()};
     
      
    }

    function calculateLeaveCredits()
    {
      var total_earned_vl = document.getElementById("total_earned_vl").value;
      var total_earned_sl = document.getElementById("total_earned_sl").value;
      var less_vl = document.getElementById("less_vl").value;
      var less_sl = document.getElementById("less_sl").value;

      var balance_vl = total_earned_vl - less_vl;
      var balance_sl = total_earned_sl - less_sl;

      document.getElementById("balance_vl").value = balance_vl;
      document.getElementById("balance_sl").value = balance_sl;

    }



  
    if (window.location.pathname == "/employeereport") {
  
      document.getElementById("month_year").onchange = function() {
  
        var selected_month_year = document.getElementById("month_year").value;
  
  
        var res = selected_month_year.split("-");
        var data = getWeeksStartAndEndInMonth(res[1], res[0])
        console.log(data[0].start);
  
        $('#week').find('option:not(:first)').remove();
  
        var week = document.getElementById("week");
        for (var i = 0; i < data.length; i++) {
          var option = document.createElement("option");
          label = i + 1;
          option.text = "week " + label;
          option.value = data[i].start + "-" + data[i].end;
          week.appendChild(option);
        }
      };
    }

    if (window.location.pathname == "/payslip-general") {

      $('body').on('click', '#deletePayslip', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deletePayslip">';
        html += '<input type="hidden" value=' + id + ' name="delete_payslip_id" >';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
   
    
    }

    if (window.location.pathname == "/payslip-mail") {

      $('body').on('click', '#deletePayslip', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deleteManyPayslip">';
        html += '<input type="hidden" value=' + id + ' name="delete_payslip_pay_period" >';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
   
    
    }


  
  
  
  
    if (window.location.pathname == "/manageholidays") {
   
      $('body').on('click', '#editHoliday', function(event) {
        var id = $(this).data('id');
        console.log(id);
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
  
        $.ajax({
          type: 'GET',
          url: '/viewHoliday/' + id,
          dataType: 'JSON',
  
          success: function(data) {
            console.log(data);
  
            document.getElementById("holiday_id").value = data.data[0].id;
            document.getElementById("edit_holiday_name").value = data.data[0].holiday_name;
            document.getElementById("edit_inclusive_dates").value = data.data[0].inclusive_dates;
            document.getElementById("edit_remarks").value = data.data[0].remarks;
          },
        });
      });
  
  
  
  
      $('body').on('click', '#deleteHoliday', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deleteHoliday">';
        html += '<input type="hidden" value=' + id + ' name="delete_holiday_id" >';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
    }

    if (window.location.pathname == "/manageobao") {
   
      $('body').on('click', '#editOBAO', function(event) {
        var id = $(this).data('id');
        console.log(id);
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
  
        $.ajax({
          type: 'GET',
          url: '/viewOBAO/' + id,
          dataType: 'JSON',
  
          success: function(data) {
            console.log(data);


            document.getElementById("obao_id").value = data.data[0].id;

            switch(data.data[0].status)
            {
              case "OB":
                document.getElementById("edit_status_type").selectedIndex = "1";
              break;

              case  "AO":
                document.getElementById("edit_status_type").selectedIndex = "2";
              break;
            }

            document.getElementById("edit_employee").value = data.data[0].employee_id;
            
            document.getElementById("edit_ao_no").value = data.data[0].note;

            document.getElementById("edit_date").value = data.data[0].inclusive_dates;
            
            document.getElementById("edit_title").value = data.data[0].title;
            document.getElementById("edit_details").value = data.data[0].details;
            
            /*
            document.getElementById("holiday_id").value = data.data[0].id;
            document.getElementById("edit_holiday_name").value = data.data[0].holiday_name;
            document.getElementById("edit_inclusive_dates").value = data.data[0].inclusive_dates;
            document.getElementById("edit_remarks").value = data.data[0].remarks;*/



          },
        });
      });
  
  
  
  
      $('body').on('click', '#deleteOBAO', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deleteOBAO">';
        html += '<input type="hidden" value=' + id + ' name="delete_OBAO_id" >';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
    }


    if (window.location.pathname == "/manageattendanceothers") {
  
      const attendance_forms = [
        "time_in_form",
        "time_out_form",
      ];
  
      const attendance_select = [
        "time in",
        "time out",
      ];
  
      hideForm(attendance_forms);
  
      const attendance_type = document.querySelector('#attendance_type');
  
      //On change of leave type dropdown
      if (document.body.contains(document.getElementById('attendance_type'))) {
        attendance_type.addEventListener('change', (event) => {
          const selected_attendance = `${event.target.value}`;
  
          for (var i in attendance_forms) {
            if (selected_attendance == attendance_select[i]) {
              //clear form
              document.getElementById(attendance_forms[i]).reset();
              //show selected form and hide others
              showOneForm(attendance_forms, attendance_forms[i]);
            }
          }
        });
      }
  
      $('body').on('click', '#deleteAttendance', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deleteattendanceother">';
        html += '<input type="text" value=' + id + ' name="delete_attendance_id" hidden>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
  
      $('body').on('click', '#presentAttendance', function(event) {
  
        var id = $(this).data('id');
        document.getElementById("present_attendance_id").value = id;
  
      }); 

    }

    if (window.location.pathname == "/manageattendanceobao") {
  
      const attendance_forms = [
        "time_in_form",
        "time_out_form",
      ];
  
      const attendance_select = [
        "time in",
        "time out",
      ];
  
      hideForm(attendance_forms);
  
      const attendance_type = document.querySelector('#attendance_type');
  
      //On change of leave type dropdown
      if (document.body.contains(document.getElementById('attendance_type'))) {
        attendance_type.addEventListener('change', (event) => {
          const selected_attendance = `${event.target.value}`;
  
          for (var i in attendance_forms) {
            if (selected_attendance == attendance_select[i]) {
              //clear form
              document.getElementById(attendance_forms[i]).reset();
              //show selected form and hide others
              showOneForm(attendance_forms, attendance_forms[i]);
            }
          }
        });
      }
  
      $('body').on('click', '#deleteAttendance', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deleteattendanceobao">';
        html += '<input type="text" value=' + id + ' name="delete_attendance_id" hidden>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
  
      $('body').on('click', '#presentAttendance', function(event) {
  
        var id = $(this).data('id');
        document.getElementById("present_attendance_id").value = id;
  
      }); 
      
    }

  
  
    if (window.location.pathname == "/manageattendance") {
  
      const attendance_forms = [
        "time_in_form",
        "time_out_form",
      ];
  
      const attendance_select = [
        "time in",
        "time out",
      ];
  
      hideForm(attendance_forms);
  
      const attendance_type = document.querySelector('#attendance_type');
  
      //On change of leave type dropdown
      if (document.body.contains(document.getElementById('attendance_type'))) {
        attendance_type.addEventListener('change', (event) => {
          const selected_attendance = `${event.target.value}`;
  
          for (var i in attendance_forms) {
            if (selected_attendance == attendance_select[i]) {
              //clear form
              document.getElementById(attendance_forms[i]).reset();
              //show selected form and hide others
              showOneForm(attendance_forms, attendance_forms[i]);
            }
          }
        });
      }
  
      $('body').on('click', '#deleteAttendance', function(event) {
  
        var id = $(this).data('id');
        console.log(id);
        $('#delete_modal_body').html("");
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to delete this record? This process cannot be undone.</p><center>';
        html += '<form method="get" action="/deleteattendance">';
        html += '<input type="text" value=' + id + ' name="delete_attendance_id" hidden>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
  
        $('#delete_modal_body').append(html)
      });
  
      $('body').on('click', '#presentAttendance', function(event) {
  
        var id = $(this).data('id');
        document.getElementById("present_attendance_id").value = id;
  
      }); 
    }

    if (window.location.pathname == "/accounting-dashboard") {
      
      //get monthly deductions
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: 'GET',
        url: '/deductionscontri',
        dataType: 'JSON',
  
        success: function(data) {
         // console.log(data);
          // console.log(data.label[0]);
          var deduction_data = [];

          for (var i=0;i<=11;i++)
          {
            deduction_data[i]= { y: data.label[i], 
                                 a: data.gsis[i], 
                                 b: data.philhealth[i], 
                                 c: data.pagibig[i], 
                                 d: data.tax[i], 
                                 e: data.ilsea[i],
                                };
          }

          config = {
            data: deduction_data,
            xkey: 'y',
            ykeys: ['a', 'b','c','d','e'],
            labels: ['GSIS', 'Philhealth','Pagibig','Tax','ILSEA'],
            hideHover: 'auto',
            resize: true,
        };
      
      config.element = 'deduction-bar-chart';
      config.stacked = true;
      Morris.Bar(config);
        },
      });

      //get contributions of GSIS
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: 'GET',
        url: '/gsiscontri',
        dataType: 'JSON',
  
        success: function(data) {
         // console.log(data);

          var deduction_data = [];

          for (var i=0;i<=2;i++)
          {
            deduction_data[i]= { y: data.label[i], 
                                 a: data.gsis_insurance[i], 
                                 b: data.gsis_policy_loan[i], 
                                 c: data.gsis_conso[i], 
                                 d: data.gsis_emergency[i], 
                                 e: data.gsis_computer[i],
                                 f: data.gsis_ins_diff[i],
                                 g: data.gsis_educ[i],
                                 h: data.gfal[i],
                                };
          }

          config = {
            data: deduction_data,
            xkey: 'y',
            ykeys: ['a', 'b','c','d','e','f','g','h'],
            labels: ['Life & Retirement Ins. Prem', 'Policy Loan','Consolidated Loan','Emergency Loan',' Computer Loan',' Life and Retirement Differential','Educational Assistance',' GFAL'],
            hideHover: 'auto',
            resize: true,
        };
      
      config.element = 'gsis-bar-chart';
      Morris.Bar(config);

 
        },
      });

      //get contributions of Pagibig
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: 'GET',
        url: '/pagibigcontri',
        dataType: 'JSON',
  
        success: function(data) {
          //console.log(data);

          var deduction_data = [];

          for (var i=0;i<=2;i++)
          {
            deduction_data[i]= { y: data.label[i], 
                                 a: data.pagibig_contri[i], 
                                 b: data.pagibig_mp[i], 
                                 c: data.pagibig_cal[i], 
                                 d: data.pagibig_mp2[i], 
                                };
          }

          config = {
            data: deduction_data,
            xkey: 'y',
            ykeys: ['a', 'b','c','d'],
            labels: ['Contributions', 'Multi Purpose Loan','Calamity Loan',' MP2'],
            hideHover: 'auto',
            resize: true,
        };
      
      config.element = 'pagibig-bar-chart';
      Morris.Bar(config);

 
        },
      });

      //get contributions of Philhealth
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: 'GET',
        url: '/philhealthcontri',
        dataType: 'JSON',
  
        success: function(data) {
         // console.log(data);

          var deduction_data = [];

          for (var i=0;i<=2;i++)
          {
            deduction_data[i]= { y: data.label[i], 
                                 a: data.philhealth_contri[i], 
                                 b: data.philhealth_diff[i], 
                                };
          }

          config = {
            data: deduction_data,
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Contributions', 'Contributions-Differential'],
            hideHover: 'auto',
            resize: true,
        };
      
      config.element = 'philhealth-bar-chart';
      Morris.Bar(config);

        },
      });

      //get contributions of ILSEA
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
      });
      
      $.ajax({
        type: 'GET',
        url: '/ilseacontri',
        dataType: 'JSON',
        
        success: function(data) {
        //console.log(data);

        var deduction_data = [];

          for (var i=0;i<=2;i++)
          {
            deduction_data[i]= { y: data.label[i], 
                                 a: data.union_dues[i], 
                                 b: data.paluwagan_shares[i], 
                                 c: data.ilsea_loan[i], 
                                 d: data.paluwagan_loan[i], 
                                };
          }

          config = {
            data: deduction_data,
            xkey: 'y',
            ykeys: ['a', 'b','c','d'],
            labels: ['Union Dues', 'Paluwagan Shares','ILSEA Loan', 'Paluwagan Loan'],
            hideHover: 'auto',
            resize: true,
        };
      
      config.element = 'ILSEA-bar-chart';
      Morris.Bar(config);
        },
      });


      //get contributions of TAX
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
      });
      
      $.ajax({
        type: 'GET',
        url: '/taxcontri',
        dataType: 'JSON',
        
        success: function(data) {
        //console.log(data);

        var deduction_data = [];

        for (var i=0;i<=11;i++)
        {
          deduction_data[i]= { y: data.labels[i], 
                               a: data.tax[i]
                              };
        }

        config = {
          parseTime: false,
          data: deduction_data,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['W/Holding Tax'],
          hideHover: 'auto',
          resize: true,
      };
    
    config.element = 'tax-line-chart';
    Morris.Line(config);


        },
      });  
    }

    if(window.location.pathname == "/elections-dashboard") {
      console.log('testing');
      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      //voters percentage
      $.ajax({
        type: 'GET',
        url: '/voters-percentage',
        dataType: 'JSON',
  
        success: function(data) {
          var voted = data.voted;
          var not_voted = data.not_voted;
          Morris.Donut({
            element: 'pie-chart-voters',
            data: [{
                label: "Voted (%)",
                value: voted,
                color: "#28a745",},
              {
                label: "Not yet voted (%)",
                value: not_voted,
                color: "#808080",},
            ]});
        },});


        $.ajax({
          type: 'GET',
          url: '/voters-position',
          dataType: 'JSON',
    
          success: function(data) {

  
            
             var chart_data = [
              { y: 'President', a:data.president}, 
              {y: 'Vice President',  a: data.vice_president},
              {y: 'Secretary',       a: data.sec}, 
              {y: 'Treasurer',       a: data.treasurer},
              {y: 'Bookkeeper',       a: data.bookkeeper},
              {y: 'Auditor',       a: data.auditor},
              {y: 'PRO',       a: data.pro},
              {y: '1st Level',       a: data.first_level},
              {y: '2nd Level',       a: data.second_level}, 
                                  ];
            
  
            config = {
              data: chart_data,
              xkey: 'y',
              ykeys: ['a'],
              labels: ['Votes'],
              hideHover: 'auto',
              resize: true,
          };
        
        config.element = 'bar-chart-voters';
        Morris.Bar(config);
  
   
          },
        });



    }
  
  
    if (window.location.pathname == "/dashboard") {
  
  
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
  
      //pie chart percentage today
      $.ajax({
        type: 'GET',
        url: '/piechartPercentageToday',
        dataType: 'JSON',
  
        success: function(data) {
  
          var present = Math.round((data.present * 100) / data.employee);
          var absent = Math.round(((data.employee - data.present) * 100) / data.employee);
          //console.log(data.data[0].image);
          Morris.Donut({
            element: 'pie-chart-today',
            data: [{
                label: "Present (%)",
                value: present,
                color: "#28a745",
  
              },
              {
                label: "Absent/Leave (%)",
                value: absent,
                color: "#ffc107",
              },
  
            ]
  
          });
  
        },
      });
  
      //pie chart percentage this month
      $.ajax({
        type: 'GET',
        url: '/piechartPercentageMonth',
        dataType: 'JSON',
  
        success: function(data) {
          
        
          //get all holiday dates and convert them to array
          var conso_dates="";
          for(var i=0;i<data.holiday.length;i++)
          {
              conso_dates+=data.holiday[i].inclusive_dates+",";
          }
          var holiday_dates= conso_dates.split(",");
          
          //get current Month
          var currentMonth = new Date().getMonth();
          //get current Year
          var currentYear = new Date().getFullYear();
          //get array days in a month
          var DaysInMonth = getDaysInMonth(currentMonth,currentYear);
         

          //remove holidays and weekends
          for(var i=0;i<DaysInMonth.length;i++)
          {
            for(var j=0;j<holiday_dates.length;j++)
            {
                //if holiday
                if(DaysInMonth[i]==holiday_dates[j])
                {
                  DaysInMonth.splice(i,1);
                }
                //if weekend
                else if(isWeekend(DaysInMonth[i]))
                {
                  DaysInMonth.splice(i,1);
                }
            }
          }

          var expectedDays = DaysInMonth.length*data.employee;
          

          var present = Math.round((data.present * 100) / expectedDays);
          var absent = Math.round(((expectedDays - data.present) * 100) / expectedDays);
          
          Morris.Donut({
            element: 'pie-chart-month',
            data: [{
                label: "Present (%)",
                value: present,
                color: "#28a745",
  
              },
              {
                label: "Absent/Leave (%)",
                value: absent,
                color: "#ffc107",
              },
  
            ]
  
          });
  
        },
      });
  
      //pie chart percentage this year
      $.ajax({
        type: 'GET',
        url: '/piechartPercentageYear',
        dataType: 'JSON',
  
        success: function(data) {

         
          var conso_dates="";
          for(var i=0;i<data.holiday.length;i++)
          {
              conso_dates+=data.holiday[i].inclusive_dates+",";
          }
          var holiday_dates= conso_dates.split(",");
          
          //get current Year
          var currentYear = new Date().getFullYear();
          //get array days in a month
          var DaysInYear = getDaysInYear(currentYear);
         

          //remove holidays and weekends
          for(var i=0;i<DaysInYear.length;i++)
          {
            for(var j=0;j<holiday_dates.length;j++)
            {
                //if holiday
                if(DaysInYear[i]==holiday_dates[j])
                {
                  DaysInYear.splice(i,1);
                }
                //if weekend
                else if(isWeekend(DaysInYear[i]))
                {
                  DaysInYear.splice(i,1);
                }
            }
          }

          

          var expectedDays = DaysInYear.length*data.employee;
  
          var present = Math.round((data.present * 100) / expectedDays);
          var absent = Math.round(((expectedDays - data.present) * 100) / expectedDays);

          Morris.Donut({
            element: 'pie-chart-year',
            data: [{
                label: "Present (%)",
                value: present,
                color: "#28a745",
  
              },
              {
                label: "Absent/Leave (%)",
                value: absent,
                color: "#ffc107",
              },
  
            ]
  
          });
  
        },
      });
    }
  
    if (pathname.indexOf("/review-leave-supervisor", 0) != -1) {
      //show modal consent
      $('#staticBackdrop').modal({backdrop: 'static', keyboard: false});
      
    }

    if (window.location.pathname == "/managefiledcto") {
      
      //cancel CTO

      $('body').on('click', '#cancelCto', function(event) {
        var id = $(this).data('id');
        console.log(id);
        $('#cancel_modal_body_cto').html("");
        
        html = '<center><h1 style="color:red;"><i class="feather icon-trash-2"></i></h1><center>';
        html += '<br><center><h3>Are you sure?</h3><center>';
        html += '<br><center><p>Do you really want to cancel this CTO request? <br>This process cannot be undone.</p><center>';
        html += '<form method="get" action="/cancel-filed-cto">';
        html += '<input type="text" value=' + id + ' name="cancel_leave_id" hidden>';
        html += '<input type="text" class="col-6 form-control input-sm" name="cancel_reason" placeholder="Reason (optional)"><br>';
        html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
        $('#cancel_modal_body_cto').append(html)
    });
    
    }  

    if (window.location.pathname == "/managefiledleaves") {
      
      //cancel leave

      $('body').on('click', '#cancelLeave', function(event) {
        var id = $(this).data('id');
        console.log(id);
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
    
    }  
  
  
    if (window.location.pathname == "/manageleaves") {
      $('body').on('click', '#decline_btn', function(event) {
        var id = $(this).data('id');
        console.log(id);
        var curDate = currentDate();
        $('.modal-content .modal-body').html("");
        html = '<form method="PATCH " action="/declineleave">';
        html += '<p>Please provide a reason for declining this leave request</p>';
        html += '<textarea rows="10" name="decline_reason" class="form-control" required></textarea> ';
        html += '<input type="hidden" value=' + id + ' name="id"><br>';
        html += '<input type="hidden" value=' + curDate + ' name="curDate"><br>';
        html += '<p>CERTIFICATION OF LEAVE CREDITS as of ' + curDate + '</p>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="remaining_vl" placeholder="Remaining Vacation Leave (days)" required><br>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="remaining_sl" placeholder="Remaining Sick Leave (days)" required><br>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="remaining_slp" placeholder="Remaining SLP (days)" required><br>';
  
        html += '<button type="submit" class="btn btn-danger">Decline</button>';
        html += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
        html += '	</form>';
        $('.modal-content .modal-body').append(html)
      });

      $('body').on('click', '#decline_cto_btn', function(event) {
        var id = $(this).data('id');
        console.log(id);
        var curDate = currentDate();
        $('.modal-content .modal-body').html("");
        html = '<form method="PATCH " action="/declineleavecto">';
        html += '<input type="hidden" value=' + id + ' name="id">';
        html += '<input type="hidden" value=' + curDate + ' name="curDate">';
        html += 'CERTIFICATION OF ILC/COC EARNED as of ' + curDate + '<br><br>';
        html += '<input type="number" step="any" min=0 class="form-control" name="hours_earned" placeholder="Number of hours earned" required><br>';
        html += '<label>Date of Last Cerfication</label><br>';
        html += '<input type="date"  class="form-control" name="last_cert" placeholder="Date of Last Certification" required><br>';
  
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
        html = '<form method="PATCH " action="/approveleave">';
        html += '<p>APPROVED FOR:</p>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="days_with_pay" placeholder="Days with pay" required><br>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="days_without_pay" placeholder="Days without pay" required><br>';
        html += '<input type="text"  step="any" min=0 class="form-control" name="others_specify" placeholder="Others (Specify)"><br>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="others_days" placeholder="Others (days)"><br>';
  
        html += '<input type="hidden" value=' + id + ' name="id"><br>';
        html += '<input type="hidden" value=' + curDate + ' name="curDate">';
        html += '<p>CERTIFICATION OF LEAVE CREDITS as of ' + curDate + '</p>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="remaining_vl" placeholder="Remaining Vacation Leave (days)" required><br>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="remaining_sl" placeholder="Remaining Sick Leave (days)" required><br>';
        html += '<input type="number"  step="any" min=0 class="form-control" name="remaining_slp" placeholder="Remaining SLP (days)" required><br>';
  
        html += '<button type="submit" class="btn btn-primary">Approve</button>';
        html += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
        html += '	</form>';
        $('.modal-content .modal-body').append(html)
  
      });

      $('body').on('click', '#approve_cto_btn', function(event) {
        var id = $(this).data('id');
        console.log(id);
        var curDate = currentDate();
        $('.modal-content .modal-body').html("");
        html = '<form method="PATCH " action="/approveleavecto">';
        html += '<input type="hidden" value=' + id + ' name="id">';
        html += '<input type="hidden" value=' + curDate + ' name="curDate">';
        html += 'CERTIFICATION OF ILC/COC EARNED as of ' + curDate + '<br><br>';
        html += '<input type="number" step="any" min=0 class="form-control" name="hours_earned" placeholder="Number of hours earned" required><br>';
        html += '<label>Date of Last Cerfication</label><br>';
        html += '<input type="date"  class="form-control" name="last_cert" placeholder="Date of Last Certification" required><br>';
  
        html += '<button type="submit" class="btn btn-primary">Approve</button>';
        html += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
        html += '	</form>';
        $('.modal-content .modal-body').append(html)
  
      });







    }
  

    if (window.location.pathname == "/forms") {      
        //publish form
        $('body').on('click', '#publish_form_btn', function(event) {
          $("#publish_election_form_id").val($(this).data('id'));
        });

        //unpublish form
        $('body').on('click', '#unpublish_form_btn', function(event) {
          $("#unpublish_election_form_id").val($(this).data('id'));
        });

        //archive form
        $('body').on('click', '#archive_form_btn', function(event) {
          $("#archive_election_form_id").val($(this).data('id'));
        });
    }

    if (window.location.pathname == "/categories") {      
      //archive category
      $('body').on('click', '#archive_category_btn', function(event) {
        $("#archive_election_category_id").val($(this).data('id'));
      });
    }

    if (window.location.pathname == "/voters") {      
      //archive category
      $('body').on('click', '#remove_voter_btn', function(event) {
        $("#delete_voter_id").val($(this).data('id'));
      });
    }

    if (window.location.pathname == "/candidates") {      
      $('body').on('click', '#remove_candidate_btn', function(event) {
        $("#delete_candidate_id").val($(this).data('id'));
      }); 
    }
    

    if (window.location.pathname == "/users") {
  
      $(document).ready(function() {
  
        $('body').on('click', '#resetPassword', function(event) {
  
          var id = $(this).data('id');
          $("#employee_id_reset").val(id);
          console.log(id);
  
        });
  
  
        $('body').on('click', '#disableUser', function(event) {
  
          var id = $(this).data('id');
          console.log(id);
          $('#modal_body').html("");
          html = '<center><h1 style="color:red;"><i class="feather icon-x-circle"></i></h1><center>';
          html += '<br><center><h3>Are you sure?</h3><center>';
          html += '<br><center><p>Disabling a user will prevent the user from accesssing<br>this application.</p><center>';
          html += '<form method="get" action="/disableuser">';
          html += '<input type="text" value=' + id + ' name="delete_user_id" hidden>';
          html += '<center><input type="submit" class="btn btn-danger" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
          $('#modal_body').append(html)
  
        });

        $('body').on('click', '#enableUser', function(event) {
  
          var id = $(this).data('id');
          console.log(id);
          $('#modal_body').html("");
          html = '<center><h1 style="color:#04a9f5;"><i class="feather icon-check-circle"></i></h1><center>';
          html += '<br><center><h3>Are you sure?</h3><center>';
          html += '<br><center><p>You are about to enable a disabled user, this will<br>give the user access to this application</p><center>';
          html += '<form method="get" action="/enableuser">';
          html += '<input type="text" value=' + id + ' name="delete_user_id" hidden>';
          html += '<center><input type="submit" class="btn btn-primary" value="Confirm"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
          $('#modal_body').append(html)
  
        });
  
  
        $('body').on('click', '#showUser', function(event) {
  
          var id = $(this).data('id');
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
  
          $.ajax({
            type: 'GET',
            url: '/users/details/' + id,
            dataType: 'JSON',
  
            success: function(data) {
              $('#user_modal_body').html("");
              //console.log(data);
              var name = data.data[0].firstname + ' ' + data.data[0].middlename + ' ' + data.data[0].lastname + ' ' + data.data[0].extname;
              name = name.replaceAll(" null", "");
  
  
              html = '<div class="container"> <img width="150px" height="150px" class="rounded-circle mx-auto d-block" src="' + data.data[0].image + '"><br>';
              html += '<center><h4>' + name + '</h4></center>';
              html += '<center><span>' + data.data[0].position + '</span></center>';
              html += '<center><span class="text-primary">' + data.data[0].email + '</span></center><br>';
              html += '<div class="row">';
  
              html += '<div class="col">';
              html += 'Employee Number :</div>';
              html += '<div class="col">';
              html += data.data[0].employee_number + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '<div class="col">';
              html += 'Item Number :</div>';
              html += '<div class="col">';
              html += data.data[0].item_number + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '<div class="col">';
              html += 'Salary Grade :</div>';
              html += '<div class="col">';
              html += data.data[0].sg + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '<div class="col">';
              html += 'Step Increment :</div>';
              html += '<div class="col">';
              html += data.data[0].stepinc + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '<div class="col">';
              html += 'Unit :</div>';
              html += '<div class="col">';
              html += data.data[0].unit + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '<div class="col">';
              html += 'Division :</div>';
              html += '<div class="col">';
              html += data.data[0].division + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '<div class="col">';
              html += 'Status :</div>';
              html += '<div class="col">';
              html += data.data[0].status + '</div>';
              html += '<div class="w-100"></div>';
  
              html += '</div>';
              html += '</div>';
              $('#user_modal_body').append(html)
              $('#user_modal').modal('show');
  
            },
          });
        });
  
        $('body').on('click', '#changeUserStat', function(event) {
  
          var id = $(this).data('id');
          console.log(id);
          $('#userstat_modal_body').html("");
          html = '<br><center><h3>Are you sure?</h3><center>';
          html += '<br><center><p>You are about to change the status of this record?</p><center>';
          html += '<form method="get" action="/changeuserstat">';
          html += '<input type="hidden" value=' + id + ' name="userstat_user_id">';
          html += '<center><input type="submit" class="btn btn-danger" value="Continue"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><center></form>';
          $('#userstat_modal_body').append(html)
  
  
        });
  
      });
  
    }
  
    if (pathname.indexOf("/manageleaves/details/", 0) != -1) {
  
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
  
  
      supervisor_note = supervisor_note.split("-");
      if (supervisor_note[0] != "Waiting for approval") {
        if (supervisor_note[0] == "Disapproved") {
          document.getElementById("radio_supervisor_disapproved").checked = true;
          document.getElementById("v_supervisor_reason").textContent = supervisor_note[1];
        } else {
          document.getElementById("radio_supervisor_approved").checked = true;
        }
      }
  
  
      hr_note = hr_note.split(",");
  
      if (hr_note[0] != "Waiting for approval") {
        if (hr_note.length == 7) {
          document.getElementById("v_vl").textContent = hr_note[0];
          document.getElementById("v_sl").textContent = hr_note[1];
          document.getElementById("v_slp").textContent = hr_note[2];
          document.getElementById("v_total").textContent = hr_note[3];
          document.getElementById("dwp").textContent = hr_note[4];
          document.getElementById("dwop").textContent = hr_note[5];
          document.getElementById("dothers").textContent = hr_note[6];
        } else {
          document.getElementById("v_vl").textContent = hr_note[0];
          document.getElementById("v_sl").textContent = hr_note[1];
          document.getElementById("v_slp").textContent = hr_note[2];
          document.getElementById("v_total").textContent = hr_note[3];
          document.getElementById("v_reason_disapproved").textContent = hr_note[4];
        }
      }
  
  
      if (document.getElementById("value_approver_id").value != "") {
        var id = document.getElementById("value_approver_id").value;
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
  
        $.ajax({
          type: 'GET',
          url: '/users/details/' + id,
          dataType: 'JSON',
  
          success: function(data) {
            var name = data.data[0].firstname + ' ' + data.data[0].middlename + ' ' + data.data[0].lastname + ' ' + data.data[0].extname;
            name = name.replaceAll(" null", "");
            var position = data.data[0].position;
            document.getElementById("approver_name").textContent = name;
            document.getElementById("approver_position").textContent = position;
            console.log(name);
  
  
          },
        });
      }
  
      if (document.getElementById("value_signatory_id").value != "") {
        var id = document.getElementById("value_signatory_id").value;
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
  
        $.ajax({
          type: 'GET',
          url: '/approver/details/' + id,
          dataType: 'JSON',
  
          success: function(data) {
            var name = data.data[0].firstname + ' ' + data.data[0].middlename + ' ' + data.data[0].lastname + ' ' + data.data[0].extname;
            name = name.replaceAll(" null", "");
            var position = data.data[0].position;
            document.getElementById("signatory_name").textContent = name.toUpperCase();
            document.getElementById("signatory_position").textContent = position;
            //console.log(name);
  
  
          },
        });
      }
    }
  });


  function isWeekend(date1){
    var dt = new Date(date1);
     
    if(dt.getDay() == 6 || dt.getDay() == 0)
       {
        return true;
        } 
    }

      function getDaysInMonth(month, year) {
        var date = new Date(year, month, 1);
        var days = [];
        while (date.getMonth() === month) {
          days.push(convert(new Date(date)));
          date.setDate(date.getDate() + 1);
        }
        return days;
      }

      function getDaysInYear(year)
      {
        var date = new Date(year, 0, 1);
        var days = [];
        while (date.getFullYear() === year) {
          days.push(convert(new Date(date)));
          date.setDate(date.getDate() + 1);
        }
        return days;
      }



      
      function convert(str) {
        var date = new Date(str),
          mnth = ("0" + (date.getMonth() + 1)).slice(-2),
          day = ("0" + date.getDate()).slice(-2);
        return [date.getFullYear(), mnth, day].join("-");
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
  
  function hideForm(forms) {
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
  
  function getWeeksStartAndEndInMonth(month, year) {
    let weeks = [],
      firstDate = new Date(year, month-1, 1),
      lastDate = new Date(year, month, 0),
      numDays = lastDate.getDate();
    

    start = 1;
    let end=0;
    var temp=firstDate.getUTCDay();
    if(temp==6)
    {
      end = 7;
    }

    else

    {
      end = GetfirstWeekEnd(month, year).getDate();
    }
    
    while (start <= numDays) {
      weeks.push({start: start,end: end});

      start = end + 1;
      end = end + 7;

      end = start === 1 && end === 8 ? 1 : end;
      
      if (end > numDays) {
        end = numDays;
      }
    }
    return weeks;
  }
  
  function GetfirstWeekEnd(month, year) {
    var firstDay = new Date(year + "-" + month + "-" + 1);
    var first = firstDay;
    const lastDay = new Date(year, month, 0);
    const daysInMonth = lastDay.getDate();
    let dayOfWeek = firstDay.getDay();
    var endday = "";
    var startCount = dayOfWeek;
    if (startCount != 0) {
      endday = new Date(firstDay.setDate(firstDay.getDate() + (6 - dayOfWeek)));
    } else {
      endday = new Date(firstDay.setDate(firstDay.getDate()));
  
    }
    return endday;
  }
  
  function endFirstWeek(firstDate, firstDay) {
    if (!firstDay) {
      return 7 - firstDate.getDay();
    }
    if (firstDate.getDay() < firstDay) {
      return firstDay - firstDate.getDay();
    } else {
      return 7 - firstDate.getDay() + firstDay;
    }
  }