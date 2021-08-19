/**
 * Center Modal
 */
$(document).ready(function() {
	function alignModal(){
		var modalDialog = $(this).find(".modal-dialog");
	    /* Applying the top margin on modal dialog to align it vertically center */
	    modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
	}
	    
	// Align modal when it is displayed
	$(".modal").on("shown.bs.modal", alignModal);
	
	// Align modal when user resize the window
	$(window).on("resize", function(){
	    $(".modal:visible").each(alignModal);
	});
});

/**
 * Create New Job for Employee
 */
$(document).on('submit', '#addNewEmployeeJobForm', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#addNewEmployeeJobModal").modal('hide');
    $(".loadingGif").show();
    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data)
        {   
            //alert(data);
            data = JSON.parse(data);
            var addNewEmployeeJobTable = document.getElementById("addNewEmployeeJobTable");
            if(data['Status'] =="CREATED"){
                var addNewEmployeeJobRow = $("#addNewEmployeeJobRow").html();
                
                var NewEmployeeJobRow = addNewEmployeeJobTable.insertRow();
                $(NewEmployeeJobRow).html(addNewEmployeeJobRow);
            }
            else{
            	var NewEmployeeJobRow = $("#jobrow"+data['uh_id']);
            }
                 var locName = $("#locID option:selected").text();
                 var deptName = $("#deptID option:selected").text();
                 var desigName = $("#desigID option:selected").text();
                 var contractType = $("#contractType option:selected").text();
                 var contractDuration = $("#contractDuration option:selected").text();
                 var effective_date = data['effective_date'];
                 var status = '<a href="javascript:void(0);" type="button" class="btn btn-info btn-xs"><strong>Make Active</strong></a>';

                 //alert(groupName);
                $(NewEmployeeJobRow).attr("id", "jobrow"+data['uh_id'])
                $(NewEmployeeJobRow).find(".locName").html(locName);
                $(NewEmployeeJobRow).find(".deptName").html(deptName);
                $(NewEmployeeJobRow).find(".desigName").html(desigName);
                $(NewEmployeeJobRow).find(".contractType").html(contractType);
                $(NewEmployeeJobRow).find(".contractDuration").html(contractDuration);
                $(NewEmployeeJobRow).find(".effective_date").html(effective_date);
                $(NewEmployeeJobRow).find(".status").html(status);

                //$(addNewEmployeeJobTable).find(".Status").html("Inactive");
                //$(NewEmployeeJobRow).find(".Status").html("Active");
                $(NewEmployeeJobRow).find(".delete").attr("onclick","del("+data['uh_id']+")");

                $(NewEmployeeJobRow).find(".edit").attr("desig_id",$("#desigID option:selected").val());
                $(NewEmployeeJobRow).find(".edit").attr("loc_id",$("#locID option:selected").val());
                $(NewEmployeeJobRow).find(".edit").attr("dept_id",$("#deptID option:selected").val());
                $(NewEmployeeJobRow).find(".edit").attr("uh_contract_type",$("#contractType option:selected").val());
                $(NewEmployeeJobRow).find(".edit").attr("uh_contract_duration",$("#contractDuration option:selected").val());
                $(NewEmployeeJobRow).find(".edit").attr("uh_id",data['uh_id']);

                $(".loadingGif").hide();
                 	$.smallBox({
                 		title : "Action Status",
                 		content : "<i class='fa fa-clock-o'></i> <i>Record Saved successfully...</i>",
                 		color : "#659265",
                 		iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 		timeout : 4000
                });
                 	
                setTimeout(function(){ window.location=""; }, 2000);

                for(var count = 0; count< data['lt_Name'].length; count++){

                    if(data['leaveAnnual'][count] == 'Y'){

                        var leaveTable = document.getElementById("AnnualLeaveTable");

                    }
                    else{

                         var leaveTable = document.getElementById("OtherLeaveTable");
                    }

                    var copyLeaveRow = document.getElementById("copyLeaveRow").innerHTML;
                    var leaveRow = leaveTable.insertRow();
                    $(leaveRow).html(copyLeaveRow);
                     $(leaveRow).attr('id', 'ULrow'+data['ul_id'][count]);
                    $(leaveRow).find('.leaveYear').html(data['leaveYear'][count]);
                    $(leaveRow).find('.lt_Name').html(data['lt_Name'][count]);
                    $(leaveRow).find('.leaveNumber').html(data['leaveNumber'][count]);
                    $(leaveRow).find('.leaveNumber').addClass("Year"+data['leaveYear'][count]+"UL"+data['ul_id'][count]);
                    $(leaveRow).find('.leaveExpiryDate').html(data['leaveExpiryDate'][count]);
                    $(leaveRow).find('.leaveStatus').html('Active');
                    $(leaveRow).find('.edit').attr('ul_id', data['ul_id'][count]);
                    $(leaveRow).find('.edit').attr('ul_Number', data['leaveNumber'][count]);
                    $(leaveRow).find('.delete').attr('onclick', 'delUL('+data['ul_id'][count]+')');


                }
            

        }
    });
});

