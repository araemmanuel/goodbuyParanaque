
(function($){
	//var baseURL = 'http://localhost/goodBuy1/';
	var timer = null;
	var timeout = 500;
	sessionStorage.setItem('prod_add_last_user', 'none');		
	sessionStorage.setItem('prod_edit_last_user', 'none');		
	sessionStorage.setItem('var_add_last_user', 'none');		
	sessionStorage.setItem('var_edit_last_user', 'none');		
	// var baseURL = 'http://paranaque.goodbuy-bolinao.com/';
	var baseURL = 'http://localhost/goodbuyParanaque/';
	//START SIG	
	load_category_charts(baseURL);
	chart(baseURL, 'admin/get_monthly_sale_chart', "monthly-sales");
	
	$( ".other-pics" ).each(function(index) {
			$(this).hover(function(){
				$(".main-holder").attr("src",$(this).attr('src'));
		});
	});

	$('#tblproducts').DataTable({  
		"processing":true, 
		"serverSide":true,  
		"order":[],   
		"ajax":{  
					url: baseURL + 'admin/inventory/get_products',  
					type:"POST"  
				},  
		"columnDefs":[  
		{  
			  "targets": [ 0 ],
                "visible": false,
                "searchable": false 
		},
		{  
			"targets": [ 1 ],
			"orderable": false
		}],
		"stateSave": true		
	});
	$('#dt-all-products').DataTable({  
		"processing":true,  
		"serverSide":true,  
		"order":[[ 0, "desc" ]],   
		"ajax":{  
					url: baseURL + 'admin/sales_management/get_all_products',  
					type:"POST"  
				},  
		"columnDefs":[  
		{  
			  "targets": [ 0 ],
                "visible": false,
                "searchable": false 
		},
		{  
			"targets": [ 1 ],
			"orderable": false
		}],
		"stateSave": true		
	});
	var dt_sales = $('#dt-sales').DataTable(
	{
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
		"order": [[ 0, "asc" ]],
		"stateSave": true			
    }
	);
	
	var salesTable = $('#dt-sales').dataTable()
	salesTable.fnPageChange( 'last' ); 
	$('#dt-history').DataTable(
	{
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
		"order": [[ 0, "desc" ]],
		"stateSave": true			
    }
	);
	
	$('#dt-transfer').DataTable({  
		"processing":false,  
		"serverSide":true,  
		"order":[],   
		"ajax":{  
					url: baseURL + 'admin/transfer/get_products',  
					type:"POST"  
				},  
		"columnDefs":[  
		{  
			"targets": [ 0 ],
            "visible": false,
            "searchable": false 
		},
		{  
			"targets": [ 1 ],
			"orderable": false
		}
		],
		"stateSave": true		
	});
	$('#dt-card-holders').DataTable({  
		"processing":false,  
		"serverSide":true,  
		"ajax":{  
					url: baseURL + 'admin/rewards_card/get_card_holders',  
					type:"POST"  
				},  

		"stateSave": true	
	});
	 $('#dt-datalog').DataTable(
	{
		"processing":false,  
		"serverSide":true,  
		"order": [[ 0, "desc" ]],
		"ajax":{  
					url: baseURL + 'admin/get_log_data',  
					type:"POST"  
				}
				,  
		"columnDefs": [{
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }]
	});
	$('#dt-expenses').DataTable(
	{
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
		"order": [[ 1, "desc" ]]
    }
	);
	$('#dt-terminals').DataTable(
	{
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
    }
	);
	

	
	 $('#dt-users').DataTable(
	{
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ],
		"order": [[ 0, "desc" ]],
		"stateSave": true		
    }
	);

	 // $("#example").DataTable().ajax.reload( null, false );
    //  $("#example").DataTable().page('last').draw('page');
	
	$('.dt-rpt').DataTable({  
		"paging": false		
	});
	$('#dt-orders').DataTable({  
			"stateSave": true		
	});
	$('#dt-min-qty').DataTable({  
			"stateSave": true		
	});
	var dt_tally = $('#dt-tally').DataTable({  
		"processing":true, 
		"serverSide":true,  
		"order":[],   
		"ajax":{  
					url: baseURL + 'admin/reports/get_products',  
					type:"POST"  
				},  
		"columnDefs":[  
		{  
			  "targets": [ 0 ],
                "visible": true,
                "searchable": false 
		},
		{  
			"targets": [ 0 ],
			"orderable": false
		}],
		"stateSave": true,	
	});	
	

	/*
	if (sessionStorage.getItem('var_add') == 'true') {
		showNotification('bg-black','Product Variant Reactivated Successfully!', 'top', 'right', null, null); 
		sessionStorage.setItem('var_add','false');
    }*/
	if (sessionStorage.getItem('user_add') == 'true') {
		showNotification('bg-black','User Added Successfully!', 'top', 'right', null, null); 
		sessionStorage.setItem('user_add','false');
    }
	if (sessionStorage.getItem('prod_edit') == 'true') {
		showNotification('bg-black','Product Edited Successfully!', 'top', 'right', null, null); 
		sessionStorage.setItem('prod_edit','false');
    }
	
	$('#test').on('click', function () {
	    sessionStorage.reloadAfterPageLoad = true;
		window.location.reload();
	});
	

	//$("#prod-name").easyAutocomplete(prodCodeOpts3);
	$("#div-prod-name").hide();
	refreshProdNameList(baseURL);
	refreshProdImageHover();
	$(".stock-on-hand").hide();
	$('#prod-name').on('keyup', function () {	
		var address = "admin/inventory/get_prod_names";
		clearTimeout(timer);
		timer = setTimeout(function(){
			generate_prod_name(baseURL, '#prod-name', address);	
		}, timeout);
	});
	$("#div-card").hide();
	refreshCardList(baseURL, 'admin/sales_management/get_membership_id');
	$('#card-no').on('keyup', function () {
		var address = "admin/sales_management/get_card_no";

		if($(this).val())
		{
			$('#ul-card').empty();
			$.ajax({
                type: "POST",
                url: baseURL + address,
				data: $('#dp-form').serialize() + "&ajax_card=" +$(this).val(),
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						$("#div-card").show();
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
							
							if(p)
								{
									if(resultCtr == 1)
										$('#ul-card').empty();												
									//$('#ul-prod-name').empty();	
									resultCtr++;				
									result = p.toString().split(',');
									if (result[2]) 
									{
										$("#ul-card").append('<li class="li-card li-ajax" data-value="'+result[0]+'" data-points="'+ result[1]+'">'+result[2]+'</li>');						
									}
									else
										$("#ul-card").append('<li class="li-card li-ajax" data-value="'+result[0]+'" data-points="'+ result[1]+'">'+result[0]+'</li>');						
								}	
							
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						//alert('Prod Name Keyup Error: ' + textStatus + ' ' +errorThrown);
					}
			});				
		}
		else
			$("#div-card").hide();
		$("#div-card").hide();
	
	});
		
	$("#div-prod-code").hide();		
	$("#div-prod-info").hide();	
	refreshProdCodeList(baseURL, "admin/sales_management/get_sale_prod_info");
	refreshProdCodeList(baseURL, "admin/transfer/get_receive_prod_info");	
	$('#prod-code').on('keyup', function () {		
		var address = "admin/sales_management/get_prod_codes";			
		clearTimeout(timer);
		if ($(this).val) 
		{
				timer = setTimeout(function(){
				generate_prod_codes(baseURL, address, '#prod-code');

			}, timeout);
		}
	});

	$('#receive-prod-code').on('keyup', function () {
		var address = "admin/transfer/get_prod_codes";			
		clearTimeout(timer);
		if ($(this).val) 
		{
				timer = setTimeout(function(){
				generate_prod_codes(baseURL, address, '#receive-prod-code');
			}, timeout);
		}
		
	});
	
		
	//Datetime picker
    //autosize($('textarea.auto-growth'));
	$('.datepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });

	
	//Product Add Form
	if($("#cat_id").val() != '- Please select -' )
		load_dropdown(baseURL, '#subcat_name','admin/inventory/get_subcategories/'+$("#cat_id").val(),  $(this).attr('data-selected-subcat')); 	

	$("#cat_id").on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		$('#subcat_name').empty()
		if($(this).val() == '' || $(this).val() == '- Please select -')
			load_dropdown(baseURL, '#subcat_name','admin/inventory/get_subcategories/-1'); 				
		else
			load_dropdown(baseURL, '#subcat_name','admin/inventory/get_subcategories/'+$(this).val(), $(this).attr('data-selected-subcat')); 				
    });
	$("#cat_id2").on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		$('#subcat_name2').empty()
		if($(this).val() == '' || $(this).val() == '- Please select -')
			load_dropdown(baseURL, '#subcat_name2','admin/inventory/get_subcategories/-1'); 				
		else
			load_dropdown(baseURL, '#subcat_name2','admin/inventory/get_subcategories/'+$(this).val(), $(this).attr('data-selected-subcat')); 				
    });
	$("#modal-edit-prod-cat").on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		$('#modal-edit-prod-subcat').empty()
		if($(this).val() == '' || $(this).val() == '- Please select -')
			load_dropdown(baseURL, '#modal-edit-prod-subcat','admin/inventory/get_subcategories/-1'); 				
		else
			load_dropdown(baseURL, '#modal-edit-prod-subcat','admin/inventory/get_subcategories/'+$(this).val(), $(this).attr('data-selected-subcat')); 				
    });

	$(".primary-img").change(function() {
		if($(".primary-img").val())
		{
			sessionStorage.setItem('prod_add_last_user', 'browser');	
			sessionStorage.setItem('prod_edit_last_user', 'browser');	
			sessionStorage.setItem('var_add_last_user', 'browser');	
			sessionStorage.setItem('var_edit_last_user', 'browser');	
		}
		imagePreview(this, '.upload-preview');
		$("#img-primary").parent().remove();
	});
	$(".other-img").change(function() {
		imagesPreview(this, '.browse-prod-other-pics', true);
	});
	$(".add-other-img").change(function() {
		imagesPreview(this, '.browse-prod-other-pics');
	});
	
	
	$('.btn-capture').hide();
	
	$(".btn-access-cam").on('click', function () {
		//https://stackoverflow.com/questions/41831266/playing-video-only-when-button-is-clicked
		$('.btn-capture').show();
		$('.cam').play();
	});
	$("#dt-var-add tr").on('click',function () {
		$("input[name='prod_qty']").val($(this).find('.qty').text());
		$("input[name='prod_sprice']").val($(this).find('.sprice').text());
		$("input[name='prod_pprice']").val($(this).find('.pprice').text());
	
	});
	//REPORTS
	print_by_filter('#dt-tally', '#btn-print-tally', 1);
	print_by_filter('#dt-dpurchase', '#btn-print-dpurchase', 1);
	print_by_filter('#dt-dprofit', '#btn-print-dprofit', 1);
	print_by_filter('#dt-dtrans', '#btn-print-dtrans');
	print_by_filter('#dt-dsales', '#btn-print-dsales', 1, '#btn-email-dsales');
	print_by_filter('#dt-dinvent', '#btn-print-dinvent', 1);
	print_by_filter('#dt-dexpense', '#btn-print-dexpense', 0);
	print_by_filter('#dt-dtransfer', '#btn-print-dtransfer', 1);
	print_by_filter('#dt-dnonsale', '#btn-print-dnonsale', 1);
	print_by_filter('#dt-spurchase', '#btn-print-spurchase', 1);
	print_by_filter('#dt-ssold', '#btn-print-ssold', 0);
	print_by_filter('#dt-sinvent', '#btn-print-sinvent', 0);
	print_by_filter('#dt-stransfer', '#btn-print-stransfer', 1);
	print_by_filter('#dt-snonsale', '#btn-print-snonsale', 0);
	/*
	$('#btn-email-dsales').on('click', function () {
		$('#btn-email-dsales').attr('href', $('#btn-email-dsales').attr('href') +'null/'  + $('#dsales-email').val());
		location.href = $(this).attr("href");
		//alert($('#btn-email-dsales').attr('href'));
		return false;
	});
	*/
	if($("#rpt-cat_id").val() != 'ALL' )
	{
		load_dropdown(baseURL, '#rpt-subcat_name','admin/inventory/get_subcategories/'+$("#rpt-cat_id").val(),  $('.btn-rpt-print').attr('data-subcat')); 	
		//alert(sessionStorage.getItem('subcat_loaded'));
		if(sessionStorage.getItem('subcat_loaded') == 'true')
		{
			sessionStorage.setItem('subcat_loaded', 'false');	
			load_dt_rpt('#rpt-from');
		}	
	}

	$("#rpt-cat_id").on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		$('#rpt-subcat_name').empty()
		if($(this).val() == '' || $(this).val() == 'ALL')
			load_dropdown(baseURL, '#rpt-subcat_name','admin/inventory/get_subcategories/-1'); 				
		else
			load_dropdown(baseURL, '#rpt-subcat_name','admin/inventory/get_subcategories/'+$(this).val(), $(this).attr('data-selected-subcat')); 				
    });
	$('#rpt-from').change(function() {
		if($('#rpt-to').val())
		{
			load_dt_rpt(this);						
		}
	});
	$('#rpt-to').change(function() {
		if($('#rpt-from').val())
		{
			load_dt_rpt(this);			
		}
	});
	if(!$('#rpt-from').val() || !$('#rpt-to').val())
		$('.btn-rpt-print').attr("disabled", true);
	$('.rpt-cat').change(function() {
		if($('#rpt-to').val() && $('#rpt-from').val())
		{	
			load_dt_rpt(this);			
		}
	});	
	
	$('#rpt-cat_id').change(function() {
		if($('#rpt-to').val() && $('#rpt-from').val())
		{
			load_dt_rpt(this);		
			//console.log($('#rpt-cat_id').val());
			sessionStorage.setItem('subcat_loaded', 'true');				
		}
	});	
	
	
	$('#rpt-subcat_name').change(function() {
		if($('#rpt-to').val() && $('#rpt-from').val())
		{
			load_dt_rpt(this);			
		}
	});
		
	change_report(baseURL);
	$("#rpt-name").on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		change_report(baseURL);
	});
	$('#date-from').change(function() {		
		//if($('#date-to').val())
			change_report(baseURL);
	});
	$('#date-to').change(function() {
		///if($('#date-from').val())
			change_report(baseURL);
	});
	$('#report-search').on('keyup', function () {
		change_report(baseURL);
	});
	$('#dt-orders tbody').on('click', '.open-deliver-date', function () {
		//load_modal_form('#modal-add-qty','#form-add-qty', 'admin/inventory/get_prod_id/'+ $(this).attr('data-prod-id'));
		$('#modal-order-no').attr('value', $(this).attr('data-order-no'));
		$('#modal-deliver-date').modal('show');
		submit_modal_form(baseURL, '#modal-deliver-date','#btn-set-deliver-date', '#form-set-deliver-date', $(this).attr('data-href'));
		return false;
	});	
	//ADMIN DASHBOARD
	$('#dt-min-qty tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
	});
	$('#dt-min-qty tbody').on('click', '.open-add-qty',function () {
		$("input[name='modal-date-delivered']").attr('value', get_date_today());
		$('#modal-prod_id').attr('value', $(this).attr('data-prod-id'));
		$('#modal-sku').attr('value', $(this).attr('data-sku'));
		$('#modal-add-qty').modal('show');
		submit_modal_form(baseURL, '#modal-add-qty','#btn-add-qty', '#form-add-qty', $(this).attr('data-href'));
		return false;
	});
	
	//STOCK HISTORY
	$('#dt-history tbody').on('click', '.open-sh-qty',function () {
		$("input[name='modal-date-delivered']").attr('value', get_date_today());
		$('#modal-sh-id').attr('value', $(this).attr('data-sh-id'));
		$('#modal-sku').attr('value', $(this).attr('data-sku'));
		$('#sh-qty').attr('value', $(this).attr('data-sh-qty'));
		$('#modal-sh-qty').modal('show');
		submit_modal_form(baseURL, '#modal-sh-qty','#btn-sh-qty', '#form-sh-qty', $(this).attr('data-href'));
		
		return false;
	});
	
	//PRODUCTS INVENTORY
	prod_add(baseURL);
	prod_edit(baseURL);
	var_add(baseURL);
	var_edit(baseURL);
	$('#tblproducts tbody').on('click','.btn-view-prod' ,function () {
		load_modal_form(baseURL,'#product-info', '.form-prod-view','admin/inventory/get_product_variant/'+  $(this).attr('data-prod-id') + '/' +$(this).attr('data-sku'));
		return false;
	});
	$('#btn-reset-prod-add').on('click', function () {
		$('#dp-form')[0].reset();
		$(".stock-on-hand").hide();
		$(".input-attrib").attr("disabled", false);
		$("#padd-pid").val("");
		$("#padd-sku").val("");
		load_dropdown(baseURL, '#subcat_name','admin/inventory/get_subcategories/-1'); 				
		$("#cat_id").val('default');
		$("#cat_id").selectpicker("refresh");
		$("#sup_name").val('default');
		$("#sup_name").selectpicker("refresh");
		
		$("#div-prod-name").hide();
		$( ".add-prod-other-pics" ).empty();
		$("#attribs").empty();
		$('#primary_image').attr('src', baseURL + 'assets/images/no-photo.jpg');
	});
	
	$('#tblproducts tbody').on('click', '.open-print-window', function() {
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});
	$('#add-result').on('click', '.open-print-window', function() {
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});	
	$('#tblproducts tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
	});
	$('#tblproducts tbody').on('click', '.open-discount',function () {
		load_modal_form(baseURL,'#modal-discount', '#form-edit-discount','admin/inventory/get_discount/'+ $(this).attr('data-prod-id') + '/' + $(this).attr('data-sku'));
		return false;
	});
	$('#tblproducts tbody').on('click', '.open-add-qty-prod',function () {
		$("input[name='modal-date-delivered']").attr('value', get_date_today());
		$('#modal-prod_id').attr('value', $(this).attr('data-prod-id'));
		$('#modal-sku').attr('value', $(this).attr('data-sku'));
		$('#modal-add-qty').modal('show');
		submit_modal_form(baseURL, '#modal-add-qty','#btn-add-qty', '#form-add-qty', $(this).attr('data-href'));
		return false;
	});
	$('#dt-prod-var tbody').on('click', '.open-add-qty',function () {
		$("input[name='modal-date-delivered']").attr('value', get_date_today());
		$('#modal-prod_id').attr('value', $(this).attr('data-prod-id'));
		$('#modal-sku').attr('value', $(this).attr('data-sku'));
		$('#modal-add-qty').modal('show');
		submit_modal_form(baseURL, '#modal-add-qty','#btn-add-qty', '#form-add-qty', $(this).attr('data-href'));
		return false;
	});
	$("#chk-all-prod").on('click',function () {
		$('.chk-prod').not(this).prop('checked', this.checked);
	});
	
	$('#dt-prod-var tbody').on('click', '.open-discount',function () {
		load_modal_form(baseURL,'#modal-discount', '#form-edit-discount','admin/inventory/get_discount/'+ $(this).attr('data-prod-id') + '/' + $(this).attr('data-sku'));
		return false;
	});
	$("#show-prod").on('click',function () {
		$('#form-prod-online').attr('action', baseURL + 'admin/inventory/selected_online/1');
		$('#form-prod-online').submit();
	});
	$("#hide-prod").on('click',function () {
		$('#form-prod-online').attr('action', baseURL + 'admin/inventory/selected_online/0');
		$('#form-prod-online').submit();
	});	
	$('#dt-ns tbody').on('click', '.open-edit-ns',function () {
		//$("").attr('value', get_date_today());
		$('#modal-id').attr('value', $(this).attr('data-ns-id'));
		$('#modal-sku').attr('value', $(this).attr('data-sku'));
		$('#modal-qty').attr('value', $(this).attr('data-qty'));
		$('#modal-reason').attr('value', $(this).attr('data-reason'));
		$('#modal-sku').focus();
		$('#modal-qty').focus();
		$('#modal-reason').focus();
		$('#modal-edit-ns').modal('show');
		submit_modal_form(baseURL, '#modal-edit-ns','#btn-edit-ns', '#form-edit-ns', $('#form-edit-ns').attr('action'));
		return false;
	});
	$('#dt-ns tbody').on('click', '.open-edit-ns',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
	});
	
	$('#form-selected-tag').on('submit', function() {
        window.open($(this).attr('action'), 'formpopup', 'width=800,height=650,scrollbars=no,resizable=yes');
        this.target = 'formpopup';
    });
	$("#btn-print-batch").on('click',function () {
		$('#form-selected-tag').submit();
		return false;
	});	
	//sessionStorage.setItem('tally-clicked', 'false');	
	$('#form-selected-tally').on('submit', function() {
        if(sessionStorage.getItem('tally-clicked') == 'true')
		{
			window.open($(this).attr('action'), 'formpopup', 'width=800,height=650,scrollbars=no,resizable=yes');
			this.target = 'formpopup';		
		}
		else
			this.target = '_self';		
		
		sessionStorage.setItem('tally-clicked', 'false');
    });
	if(!$('input[name="chk-tally[]"]:checked').length > 0)
	{
		$("#btn-reset").attr("disabled", true);
		$("#btn-print-tally").attr("disabled", true);
	}
	$('#dt-tally tbody').on('change', ".chk-tally", function () {
		if(this.checked) {
			$("#btn-print-tally").attr("disabled", false);
			$("#btn-reset").attr("disabled", false);
		}
		else{
			$("#btn-print-tally").attr("disabled", true);
			$("#btn-reset").attr("disabled", true);
		}
		return false;
	});
	
	if($('#filter-tally').val() == 'ALL')
	{
		dt_tally.search('').draw();   
	}
	
	$('#filter-tally').on('change', function(){
		if(this.value == 'ALL')
		{
			$('#btn-print-tally').attr('data-href', baseURL + 'admin/reports/pdf_tally/');
			dt_tally.search('').draw();   			
			//dt_tally.column(1).search('^'+this.value+'$', true, false).draw();
			//dt_tally.column(1).search(this.value).draw();			
		}
		else	
		{
			$('#btn-print-tally').attr('data-href', baseURL + 'admin/reports/pdf_tally/' +  $(this).val());
			dt_tally.search(this.value).draw();   
			//dt_tally.column(1).search('^'+this.value+'$', true, false).draw();
			//dt_tally.column(1).search(this.value).draw();	
		}
	});
	
	
	$("#btn-print-tally").on('click',function () {
		var atLeastOneIsChecked = $('input[name="chk-tally[]"]:checked').length > 0;
		if(atLeastOneIsChecked)
		{
			var origFormAction = $('#form-selected-tally').attr('action');
			$('#form-selected-tally').attr('action', $(this).attr('data-href'));
			sessionStorage.setItem('tally-clicked', 'true');	
			$('#form-selected-tally').submit();
			$('#form-selected-tally').attr('action', origFormAction);
		}
		else
			$('#btn-print-tally-error').html('Cannot print. No product selected.');
		return false;
	});	
	
	//PRODUCT VARIANTS INVENTORY
	attrib_suggestions(baseURL, '#prod-add-');
	attrib_suggestions(baseURL, '#prod-edit-');
	attrib_suggestions(baseURL, '#var-add-');
	attrib_suggestions(baseURL, '#var-edit-');

	$('#dt-prod-var tbody').on('click','.btn-view-prod' ,function () {
		load_modal_form(baseURL,'#product-info', '.form-prod-view','admin/inventory/get_product_variant/'+  $(this).attr('data-prod-id') + '/' +$(this).attr('data-sku'));
		return false;
	});	
	$('#dt-prod-var tbody').on('click', '.open-print-window', function() {
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});	
	$('#dt-prod-var tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
	});
	$('#dt-prod-var tbody').on('click', '.open-discount',function () {
		load_modal_form(baseURL,'#modal-discount', '#form-edit-discount','admin/inventory/get_discount/'+ $(this).attr('data-prod-id') + '/' + $(this).attr('data-sku'));
		return false;
	});

	$('#open-sup-modal').on('click', function () {
		submit_modal_form(baseURL, '#modal-add-sup','#modal-btn-add-sup', '#form-add-sup', $('#form-add-sup').attr('action'));
		if(baseURL + 'admin/inventory/prod_add_form' == window.location.href )
		{
			datatable_replace_search('#dt-modal-sup','input[name="supplier"]');
		}
		
		return false;
	});

	//PRINT MISSING SMALL TAGS
	print_missing_tags('#tblproducts tbody','.open-prod-sm','#form-print-psm', '#modal-sm-id', '#modal-sm-sku', '#modal-sm-qty', '#modal-sm-tags', '#btn-print-psm', 'small_tags/');
	print_missing_tags('#tblproducts tbody','.open-prod-lg','#form-print-plg', '#modal-lg-id', '#modal-lg-sku', '#modal-lg-qty', '#modal-lg-tags', '#btn-print-plg', 'large_tags/');
	print_missing_tags('#dt-prod-var tbody','.open-prod-sm2','#form-print-psm2', '#modal-sm2-id', '#modal-sm2-sku', '#modal-sm2-qty', '#modal-sm2-tags', '#btn-print-psm2', 'small_tags/');
	print_missing_tags('#dt-prod-var tbody','.open-prod-lg2','#form-print-plg2', '#modal-lg2-id', '#modal-lg2-sku', '#modal-lg2-qty', '#modal-lg2-tags', '#btn-print-plg2', 'large_tags/');
	
	//ALL PRINT
	$('.open-print-window').on('click', function() {
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});	
	$('.open-edit-cat').on('click', function () {
		load_modal_form(baseURL,'#modal-edit-cat', '#form-edit-cat','admin/category/get_category/'+ $(this).attr('data-cat-id'));
		submit_modal_form(baseURL, '#modal-edit-cat','#btn-edit-cat', '#form-edit-cat', $(this).attr('data-href'));
		return false;
	});

	$('.open-edit-subcat').on('click', function () {
		load_dropdown(baseURL, '#modal-categories', 'admin/category/get_category_names',  $(this).attr('data-cat-name'));
		load_modal_form(baseURL,'#modal-edit-subcat', '#form-edit-subcat','admin/category/get_subcategory/'+ $(this).attr('data-subcat-id'));
		submit_modal_form(baseURL, '#modal-edit-subcat','#btn-edit-subcat', '#form-edit-subcat', $(this).attr('data-href'));
		return false;
	});
	
	$('.confirm').on('click', function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
	});

	$('.open-edit-attrib-type').on('click', function () {
		load_modal_form(baseURL, '#modal-edit-attrib-type', '#form-edit-attrib-type','admin/inventory/get_attrib_type_detail/'+ $(this).attr('data-attrib-type-id'));
		submit_modal_form(baseURL, '#modal-edit-attrib-type','#btn-edit-attrib-type', '#form-edit-attrib-type', $(this).attr('data-href'));
		return false;
	});
	$('.open-edit-attrib-value').on('click', function () {
		load_dropdown(baseURL, '#modal-attrib-type', 'admin/inventory/get_attrib_types',  $(this).attr('data-attrib-type'));
		load_modal_form(baseURL,'#modal-edit-attrib-value', '#form-edit-attrib-val','admin/inventory/get_attrib_value_detail/'+ $(this).attr('data-attrib-value-id'));
		submit_modal_form(baseURL, '#modal-edit-attrib-value','#btn-edit-attrib-val', '#form-edit-attrib-val', $(this).attr('data-href'));
		return false;
	});
	
	$('#dt-sup tbody').on('click','.open-edit-sup' ,function () {
		$('#modal-sup-id').attr('value', $(this).attr('data-sup-id'));
		$('#modal-sup-name').attr('value', $(this).attr('data-name'));
		$('#modal-sup-name').attr('value', $(this).attr('data-name'));
		$('#modal-contact').attr('value', $(this).attr('data-contact'));
		$('#modal-address').attr('value', $(this).attr('data-address'));
		$('#modal-sup-name').focus();
		$('#modal-contact').focus();
		$('#modal-address').focus();
		submit_modal_form(baseURL, '#modal-edit-sup','#btn-edit-sup', '#form-edit-sup', $('#form-edit-sup').attr('action'));
		return false;
	});
	$('#dt-modal-sup tbody').on('click','.open-edit-sup' ,function () {
		$('#modal-sup-id').attr('value', $(this).attr('data-sup-id'));
		$('#modal-sup-name').attr('value', $(this).attr('data-name'));
		$('#modal-sup-name').attr('value', $(this).attr('data-name'));
		$('#modal-contact').attr('value', $(this).attr('data-contact'));
		$('#modal-address').attr('value', $(this).attr('data-address'));
		$('#modal-sup-name').focus();
		$('#modal-contact').focus();
		$('#modal-address').focus();
		submit_modal_form(baseURL, '#modal-edit-sup','#btn-edit-sup', '#form-edit-sup', $('#form-edit-sup').attr('action'));
		return false;
	});
	
	
	//baseURL, address, datatable, input
	datatable_search(baseURL, 'admin/inventory/suppliers', '#dt-sup','input[name="supplier"]');
	datatable_search(baseURL, 'admin/category','#dt-cat','input[name="cat_name"]');
	datatable_search(baseURL, 'admin/category','#dt-subcat','input[name="subcat_name"]','select[name="cat_name_forsubcat"]',2);
	datatable_search(baseURL, 'admin/inventory/product_attributes', '#dt-attrib-type','input[name="attrib_type_name"]');
	datatable_search(baseURL, 'admin/inventory/product_attributes', '#dt-attrib-val','input[name="attrib_val_name"]','select[name="attrib_type"]',1);
	datatable_search(baseURL, 'admin/transfer/manage_locations', '#dt-loc','input[name="location"]');
	datatable_search(baseURL, 'admin/order_management/manage_courier','#dt-courier','input[name="cour-name"]');
	datatable_search(baseURL, 'admin/banner', '#dt-banner','input[name="banner-name"]');

	//SALES MANAGEMENT
       /*
	if((window.location.href.indexOf('admin/sales_management') > -1))
		tag_scanner(baseURL, 'player2');

	if((window.location.href.indexOf('admin/sales_management/change_item/') > -1))
		tag_scanner(baseURL, 'player3');
       */
	
	if(!$('#selected-sales-date').val())	
		$('#form-add-sales').hide();
	
	$('#sales-date').change(function() {		
		 $('#form-sales-date').submit(); 
	});
	
	$('#dt-sales tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
		return false;
	});
	$('#dt-sales tbody').on('click', '.open-edit-sale', function () {
		load_modal_form(baseURL,'#modal-edit-sale', '#form-edit-sale','admin/sales_management/get_sale/'+ $(this).attr('data-invoice-no'));
		submit_modal_form(baseURL, '#modal-edit-sale','#btn-edit-sale', '#form-edit-sale', $(this).attr('data-href'));
		return false;
	});
	
	$('#dt-expenses tbody').on('click','.open-edit-expense' ,function () {
		load_modal_form(baseURL,'#modal-edit-expense', '#form-edit-expense','admin/expenses/get_expense/'+ $(this).attr('data-exp-id'));
		submit_modal_form_file(baseURL, '#modal-edit-expense','#btn-edit-expense', 'form-edit-expense', $(this).attr('data-href'));
		return false;
	});
	$('#dt-expenses tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
		return false;
	});
	$('.open-edit-loc').on('click', function () {
		load_modal_form(baseURL,'#modal-edit-loc', '#form-edit-loc','admin/transfer/get_location/'+ $(this).attr('data-loc-id'));
		submit_modal_form(baseURL, '#modal-edit-loc','#btn-edit-loc', '#form-edit-loc', $(this).attr('data-href'));
		return false;
	});
	
	$('#open-vat-reg').on('click', function () {
		load_modal_form(baseURL,'#modal-vat-reg', '#form-vat-reg','admin/get_reg_tin');
		return false;
	});
	
	$('#open-vat-perc').on('click', function () {
		load_modal_form(baseURL,'#modal-vat-perc', '#form-vat-perc','admin/get_reg_tin');
		return false;
	});
	
	
	$('#open-return-policy').on('click', function () {
		load_modal_form(baseURL,'#modal-return-policy', '#form-return-policy','admin/get_return_policy');
		return false;
	});
	$('#open-min-qty').on('click', function () {
		load_modal_form(baseURL, '#modal-min-qty', '#form-min-qty','admin/get_min_qty');
		return false;
	});
	$('#open-shipping-fee').on('click', function () {
		load_modal_form(baseURL, '#modal-shipping-fee', '#form-shipping-fee','admin/get_shipping_fee');
		return false;
	});
	$('#open-edit-prod').on('click', function () {
		load_dropdown(baseURL, '#modal-edit-prod-cat', 'admin/category/get_category_names',  $(this).attr('data-cat-name'));
		load_dropdown(baseURL, '#modal-edit-prod-subcat', 'admin/inventory/get_subcategories/'+ $(this).attr('data-cat-id'),  $(this).attr('data-subcat-name'));
		load_dropdown(baseURL, '#modal-edit-prod-sup', 'admin/inventory/get_supplier_names',  $(this).attr('data-sup-name'));
		load_modal_form(baseURL, '#modal-edit-prod', '#form-edit-prod','admin/inventory/get_prod_info/'+ $(this).attr('data-prod-id'));
		submit_modal_form(baseURL, '#modal-edit-prod','#btn-edit-prod', '#form-edit-prod', $('#form-edit-prod').attr('action'));
		return false;
	});
	
	$('#open-cat-modal').on('click', function () {
		submit_modal_form(baseURL, '#modal-cat','#btn-add-cat', '#form-add-cat', $('#form-add-cat').attr('action'));
		//Added by Jade Carlos
		if(baseURL + 'admin/inventory/prod_add_form' == window.location.href )
		{
			datatable_replace_search('#cat_val_tbl','input[name="cat_name"]');
		}
		return false;
	});
	$('#open-subcat-modal').on('click', function () {
		submit_modal_form(baseURL, '#modal-subcat','#btn-add-subcat', '#form-add-subcat', $('#form-add-subcat').attr('action'));
		if(baseURL + 'admin/inventory/prod_add_form' == window.location.href )
		{
			datatable_replace_search('#subcat_val_tbl','input[name="subcat_name"]','select[name="cat_name_forsubcat"]',2);
		}
		return false;
	});
	$('#open-attrib-type-modal').on('click', function () {
		submit_modal_form(baseURL, '#modal-add-attrib-type','#btn-attrib-type', '#form-attrib-type', $('#form-attrib-type').attr('action'));
		if(baseURL + 'admin/inventory/prod_add_form' == window.location.href )
		{
			datatable_replace_search('#attrib_type_tbl','input[name="attrib_type_name"]');
		}
		return false;
	});
	$('#open-attrib-modal').on('click', function () {
		submit_modal_form(baseURL, '#modal-add-attrib-val','#btn-attrib-val', '#form-attrib-val', $('#form-attrib-val').attr('action'));
		if(baseURL + 'admin/inventory/prod_add_form' == window.location.href )
		{
			datatable_replace_search('#attrib_value_tbl','input[name="attrib_val_name"]','select[name="attrib_type"]',1);
		}
		return false;
	});
	
	$('#btn-discount-all').on('click', function () {
		submit_modal_form(baseURL, '#modal-discount-all','#btn-discount-all', '#form-discount-all', $('#form-discount-all').attr('action'));
		return false;
	});
	
	//ORDER MANAGEMENT
	 $('#form-order-bprint').on('submit', function() {
		var atLeastOneIsChecked = $('input[name="chk-deliver[]"]:checked').length > 0;
		if(atLeastOneIsChecked)
		{
			window.open($(this).attr('action'), 'formpopup', 'width=800,height=650,scrollbars=no,resizable=yes');
			this.target = 'formpopup';
			$('#batch-delivery-receipt').empty();
		}
		else
		{
			$('#batch-delivery-receipt').html('No delivery receipts were selected for batch print.');    
			return false;
		}		
    });
	$('#ns-prod-code').on('keyup', function () {
		var address = "admin/sales_management/get_prod_codes";		
		clearTimeout(timer);
		if ($(this).val) 
		{
				timer = setTimeout(function(){
					generate_prod_codes(baseURL, address, '#ns-prod-code');
				}, timeout);
		}
	});
	$('#dt-courier tbody').on('click', '.open-edit-courier',function () {
		//$("").attr('value', get_date_today());
		$('#modal-cour_id').attr('value', $(this).attr('data-cour_id'));
		$('#modal-name').attr('value', $(this).attr('data-name'));
		$('#modal-shipping_fee').attr('value', $(this).attr('data-fee'));
		$('#modal-name').focus();
		$('#modal-shipping_fee').focus();
		submit_modal_form(baseURL, '#modal-edit-courier','#btn-edit-courier', '#form-courier', $('#form-courier').attr('action'));
		return false;
	});
	
	
	//REWARDS CARD
	 $('#form-app-print').on('submit', function() {
        window.open($(this).attr('action'), 'formpopup', 'width=800,height=650,scrollbars=no,resizable=yes');
        this.target = 'formpopup';
    });
	 
	$('#form-card-batch-print').on('submit', function() {
        window.open($(this).attr('action'), 'formpopup', 'width=800,height=650,scrollbars=no,resizable=yes');
        this.target = 'formpopup';
    });
	
	$('#btn-batch-card').on('click', function() {
		var atLeastOneIsChecked = $('input[name="chk-reward-card[]"]:checked').length > 0;
		if(atLeastOneIsChecked)
		{
			$('#form-card-batch-print').submit();
			$('#batch-reward-card').empty();
		}
		else
			$('#batch-reward-card').html('No reward cards were selected for batch print.');
		return false;
    });
	
	$('#dt-card-holders tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
		return false;
	});
	$('#dt-card-holders tbody').on('click', '.open-print-window',function () {
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});
	
	
	//TRANSFER MANAGEMENT
	$('#dt-transfer tbody').on('click','.btn-view-prod' ,function () {
		load_modal_form(baseURL,'#product-info', '.form-prod-view','admin/inventory/get_product_variant/'+  $(this).attr('data-prod-id') + '/' +$(this).attr('data-sku'));
		return false;
	});
	$('#dt-receive tbody').on('click','.btn-view-prod' ,function () {
		//console.log($(this).attr('data-prod-id'));
		//console.log($(this).attr('data-sku'));
		load_modal_form(baseURL,'#product-info', '.form-prod-view','admin/inventory/get_product_variant/'+  $(this).attr('data-prod-id') + '/' +$(this).attr('data-sku'));
		return false;
	});	
	
	
	$("input[name='items']").removeAttr('checked');
	refreshRemoveItem();
	$("#chk-all-transfer").on('click', function () {
		if($(this).is(":checked"))
		{
			$("#tbody-items").empty();  
			$("input[name='items']").prop('checked', true);
			$.each($("input[name='items']:checked"), function(str) {
				item = $(this).attr('data-prod-code');
				qty =  $(this).attr('data-qty');
				name =  $(this).attr('data-name');
				options =  $(this).attr('data-options');			
				addToTransferBox(item, qty, 'transfer', name, options);
				refreshRemoveItem();
			});
		}
		else
		{
			$("#tbody-items").empty();
			$("input[name='items']").prop('checked', false);
		}
	
	});
	$("#chk-all-receive").on('click', function () {
		if($(this).is(":checked"))
		{
			$("#tbody-items").empty();  
			$("input[name='receive_items']").prop('checked', true);
			$.each($("input[name='receive_items']:checked"), function(str) {
				item = $(this).attr('data-prod-code');
				qty =  $(this).attr('data-qty');
				name =  $(this).attr('data-name');
				options =  $(this).attr('data-options');	
				loc =  $(this).attr('data-location');	
				loc_id =  $(this).attr('data-loc-id');	
				//transfer_id =  $(this).attr('data-transfer-id');			
				addToTransferBox(item, qty, 'receive', name, options, loc, loc_id);
				refreshRemoveItem();
			});
		}
		else
		{
			$("#tbody-items").empty();
			$("input[name='items']").prop('checked', false);
		}
	
	});
	$("#dt-transfer tbody").on('click', "input[name='items']" ,function () {
		$("#tbody-items").empty();   
		$.each($("input[name='items']:checked"), function(str) {
			item = $(this).attr('data-prod-code');
			qty =  $(this).attr('data-qty');
			name =  $(this).attr('data-name');
			options =  $(this).attr('data-options');			
			addToTransferBox(item, qty, 'transfer', name, options);
			refreshRemoveItem();
		});	
	});
	
	$("#btn-transfer-box").on('click',function () {
			$("#tbody-items").empty();		
			var item = $('#prod-code').val();
			var qty = $('#quantity').val();
			var name = $('#chk-'+item).attr('data-name');
			var options = $('#chk-'+item).attr('data-options');
			$('#chk-'+item).prop('checked', true);			
			addToTransferBox(item, qty, 'transfer', name, options);
			refreshRemoveItem();		
			//$('#prod-code').val("");
			//$('#transfer-qty').val("");
				
	
	});
	$('#dt-receive tbody').on('click', "input[name='receive_items']",function () {
		$("#tbody-items").empty();   
		$.each($("input[name='receive_items']:checked"), function(str) {
			item = $(this).attr('data-prod-code');
			qty =  $(this).attr('data-qty');
			name =  $(this).attr('data-name');
			options =  $(this).attr('data-options');	
			loc =  $(this).attr('data-location');	
			loc_id =  $(this).attr('data-loc-id');	
			//transfer_id =  $(this).attr('data-transfer-id');			
			addToTransferBox(item, qty, 'receive', name, options, loc, loc_id);
			refreshRemoveItem();
		});
	});
	
	$('#tag-date-from').change(function() {		
		if($('#tag-date-to').val())
		{
			$('#form-selected-tag').attr('action',baseURL + "admin/inventory/price_tags/"+$('#tag-date-from').val()+ '/'+ $('#tag-date-to').val() + $('#tag-type').val());
			$('#form-price-tag').submit(); 
		}	
		 //$('#form-price-tag').submit(); 
	});
	$('#tag-date-to').change(function() {
	
		if($('#tag-date-from').val())
		{
			$('#form-selected-tag').attr('action',baseURL + "admin/inventory/price_tags/"+$('#tag-date-from').val()+ '/'+ $('#tag-date-to').val() + $('#tag-type').val());
			$('#form-price-tag').submit(); 
		}
	});
	$('#tag-type').change(function() {
	
		if($('#tag-date-from').val() && $('#tag-date-to').val())
		{
			$('#form-selected-tag').attr('action',baseURL + "admin/inventory/price_tags/"+$('#tag-date-from').val()+ '/'+ $('#tag-date-to').val() + '/' + $('#tag-type').val());
			console.log($('#btn-print-batch').attr('data-href'));
		}	
			//$('#form-price-tag').submit(); 
	});
	$('.add-footwear-row').on('click', function () {	
			ctr = 1;
			$('#foot-'+ctr).remove();
			$('#edit-add-cntry').remove();
			var row = '<div class="footwear-row row clearfix">' +
						'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">' +
							'<div class="form-group form-float">'+
							'<small class="form-label">Country</small>'+
								'<div class="form-line success">'+
									'<input type="text" name="cntry[]" id = "purchase_price" class="form-control" min="0.00" step="0.01" required>'+
								'</div>'	+						
							'</div>'+
						'</div>'+ 
						'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">'+ 
							'<div class="form-group form-float">'+ 
							'<small class="form-label">Size</small>'+ 
								'<div class="form-line success">'+ 
									'<input type="number" name="cntry_size[]" id = "purchase_price" class="form-control" min="0.00" step="0.01" required>'+ 
								'</div>'+ 
							'</div>'+ 
						'</div>	'+ 
						'<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3" id = "foot-'+(ctr++)+'">'+ 
							'<div class="align-left" data-toggle="tooltip" data-placement="bottom" title="Add Country Size">'+ 
								'<a class="btn btn-link waves-effect add-footwear-row">'+ 
									'<i class="material-icons">add</i>'+ 
								'</a>'+ 
							'</div>'+ 
						'</div>' + 
						'</div>';
			$('#footwear').append(row);
			refreshAddCountryRow();
		});	
	//	
	$('.remove').on('click', function (event) {
		///console.log('.'+$(this).attr('data-ft'));
		$('.'+$(this).attr('data-ft')).remove();
	});	
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				
			$('#type').val($(e.target).attr('data-id'));
			
			
		});
	
	
	//TERMINAL MANAGEMENT
	$('#dt-terminals tbody').on('click','.open-edit-terminal' ,function () {
		$('#modal-ter-id').attr('value', $(this).attr('data-ter-id'));
		$('#modal-terminal').attr('value', $(this).attr('data-terminal'));
		$('#modal-device').attr('value', $(this).attr('data-device'));
		$('#modal-terminal').focus();
		$('#modal-device').focus();
		submit_modal_form(baseURL, '#modal-edit-terminal','#btn-edit-terminal', '#form-edit-terminal', $(this).attr('data-href'));
		return false;
	});
	$('#dt-expenses tbody').on('click', '.confirm',function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
		return false;
	});
	
	
	//BANNER MANAGEMENT
	
	$('#dt-banner tbody').on('click', '.open-edit-banner',function () {
		$('#modal-ban-id').attr('value', $(this).attr('data-id'));
		$('#modal-banner-name').attr('value', $(this).attr('data-name'));
		$('#modal-banner-name').focus();
		submit_modal_form(baseURL, '#modal-edit-banner','#btn-edit-banner', '#form-banner', $('#form-banner').attr('action'));
		return false;
	});
	$("#show-banner").on('click',function () {
		$('#form-banner-online').attr('action', baseURL + 'admin/banner_selected_online/1');
		$('#form-banner-online').submit();
	});
	$("#hide-banner").on('click',function () {
		$('#form-banner-online').attr('action', baseURL + 'admin/banner_selected_online/0');
		$('#form-banner-online').submit();
	});	
	
	//FORMS	formSelector, submitBtn, address, redirectURL
	submit_form(baseURL, '#form-sup', '#btn-add-sup', $('#form-sup').attr('action'), 'admin/inventory/suppliers');
	submit_form(baseURL, '#form-add-cust', '#btn-add-cust', baseURL+'admin/rewards_card/add_card_holder', 'admin/rewards_card/cust_add_form');
	submit_form(baseURL, '#form-cat', '#btn-cat', $('#form-cat').attr('action'), 'admin/category');
	submit_form(baseURL, '#form-subcat', '#btn-subcat', $('#form-subcat').attr('action'), 'admin/category');
	submit_form(baseURL, '#form-attrib-type', '#btn-attrib-type', $('#form-attrib-type').attr('action'), 'admin/inventory/product_attributes');
	submit_form(baseURL, '#form-attrib-val', '#btn-attrib-val', $('#form-attrib-val').attr('action'), 'admin/inventory/product_attributes');
	submit_form(baseURL, '#form-ns', '#btn-ns', $('#form-ns').attr('action'), 'admin/inventory/non_saleable');
	submit_form(baseURL, '#form-add-sales', '#btn-add-sale', $('#form-add-sales').attr('action'), 'admin/sales_management/display', dt_sales);//'admin/sales_management/display'
	submit_form(baseURL, '#form-courier', '#btn-courier', $('#form-courier').attr('action'), 'admin/order_management/manage_courier');
	submit_form(baseURL, '#form-courier2', '#btn-edit-courier', $('#form-courier2').attr('action'), 'admin/order_management/manage_courier');
	submit_form(baseURL, '#form-loc', '#btn-loc', $('#form-loc').attr('action'), 'admin/transfer/manage_locations');	
	submit_form_file(baseURL, 'form-exp', '#btn-exp', $('#form-exp').attr('action'), 'admin/expenses');	
	submit_form(baseURL, '#form-user', '#btn-add-user', $('#form-user').attr('action'), 'admin/user_management/add_form');	
	submit_form(baseURL, '#form-user2', '#btn-edit-user', $('#form-user2').attr('action'), 'admin/user_management');	
	submit_form(baseURL, '#form-banner', '#btn-banner', $('#form-banner').attr('action'), 'admin/banner');	
	submit_form(baseURL, '#form-terminal', '#btn-terminal', $('#form-terminal').attr('action'), 'admin/terminal');
	submit_form(baseURL, '#form-change-item', '#btn-change-item', $('#form-change-item').attr('action'), 'admin/sales_management/display');//'admin/sales_management/display'
	
	//END FORMS
	//END SIG

