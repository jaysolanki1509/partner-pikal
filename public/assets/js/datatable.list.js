function loadList ( tbl_id, url, order, columns,multiselect) {

	//multiselect = false;
	if(typeof(multiselect) == "undefinded"){
		multiselect = false;
	}

	//check row reorder table
	var reorder = false;
	if ( tbl_id == 'itemTable') {
		reorder = true;
	}
	table = jQuery('#'+tbl_id ).DataTable({	
		"responsive": false,
		"bProcessing": true,
		"bServerSide": true,
		"showFilterRow":true,
		"rowReorder": true,
		"pageLength": 100,
		//"scrollX": true,

		'aoColumnDefs': [{
	           'bSortable': false,
	           'aTargets': 0
		}],
		"order": [[ order, "desc" ]],
		'initComplete': function ()
		{
			  //$('.dataTable').width('100%');
			$('.sidebar-toggle').click(function(){
				$('.dataTable').width('98%');
			});
			
			  var r = $('#'+tbl_id+' tfoot tr');
			  r.find('th').each(function(){
				  $(this).css('padding', 8);
			  });
			  
			  $('#'+tbl_id+' thead').append(r);
			  $('#search_0').css('text-align', 'center');

		  	//add filters 
			$('#'+tbl_id+' thead tr:eq(1) th').each( function () {


				if ( $(this).index() == 0 && multiselect != false ) {
					$(this).html( '<input type="checkbox" id="sel_all">');
					return;		
				}
				
				
				//console.log("here");
				var title = $('#'+tbl_id+' thead th').eq( $(this).index() ).attr('title');
				if ( title == 'Action' || title == 'Image' || title == 'Drag' || title == 'Visibility') {
					return;
				}

				var filt_id = title.split(' ').join('_')+'_filter';
				 if ( title == 'Payment Date' || title == 'Purchase Date' || title == 'Expense Date' || title == 'Invoice Date' || title == 'Last Modified' || title == 'Updated At' || title == 'Registered Date' || title == 'Opened Date' || title == "Date Of Joining" || title == "Last Login" || title == "Loggedin Time" || title == "Date" || title == "LastVisit" ) {

					 $(this).html( '<input id="'+filt_id+'" type="text" class="cr_datepicker" id="datetimepicker" placeholder="Search '+title+'" />' );
			        
			     } else if ( title == 'Location' || title == 'Category' ) {

			    	 $(this).html( '<select id="'+filt_id+'"><option value="">Search '+title+'</option></select>' );
			    	 
			     } else if( title == "Status" ) {

					 if ( tbl_id == 'expenseTable' ) {
						 $(this).html('<select id="'+filt_id+'" ><option value="">All</option><option value="entered">Entered</option><option value="verified">Verified</option><option value="paid">Paid</option><option value="canceled">Canceled</option></select>');
					 } else if ( tbl_id == 'CampaignTable') {
						 $(this).html('<select id="'+filt_id+'" ><option value="">All</option><option value="1">Verified</option><option value="0">Unverified</option></select>');
					 } else {
						 $(this).html('<select id="'+filt_id+'" ><option value="">All</option><option value="liability">Liability</option><option value="paid">Paid</option><option value="invoiced">Invoiced</option></select>');
					 }
    			     
			     } else if( title == "Opened" ) {
			    	 
    			     $(this).html('<select id="'+filt_id+'"><option value="1=1">Search Opened</option><option value="1">Yes</option><option value="0">No</option></select>');
    			     
			     } else if( title == "Registered" ){
			    	 
    			     $(this).html('<select id="'+filt_id+'"><option val="1=1">Search status</option><option val="1">Yes</option><option val="0">No</option></select>');
    			     
			     } else if ( title == 'Type' && tbl_id == 'unsubscribelist_tbl' ) {

			     	$(this).html('<select id="'+filt_id+'"><option value="">Search Type</option><option value="blocked">Blocked</option><option value="unsubscribed">Unsubscribed</option><option value="bounced">Bounced</option></select>');

			     } else if( title == "Type" ) {

					 if ( tbl_id == 'emailreport_tbl' ) {

						 $(this).html('<select id="'+filt_id+'"><option value="1=1">All</option><option value="Email">Email</option><option value="Greeting">Greeting</option></select>');

					 } else if ( tbl_id == 'unsubscribelist_tbl' ) {

						 $(this).html('<select id="'+filt_id+'"><option value="">Search Type</option><option value="blocked">Blocked</option><option value="unsubscribed">Unsubscribed</option><option value="bounced">Bounced</option></select>');

					 } else if ( tbl_id == 'expenseTable' ) {

						 $(this).html('<select id="'+filt_id+'"><option value="">All</option><option value="expense">Expense</option><option value="cash">Cash</option></select>');

					 }

			     } else {

			       	$(this).html( '<input id="'+filt_id+'" type="text" placeholder="Search '+title+'" />' );
			       	
			     }   

			     //select box fileter based on chart 
			     if ( tbl_id == 'emailreport_tbl' ) {
			     	var check_url = url.split('&');
			     	//console.log(check_url);
			     	if (check_url.length > 1){
			     		var flag_email = check_url[1].split('=');
			     		var flag_greeting = check_url[2].split('=');
			     		//alert(flag_email[1]);
			     		if ( flag_email[0] == 'email_flag' && flag_email[1] != ''){
			     			if ( flag_email[1] != 'Opened'){
			     				//alert('here');
			     			$('#Status_filter').val(flag_email[1]).prop('selected', true);
			     			} else if( flag_email[1] == 'Opened'){
			     				$('#Opened_filter').val('1').prop('selected', true);
			     			} 
			     			$('#Type_filter').val('Email').prop('selected', true);
			     		} else if( flag_greeting[0] == 'greeting_flag' && flag_greeting[1] != '' ){
			     			if ( flag_greeting[1] != 'Opened'){
			     			$('#Status_filter').val(flag_greeting[1]).prop('selected', true);
			     			} else if( flag_greeting[1] == 'Opened'){
			     				$('#Opened_filter').val('1').prop('selected', true);
			     			}
			     			$('#Type_filter').val('Greeting').prop('selected', true);
			     		}
			     	} 
			     }

		        //send request when filter table     
				$( 'input,select', this ).on( 'keyup change', function (event) {

					//$(this).val($(this).val().replace(/[^0-9A-Za-z@._-]/g,''));
					if ( event.keyCode == 13 || event.currentTarget.id == 'Purchase_Date_filter' || event.currentTarget.id == 'Type_filter' || event.currentTarget.id == 'Location_filter' || event.currentTarget.className == 'cr_datepicker' || event.currentTarget.id == 'Opened_filter' || event.currentTarget.id == 'Status_filter' || event.currentTarget.id == 'Category_filter' ) {

							if ( this.id == 'sel_all' )return;
				        	
				        	var col = $(this).parent().parent().children().index($(this).parent());

							//if table don't checkbox than
							if ( tbl_id != 'PurchaseTable' || tbl_id != 'customerTable'|| tbl_id != 'itemTable') {
								col++;
							}

							table.column( col ).search( this.value ).draw();
				        	/*if ( url == '/purchase' && event.currentTarget.id == 'Created_Date_filter' && col == 5 ) {
				        		
				        		col++;
					            table.column( col ).search( this.value ).draw();
					            
			        	    } else if ( url == '/stocks') {

								if ( event.currentTarget.id == 'Updated_At_filter' && col == 3 ) {
									col++;
									table.column( col ).search( this.value ).draw();
								} else if ( event.currentTarget.id == 'Location_filter' && col == 2 ) {
									col++;
									table.column( col ).search( this.value ).draw();
								} else {
									table.column( col ).search( this.value ).draw();
								}


							} else if ( url == '/emailtemplates' && title == 'Last Modified' && col == 4 ) {
			        	    	
			        	    	col++;
					            table.column( col ).search( this.value ).draw();
			        	    	
			        	    } else if ( url.match(/emailreport/g) && title == 'Sent' && col == 8 ) {

			        	    	col++;
					            table.column( col ).search( this.value ).draw();
			        	    	
			        	    } else {

				        		 table.column( col ).search( this.value ).draw();
				        		 
			        	    }*/

			        	 			        	    
			        	    var bedge_id = this.id+'_bedge';
			        	    var find_bedge = $('#filter_div').find('#'+bedge_id);

							if ( this.value ) {

								if ( find_bedge ) {
									$('#filter_div #'+bedge_id).remove();
								}  
								
								var bedge = '';
								if ( typeof this[0] != 'undefined' ) {
									if ( this[0].tagName == 'OPTION') {
										bedge = '<a title="Selected Records" id="'+bedge_id+'" class="bg-orange filter-bedge" href="javascript:void(0)" >'+$("#"+this.id +" option:selected").html()+' &nbsp;<i title="Remove selection" onclick=javascript:removefilter("'+bedge_id+'","'+url+'"); class="glyphicon glyphicon-remove "></i></a>';
									}	
								} else {
									bedge = '<a title="Selected Records" id="'+bedge_id+'" class="bg-orange filter-bedge" href="javascript:void(0)" >'+ this.value+' &nbsp;<i title="Remove selection" onclick=javascript:removefilter("'+bedge_id+'","'+url+'"); class="glyphicon glyphicon-remove"></i></a>';				  									
								}									
									
								$('#filter_div').append(bedge);			        	    		

			        	    } else {

			        	    	if ( find_bedge ) {
			        	    		$('#filter_div #'+bedge_id).remove();
			        	    	}

			        	    }
			        	    
					}	

								        	
			    });
		        
		    } );			
			
			var api = this.api();
			var locations = api.context[0].json.locations;
			var categories = api.context[0].json.categories;


			if ( locations ) {

				locations.forEach(function (entry) {
					 $('#Location_filter').append(new Option(entry.name, entry.id));
				 });				 
				 
			}

			if ( categories ) {

				categories.forEach(function (entry) {
					$('#Category_filter').append(new Option(entry.title, entry.id));
				});

			}
			
			var pickerOpts = {
				dateFormat: "yy-mm-dd",
		    };

			//Datepicker on date filter
			$('.cr_datepicker').DatePicker({
				format: "yyyy-mm-dd",
				orientation: "auto",
				autoclose: true,
				todayHighlight: true
			});
		    //$( ".cr_datepicker" ).datepicker(pickerOpts);

		    $('.cr_datepicker').on('change', function(){
		        $('.datepicker').hide();
		    });

			$('#'+tbl_id+' select').select2();
			$('.dataTables_length select').select2();
		    
		    //select all records
			$( '#sel_all' ).click( function ( event ) {  //on click
								
		        if ( this.checked ) { // check select status
		        	
		        	var api_obj = api.context[0];		        	
					var filter_data = api_obj.oAjaxData;
												        		
		        	var total_records = api_obj._iRecordsTotal;
		        	var display_records = api_obj._iDisplayLength;   	
		        	
		        		        	
		        	if ( total_records > display_records ) {
		        				        				        			        	
       		 			$('.content .alert-info').remove();
		        		
		        		var message = '<i class="glyphicon glyphicon-warning-sign" style="border:0px !important;position:initial !important">&nbsp;</i> All records of this page has been selected. For selecting <strong>'+ total_records +'</strong> Records <a id="select_all_link" href="javascript:void(0)"> Click Here</a>'+
		        					  '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="right:0px !important"><span aria-hidden="true">&times;</span></button>';
		        		
		        		$('<div>').addClass('alert alert-info alert-dismissible')
								  .attr('role','alert')								  
								  .append(message)
								  .prependTo( ".content" );
		        		
		        		$('#select_all_link').click( function () {
		        			
		        			var filter_query = api_obj.json.filter_query;
		        			//console.log(api_obj.json);
		        			if ( !filter_query) {		        				
		        				$('#filter_query').val('all');
		        			} else {       
		        				$('#filter_query').val(filter_query);
		        			}

		        			var bedge = '<a title="Selected Records" id="total_record_bedge" class="bg-black filter-bedge" href="javascript:void(0)" >Total <b>'+ total_records +' Contacts</b> has been selected &nbsp;<i title="Remove selection" onclick=javascript:removeSelection(); class="glyphicon glyphicon-remove"></i></a>';
		        			$(bedge).prependTo('#filter_div');
				         	
				         	$('.alert').hide();
		    		    });

		        		if( url == '/datastores' ){
		        			$('.content .alert-info').remove();
		        		}		        		
					}
		        	
		            $( '.checkbox1' ).each ( function() { //loop through each checkbox
		            	
		            	var $This = $( this );            	
		      		  	var id = $This.parent().parent().attr('id');     		  	
			      		var index = $.inArray(id, selected);
			
			      	    if ( index === -1 ) {
			      	        selected.push( id );
			      	    }
		      		  	      		  	
		                this.checked = true;  //select all checkboxes with class "checkbox1"
		                
		            });
		            

		            if ( url == '/contacts') {
		            	$("div.toshow").show();
			            $("#add_contact").hide();
			            $("#export_contact").hide();
			            
		            }
		            if ( url == '/users') {
		            	$("div.toshow").show();
		            }			            
		            
		            
		        } else {
		        	
		        	$('#filter_query').val('');
		        	
		            $('.checkbox1').each( function () { //loop through each checkbox
		            	
		            	var $This = $( this );
		            	var id = $This.parent().parent().attr('id');
		      		  	var index = $.inArray(id, selected);
		      		
			      	    if ( index !== -1 ) {
			      	    	selected.splice( index, 1 );
			      	    }
		      		  	
		                this.checked = false; //deselect checkboxes with class "checkbox1"               
		                
		            });
		            
		            if ( url == '/contacts' ) {
		            	$("div.toshow").hide();
			            $("#add_contact").show();
			            $("#export_contact").show();
			            $('.content .alert-info').remove();
		            }
		            if ( url == '/users') {
		            	$("div.toshow").hide();
		            }
		            
		        }
		        
		    });
		  
		},
		"sAjaxSource": url,
		//"sDom": '<"row view-filter"<"col-sm-6"<"pull-right"l><"pull-left"f><"clearfix">>>t<"row view-pager"<"col-sm-6"<"text-left"i>>><"top">rt<"bottom"fp><"clear">',		
		"dom": '<"col-sm-6"<"top"i>><"col-sm-6"<"top pull-right"l>>rt<"bottom"fip><"clear">',
		"aoColumns": columns,
    	"rowCallback": function( row, data ) {	
    		
    		$('#filter_query').val('');
    		$('#sel_all').prop('checked', false); // Unchecks it
    		
            /*if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
            	$('#'+data.DT_RowId).attr('checked', true);
            }*/
        }
	});


	table.on( 'row-reorder', function ( e, diff, edit ) {


        var sel_row = edit.triggerRow.data();
        var sel_row_id= sel_row.DT_RowId;

        var row_id = [];
		for ( var i=0, ien=diff.length ; i<ien ; i++ ) {

			var rowData = table.row( diff[i].node ).data();
			row_id.push(rowData.DT_RowId);

		}

		if ( row_id.length > 1 ) {

			$.ajax({
				url: '/update-menu-sequence',
				type: "POST",
				data: {ids: row_id,selected_id:sel_row_id},
				success: function (data) {
					if ( data == 'success' ) {
						table.draw();
					}
				}
			});

		}

	} );
	
	
}