/****************************************
    Adjust Leaves Of Employee Starts Here
*****************************************/
function EmployeeLeaveAdjust(){

     event.preventDefault();
    var formElement =   document.getElementById('EmployeeLeaveAdjustForm');
    var formData    =   new FormData(formElement);
    //formData.append('user_id', user_id);
    //alert(formData.getAll('userleave'));
    var formError   =   false;
    $("#EmployeeLeaveAdjustModal").modal('hide');
    $(".loadingGif").show();
    var CurrentYear = $("#CurrentYear").val();
    $.ajax({


        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data)
        {
            data = JSON.parse(data);

            

            for(var index in data.userleave) { 
                var newLeave = data.userleave[index]; 
               // alert(index);
                $(".Year"+CurrentYear+"UL"+index).html(newLeave);

            }
            $.smallBox({
                 title : "Action Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Saved successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });

             

            $(".loadingGif").hide();   
        }
    });

}

/****************************************
    Adjust Single Leave Starts Here
*****************************************/
function SingleLeaveAdjust(){

     event.preventDefault();
    var formElement =   document.getElementById('SingleLeaveAdjustForm');
    var formData    =   new FormData(formElement);
    //formData.append('user_id', user_id);
    //alert(formData.getAll('userleave'));
    var formError   =   false;
    $("#SingleLeaveAdjustModal").modal('hide');
    $(".loadingGif").show();
    var CurrentYear = $("#CurrentYear").val();
    $.ajax({


        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data)
        {

            data = JSON.parse(data);
            if(data['Status']=="UPDATED"){

            var ul_Number = formData.get("ul_Number");
            var ul_id = $("#SingleLeaveAdjustId").val();
            
            $(".UL"+ul_id).html(ul_Number);
            $.smallBox({
                 title : "Action Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Saved successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });
            $(".loadingGif").hide();   
        }
        }
    });

}

/**
 * Clear All Values in Leave Type Modal Form 
 */
$(document).on('click', '#createLeaveTypeForm', function(event) {
    $("#leaveTypeName").val('');
});

/**
 * Create New Leave Type Record
 */
$(document).on('submit', '#create-leaveType-form', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#leaveTypeModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {   
            if(data.trim()!="")
            {
                var lt_id=data.trim();
                var row=$("#copyRow").clone().show();
                var scale=$("#desigScale").val();
                status=formData.get('lt_Status');
                
                if(status=='A'){ 
                	ltStatus="<span class='center-block padding-5 label label-success'>Active</span>";
                }else{ 
                	ltStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
                
                $(row).attr('id',"row"+lt_id);
                $(row).find('.edit').attr('href','javascript:editGetleaveType('+lt_id+')');
                $(row).find('.delete').attr('href','javascript:del('+lt_id+')');
                $(row).find(".name").html(formData.get('lt_Name'));
                
                $(row).find(".status").html(ltStatus);
                $(row).find(".status").attr('status-value',formData.get('lt_Status'));
                $("#headRow").after(row);
                $(".loadingGif").hide();
                
                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });
            }
        }

    })
});

/**
 * Onclick Edit Leave Type Button
 */
function editGetLeaveType(id)
{
    var name = $("#row"+id).find(".name").text();
    
    var status = $("#row"+id).find(".status").attr('status-value');
    
    $("#lt_id").val(id);
    $("#leaveTypeName").val(name);
   
    $("#ltStatus").val(status);
    $("#leaveTypeModal").modal('show');
    $("#leaveTypeModal").find("#create-leaveType-form").attr('id','update-leaveType-form');
}

/**
 * Update Leave Type Modal Form
 */
$(document).on('submit', '#update-leaveType-form', function(event) {
    event.preventDefault();

    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#leaveTypeModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            var lt_id=$("#lt_id").val();
            var status=formData.get('lt_Status');
            
            if(status=='A'){ 
            	ltStatus="<span class='center-block padding-5 label label-success'>Active</span>";
            }else{ 
            	ltStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
            }
            
            $("#row"+lt_id).find(".name").text(formData.get('lt_Name'));
            $("#row"+lt_id).find(".status").html(ltStatus);
            $("#row"+lt_id).find(".status").attr('status-value',formData.get('lt_Status'));
            //$("#leaveTypeModal").modal('hide');
            $(".loadingGif").hide();
            
            $.smallBox({
            	title : "Update Status",
            	content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
            	color : "#659265",
            	iconSmall : "fa fa-check fa-2x fadeInRight animated",
            	timeout : 4000
            });
        }
    });
});


/**
 * Clear All Form Values in Document Modal Form 
 */
$(document).on('click', '#createDocumentForm', function(event) {
    $("#docName").val('');
    $("#docType").val('F');
    $("#docstatus").val('A');
});

/**
 * Create New Document Record
 */