/**/
//GETTING IMAGEES FROM SERVER: https://stackoverflow.com/questions/29740089/how-to-create-thumbnail-for-uploaded-images-on-dropzone-js	
//better solution: https://github.com/enyo/dropzone/issues/817

}(jQuery));	
// WORKING FUNCTIONS
//"#dt-tally" , '#btn-print-tally'
//print_missing_tags('#tblproducts tbody','.open-prod-sm','#form-print-psm', '#modal-sm-id', '#modal-sm-sku', '#modal-sm-qty', '#modal-sm-tags', '#btn-print-psm', 'small_tags/');
function print_missing_tags(tbl, tbodyClass, formSelector, id, sku, qty, modal, btnPrintSelector, tagType)
{
	$(tbl).on('click', tbodyClass,function () {
		$(formSelector)[0].reset();								
		$(id).attr('value', $(this).attr('data-id'));
		$(sku).attr('value', $(this).attr('data-sku'));
		$(qty).attr('value', $(this).attr('data-qty'));
		$(modal).modal('show');		
	});	
	$(btnPrintSelector).on('click', function () {
		$(this).attr('data-href',  tagType+$(id).val()+'/'+$(sku).val()+ '/0/'+$(qty).val());
		return false;
	});
}
function print_by_filter(datatable, printBtn, paramCtr = 0, emailBtn = null)
{
	$(datatable).on('search.dt', function() {
		var srchVal = $('.dataTables_filter input').val();
		var origFormAction = $(printBtn).attr('data-href2');
		var str;
		
		if(paramCtr == 1)
			str = origFormAction+'--/0/';
		else if(paramCtr == 2)
			str = origFormAction+'--/--/0/';	
		else
			str = origFormAction+'/0/';	
		$(printBtn).attr('data-href', str.replace('0/', srchVal));
		$(emailBtn).attr('href', str.replace('0/', srchVal));
	});

}
function imagesPreview(input, placeToInsertImagePreview, overwrite = false) {
	if (input.files) {
		if(overwrite)
			$(placeToInsertImagePreview).empty();
				
		var filesAmount = input.files.length;
		for (i = 0; i < filesAmount; i++) {
				var reader = new FileReader();
				reader.onload = function(event) {
					//class="other-pics"
					$(placeToInsertImagePreview).append('<img src="'+event.target.result+'"  style="height:50px !important;width:50px !important;">');
					refreshProdImageHover();
						
					/*
						$(placeToInsertImagePreview).append('<div class="product-pic-small"><img id="img-'+i+'" style="width:60px;height:60px;" src="'
						+event.target.result
						+'" class="other-pics" ><br><button type="button" id="'+i
						+'" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');
						refreshBtnRemoveOtherPic(baseURL);
					*/
				}
				reader.readAsDataURL(input.files[i]);
			}
	}

}
function attrib_suggestions(baseURL, idSelector, caller = false)
{
	$.ajax({
			type: "GET",
			url: baseURL+ 'admin/inventory/get_option_groups',
			dataType : 'json',
			success: function(data){	
			
			$.each(data, function(i, p) {
						$.each(p, function(i, p) {
							
							if(i.toString()=='opt_grp_id')
								autocomplete(idSelector+p.toString(), 'get_options2/'+p.toString())
					});				
				});	
			},
			error: function(req, textStatus, errorThrown) {
				////alert('Get Data From URL Error: ' + textStatus + ' ' +errorThrown);
			}
		});	
}
function autocomplete(inputSelector, address)
{
	/*
	$.ajaxSetup({
			beforeSend: function(xhr, options) {
				options.url = 'http://localhost/goodBuy1/'+'admin/inventory/get_options2';
			}
		});
	*/
	$(inputSelector).autocomplete({
		
			max:3,
			minLength:3,
			serviceUrl: address,
	});	
}
function submit_form_file(baseURL, formSelector, submitBtn, submitURL, redirectURL, datatable = null)
{
	$('.validation-errors').empty();
	$(submitBtn).click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$(submitBtn).attr("disabled", true);	
			$.ajax({
				type: "POST",             
				url: submitURL, 
				data: new FormData(document.getElementById(formSelector)),
				contentType: false,       
				cache: false,             
				processData:false, 
				dataType: 'json',
				success: function(data) {
						$.each(data, function (i,p) {
							$('#'+i+'_error').html(p);
							if(i == 'success') 
							{
								if(redirectURL == null)
								{
									datatable.ajax.reload();
									 $(submitBtn).attr("disabled", false);
								}
								else
									window.location = baseURL+redirectURL;
							}	
						});
					},
					error: function(req, textStatus, errorThrown) {
						//alert('Form Submit Error: ' + textStatus + ' ' +errorThrown);
					},
				complete: function(){
					$(submitBtn).attr("disabled", false);
				}
			});
		});		
}

