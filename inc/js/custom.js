/*CUSTOM JS FILE*/


/*=====================================
||
||    FORM VALIDATIONS
||
||=====================================*/



    /*======================
    *   CREATE LOT TYPE FORM
    =======================*/
    function createLotForm()
    {
        $(".lotImg").css({"display" : "none"});
        var formElement =   document.getElementById('create-lot-form');
        var formData    =   new FormData(formElement);
        var formError   =   false;

        $("#create-lot-form input, #create-lot-form select, #create-lot-form textarea").each(function(key, value){
        	if(this.value ==  '' )
            {
                var id =   $(this).attr('id');
                $("."+id).addClass("has-error");
                $("."+id).find('span').removeClass('hide');

               $('html, body').animate({ scrollTop : $(".panel-heading").offset().top}, "slow");

                formError   =   true;
            }
            else
            {
                //alert(this.value);
                var id =   $(this).attr('id');
                $("."+id).removeClass("has-error");
                $("."+id).find('span').addClass('hide');

            }

        });

        if( !checkFileInput() )
        {
            $(".lotImg").show();
            formError   =   true;
        }

        if( formError   ==  false )
        {
            $.ajax({

                url:'process-forms.php',
                type:'post',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                success:function(html)
                {
                    alert(html);
                }

            });
        }
        return false;

    }

    
    /*=========================
     *   CREATE TAX TYPE FORM
     =========================*/
    function createTaxTypeForm()
    {
        var formElement =   document.getElementById('create-tax-type-form');
        var formData    =   new FormData(formElement);
        var formError   =   false;

        /*show processing*/
        $("#form-action").hide();
        $("#processing").show();

        $("#create-tax-type-form input, #create-tax-type-form select").each(function(key, value){



            if(this.value ==  '' )
            {
                var id =   $(this).attr('id');
                $("."+id).addClass("has-error");
                $("."+id).find('span').removeClass('hide');

                $('html, body').animate({ scrollTop : $(".panel-heading").offset().top}, "slow");

                formError   =   true;

                /*show processing*/
                $("#form-action").show();
                $("#processing").hide();
            }
            else
            {
                //alert(this.value);
                var id =   $(this).attr('id');
                $("."+id).removeClass("has-error");
                $("."+id).find('span').addClass('hide');

            }

        });

        if( formError   ==  false )
        {
            $.ajax({

                url:'process-forms.php',
                type:'post',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                success:function(html)
                {
                    if($.trim(html) == 'NO')
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        /*Reset form*/
                        document.getElementById('create-tax-type-form').reset();
                        modalMessages("Success", "Tax Type Created");
                        $("#multiModal").modal();

                        //Empty the previous array and then get new data from DB
                        $(".checkDuplicate").removeClass("hide");
                        getDBData();
                    }
                    else if($.trim(html)    ==  'UPDATE-TX-TYPE-DONE' )
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        /*Reset form*/
                        document.getElementById('create-tax-type-form').reset();
                        $("#txTypeModal").modal('hide');
                        
                        /*Show message modal*/
                        modalMessages("Success", "Tax Type Updated");
                        $("#multiModal").modal();
                        
                        //location.reload();

                        //fetch data - updated -
                        fetchtDataRequest("tx_type_data", "getTaxType");
                        
                    }
                    else
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        /*Reset form*/
                        document.getElementById('create-tax-type-form').reset();
                        modalMessages("Error", "This Tax Type Already Created");
                        $("#multiModal").modal();
                    }
                }

            });
        }
        return false;

    }



    /*=========================
     *   CREATE METER FORM
     =========================*/
    function createMeterForm()
    {

        var formElement =   document.getElementById('create-meter-form');
        var formData    =   new FormData(formElement);
        var formError   =   false;

        /*show processing*/
        $("#form-action").hide();
        $("#processing").show();

        $("#create-meter-form input, #create-meter-form select").each(function(key, value){



            if(this.value ==  '' )
            {
                var id =   $(this).attr('id');
                $("."+id).addClass("has-error");
                $("."+id).find('span').removeClass('hide');

                $('html, body').animate({ scrollTop : $(".panel-heading").offset().top}, "slow");

                formError   =   true;

                /*show processing*/
                $("#form-action").show();
                $("#processing").hide();
            }
            else
            {
                //alert(this.value);
                var id =   $(this).attr('id');
                $("."+id).removeClass("has-error");
                $("."+id).find('span').addClass('hide');

            }

        });

        if(formError   ==  false)
        {
            $.ajax({
                url:'process-forms.php',
                type:'post',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                success:function(html)
                {
                    if($.trim(html) == 'ADD-METER-DONE' )
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        //Reset form
                        document.getElementById('create-meter-form').reset();
                        modalMessages("Success", "Meter Created");
                        $("#multiModal").modal();
                    }
                    else if($.trim(html) == 'METER-EXISTS' )
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        //Reset form
                        document.getElementById('create-meter-form').reset();
                        modalMessages("Error", "Meter Already Exists");
                        $("#multiModal").modal();
                    }
                    else if($.trim(html) == 'UPDATE-METER-TYPE-DONE')
                    {
                        /*show processing*/
                        //$("#form-action").show();
                        //$("#processing").hide();

                        //Reset form
                        //document.getElementById('create-meter-form').reset();
                        //$("#meterTypeModal").modal('hide');
                        document.getElementById('page-content').click();
                        modalMessages("Success", "Meter Updated Successfully");
                        $("#multiModal").modal();
                        location.reload();

                        //fetch data - updated
                        fetchtDataRequest("meter_type_data", "getMeterTypes");
                    }
                }

            });
        }
        return false;

    }
    
    /****************************
     *   FILTER LOT UNIT  **
     ***************************/
    function filterLotUnit()
    {
        var lotType   =   $("#lot_type").val();

        if(lotType=='WATER' ){
            $("#trxType").val('WTM');
        }
        else if( meter   ==  'GAS' ){
            $("#trxType").val('GSM');
        }
        else if( meter   ==  'ELECTRICITY' ){
            $("#trxType").val('ETM');
        }
    }



    /*======================
     *   CHECK INPUT - INT
     =======================*/
    function checkInput(value, inputID)
    {
        if(!$.isNumeric( value ) ){
            $("#"+inputID).val('');
        }
    }



    /*======================
     *   GET LOT NO
     =======================*/
    function getLotNo()
    {
        var lotType     =   $("#lotType").val();
        var lotUnits    =   $("#lotUnits").val();

        lotNo           =   lotType+lotUnits
    }


    /****************************
     * CHECK FILE INPUT        **
     ***************************/
    function checkFileInput()
    {
        var lotImg  =   $("#lotImg").val();

        if( lotImg == '' ){
            return false;
        }

        return true;

    }



    /****************************
     *  MULTI MODAL MESSAGES (created at footer.php)  **
     ***************************/
    function modalMessages(title, body, type)
    {
    	if (type !== undefined){
    		$(".modal").removeClass("modal-success").addClass('modal-'+type);
    	} 
    	
        $(".modal-title").html(title);
        $(".modal-body").html(body);
    }


    /****************************
     *      EDIT TX TYPE     **
     ***************************/
    $(".editTxType").on('click', function(e){
        var id         =   e.target.parentNode.parentNode.parentNode.getAttribute("id");
        var tax_type   =   e.target.parentNode.parentNode.parentNode.childNodes[3].textContent;
        var tax_rate   =   e.target.parentNode.parentNode.parentNode.childNodes[5].textContent;
        
        //pre fill the fields
        $("#lotType").val(tax_type);
        $("#taxRate").val(tax_rate);
        $("#updateTxID").val(id);

        //Modal
        $("#txTypeModal").modal();

    });



    /****************************
     *      DELETE TAX TYPE     **
     ***************************/
    function deleteTxType(id)
    {
        var warning     =       confirm("Do you want to Delete this Tax type?");

        if( warning     ==      true )
        {
            //Run Ajax to delete the tax type
            $.ajax({
                url:    'process-forms.php',
                type:   'post',
                data:   {delete_tax_id :   id},
                success:function(html){
                	if($.trim(html) == 'DELETE-TX-TYPE-DONE'){
                        /*show processing*/

                		/*show modal message*/
                        modalMessages("Success", "Successfully delete tax.");
                        $("#multiModal").modal();
                    }
                	else{
                		/*show processing*/

                		/*show modal message*/
                        modalMessages("Failed", "Failed to delete tax.");
                        $("#multiModal").modal();
                	}
                	
                    //fetch data - updated
                    fetchtDataRequest("tx_type_data", "getTaxType");
                }

            });
        }
        else
        {
        }
    }



    /****************************
     *   RE-SET DATA REQUEST    **
     ***************************/
    function fetchtDataRequest(data_element, function_name)
    {
            //Run Ajax to delete the tax type
            $.ajax({

                url:    'process-forms.php',
                type:   'post',
                data:   {fetch_data_request     :   function_name},
                success:function(html){
                    $("#"+data_element).html(html);
                }

            });
    }



    /****************************
     *   GET TRANSACTION TYPE ON METER  **
     ***************************/
    function getTrxType()
    {
        var meter = $("#meterName").val();
        
        $("#trxType").val(meter);
    }


    
    

    /****************************
     *      EDIT METER TYPE     **
     ***************************/
    $(".editMeterType").on('click', function(e){
        var id              =   e.target.parentNode.parentNode.parentNode.getAttribute("id");
        var meter           =   e.target.parentNode.parentNode.parentNode.childNodes[3].textContent;
        var trx_type        =   e.target.parentNode.parentNode.parentNode.childNodes[5].textContent;
        var tax_type        =   e.target.parentNode.parentNode.parentNode.childNodes[7].textContent;
        var minimum_charges =   e.target.parentNode.parentNode.parentNode.childNodes[9].textContent;

        //pre fill the fields
        $("#meterName").val(meter);
        $("#trxType").val(trx_type);
        $("#taxType").val(tax_type);
        $("#minCharges").val(minimum_charges);
        $("#updateMeter").val(id);

        //Modal
        $("#meterTypeModal").modal();
    });



    /**********************************
     *     VALIDATE THE INPUT       **
     **********************************/
    function validateInputLimit(value, elementID)
    {

        if( value.length > 3 ){
            value   =   value.substring(0, value.length - 1);
            $("#"+elementID).val(value);
        }
    }



    /**********************************
     * CHECK IF ITEM ALREADY CREATED **
     **********************************/
    //Global array
    var DBValuesArray   =   [];
    getDBData();
    function getDBData()
    {
        //Get the values form DB on page load, and store in the global array "DBValuesArray".
        //Used on checking if a certain value is already present or created.

        $.ajax({

            url:'process-forms.php',
            type: 'post',
            data: {checkIfExists : 'checkIfExists'},
            dataType: 'json',
            success:function(html){
                DBValuesArray   =   html;
            }

        });
    }


    /**********************************
     * CHECK IF ITEM ALREADY CREATED **
     **********************************/
    function checkDBValuesArray(value)
    {
        if($.trim( value ) !=   ''){

            //check the array we created and stored values from function above "getDBData()"
            var index   =   jQuery.inArray(value, DBValuesArray);

            if( index   !=  -1 )
            {
                $(".checkDuplicate").html("Tax Type Already Exists");
                $(".checkDuplicate").show();
            }
            else{
                $(".checkDuplicate").html("");
                $(".checkDuplicate").hide();
                //DBValuesArray.push(value);
            }
        }

    }



    /**********************************
     * SELECT METER TO ASSIGN TO LOT **
     **********************************/
    var metersArray = [];
    function selectMeter(id, meterName)
    {
        //check if meter id already slected
    	var meter_index = jQuery.inArray(id, metersArray);
       
        if(meter_index ==  -1){
            $("#meterNames").append("<div id='hide_"+id+"' class='show_meter'><span onclick='removeMeter("+id+")' class='close'>x</span> <span class='meter_name'> "+ meterName +" </span><div class='meter_quantity'><input id='"+meterName+"' onkeyup='checkInput(this.value, \""+meterName+"\")' name='"+meterName+"' type='text' placeholder='Quantity'/></div></div><div class='clearfix'></div>");
            metersArray.push(id);
        }
        else
        {
        	// show modal message that meter has been selected
            modalMessages("Notification", meterName+" meter has been selected.", "primary");
            $("#multiModal").modal();
            
            //var extra_index = jQuery.inArray(id, metersArray);
            //metersArray.splice(extra_index, 1);
        }
    
        var input_meter_ids =   metersArray;
        $(".extra_fields").html("<input type='hidden' name='meter_id' value='"+input_meter_ids+"'>");
    }

    /**
     * DELETE SELECTED METER
     * @param id
     */
    function removeMeter(id)
    {    	
    	// remove the selected meter
    	$("#hide_"+id).remove();
    	
    	//also remove from the array
    	var meter_index = jQuery.inArray(String(id), metersArray);
    	metersArray.splice(meter_index, 1);
    }


    /*=========================
     *   ASSIGN METER FORM
     =========================*/
    function assignMeterForm()
    {
        var formElement =   document.getElementById('assign-meter-form');
        var formData    =   new FormData(formElement);
        var formError   =   false;

        /*show processing*/
        $("#form-action").hide();
        $("#processing").show();

        $("#wrapperAllMetes").css({"border" : "none"});
        $("#meterError").css({'display' : 'none'});

        $("#assign-meter-form input, #assign-meter-form select").each(function(key, value){
            if(this.value == '')
            {
                var id =   $(this).attr('id');
                $("."+id).addClass("has-error");
                $("."+id).find('span').removeClass('hide');
                
                if( id  ==  'ELECTRICITY' ){
                    $("#"+id).css({"border" : "3px solid red"});
                }

                if( id  ==  'WATER' ){
                    $("#"+id).css({"border" : "3px solid red"});
                }

                if( id  ==  'GAS' ){
                    $("#"+id).css({"border" : "3px solid red"});
                }
				
                
                $('html, body').animate({ scrollTop : $(".panel-heading").offset().top}, "slow");

                formError   =   true;

                /*show processing*/
                $("#form-action").show();
                $("#processing").hide();
            }
            else
            {
                //alert(this.value);
                var id =   $(this).attr('id');
                $("."+id).removeClass("has-error");
                $("."+id).find('span').addClass('hide');

                if( id  ==  'ELECTRICITY' ){
                    $("#"+id).css({"border" : "0px"});
                }

                if( id  ==  'WATER' ){
                    $("#"+id).css({"border" : "0px"});
                }

                if( id  ==  'GAS' ){
                    $("#"+id).css({"border" : "0px"});
                }

            }

            if(metersArray.length  ==  0)
            {
                $("#wrapperAllMetes").css({"border" : "1px solid red"});
                $("#meterError").css({'display' : 'block'});
                formError   =   true;

                /*show processing*/
                $("#form-action").show();
                $("#processing").hide();
            }

        });

        if( formError   ==  false )
        {
            $.ajax({

                url:'process-forms.php',
                type:'post',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                success:function(html){
                    if($.trim(html) == 'ASSIGN-METER-DONE' )
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        //Reset form
                        document.getElementById('assign-meter-form').reset();
                        modalMessages("Success", "Meters Assigned to Lot");
                        $("#multiModal").modal();
                    }
                    else if($.trim(html) == 'UPDATE-ASSIGN-METER-DONE' )
                    {
                        /*show processing*/
                        $("#form-action").show();
                        $("#processing").hide();

                        //fetch updated Data
                        fetchtDataRequest('meter_lot_data', 'getAllAssignedMeters');

                        $("#assignmeterTypeModal").modal('hide');
                        //Reset form
                        document.getElementById('assign-meter-form').reset();
                        modalMessages("Success", "Meter Assigned to Lot Successfully");
                        $("#multiModal").modal();


                    }

                }

            });
        }
        return false;

    }

    /****************************
     *      ADD NEW METER    **
     ***************************/
    $("#addNewMeter").on('click', function(e){

        //Modal
        $("#addMeterModal").modal();

    });
    
    
    /****************************
     *      EDIT METER TYPE     **
     ***************************/
    $(".editMeterType").on('click', function(e){
        var id              =   e.target.parentNode.parentNode.parentNode.getAttribute("id");
        var lot_type        =   e.target.parentNode.parentNode.parentNode.childNodes[2].textContent;
        var electricity     =   e.target.parentNode.parentNode.parentNode.childNodes[3].textContent;
        var water           =   e.target.parentNode.parentNode.parentNode.childNodes[4].textContent;
        var gas             =   e.target.parentNode.parentNode.parentNode.childNodes[5].textContent;

        //pre fill the fields
        $("#setLotType").val(lot_type);
        /*$("#trxType").val(trx_type);
        $("#taxType").val(tax_type);
        $("#minCharges").val(minimum_charges);*/
        $("#updateAssignMeter").val(id);

        //Modal
        $("#assignmeterTypeModal").modal();

    });


    /****************************
     *      EDIT LOT TYPE     **
     ***************************/
    $(".editLotType").on('click', function(e){
        var id              =   e.target.parentNode.parentNode.parentNode.getAttribute("id");
        //var meter           =   e.target.parentNode.parentNode.parentNode.childNodes[2].textContent;
        //var trx_type        =   e.target.parentNode.parentNode.parentNode.childNodes[3].textContent;
        //var tax_type        =   e.target.parentNode.parentNode.parentNode.childNodes[4].textContent;
        //var minimum_charges =   e.target.parentNode.parentNode.parentNode.childNodes[5].textContent;

        //pre fill the fields
        //$("#meterName").val(meter);
        //$("#trxType").val(trx_type);
        //$("#taxType").val(tax_type);
        //$("#minCharges").val(minimum_charges);
        //$("#updateMeter").val(id);

        //Modal
        $("#lotTypeModal").modal();

    });

    function Delete(id, table_name, function_name, data_element)
    {
        var warning     =   confirm("Do you want to Delete this item");

        if( warning     ==      true )
        {
            //Run Ajax to delete the tax type
            $.ajax({

                url:    'process-forms.php',
                type:   'post',
                data:   {
                            row_to_delete :   id,
                            table_name    :   table_name,
                            function      :   function_name,
                            fetch_data    :   data_element
                        },
                success:function(html){
                    fetchtDataRequest(data_element, function_name);

                    modalMessages("Success", "Meter Assigned Deleted Successfully");
                    $("#multiModal").modal();
                }

            });
        }
    }