$(document).on('submit', '#create-document-form', function(event) {
    event.preventDefault();
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#docModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data:formData,
        cache:false,
        contentType:false,
        processData:false,
        success:function(html)
        {
            var newId=parseInt(html);
            html=html.replace(/[0-9]/g, '')
            
            if(html.trim()=='INSERTED')
            {
                var docName=$("#docName").val();
                var docType=$("#docType").val(); 
                var docStatus=$("#docStatus").val(); 
                var newRow;
                
                if(docType=='F'){ 
                	type="Files"; 
                }else{ 
                	type='Images'; 
                }
                
                if(docStatus=='A'){ 
                	status="<span class='center-block padding-5 label label-success'>Active</span>"; 
                } else{ 
                	status="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
				
                newRow = "<tr id='row"+newId+"'>" +
                			"<td class='docName' data-class='expand'>"+docName+"</td>" +
                			"<td class='docType' style='width:10%;' docType-value='"+docType+"'>"+type+"</td>" +
                			"<td class='docStatus' style='width:10%;' status-value='"+docStatus+"'>"+status+"</td>" +
                			"<td data-hide='phone' style='width:8%;'>" +
                				"<div class='btn-group'>" +
                					"<button class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>Action <span class='caret'></span></button>" +
                					"<ul class='dropdown-menu dropdown-menu-right'>" +
										"<li>" +
											"<a href='javascript:editGetDocument("+newId+")' class='edit'><i class='fa fa-pencil fa-lg fa-fw txt-color-greenLight'></i> Edit</a>" +
										"</li>" +
										"<li>" +
											"<a href='javascript:del("+newId+")' class='delete'><i class='fa fa-trash-o fa-lg fa-fw txt-color-greenLight'></i> Delete</a>" +
										"</li>" +
									"</ul>" +
								"</div>" +
							"</td>" +
						 "</tr>";
                
                //$("#headRow").after(newRow);
                $('tbody').prepend(newRow);
                $(".loadingGif").hide();
                
                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });
            }
        }
    });
});

/**
 * Onclick Edit Document Button
 */
function editGetDocument(doc_id)
{
    var name = $("#row"+doc_id).find(".docName").text();
    var type = $("#row"+doc_id).find(".docType").attr('docType-value');
    var status = $("#row"+doc_id).find(".docStatus").attr('status-value');
    
    $("#doc_id").val(doc_id);
    $("#docName").val(name);
    $("#docType").val(type);
    $("#docStatus").val(status);
    $("#docModal").modal('show');
    $("#docModal").find("#create-document-form").attr('id','update-document-form');
}

/**
 * Update Document Record
 */
$(document).on('submit', '#update-document-form', function(event) {
    event.preventDefault();
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#docModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data:formData,
        cache:false,
        contentType:false,
        processData:false,
        success:function(html)
        {
            var update_id=parseInt(html);
            html=html.replace(/[0-9]/g, '');
            if(html.trim()=='UPDATED')
            {
                var docName=$("#docName").val();
                var docType=$("#docType").val(); 
                var docStatus=$("#docStatus").val();  
                
                if(docType=='F'){ 
                	type = 'Files'; 
                }else { 
                	type = 'Images'; 
                }
                
                if(docStatus=='A'){ 
                	status="<span class='center-block padding-5 label label-success'>Active</span>"; 
                } else { 
                	status="<span class='center-block padding-5 label label-danger'>In-Active</span>";
                }
                
                $("#row"+update_id).find(".docName").html(docName);
                $("#row"+update_id).find(".docType").html(type);
                $("#row"+update_id).find(".docStatus").html(status);
                $("#row"+update_id).find(".docType").attr('docType-value',docType);
                $("#row"+update_id).find(".docStatus").attr('status-value',docStatus);
                
                $(".loadingGif").hide();

                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });
            }
        }

    });
});