function submit_form(baseURL, formSelector, submitBtn, submitURL, redirectURL, datatable = null)
{
	$('.validation-errors').empty();
	$(submitBtn).click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$(submitBtn).attr("disabled", true);		
			$.ajax({
					type: "POST",
					url: submitURL,
					data: $(formSelector).serialize(),
					dataType: 'json',
					success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
								if(i == 'success') 
								{
									if(redirectURL == null)
									{
										datatable.ajax.reload();
										 $(submitBtn).attr("disabled", false);
									}
									else
										window.location = baseURL+redirectURL;
								}	
							});
						},
						error: function(req, textStatus, errorThrown) {
							//alert('Form Submit Error: ' + textStatus + ' ' +errorThrown);
						},
					complete: function(){
                        $(submitBtn).attr("disabled", false);
                    }						
				});
		});		
}
function datatable_search(baseURL, address, datatableSelector, inputSelector, selectSelector = null, columnFilterIndex = null)
{
	if(baseURL + address  == window.location.href )
	{
		datatable_replace_search(datatableSelector, inputSelector, selectSelector, columnFilterIndex);
	}
}
function datatable_replace_search(datatable_id, input_text_name, select_input_name = null, column_to_be_filtered = null)
{
	var attrib_table = $(datatable_id).DataTable({
        "pagingType": "full_numbers"
    });
	$('.dataTables_filter').hide();
	$(input_text_name).on('keyup',function(){
		var attrib_type_value_tbl_filter = $(datatable_id+'_wrapper').children('.row').children('.col-sm-6').children(datatable_id+'_filter').children('label').children('.input-sm');
		attrib_type_value_tbl_filter.val($(input_text_name).val());
		attrib_type_value_tbl_filter.trigger('keyup');
	});
	if(select_input_name != null)
		attrib_table.column(column_to_be_filtered).search($(select_input_name).val()).draw();
	
	$(select_input_name).on('change',function(){
		attrib_table.column(column_to_be_filtered).search('^'+this.value+'$', true, false).draw();
	});
}
function imagePreview(input, imgSelector) 
{

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $(imgSelector).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
function get_attrib_types(baseURL, address)
{
	$.ajax({
                type: "POST",
                url: baseURL + address,
				dataType: 'json',
                success: function(data) {
					    var ctr = 0;
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
							if(p)
							{
								ctr++;
																	
									var html = '<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">'+
													'<div class="form-group demo-tagsinput-area form-float">'+
														'<small class="form-label col-grey"><?=$og->opt_grp_name?></small>'+
														'<div class="form-line success">'	+
															'<input type="text" name = "<?=$og->opt_grp_name?>" class="form-control" />'+				
														'</div>'+
														'<div class="validation-errors" id="attrib_error">'+
														'</div>'+'</div>'+'</div>';
								$(".attrib-inputs").append(html);						
							}	
						});
					});
				},
				error: function(req, textStatus, errorThrown) {
					//alert('Prod Name Keyup Error: ' + textStatus + ' ' +errorThrown);
				}
		});
}
function get_date_today()
{
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	return now.getFullYear()+"-"+(month)+"-"+(day) ;
}
function load_dt_rpt(selector)
{
	if($('#rpt-from').val() <= $('#rpt-to').val())
	{
		$('.rpt').submit();
		if($(selector).attr('data-rpt') == 'dpurchase' || $(selector).attr('data-rpt') == 'dprofit' || $(selector).attr('data-rpt') == 'dtransferred')
		{
			$('.btn-rpt-print').attr('data-href', $('.btn-rpt-print').attr('data-loc')
												+$('#rpt-from').val() + '/'+  $('#rpt-to').val() + '/' + $('.rpt-cat').val());	
		}
		else if($(selector).attr('data-rpt') == 'dsales')
		{
			$('.btn-rpt-print').attr('data-href', $('.btn-rpt-print').attr('data-loc')
												+$('#rpt-from').val() + '/'+  $('#rpt-to').val() + '/' + $('#rpt-cat_id').val() + '/' +  $('#rpt-subcat_name').val());		
		}
		else
		{
			$('.btn-rpt-print').attr('data-href', $('.btn-rpt-print').attr('data-loc')
			+$('#rpt-from').val() + '/'+  $('#rpt-to').val());					
		}		
	}
	else
	{
		$('.validation-errors').html('<p>Error: Date From cannot be later than date to.</p>');
		$('.btn-rpt-print').attr("disabled", true);
	}
}
function generate_prod_name(baseURL, inputSelector, address)
{
	if($(inputSelector).val())
				{
					$('#ul-prod-name').empty();
					$.ajax({
						type: "POST",
						url: baseURL + address,
						data: $('#dp-form').serialize() + "&ajax_prod_name=" +$(inputSelector).val(),
						dataType: 'json',
						success: function(data) {
								resultCtr = 1;
								
								$.each(data, function(i, p) {
									$.each(p, function(i, p) {
									$("#div-prod-name").show();
									if(p)
										{
											if(resultCtr == 1)
												$('#ul-prod-name').empty();												
											//$('#ul-prod-name').empty();	
											resultCtr++;				
											stat = p.toString().split('STATUS:');
											dropdownArray = stat[1].split('SKU:');
											if(stat[0] == 0)
												$("#ul-prod-name").append('<li class="li-prod-name li-ajax" style="color:red;" data-value="'+dropdownArray[0]+'">'+dropdownArray[1]+'</li>');						
											else
												$("#ul-prod-name").append('<li class="li-prod-name li-ajax" data-value="'+dropdownArray[0]+'">'+dropdownArray[1]+'</li>');						
										}	
									
									});
								});

							},
							error: function(req, textStatus, errorThrown) {
								//alert('Prod Name Keyup Error: ' + textStatus + ' ' +errorThrown);
							}
					});				
				}
				else
					$("#div-prod-name").hide();
				$("#div-prod-name").hide();
}
function generate_prod_codes(baseURL, address, selector)
{
	$( ".validation-errors" ).empty();
		if($(selector).val())
		{
			$('#ul-prod-code').empty();
			$.ajax({
                type: "POST",
                url: baseURL + address,
				data: $('#dp-form').serialize() + "&ajax_prod_code=" +$(selector).val(),
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
							
							$("#div-prod-code").show();	
							//end
							
							if(p)
								{	
									if(resultCtr == 1)
										$('#ul-prod-name').empty();
									
									resultCtr++;				
									dropdownArray = p.toString().split('DIV');
									
									if(dropdownArray[0] == 0)
										$("#ul-prod-code").append('<li class="li-prod-code li-ajax" style="color:red;" data-value="'+dropdownArray[2]+'">'+dropdownArray[1]+'</li>');						
									else
										$("#ul-prod-code").append('<li class="li-prod-code li-ajax" data-value="'+dropdownArray[2]+'">'+dropdownArray[1]+'</li>');						
								}	
							
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						alert('Generate Prod. Code Error: ' + textStatus + ' ' +errorThrown);
					}
			});				
		}
		$("#div-prod-code").hide();
}
function objLength( object ) {
    return Object.keys(object).length;
}
function refreshRemoveItem() {
    // Remove handler from existing elements
    $('.remove-item').off(); 

    // Re-add event handler for all matching elements
		$(".remove-item").on('click', function () { 
			var selector = '#' + $(this).attr('id');
			$(selector).parent().parent().remove();
			$('#chk-'+$(this).attr('id')).attr('checked',false);
		//return false;
	});
}