//remove filter and redraw list 
function removefilter(ele_id, url) {
	console.log(url);

	var filt_id = ele_id.split('_bedge');

	if ( jQuery("#"+filt_id[0]).prop("tagName") == 'SELECT' ) {

		if ( url.match(/emailreport/g) && ( filt_id[0] == 'Type_filter' || filt_id[0] == 'Opened_filter')) {
			jQuery("#"+filt_id[0]).val("1=1");
		} else {
			$('#'+filt_id[0]).val('');	
		}

	} else {		
		$('#'+filt_id[0]).val('');
	}
	

	var tbl_id = $('#'+filt_id[0]).closest('table').attr('id');
	var oTable = $('#'+tbl_id).DataTable();

	var col = $('#'+filt_id[0]).parent().parent().children().index($('#'+filt_id[0]).parent());
	
	if ( url == '/contacts' && filt_id[0] == 'Purchase_Date_filter' && col == 5 ) {
		
		col++;
        oTable.column( col ).search( $('#'+filt_id[0]).val() ).draw();
        
    } else if ( url == '/emailtemplates' && filt_id[0] == 'Last_Modified_filter' && col == 4 ) {
    	
    	col++;
        oTable.column( col ).search( $('#'+filt_id[0]).val() ).draw();
    	
    } else if ( url.match(/emailreport/g) && filt_id[0] == 'Sent_filter' && col == 8 ) {

    	col++;
        oTable.column( col ).search( $('#'+filt_id[0]).val() ).draw();
    } else {
    	
		 oTable.column( col ).search( $('#'+filt_id[0]).val() ).draw();
		 
    }

	$('#'+ele_id).remove();

}

function removeSelection() {

	$('#filter_query').val('');
	$('#sel_all').attr('checked', false);

	$( '.checkbox1' ).each ( function() { //loop through each checkbox
		            	    			  	      		  	
        this.checked = false;  //unselect all checkboxes with class "checkbox1"
        
    });

    selected = [];
    $('#total_record_bedge').remove();

}