//01/08/17 FIXED BY MALEK - FAILED TO SUBMIT FORM IN FIREFOX
$(document).on('submit', '#createNoticeForm', function(event) {
    event.preventDefault();
    
    //var formElement =   document.getElementById('createNoticeForm');
    var formData    =   new FormData(this);
    var formError   =   false;

    $("#noticeModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            console.log(data);
            if(data.trim()!="")
            {
                var notice_id=data.trim();
                //var row=document.getElementById('copyRow').innerHTML;
                var row=$("#copyRow").clone().show();
                var title=$("#notice_Title").val();
                var description=$("#notice_Description").val();
                var fromdate = $("#notice_FromDate").val();
                var todate = $("#notice_ToDate").val();
                var remarks=$("#notice_Remarks").val();
                var status=$("#notice_Status").val();
                
                title = "<strong>"+title+"</strong>";
                
                if(status=='A'){ 
                	notice_Status="<span class='center-block padding-5 label label-success'>Active</span>";
                }else{ 
                	notice_Status="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
                
                $(row).attr('id',"row"+notice_id);
                $(row).find('.edit').attr('href','javascript:editGetNotice('+notice_id+')');
                $(row).find('.delete').attr('href','javascript:del('+notice_id+')');
                $(row).find(".title").html(title);
                 description = description.replace(/\r?\n/g, '<br />');
                $(row).find(".description").html(description);
                $(row).find(".fromdate").html(fromdate);
                $(row).find(".todate").html(todate);
                $(row).find(".remarks").html(remarks);
                $(row).find(".status").attr('status-value',status);
                $(row).find(".status").html(notice_Status);
                $("#headRow").after(row);
                $(".loadingGif").hide();
                
                $.smallBox({
                 title : "Create Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });
                
                setTimeout(function(){ window.location=""; }, 2000);

            }
        }

    })
    
});

/**
 * Onclick Edit Notice Button
 */
function editGetNotice(id)
{
    var title = $("#row"+id).find(".title").text();
    var description=$("#row"+id).find(".description").text();
    var fromdate = $("#row"+id).find(".fromdate").text();
    var status = $("#row"+id).find(".status").attr('status-value');
    var todate = $("#row"+id).find(".todate").text();
    var remarks = $("#row"+id).find(".remarks").text();
    
    $("#notice_id").val(id);
    $("#notice_Title").val(title);
    $("#notice_Description").val(description);
    $("#notice_FromDate").val(fromdate);
    $("#notice_ToDate").val(todate);
    $("#notice_Status").val(status);
    $("#notice_Remarks").val(remarks);
    $("#noticeModal").modal('show');
    $("#noticeModal").find("#save").attr('onsubmit','updateNotice()');
}

/**
 * Update Notice Details
 */
function updateNotice()
{
    event.preventDefault();
    var formElement =   document.getElementById('createNoticeForm');
    var formData    =   new FormData(formElement);
    var formError   =   false;
    $("#noticeModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({

        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,


        success:function(data,success)
        {

            
            if(data.trim()=='UPDATED')
            {
                var id =$("#notice_id").val();
                var title=$("#notice_Title").val();
                var description=$("#notice_Description").val();
                var fromdate=$("#notice_FromDate").val();
                var todate=$("#notice_ToDate").val();
                var remarks=$("#notice_Remarks").val();
                var status=$("#notice_Status").val();
if(status=='A'){ notice_Status="Active";}else{ notice_Status="In-Active"; }
               
               
                $("#row"+id).find(".title").html(title);
                description = description.replace(/\r?\n/g, '<br />');

                $("#row"+id).find(".description").html(description);
                $("#row"+id).find(".fromdate").html(fromdate);
                $("#row"+id).find(".todate").html(todate);
                $("#row"+id).find(".remarks").html(remarks);
                $("#row"+id).find(".status").attr('status-value',status);
                $("#row"+id).find(".status").html(notice_Status);
                $(".loadingGif").hide();
                

                $.smallBox({
                 title : "Create Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });

            }
        }

    })
}

/**
 * Clear All Form Values
 */
$('#createHolidayFormModal').on('click', function () {
    $("#holiday_Title").val('');
    $("#holiday_Description").val('');
    $("#holiday_Date").val('');
    $("#holiday_id").val('');
});

/**
 * Create New Holiday Record
 */
$(document).on('submit', '#createHolidayForm', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#holidayModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            if(data.trim()!="")
            {
                var holiday_id=data.trim();
                var row=$("#copyRow").clone().show();
                var title=$("#holiday_Title").val();
                var description=$("#holiday_Description").val();
                
                var date=$("#holiday_Date").val();
            
                var status=$("#holiday_Status").val();
                
                if(status=='A'){ 
                	holiday_Status="<span class='center-block padding-5 label label-success'>Active</span>";
                }else{ 
                	holiday_Status="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
                
                $(row).attr('id',"row"+holiday_id);
                $(row).find('.edit').attr('href','javascript:editGetHoliday('+holiday_id+')');
                $(row).find('.delete').attr('href','javascript:del('+holiday_id+')');
                $(row).find(".title").html(title);
                $(row).find(".description").html(description);
                
                $(row).find(".date").html(date);
            
                $(row).find(".status").attr('status-value',status);
                $(row).find(".status").html(holiday_Status);
                $("#headRow").after(row);
                
                $(".loadingGif").hide();

                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });

            }
        }

    })
});

/**
 * Onclick Edit Holiday Button
 */
function editGetHoliday(id)
{
    var title = $("#row"+id).find(".title").text();
    var description=$("#row"+id).find(".description").text();
    var date = $("#row"+id).find(".date").text();
    var status = $("#row"+id).find(".status").attr('status-value');
    
    $("#holiday_id").val(id);
    $("#holiday_Title").val(title);
    $("#holiday_Description").val(description);
    $("#holiday_Date").val(date);
    $("#holiday_Status").val(status);
    $("#holidayModal").modal('show');
    $("#holidayModal").find("#createHolidayForm").attr('id','updateHolidayForm');
}

/**
 * Update Holiday Record
 */
$(document).on('submit', '#updateHolidayForm', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;

    $("#holidayModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            if(data.trim()=='UPDATED')
            {
                var id =$("#holiday_id").val();
                var title=$("#holiday_Title").val();
                var description=$("#holiday_Description").val();
                var date=$("#holiday_Date").val();
                var status=$("#holiday_Status").val();
                
                if(status=='A'){ 
                	holiday_Status="<span class='center-block padding-5 label label-success'>Active</span>";
                }else{ 
                	holiday_Status="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
            	
                $("#row"+id).find(".title").html(title);
                $("#row"+id).find(".description").html(description.replace(/\n\r?/g, "<br />"));
                $("#row"+id).find(".date").html(date);
                $("#row"+id).find(".status").attr('status-value',status);
                $("#row"+id).find(".status").html(holiday_Status);
                
                $(".loadingGif").hide();

                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });
            }
        }
    })
});

/*
 * Clear All Values in Create Designation Modal Form 
 */
$(document).on('click', '#createDesignationForm', function(event) {
    $("#desig_id").val('');
    $("#desigName").val('');
    $("#desigShortName").val('');
    $("#desigScale").val('');
});

/**
 * Create New Designation Record
 * 
 * last editted: justin, 16/08/17
 */