function refreshProdImageHover()
{
	$('.other-pics').off();
	$( ".other-pics" ).each(function(index) {
			$(this).hover(function(){
			$(".main-holder").attr("src",$(this).attr('src'));
		});
	});
}
function load_category_charts(baseURL)
{
	$.ajax({
			type: "POST",
			url: baseURL + 'admin/get_categories_for_event_handling',
			dataType : 'json',
			success: function(data){
				$.each(data, function(i, p) {
						$.each(p, function(i, p) {
							cat = p.toString().split('-');
							//console.log(cat[0]);
							chart(baseURL, 'admin/get_cat_chart_data/'+cat[0], 'chart-'+cat[1]);
					});				
				});			
			},
			error: function(req, textStatus, errorThrown) {
				////alert('Chart Error: ' + textStatus + ' ' +errorThrown);
			}
	});
}
function chart(baseURL, address, canvasID)
{
	$.ajax({
			type: "POST",
			url: baseURL + address,
			dataType : 'json',
			success: function(data){
				var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				var salesData = [];//0,0,0,0,0,0,0,0,0,0,0,0
				var label = [];
				$.each(data, function(i, p) {
						$.each(p, function(i, p) {
							//console.log(p.toString());
							sale = p.toString().split('-');
							salesData.push(sale[0]);
							label.push(months[sale[1] - 1]);
					});				
				});	
				if(document.getElementById(canvasID) !== null )
					new Chart(document.getElementById(canvasID).getContext("2d"), getChartJs('line2', label, salesData)); // monthly sales
			},
			error: function(req, textStatus, errorThrown) {
				////alert('Chart Error: ' + textStatus + ' ' +errorThrown);
			}
	});
}
/*
function tag_scanner(baseURL, playerID)
{
	var successBeep = new Audio(baseURL + 'assets/beep.mp3');
	var failedBeep = new Audio(baseURL + 'assets/longBeep.mp3');
	const player = document.getElementById(playerID);
	cam(player);
	var qr = new QCodeDecoder();

	if (!(qr.isCanvasSupported() && qr.hasGetUserMedia())) {
		//alert('Your browser doesn\'t match the required specs.');
		throw new Error('Canvas and getUserMedia are required');
	}
	var video = document.getElementById('player2');			
	resultHandler = function(err, result) {
		if(result)
		{	
			console.log(result);
			
			result = result.split('Name =');
			result = result[0].split(' = ');
			result = result[1].trim();
			var patt = new RegExp(/^[A-Z]{4}([1-9][0-9]{0,2}|9000000)$/);
			var match = patt.test(result);
			//console.log(match && ($("#prod-code").val() != result));
            //console.log(result);
			
			if(($("#prod-code").val() != result))
			{				
		
				address = "admin/sales_management/get_sale_prod_info";	
				$.ajax({
					type: "POST",
					url: baseURL + address,
					data: $('#dp-form').serialize() + "&ajax_sku=" +result,
					dataType: 'json',
					success: function(data) {
						$('.validation-errors').empty();
						//showNotification('bg-teal','Product Scanned!', 'top', 'right', null, null); 
							$.each(data, function(i, p) {
								if(i.toString() == 'error')
								{
									$('#scan-error').html(p.toString());	
									failedBeep.play();
								}
								else
								{
									$.each(p, function(i, p) {			
									if(p)
										{
												$("#div-prod-info").show();	
												$("#prod-code").val(result);
												$("#prod-qty").val(1);
												if(i.toString() == 'sku')
													$("#prod-code").val(p.toString());
												else
													$("#" + i.toString()).val(p.toString());
										}	     
									});
									successBeep.play();
								}							
							});

						},
						error: function(req, textStatus, errorThrown) {
							//alert('Tag Scanner Error: ' + textStatus + ' ' +errorThrown);
						}
					});
			}	
						
		}
	}
	qr.decodeFromCamera(video, resultHandler);	
}
*/
function refreshAddCountryRow() {
    $('.add-footwear-row').off(); 
   ctr = 1;
	$('.add-footwear-row').on('click', function () {	
			$('#foot-'+ctr).remove();
			var row = '<div class="footwear-row row clearfix">' +
						'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">' +
							'<div class="form-group form-float">'+
								'<small class="form-label">Country</small>'+
								'<div class="form-line success">'+
									'<input type="text" name="cntry[]" id = "purchase_price" class="form-control" min="0.00" step="0.01" required>'+
								'</div>'	+						
							'</div>'+
						'</div>'+ 
						'<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">'+ 
							'<div class="form-group form-float">'+ 
							'<small class="form-label">Size</small>'+ 
								'<div class="form-line success">'+ 
									'<input type="number" name="cntry_size[]" id = "purchase_price" class="form-control" min="0.00" step="0.01" required>'+ 
								'</div>'+ 
							'</div>'+ 
						'</div>	'+ 
						'<div class="col-lg-1 col-md-1 col-sm-3 col-xs-3" id = "foot-'+(ctr++)+'">'+ 
							'<div class="align-left" data-toggle="tooltip" data-placement="bottom" title="Add Country Size">'+ 
								'<a class="btn btn-link waves-effect add-footwear-row">'+ 
									'<i class="material-icons">add</i>'+ 
								'</a>'+ 
							'</div>'+ 
						'</div>' + 
						'</div>';
			//$(".footwear-row").clone()
			$('#footwear').append(row);
			refreshAddCountryRow();
		});
}
//,'#btn-add-primary4', '#btn-add-other4', '#btn-prod-edit', 'form-edit-',$('#btn-prod-edit').attr('data-href'));