$(document).on('submit', '#create-designation-form', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#designationModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({

        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            if(data.trim()!="")
            {
                var desig_id=data.trim();
                //var row=document.getElementById('copyRow').innerHTML;
                var row=$("#copyRow").clone().show();
                var name=$("#desigName").val();
                var shortname=$("#desigShortName").val();
                var status=$("#desigStatus").val();
                var dl_Number=$("#dl_Number").val();
                var scale=$("#desigScale").val();
                
                if(status=='A'){ 
                	desigStatus="<span class='center-block padding-5 label label-success'>Active</span>";
                }else{ 
                	desigStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
                
                $(row).attr('id',"row"+desig_id);
                $(row).find('.edit').attr('href','javascript:editGetDesignation('+desig_id+')');
                $(row).find('.delete').attr('href','javascript:del('+desig_id+')');
                $(row).find(".name").html(name);
                $(row).find(".shortname").html(shortname);
                $(row).find(".scale").html(scale);
                $(row).find(".leaves").html(dl_Number);
                $(row).find(".status").html(desigStatus);
                $(row).find(".status").attr('status-value',status);
                $("#headRow").after(row);
                $(".loadingGif").hide();
                
                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });
                
                setTimeout(function(){ window.location=""; }, 2000);

                name = '';;
                shortname = '';
                status = '';
                dl_Number = '';
                scale = '';
            }
        }

    })
});

/*
 * Onclick Edit Designation Button 
 */
function editGetDesignation(id)
{
    var name = $("#row"+id).find(".name").text();
    var shortname=$("#row"+id).find(".shortname").text();
    var scale = $("#row"+id).find(".scale").text();
    var status = $("#row"+id).find(".status").attr('status-value');
    var dl_Number = $("#row"+id).find(".leaves").text();
    
    $("#desig_id").val(id);
    $("#desigName").val(name);
    $("#desigShortName").val(shortname);
    $("#desigScale").val(scale);
    $("#desigStatus").val(status);
    $("#dl_Number").val(dl_Number);
    $("#designationModal").modal('show');
    $("#designationModal").find("#create-designation-form").attr('id','update-designation-form');
}

/**
 * Update Designation Record
 * 
 * last editted: justin, 16/08/17
 */
$(document).on('submit', '#update-designation-form', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;
    
    $("#designationModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({

        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            var desig_id=$("#desig_id").val();
            var desigName=$("#desigName").val();
            var desigShortName=$("#desigShortName").val();
            var desigScale=$("#desigScale").val();
            var status=$("#desigStatus").val();
            var leaves=$("#dl_Number").val();
            
            if(status=='A'){ 
            	desigStatus="<span class='center-block padding-5 label label-success'>Active</span>";
            }else{ 
            	desigStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
            }
            
            $("#row"+desig_id).find(".name").text(desigName);
            $("#row"+desig_id).find(".shortname").text(desigShortName);
            $("#row"+desig_id).find(".scale").text(desigScale);
            $("#row"+desig_id).find(".status").html(desigStatus);
            $("#row"+desig_id).find(".leaves").text(leaves);
            $("#row"+desig_id).find(".status").attr('status-value',status);
            $(".loadingGif").hide();
            
            $.smallBox({
            	title : "Update Status",
            	content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
            	color : "#659265",
            	iconSmall : "fa fa-check fa-2x fadeInRight animated",
            	timeout : 4000
            });
        }
    });
});

/**
 * Clear All Values in Create Department Modal Form 
 */
$(document).on('click', '#createDepartmentForm', function(event) {
	$("#deptName").val("");
    $("#deptShortName").val('');
});

/**
 * Create New Department Record
 */
$(document).on('submit', '#create-department-form', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;

    $("#departmentModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            if(data.trim()!="")
            {
                var dept_id=data.trim();
                var row=$("#copyRow").clone().show();
                status=formData.get('dept_Status');
                
                if(status=='A'){ 
                	deptStatus="<span class='center-block padding-5 label label-success'>Active</span>";
                }else{ 
                	deptStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
                var scale=$("#desigScale").val();
                $(row).attr('id',"row"+dept_id);
                $(row).find('.edit').attr('href','javascript:editGetDepartment('+dept_id+')');
                $(row).find('.delete').attr('href','javascript:del('+dept_id+')');
                $(row).find(".name").html(formData.get('dept_Name'));
                $(row).find(".shortname").html(formData.get('dept_ShortName'));
                $(row).find(".status").html(deptStatus);
                $(row).find(".status").attr('status-value',formData.get('dept_Status'));
                $("#headRow").after(row);
                $(".loadingGif").hide();
                
                $.smallBox({
                	title : "Create Status",
                	content : "<i class='fa fa-clock-o'></i> <i>Record Created successfully...</i>",
                	color : "#659265",
                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                	timeout : 4000
                });
            }
        }

    })
});

/*
 * Onclick Edit Department Button 
 */
function editGetDepartment(id)
{
    var name = $("#row"+id).find(".name").text();
    var shortname=$("#row"+id).find(".shortname").text();
    var status = $("#row"+id).find(".status").attr('status-value');
    
    $("#dept_id").val(id);
    $("#deptName").val(name);
    $("#deptShortName").val(shortname);
    $("#deptStatus").val(status);
    $("#departmentModal").modal('show');
    $("#departmentModal").find("#create-department-form").attr('id','update-department-form');
}

/**
 * Update Department Record
 * 
 * last editted: justin, 16/08/17
 */