function prod_edit(baseURL)
{
	const player = document.getElementById('prod-player2');
	const canvas = document.getElementById('canvas');
	var ctr = 1;
	var hasPrimary = hasOther = false;
	if(canvas)
	{
		const context = canvas.getContext('2d');
		const captureButton = document.getElementById('capture');

		$('#btn-add-primary4').on('click', function () {
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			$("#primary_image").attr("src",canvas.toDataURL('image/png'));
			$(".upload-preview").attr("src",canvas.toDataURL('image/png'));
			
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-primary" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="primary" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');				
			refreshBtnRemoveOtherPic(baseURL, 'primary');
			refreshProdImageHover();
			sessionStorage.setItem('prod_edit_last_user', 'cam');			
		});
		$('#btn-add-other4').on('click', function () {		
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			var id = (ctr++);
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-'+id+'" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="'+id+'" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');		
					
			refreshBtnRemoveOtherPic(baseURL);
			refreshProdImageHover();
			hasOther = true;
		});

		$('#btn-prod-edit').on('click', function () {	
			var prodAddForm = document.getElementById('form-edit-prod');
			var fd = new FormData(prodAddForm);
			
			if(sessionStorage.getItem('prod_edit_last_user') == 'cam')
			{
				blob = dataURItoBlob($('#img-primary').attr('src'));
				fd.append("primary_image",blob);
			}
			if(hasOther)
			{	
				var blob;
				for(i=1;i<ctr;i++)
				{
				
					blob = dataURItoBlob($('#img-'+i).attr('src'));
					fd.append("add_other_images[]",blob);
					fd.append("has_other",true);			
				}
			}
			
			$('.validation-errors').empty();
			$('#btn-prod-edit').attr("disabled", true);        
			$.ajax({
					type: "POST",
					url: $(this).attr('data-href'),
					data: fd,
					dataType: 'json',
					processData:false,
					contentType: false,
					success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
							
								if(i.toString() == 'success') 
								{
									window.location = baseURL + 'admin/inventory/products';
								}	
								console.log(i.toString());
							});
						},
						error: function(req, textStatus, errorThrown) {
							//alert('Prod Edit Error: ' + textStatus + ' ' +errorThrown);
						},
						complete: function(){
                        $('#btn-prod-edit').attr("disabled", false);
                    }	
				});		
			return false;
		});
		str = window.location.href;
		var on = false;
		if(str.indexOf("prod_edit_form") >= 0)
		{
			$('.custom-hide').hide();
			$('#open-cam-prod-edit').on('click',function(){
					$('.custom-hide').show();
					cam(player);
			
			});
		}
	}
}	