$(document).on('submit', '#update-department-form', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;

    $("#departmentModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            var dept_id=$("#dept_id").val();
            var status=formData.get('dept_Status');
            
            if(status=='A'){ 
            	deptStatus="<span class='center-block padding-5 label label-success'>Active</span>";
            }else{ 
            	deptStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
            }
            
            $("#row"+dept_id).find(".name").text(formData.get('dept_Name'));
            $("#row"+dept_id).find(".shortname").text(formData.get('dept_ShortName'));
            $("#row"+dept_id).find(".status").html(deptStatus);
            $("#row"+dept_id).find(".status").attr('status-value',formData.get('dept_Status'));
            
            $(".loadingGif").hide();

            $.smallBox({
            	title : "Update Status",
            	content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
            	color : "#659265",
            	iconSmall : "fa fa-check fa-2x fadeInRight animated",
            	timeout : 4000
            });
        }
    });
});

// check user no. validity
function checkUserCode()
{
	var user_UserCode =$("#userUserCode").val();
	
    if(!user_UserCode.length){
        $("#user_UserCodeCheck").html('Employe ID should not be empty!');
        $("#user_UserCodeCheck").removeClass();
        $("#user_UserCodeCheck").addClass('text-warning');
    }
    else
    {
        var data="user_UserCode="+user_UserCode;
        $.ajax({
	        url:'config-process.php',
	        type:'post',
	        data:data,
	        cache:false,
	        processData:false,
	        success:function(Codedata,success)
	        {
	           if(Codedata.trim()=='Y'){
	            $("#user_UserCodeCheck").html('Employe ID already Exists! Try with different.');
	            $("#user_UserCodeCheck").removeClass();
	            $("#user_UserCodeCheck").addClass('text-danger');
	           }
	           else if(Codedata.trim()=='N'){
	             $("#user_UserCodeCheck").html('Employe ID is Okay!');
	             $("#user_UserCodeCheck").removeClass();
	            $("#user_UserCodeCheck").addClass('text-success');
	           }
	
	        }
	    });
    }
}

function checkUserCodeEdit(user_id)
{
        var user_UserCode =$("#userUserCodeEdit").val();

        if(!user_UserCode.length){
            $("#user_UserCodeCheck").html('Employe ID should not be empty!');
            $("#user_UserCodeCheck").removeClass();
            $("#user_UserCodeCheck").addClass('text-warning');
        }
        else
        {

        var data="user_UserCode="+user_UserCode+"&user_id="+user_id;
        //alert(data);
        $.ajax({

        url:'config-process.php',
        type:'post',
        data:data,
        cache:false,
        processData:false,
        success:function(Codedata,success)
        {
           if(Codedata.trim()=='Y'){
            $("#user_UserCodeCheckEdit").html('Employe ID already Exists! Try with different.');
            $("#user_UserCodeCheckEdit").removeClass();
            $("#user_UserCodeCheckEdit").addClass('text-danger');
           }
           else if(Codedata.trim()=='N'){
             $("#user_UserCodeCheckEdit").html('Employe ID is Okay!');
             $("#user_UserCodeCheckEdit").removeClass();
            $("#user_UserCodeCheckEdit").addClass('text-success');
           }

        }
    });
    }
}

// check user email validity
function checkUserEmail()
{	
    var userEmail =$("#userEmail").val();
    
    if(!userEmail.length){
    	$("#user_EmailCheck").show();
        $("#user_EmailCheck").html('Email should not be empty!');
        $("#user_EmailCheck").removeClass();
        $("#user_EmailCheck").addClass('text-warning');
    }
    else
    {
        var data="user_Email="+userEmail;
        $.ajax({
	        url:'config-process.php',
	        type:'post',
	        data:data,
	        cache:false,
	        processData:false,
	        success:function(Emaildata,success)
	        {
	        	if(Emaildata.trim()=='Y'){
	        		$("#user_EmailCheck").html('Email already Exists! Try with different.');
	        		$("#user_EmailCheck").removeClass();
	        		$("#user_EmailCheck").addClass('text-danger');
	        	}
	        	else if(Emaildata.trim()=='N'){
	        		$("#user_EmailCheck").html('Email is Okay!');
	        		$("#user_EmailCheck").removeClass();
	        		$("#user_EmailCheck").addClass('text-success');
	        	}
	        }
        });
    }
}

function checkUserEmailEdit(user_id)
{
    var userEmail =$("#userEmailEdit").val();
        if(!userEmail.length){
            $("#user_EmailCheckEdit").html('Email should not be empty!');
            $("#user_EmailCheckEdit").removeClass();
            $("#user_EmailCheckEdit").addClass('text-warning');
        }
        else
        {
        var data="user_Email="+userEmail+"&user_id="+user_id;
        $.ajax({

        url:'config-process.php',
        type:'post',
        data:data,
        cache:false,
        processData:false,
        success:function(Emaildata,success)
        {

           if(Emaildata.trim()=='Y'){
            $("#user_EmailCheckEdit").html('Email already Exists! Try with different.');
            $("#user_EmailCheckEdit").removeClass();
            $("#user_EmailCheckEdit").addClass('text-danger');
           }
           else if(Emaildata.trim()=='N'){
             $("#user_EmailCheckEdit").html('Email is Okay!');
             $("#user_EmailCheckEdit").removeClass();
             $("#user_EmailCheckEdit").addClass('text-success');
           }

        }
        });
    }
}