function var_edit(baseURL)
{
	const player = document.getElementById('var-player2');
	const canvas = document.getElementById('canvas');
	var ctr = 1;
	var hasPrimary = hasOther = false;
	if(canvas)
	{
		const context = canvas.getContext('2d');
		const captureButton = document.getElementById('capture');

		$('#btn-add-primary3').on('click', function () {
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			$("#primary_image").attr("src",canvas.toDataURL('image/png'));
			$(".upload-preview").attr("src",canvas.toDataURL('image/png'));
			
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-primary" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="primary" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');				
			refreshBtnRemoveOtherPic(baseURL, 'primary');
			refreshProdImageHover();
			sessionStorage.setItem('var_edit_last_user', 'cam');			
		});
		$('#btn-add-other3').on('click', function () {		
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			var id = (ctr++);
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-'+id+'" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="'+id+'" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');		
			
			refreshBtnRemoveOtherPic(baseURL);
			refreshProdImageHover();
			hasOther = true;
		});

		$('#btn-var-edit').on('click', function () {	
			var prodAddForm = document.getElementById('form-var-edit');
			var fd = new FormData(prodAddForm);
			if(sessionStorage.getItem('var_edit_last_user') == 'cam')
			{
				blob = dataURItoBlob($('#img-primary').attr('src'));
				fd.append("primary_image",blob);
			}
			if(hasOther)
			{	
				var blob;
				for(i=1;i<ctr;i++)
				{
				
					blob = dataURItoBlob($('#img-'+i).attr('src'));
					fd.append("add_other_images[]",blob);
					fd.append("has_other",true);			
				}
			}
			$('.validation-errors').empty();
			$('#btn-var-edit').attr("disabled", true);
			$.ajax({
					type: "POST",
					url: $(this).attr('data-href'),
					data: fd,
					dataType: 'json',
					processData:false,
					contentType: false,
					success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
							
								if(i.toString() == 'success') 
								{
									window.location = baseURL 
										+ 'admin/inventory/product_variants/'+data.prod_id;
								}	
							});
						},
						error: function(req, textStatus, errorThrown) {
							//alert('Prod Edit Error: ' + textStatus + ' ' +errorThrown);
						},
					complete: function(){
                        $('#btn-var-edit').attr("disabled", false);
                    }	
				});		
			return false;
		});
		str = window.location.href;
		if(str.indexOf("var_edit_form") >= 0)
		{
			$('.custom-hide').hide();
			$('#open-cam-var-edit').on('click',function(){
					$('.custom-hide').show();
					cam(player);
			});
		}
	}
	//return canvas.toDataURL('image/png');//'image/png', 0.5
}	

function var_add(baseURL)
{
	const player = document.getElementById('var-player');
	const canvas = document.getElementById('canvas');
	var ctr = 1;
	var hasPrimary = hasOther = false;
	if(canvas)
	{
		const context = canvas.getContext('2d');
		const captureButton = document.getElementById('capture');

		$('#btn-add-primary2').on('click', function () {
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			$("#primary_image").attr("src",canvas.toDataURL('image/png'));
			$(".upload-preview").attr("src",canvas.toDataURL('image/png'));
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-primary" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="primary" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');				
			refreshBtnRemoveOtherPic(baseURL, 'primary');
			refreshProdImageHover();
			sessionStorage.setItem('var_add_last_user', 'cam');			
		});
		$('#btn-add-other2').on('click', function () {		
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			var id = (ctr++);
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-'+id+'" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="'+id+'" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');		
			
			refreshBtnRemoveOtherPic(baseURL);
			refreshProdImageHover();
			hasOther = true;
		});

		$('#btn-var-add').on('click', function () {		
			var prodAddForm = document.getElementById('var-form');
			var fd = new FormData(prodAddForm);
			
			if(sessionStorage.getItem('var_add_last_user') == 'cam')
			{
				blob = dataURItoBlob($('#img-primary').attr('src'));
				fd.append("primary_image",blob);
			}
			if(hasOther)
			{	
				var blob;
				for(i=1;i<ctr;i++)
				{
				
					blob = dataURItoBlob($('#img-'+i).attr('src'));
					fd.append("add_other_images[]",blob);
					fd.append("has_other",true);			
				}
			}	
			$('.validation-errors').empty();
			$('#btn-var-add').attr("disabled", true);	
			$.ajax({
					type: "POST",
					url: $(this).attr('data-href'),
					data: fd,
					dataType: 'json',
					processData:false,
					contentType: false,
					success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
							
								if(i.toString() == 'success') 
								{
									
									window.location = baseURL + 'admin/inventory/add_result/'
															+data.prod_id+'/'+data.sku
															+'/var_add_form';
									
									//sessionStorage.setItem('prod_add', 'true');	
								}	
							});
						},
						error: function(req, textStatus, errorThrown) {
							//alert('Variant Add Error: ' + textStatus + ' ' +errorThrown);
						},
						complete: function(){
							$('#btn-var-add').attr("disabled", false);
						}
				});		
			return false;
		});
		str = window.location.href;
		var on = false;
		if(str.indexOf("variant_add_form") >= 0)
		{
			$('.custom-hide').hide();
			$('#open-cam-var-add').on('click',function(){	
					$('.custom-hide').show();
						cam(player);
			});
		}
	}
	//return canvas.toDataURL('image/png');//'image/png', 0.5
}	