/**
 * Create New Employee Record
 * 
 * last editted: 14/08/17, justin
 * fix onsubmit issue occurred in FF
 */ 
$(document).on('submit', '#create-employee-form', function(event) {
    event.preventDefault();
    var formData    =   new FormData(this);
    var formError   =   false;
    
    formData.append("is_create", true);

    $("#employeeModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data:formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,status)
        {
            if(data.trim()!="")
            {
                var user_id=data.trim();
               
                var row=$("#copyRow").clone().show();
                var status=formData.get('user_Status');
                var gender= formData.get('user_Gender');
                
                if(gender=='M'){ empGender="Male"; }else{ empGender="Female"; }
                if(status=='A'){ 
                	empStatus="<span class='center-block padding-5 label label-success'>Active</span>";
                }
                else{ 
                	empStatus="<span class='center-block padding-5 label label-danger'>In-Active</span>"; 
                }
                
                $(row).find('.view').attr('href','javascript:viewEmployee('+user_id+')');
                $(row).find('.edit').attr('href','employee-edit.php?user_id='+user_id+'');
                $(row).find('.delete').attr('href','javascript:del('+user_id+')');
                $(row).find('.name').text(formData.get('user_FirstName'));
                $(row).find('.loc').text(formData.get('loc_id'));
                $(row).find('.dept').text(formData.get('dept_id'));
                $(row).find('.code').text(formData.get('user_UserCode'));
                $(row).find('.email').text(formData.get('user_Email'));
                $(row).find('.gender').text(empGender);
                $(row).find('.status').html(empStatus);
                $("#headRow").after(row);

                setTimeout(function(){ 
                	$(".loadingGif").hide(); 
                	$.smallBox({
                    	title : "Create Status",
                    	content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
                    	color : "#659265",
                    	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                    	timeout : 4000
                    });
                }, 2000);
               
                setTimeout(function(){ window.location=""; }, 5000);
            }
        }
    });
});

function viewEmployee(id)
{   
        var data="get_user_id="+id;
        $.ajax({

        url:'config-process.php',
        type:'post',
        data:data,
        cache:false,
       /* contentType:false,*/
        processData:false,
        success:function(data,success)
        {
           // alert(data);
            $("#viewEmployeeModalContent").html(data.trim());
            $("#viewEmployeeModal").modal('show');

        }
    });
}

function editGetEmp(id)
{
    var data="edit_user_id="+id;
    $.ajax({

        url:'config-process.php',
        type:'post',
        data:data,
        cache:false,
       /* contentType:false,*/
        processData:false,
        success:function(data,success)
        {
            //alert(data);
            $("#editEmployeeShow").html(data.trim());
            $("#editEmployeeModal").modal('show');

        }
    });
}
function promoteCheck(e)
{
    if($(e).is(":checked")) 
    {
        $('#ULDiv').show();
    }
    else
    {
        $('#ULDiv').hide();
    }
}

/*
 * update my profile
 */ 
$(document).on('submit', '#update-my-profile-form', function(event) {
    event.preventDefault();
    var formData    =   new FormData(this);
    var formError   =   false;
    var pid=$("#profile_id").val();
    
    formData.append("is_update",true);
    //$("#btnUpdateMyProfile").attr('disabled','disabled');
    $("#btnUpdateMyProfile").prop("disabled", true);
    $("#btnUpdateMyProfile").css("cursor", "not-allowed");
    $('#divMsg').show();
    //$(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
        	console.log(data);

            if(data.trim()=='UPDATED')
            {
                setTimeout(function(){ 
                	$(".loadingGif").hide(); 
                	$.smallBox({
                    	title : "Success!",
                    	content : "<i class='fa fa-clock-o'></i> <i>Record updated successfully...</i>",
                    	color : "#659265",
                    	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                    	timeout : 1000
                    });
                }, 2000);
                
            }else{
            	setTimeout(function(){ 
	            	$(".loadingGif").hide(); 
	            	$.smallBox({
	                	title : "Error!",
	                	content : "<i class='fa fa-clock-o'></i> <i>Error occured...</i>",
	                	color: "#D8000C",
	                	iconSmall : "fa fa-check fa-2x fadeInRight animated",
	                	timeout : 1000
	                });
            	}, 2000);
            }
            
            setTimeout(function(){ window.location=""; }, 4000);
        }
    });
});

/*
 * update employee profile
 * last editted: 12/08/17, justin
 * fix onsubmit issue occurred in FF
 */ 
$(document).on('submit', '#update-employee-form', function(event) {
    event.preventDefault();
    var formData    =   new FormData(this);
    var formError   =   false;

    formData.append("is_update",true);
    //$("#editEmployeeModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
        	console.log(data);
            if(data.trim()=='UPDATED')
            {
                var user_id=formData.get('u_id');
                var row= $("#row"+user_id);
                var status=formData.get('user_Status');
                var gender= formData.get('user_Gender');

                if(status=='A'){ empStatus="Active";}else{ empStatus="In-Active"; }
                if(gender=='M'){ empGender="Male"; }else{ empGender="Female"; }
        
                $(row).find('.name').text(formData.get('user_FirstName')+" "+formData.get('user_LastName'));
                $(row).find('.email').text(formData.get('user_Email'));
                $(row).find('.phoneno').text(formData.get('user_PhoneNo'));
                $(row).find('.gender').text(empGender);
                $(row).find('.status').text(empStatus);
                
                setTimeout(function(){ 
                	$(".loadingGif").hide(); 
                	$.smallBox({
                    	title : "Create Status",
                    	content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
                    	color : "#659265",
                    	iconSmall : "fa fa-check fa-2x fadeInRight animated",
                    	timeout : 4000
                    });
                }, 2000);
               
                setTimeout(function(){ window.location=""; }, 5000);
            }
        }
    });
});