function prod_add(baseURL)
{
	const player = document.getElementById('player');
	const canvas = document.getElementById('canvas');
	var ctr = 1;
	var hasPrimary = hasOther = false;
	if(canvas)
	{
		const context = canvas.getContext('2d');
		const captureButton = document.getElementById('capture');

		//var prodAddImage = open_cam();	
		$('#btn-add-primary').on('click', function () {
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			$("#primary_image").attr("src",canvas.toDataURL('image/png'));
			$(".upload-preview").attr("src",canvas.toDataURL('image/png'));
			
			if($("#img-primary").length)
				$("#img-primary").parent().remove();
			
			$(".add-prod-other-pics").append( '<div class="product-pic-small"><img id="img-primary" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="primary" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');				
			refreshBtnRemoveOtherPic(baseURL, 'primary');
			refreshProdImageHover();
			sessionStorage.setItem('prod_add_last_user', 'cam');			
		});
		
		$('#btn-add-other').on('click', function () {		
			context.drawImage(player, 0, 0, canvas.width, canvas.height);
			var id = (ctr++);
			$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img id="img-'+id+'" style="width:60px;height:60px;" src="'
													+canvas.toDataURL('image/jpeg')
													+'" class="other-pics" ><br><button type="button" id="'+id+'" class = "btn-remove-other-pic link"><span style="font-size:10px;text-decoration:none!important;color:#888;">REMOVE</span></button></div>');		
			
			refreshBtnRemoveOtherPic(baseURL);
			refreshProdImageHover();
			hasOther = true;
		});
			
		$('#btn-prod-add').on('click', function () {	
			address = 'admin/inventory/prod_add';
			
			var prodAddForm = document.getElementById('dp-form');
			var fd = new FormData(prodAddForm);
			if(sessionStorage.getItem('prod_add_last_user') == 'cam')
			{
				blob = dataURItoBlob($('#img-primary').attr('src'));
				fd.append("primary_image",blob);
			}
			if(hasOther)
			{	
				var blob;
				for(i=1;i<ctr;i++)
				{
				
					blob = dataURItoBlob($('#img-'+i).attr('src'));
					fd.append("add_other_images[]",blob);
					fd.append("has_other",true);			
				}
			}
			
			$('.validation-errors').empty();
			$('#btn-prod-add').attr("disabled", true);		
			$.ajax({
					type: "POST",
					url: baseURL + address,
					data: fd,
					dataType: 'json',
					processData:false,
					contentType: false,
					success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
								if(i == 'success') 
								{
							
									window.location = baseURL + 'admin/inventory/add_result/'
															+data.prod_id+'/'+data.sku
															+'/prod_add_form';
									sessionStorage.setItem('prod_add', 'true');	
								}
								if(i == 'success_reactivated') 
								{
								
									window.location = baseURL + 'admin/inventory/add_result/'
															+data.prod_id+'/'+data.sku
															+'/prod_add_form';
								
								}	
								
							});
						},
						error: function(req, textStatus, errorThrown) {
							//alert('Prod Add Error: ' + textStatus + ' ' +errorThrown);
						},
					complete: function(){
                        $('#btn-prod-add').attr("disabled", false);
                    }						
				});		
			return false;
		});

		if(baseURL + 'admin/inventory/prod_add_form' == window.location.href )
		{
			//btn btn-xs btn-block bg-green waves-effect
			if($('#open-cam-prod-add').attr('data-status') == 'on')
				$('#open-cam-prod-add').css('color', 'red'); 
			else
				$('#open-cam-prod-add').css('color', 'green');
				
			$('.custom-hide').hide();
			$('#open-cam-prod-add').on('click',function(){
				$('.custom-hide').show();
				cam(player);
			});
		}	
	}
	//return canvas.toDataURL('image/png');//'image/png', 0.5
}	
function cam(player)
{
	if($('#is-mobile').val())
	{
		const constraints = {
				video: true, video: { facingMode: { exact: "environment" } },	
		};

	navigator.mediaDevices.getUserMedia(constraints) 
			.then((stream) => {
								player.srcObject = stream;
		});	
	}	
	else
	{
		const constraints = {
				video: true,
		};
	navigator.mediaDevices.getUserMedia(constraints) 
			.then((stream) => {
								player.srcObject = stream;
		});	
	}
}

function srcToFile(src, fileName, mimeType){
    return (fetch(src)
        .then(function(res){return res.arrayBuffer();})
        .then(function(buf){return new File([buf], fileName, {type:mimeType});})
    );
}
function refreshBtnRemoveOtherPic(baseURL, type = null) {
    $('.btn-remove-other-pic').off(); 

	$(".btn-remove-other-pic").on('click', function () { 
		var selector = '#' + $(this).attr('id');
		$(selector).parent().remove();
		if(type == 'primary')
			$("#primary_image").attr("src",baseURL + 'assets/images/no-photo.jpg');
	});
	
}

function dataURItoBlob(data) {
    var binary = atob(data.split(',')[1]);
    var array = [];
    for(var i = 0; i < binary.length; i++) {
        array.push(binary.charCodeAt(i));
    }
    return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
}
function refreshCardList(baseURL, address) {
    $('#ul-card li').off(); 

	$("#ul-card").on("click", '.li-card' , function(){ 
		card = $(this).attr('data-value');
		if(card.length == 11)
		{
				$.ajax({
					type: "POST",
					url: baseURL + address,
					data: $('#dp-form').serialize() + "&ajax_card=" +card,
					dataType: 'json',
					success: function(data) {
							$("#div-card").show();		
							$.each(data, function(i, p) {
								$.each(p, function(i, p) {			
								if(p)
									{	
										result = p.toString().split(',');
										//console.log(result[0]);
										$('#card-no').val(result[0]);
										$('#db-reward-pts').val(result[1]);
										$('#db-reward-pts').focus();
										$("#div-card").hide();
									}	     
								});
							});

						},
						error: function(req, textStatus, errorThrown) {
							//alert('Refresh Card Error: ' + textStatus + ' ' +errorThrown);
						}
				});							
		}
		else
		{
			$('#card-no').val( $(this).attr('data-value'));
			$('#db-reward-pts').html('<b>STORED PTS: </b>' +$(this).attr('data-points'));
		}
		$("#div-card").hide();
	});
}
function refreshProdCodeList(baseURL, address) {
    $('#ul-prod-code li').off(); 

	$("#ul-prod-code").on("click", '.li-prod-code' , function(){ 
		sku = $(this).attr('data-value');
		$("#prod-code").val($(this).attr('data-value'));
			$.ajax({
                type: "POST",
                url: baseURL + address,
				data: $('#dp-form').serialize() + "&ajax_sku=" +sku,
				dataType: 'json',
                success: function(data) {
						$("#div-prod-info").show();	
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {			
							if(p)
							{	
									if(i.toString() == 'sku')
									{
										$("#prod-code").val(p.toString());
										$("#ns-prod-code").val(p.toString());
									}
									else if(i.toString() == 'total_qty')
										$("#transfer-qty").val(p.toString());	
									else
										$("#" + i.toString()).val(p.toString());	
								}	     
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						//alert('Refresh Prod. Code Error: ' + textStatus + ' ' +errorThrown);
					}
			});				

		$("#div-prod-code").hide();
	});
}
function refreshProdNameList(baseURL) {
    // Remove handler from existing elements
    $('#ul-prod-name li').off(); 
	
	$("#ul-prod-name").on("click", '.li-prod-name' , function(){ 
		sku = $(this).attr('data-value');
		address = "admin/inventory/get_prod";
		$( ".add-prod-other-pics" ).html('');
			$.ajax({
                type: "POST",
                url: baseURL + address,
				data: $('#dp-form').serialize() + "&ajax_sku=" +sku,
				dataType: 'json',
                success: function(data) {
						var primaryPath = null
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
								
							if(p)
								{	
									if(i.toString() == 'primary_image')
									{
										if(p.toString() == 'None')
										{
											imgPath = baseURL + 'assets/images/no-photo.jpg';
											$('#primary_image').attr('src', imgPath);
										
										}
										else	
										{
											imgPath = baseURL + p.toString();
											$('#primary_image').attr('src', imgPath);
											primaryPath = imgPath;
										}
											
										
									}
									else if(i.toString() == 'options')
									{
										$( "#attribs" ).html('<b>Attributes: </b>' + p.toString());	
									}
									else if(i.toString() == 'other_images')
									{
										imgPath = p.toString();
										var pathArray = imgPath.split(',');
										var i;
										for (i = 0; i < pathArray.length; i++) { 
											ultimatePath = baseURL + '/'+ pathArray[i];	
											$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img src="'+ultimatePath+'" class="other-pics" ></div>');
										}
										//if(primaryPath)
										//$( ".add-prod-other-pics" ).append( '<div class="product-pic-small"><img src="'+primaryPath+'" class="other-pics" ></div>');
									
									}
									else if(i.toString() == 'prod_id' || i.toString() == 'sku') 
									{
										$("input[name = '"+i.toString()+"']").val(p.toString());
									}
									else if( i.toString() == 'cat_id' || i.toString() == 'subcat_name')
									{
										$("#" + i.toString()).val(p.toString());
										load_dropdown(baseURL, '#subcat_name','admin/inventory/get_subcategories/'+p.toString()); 		
										$("#" + i.toString()).attr("disabled", true);
										$('.selectpicker').selectpicker('refresh');
									}
									else if( i.toString() == 'sup_name')
									{
										$("#" + i.toString()).val(p.toString());
										load_dropdown(baseURL, '#sup_name','admin/inventory/get_supplier_names', p.toString()); 		
										$("#" + i.toString()).attr("disabled", true);
										$('.selectpicker').selectpicker('refresh');
									}
									else if( i.toString() == 'date_added')
									{
										$("#" + i.toString()).val(p.toString());
									}
									else if (i.toString() == 'quantity')
									{
										$("#quantity").val(p.toString());
										$(".stock-on-hand").show();
										$(".stock-on-hand").val(p.toString());
										$(".stock-on-hand").focus();	
									}
									
									else
									{
										if(i.toString() == 'name')
										{
											$("#prod-name").val(p.toString());
										}
										$("#" + i.toString()).val(p.toString());
										//$("#" + i.toString()).prop("readonly", true);
										$("#" + i.toString()).focus();			
											
									}		
									var now = new Date();
									var day = ("0" + now.getDate()).slice(-2);
									var month = ("0" + (now.getMonth() + 1)).slice(-2);
									var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
									$("#date_added").val(today);	
								}	     
							});
						});
						$(".input-attrib").attr("disabled", true);
						//$(").prop("readonly", true);
							
						$("#prod-qty").focus();
						
					},
					error: function(req, textStatus, errorThrown) {
						//alert('Refresh Prod. Name Error: ' + textStatus + ' ' +errorThrown);
					}
			});				

		$("#div-prod-name").hide();
	});
}

function ajax_suggestions(obj)
{
	refreshProdNameList(obj.baseURL);
		if($(obj.inputSelector).val())
		{
			$(obj.ulSelector).empty();
			$.ajax({
                type: "POST",
                url: obj.baseURL + obj.address,
				data: $(obj.formSelector).serialize() + obj.additionalFormData,
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
							
							$(obj.divSelector).show();
							if(p)
								{	
									//$('#ul-prod-name').empty();	
									if(resultCtr == 1)
										$('#ul-prod-name').empty();
									if(obj.type == 'prod-names')
									{
										resultCtr++;					
										dropdownArray = p.toString().split('SKU:');
										
										$(obj.ulSelector).append('<li class="li-prod-name li-ajax" data-value="'+dropdownArray[0]+'">'+dropdownArray[1]+'</li>');								
									}
								}	
							
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						////alert('AJAX Suggestions Error: ' + textStatus + ' ' +errorThrown);
					}
			});				
		}
		$(obj.divSelector).hide();
}
function addToTransferBox(item, qty, type, name = null, options = null, loc=null, loc_id = null)
{
	if(type == 'transfer')
	{
		$("#tbl-confirm-items").find('tbody')
				.append($('<tr>')
					.append($('<td>')
						.append($('<p>'+item+'</p>')
						)
							.append($('<input style="border: none;border-color: transparent;" readonly>')
							.attr('type', 'hidden')	
							.attr('value', item)
							.attr('name', 'sku[]')
						)
					)
					.append($('<td>')
						.append($('<p>'+name+'</p>')
						)
					)
					.append($('<td>')
						.append($('<p>'+options+'</p>')
						)
					)
					.append($('<td>')
							.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'number')	
							.attr('value', qty)
							.attr('name', 'qty[]')
						)
					)
					.append($('<td>')
						.append($('<button>')
							.attr('class', 'btn btn-link waves-effect remove-item')
							.attr('type', 'button')
							.attr('id', item)
							.text('Remove')
						)
					)
	
				);
	}
	else if(type == 'receive')
	{
		$("#tbl-confirm-items-for-receive").find('tbody')
				.append($('<tr>')
					.append($('<td>')
						.append($('<p>'+item+'</p>')
						)
							.append($('<input style="border: none;border-color: transparent;" readonly>')
							.attr('type', 'hidden')	
							.attr('value', item)
							.attr('name', 'sku[]')
						)
					)
					.append($('<td>')
						.append($('<p>'+name+'</p>')
						)
					)
					.append($('<td>')
						.append($('<p>'+options+'</p>')
						)
					)
					.append($('<td>')
							.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'number')	
							.attr('value', qty)
							.attr('name', 'qty[]')
						)	
					)
					.append($('<td>')
						.append($('<p>'+loc+'</p>')
						)
							.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'hidden')	
							.attr('value', loc_id)
							.attr('name', 'loc[]')
						)						
					)					
					.append($('<td>')
						.append($('<button>')
							.attr('class', 'btn btn-link waves-effect remove-item')
							.attr('type', 'button')
							.attr('id', item)
							.text('Remove')
						)
					)
	
				);			
	}
}
function suggestions(baseURL, selector, address)
{
	//var baseUrl = window.location.protocol + '/' +window.location.host +window.location.pathname;
	//var URLarray = baseUrl.split('/');

	var countries = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  prefetch: {
		url: baseURL + 'admin/inventory/get_options', // URLarray[0]+'//'+URLarray[1]+'/'+URLarray[2] +'/'+ address 'http://localhost/goodBuy1/assets/countries.json'
		filter: function(list) {
		  return $.map(list, function(name) {
			return { name: name }; });
		}
	  }
	});
	countries.initialize();
	var elt = $(selector);
	elt.tagsinput({
	  typeaheadjs: {
		name: 'countries',
		displayKey: 'name',
		valueKey: 'name',
		source: countries.ttAdapter()
	  }
	});		
	//elt.tagsinput('add', "sigrid");
	countries.clearPrefetchCache();
	countries.initialize(true);
	elt.tagsinput('refresh');

	
}	
function load_dropdown(baseURL, dropdownSelector, address, selectedOption = null)
{	
	//var baseUrl = window.location.protocol + '/' +window.location.host +window.location.pathname;
	//var URLarray = baseUrl.split('/');
	
	$.ajax({
			type: "GET",
			url: baseURL + address,
			dataType : 'json',
			success: function(data){	
				$(dropdownSelector).empty();
					if(dropdownSelector == '#rpt-subcat_name')
							$(dropdownSelector).append($('<option></option>').val(0).html('ALL'));	
					
				$.each(data, function(i, p) {
					$.each(p, function(i, p) {
						
						$(dropdownSelector).append($('<option></option>').val(p.toString()).html(p.toString()));	
						if(selectedOption != null)
							$(dropdownSelector + " option[value='"+ selectedOption + "']").prop('selected', true);
						else
							$(dropdownSelector +" option").prop("selected", false);
					});
				});				
				$('.selectpicker').selectpicker('refresh');
			},
			error: function(req, textStatus, errorThrown) {
					////alert('Load Dropdown Error: ' + textStatus + ' ' +errorThrown);
			}
		});	
	//$(dropdownSelector).selectpicker('render');
	//$(dropdownSelector).selectpicker('refresh'); //http://jsfiddle.net/deepakmanwal/Mgpxr/2/
}
function load_modal_form(baseURL, modal, formSelector, address)
{	
	//var baseUrl = window.location.protocol + '/' +window.location.host +window.location.pathname;
	//var URLarray = baseUrl.split('/');
	$(formSelector)[0].reset();
	$.ajax({
                type: "POST",
                url: baseURL + address,
				data: $(formSelector).serialize(),
				dataType: 'json',
                success: function(data) {
						//let hasOtherImages = false;
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
								if(p)
								{	
									//console.log(i.toString() + " = " + p.toString());
									if(i.toString() == 'prod_id')
										$( ".other-pics" ).html('');
									if(i.toString() == 'primary_image')
									{
										
										if(p.toString() != 'None')
										{

											imgPath = baseURL +p.toString();
											primary = imgPath;
											$( ".other-pics" ).append( '<div class="product-pic-small"><img src="'+imgPath+'" name = "primary_image" class="other-pics" ></div>');
											refreshProdImageHover();	
											$('[name="'+i+'"]').attr("src", imgPath);
										}
										else
											$('[name="'+i+'"]').attr("src", baseURL + 'assets/images/no-photo.jpg');	
									}
									else if(i.toString() == 'other_images')
									{
									
										imgPath = p.toString();
										var pathArray = imgPath.split(',');
										pathArray.forEach(function(item, index, array) {
											ultimatePath = baseURL + '/'+ item;
											$( ".other-pics" ).append( '<div class="product-pic-small"><img src="'+ultimatePath+'" class="other-pics" ></div>');
											refreshProdImageHover();		
										});
									}
									else
									{
									
										$('[name="modal-'+i+'"]').val(p.toString());
									}
								}
							     
							});
						});
						$(modal).modal('show');
						//$(modal).modal('open');
					},
					error: function(req, textStatus, errorThrown) {
						////alert('Load Modal Error: ' + textStatus + ' ' +errorThrown);
					}
			});	  
}

function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
    if (colorName === null || colorName === '') { colorName = 'bg-black'; }
    if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
    if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
    if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
    var allowDismiss = true;

    $.notify({
        message: text
    },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            timer: 1000,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
}
function showConfirmMessage(t, m, url) {
    swal({
        title: t,
        text: m,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        closeOnConfirm: false
    }, function (isConfirm) {
		  if (isConfirm) {
			  window.location.replace(url);
		  }
        //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
}
function change_batch_price_tags(baseURL)
{
	search = '';
	if (document.getElementById('report-search') != null) 
	{
		search = document.getElementById('report-search').value;
	}
	//let baseUrl = window.location.protocol + '/' +window.location.host +window.location.pathname;
	//let URLarray = baseUrl.split('/');
	var address= "admin/reports/display/" + $("#rpt-name" ).val() + '/' + $("#date-from" ).val() + '/'+$("#date-to" ).val() + '/' + search;
	var url = baseURL + address;
	$("#rpt-holder").attr("src",url);
}
function change_price_tag_pdf(baseURL)
{
	/*
	search = '';
	if (document.getElementById('report-search') != null) 
	{
		search = document.getElementById('report-search').value;
	}
	*/
	var address= "admin/inventory/price_tags/" + $("#tag-size" ).val() + '/' + $("#tag-date-from" ).val() + '/'+$("#tag-date-to" ).val();
	var url = baseURL + address;
	$("#price-tag-holder").attr("src",url);
}	
function change_report(baseURL)
{
	var detailed_purchase = ['Date', 'Product Code', 'Product Name', 'Quantity', 'Purchase Price', 'Selling Price', 'Total Purchase Price', 'Total Selling Price'];

	
	$("#dt-report > thead").html("");
	detailed_purchase.forEach(function(item, index, array) {
		$('#dt-report > thead tr').append('<th> '+item+'</th>');

	});
	/*
	search = '';
	if (document.getElementById('report-search') != null) 
	{
		search = document.getElementById('report-search').value;
	}
	//let baseUrl = window.location.protocol + '/' +window.location.host +window.location.pathname;
	//let URLarray = baseUrl.split('/');
	let address= "admin/reports/display/" + $("#rpt-name" ).val() + '/' + $("#date-from" ).val() + '/'+$("#date-to" ).val() + '/' + search;
	let url = baseURL + address;
	$("#rpt-holder").attr("src",url);
	*/
}	
function submit_modal_form(baseURL, modal, submitBtn, formSelector, submitURL, func = null)
{	
	$(modal).modal('show');
	//console.log($(formSelector));
	$(formSelector)[0].reset();//[0]
	
	$('.validation-errors').empty();
	$(submitBtn).click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$(submitBtn).attr("disabled", true);
		$.ajax({
                    type: "POST",
                    url: submitURL,
				    data: $(formSelector).serialize(),
					dataType: 'json',
                    success: function(data) {
							if(data.error)
							{
								$.each(data, function (i,p) {
									$('#'+i+'_error').html(p);
								});
								
							}
					
							if(data.add_qty_success)
							{
								//window.location.reload();
								window.location.replace( data.base_url + 'admin/inventory/add_result/'
															+data.prod_id+'/'+data.sku
															+'/products/'+data.qty);
							}
							else if(data.cat_success)
							{
								//$('#modal_cat_name_error').html('<p style="color:green;">Added Successfully!</p>');
								window.location.reload();
							}
							else if(data.subcat_success)
							{
								//$('#modal_subcat_name_error').html('<p style="color:green;">Added Successfully!</p>');
								window.location.reload();
							}
							else if(data.sup_success)
							{
								//$('#modal_supplier_error').html('<p style="color:green;">Added Successfully!</p>');
								window.location.reload();
							}
							else if(data.success)
							{
								window.location.reload();
							} 	
							//$(formSelector)[0].reset();
							

					},
					error: function(req, textStatus, errorThrown) {
						//this is going to happen when you send something different from a 200 OK HTTP
						////alert('Submit Modal Error: ' + textStatus + ' ' +errorThrown);
					},
					complete: function(){
                        $(submitBtn).attr("disabled", false);
                    }
			  });	  
	});	
}

function submit_modal_form_file(baseURL, modal, submitBtn, formSelector, submitURL, func = null)
{	
	$(modal).modal('show');
	//console.log($(formSelector));
	$('#'+formSelector)[0].reset();//[0]
	
	$('.validation-errors').empty();
	$(submitBtn).click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$(submitBtn).attr("disabled", true);
		$.ajax({
                    type: "POST",
                    url: submitURL,
				    data: new FormData(document.getElementById(formSelector)),
					dataType: 'json',
					contentType: false,       
					cache: false,             
					processData:false, 
                    success: function(data) {
							if(data.error)
							{
								$.each(data, function (i,p) {
									$('#'+i+'_error').html(p);
								});
								
							}
					
							if(data.add_qty_success)
							{
								//window.location.reload();
								window.location.replace( data.base_url + 'admin/inventory/add_result/'
															+data.prod_id+'/'+data.sku
															+'/products/'+data.qty);
							}
							else if(data.cat_success)
							{
								//$('#modal_cat_name_error').html('<p style="color:green;">Added Successfully!</p>');
								window.location.reload();
							}
							else if(data.subcat_success)
							{
								//$('#modal_subcat_name_error').html('<p style="color:green;">Added Successfully!</p>');
								window.location.reload();
							}
							else if(data.sup_success)
							{
								//$('#modal_supplier_error').html('<p style="color:green;">Added Successfully!</p>');
								window.location.reload();
							}
							else if(data.success)
							{
								window.location.reload();
							} 	
							//$(formSelector)[0].reset();
							

					},
					error: function(req, textStatus, errorThrown) {
						//this is going to happen when you send something different from a 200 OK HTTP
						// alert('Submit Modal Error: ' + textStatus + ' ' +errorThrown);
					},
					complete: function(){
                        $(submitBtn).attr("disabled", false);
                    }
			  });	  
	});	
}

//NOT YET MODIFIED TO WORK WITH GOODBUY
//function definitions

function get_data_from_url(address, baseURL)
{
	$.ajax({
			type: "GET",
			url: baseURL+ address,
			dataType : 'json',
			success: function(data){	
				$.each(data, function(i, p) {
						$.each(p, function(i, p) {
							//callback(p.toString());
					});				
				});	
			},
			error: function(req, textStatus, errorThrown) {
				////alert('Get Data From URL Error: ' + textStatus + ' ' +errorThrown);
			}
		});
}


function submit_modal(modal, submitBtn, formSelector, submitURL, dropdownSelector, selectedOption)
{

	$(modal).modal('show');
	$(dropdownSelector + " " +"option[value='"+selectedOption+"']").prop('selected', true);

	$(submitBtn).click(function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$(formSelector).attr('method','POST');
		$(formSelector).attr('action',submitURL);
		$(formSelector).submit();
		return false;
	});	
}
//$('.proj_name_error').html(data.proj_name);
//$('.proj_desc_error').html(data.proj_desc);

function show_success_msg(msg)
{
	var mdl = '<div class="success-modal">' + msg + '<i class="fa fa-close hide-success-modal"></i></div>';
	if (!$(".success-modal").length) {
		$('body').prepend(mdl);
	}
	$(".success-modal").fadeIn();
	$(".hide-success-modal").click(function(){
		$(".success-modal").fadeOut();
	});		
}
function getChartJs(type, label, data) {
    var config = null;

    if (type === 'line') {
        config = {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: "Monthly Sales",
                    data: data,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.3)',
                    pointBorderColor: '#419544',
                    pointBackgroundColor: '#419544',
                    pointBorderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
	else if (type === 'line2') {
        config = {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: "Sales",
                    data: data,
                    borderColor: '#8BC34A',
                    backgroundColor: 'rgba(139, 195, 74, 0.3)',
                    pointBorderColor: '#7AAB41',
                    pointBackgroundColor: '#7AAB41',
                    pointBorderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: false
            }
        }
    }
    return config;
}