function changeLeaveStatus()
{
    var formElement =   document.getElementById('change-leave-status');
    var formData    =   new FormData(formElement);
    var formError   =   false;

    $("#changeLeaveStatusModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({

        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            
            if(data.trim()=='UPDATED')
            {
                

                $(".loadingGif").hide();
                if(formData.get('la_Status')=='A'){
                    $("#row"+formData.get('la_id')).find(".status").html("<span class='label label-success'>Approved</span>");
                }
                else if(formData.get('la_Status')=='R'){
                    $("#row"+formData.get('la_id')).find(".status").html("<span class='label label-warning'>Rejected</span>");
                }
                 else if(formData.get('la_Status')=='C'){
                    $("#row"+formData.get('la_id')).find(".status").html("<span class='label label-danger'>Cancelled</span>");
                }

                $.smallBox({
                 title : "Change Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });
            }
        }
    });
}

/*
 * change batch leave application status
 * editted by: justin, 07/08/17
 * fixing onsubmit issue on FF by using malek method
 */ 
$(document).on('submit', '#change-leave-batch-status', function(event) {
    event.preventDefault();

//function changeLeaveBatchStatus()
//{
    //var formElement =   document.getElementById('change-leave-batch-status');
    //var formData    =   new FormData(formElement);
    var formData    =   new FormData(this);
    var formError   =   false;

    $("#changeLeaveStatusModal").modal('hide');
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            alert(data);
        	console.log(data);
            if(data.trim()=='UPDATED')
            {
                if(formData.get('lb_Status')=='A'){
                    $("#row"+formData.get('lb_id')).find(".status").html("<span class='label label-success'>Approved</span>");
                }
                else if(formData.get('lb_Status')=='R'){
                    $("#row"+formData.get('lb_id')).find(".status").html("<span class='label label-warning'>Rejected</span>");
                }
                 else if(formData.get('lb_Status')=='C'){
                    $("#row"+formData.get('lb_id')).find(".status").html("<span class='label label-danger'>Cancelled</span>");
                }

                $.smallBox({
                 title : "Change Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });
            }
            
            setTimeout(function(){ window.location=""; }, 2000);
            $(".loadingGif").hide();
        }
    });
//}
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        document.getElementById('img').style.display = "block";
        $('#img').attr('src', e.target.result);
       }
        reader.readAsDataURL(input.files[0]);
       }
    }

/**
 * Apply Leave / Create Leave Application
 */
$(document).on('submit', '#leave-application-form', function(event) {
    event.preventDefault();
    
    var formData    =   new FormData(this);
    var formError   =   false;

    $(".loadingGif").show();

    $.ajax({

        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            //alert(data);
            
            if($.trim(data)=='INSERTED'){
            $.smallBox({
             title : "Create Status",
             content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
             color : "#659265",
             iconSmall : "fa fa-check fa-2x fadeInRight animated",
             timeout : 4000
            });
        }
            setTimeout(function(){ window.location=""; }, 2000);
            /*$(".loadingGif").hide();*/
            
        }
    });
});

/***************************************
*       Update My Profile
****************************************/
/*
function updateMyProfile()
{
    event.preventDefault();
    var formElement =   document.getElementById('update-my-profile');
    var formData    =   new FormData(formElement);
    var formError   =   false;
    formData.append("is_update",true);
    
    $(".loadingGif").show();

    $.ajax({
        url:'config-process.php',
        type:'post',
        data: formData,
        cache:false,
        contentType:false,
        processData:false,

        success:function(data,success)
        {
            //alert(data);   
            if(data.trim()=='UPDATED')
            {
            	$(".loadingGif").hide();

                $.smallBox({
                 title : "Create Status",
                 content : "<i class='fa fa-clock-o'></i> <i>Record Updated successfully...</i>",
                 color : "#659265",
                 iconSmall : "fa fa-check fa-2x fadeInRight animated",
                 timeout : 4000
                });

                setTimeout(function(){ window.location=""; }, 2000);
            }
        }
    });
}


*/


/*
$('#createDesignationForm').on('click', function () {
    document.getElementById('create-designation-form').reset();
    $("#designationModal").find("#create-designation-form").attr('onsubmit','createDesignation()');
    $("#desig_id").val("");
});
*/

$('#createEmployeeBtn').on('click', function () {
    //document.getElementById('create-employee-form').reset();
	$("#userEmail").val('');
	$("#userPassword").val('');
});

$('#createNoticeFormModal').on('click', function () {
    document.getElementById('createNoticeForm').reset();
    $("#noticeModal").find("#createNoticeForm").attr('onsubmit','createNotice()');
    $("#notice_id").val("");
});