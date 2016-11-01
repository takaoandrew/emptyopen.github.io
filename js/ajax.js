$(function() {


	$("#searchq").keyup(function(){
		var q = $(this).val();
		if(!q==""){
			$.ajax({
		      type : 'get',
		      dataType: 'html',
		      url : '/home/searchautocomplete/' + q , 
		      success : function(response) {
		      		$("#searchr").show();
		           $("#searchr").html(response);
		      }
		    });
		}
		else{
			$("#searchr").hide();
		}
	});


	// revenue slider
	$( "#slider-range-max" ).slider({
      range: "max",
      min: 100,
      max: 10000,
      value: 100,
      step: 100,
      slide: function( event, ui ) {
        $( "#amount" ).val("$" + ui.value + "+");
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range-max" ).slider( "value" ) + "+" );

    $('.income-filter').click(function() {

    	var revenue = $("#amount").val();
    	var rev = revenue.replace("$", "");
    	var loc = document.location.href;
    	var newloc = loc.substr(0, loc.indexOf("?"));
    	document.location.href = newloc + "?revenue_min=" + parseInt(rev);

    	return false;
    });

    // visitors slider
	$( "#slider-visitors-min" ).slider({
      range: "max",
      min: 500,
      max: 100000,
      value: 100,
      step: 500,
      slide: function( event, ui ) {
        $( "#visitors" ).val(ui.value + "+ visitors");
      }
    });
    $( "#visitors" ).val( $( "#slider-visitors-min" ).slider( "value" ) + "+ visitors" );

    $('.visitors-filter').click(function() {

    	var revenue = $("#visitors").val();
    	var rev = revenue.replace("$", "");
    	var loc = document.location.href;
    	var newloc = loc.substr(0, loc.indexOf("?"));
    	document.location.href = newloc + "?traffic_min=" + parseInt(rev);

    	return false;
    });

    // age min slider
	$( "#slider-age-min" ).slider({
      range: "max",
      min: 3,
      max: 120,
      value: 3,
      step: 1,
      slide: function( event, ui ) {
        $( "#age" ).val(ui.value + "+ months");
      }
    });
    $( "#age" ).val( $( "#slider-age-min" ).slider( "value" ) + "+ months" );

    $('.age-filter').click(function() {

    	var revenue = $("#age").val();
    	var rev = revenue.replace("$", "");
    	var loc = document.location.href;
    	var newloc = loc.substr(0, loc.indexOf("?"));
    	document.location.href = newloc + "?age_min=" + parseInt(rev);

    	return false;
    });
	
	$('#sold-popover').popover();

	$("#sbJoin").click(function(e) {
		e.preventDefault();
		
		document.location.href= '/users/join';
		
		return false;
	}); 

	$("#contact-form").validate({
		 errorClass: "alert alert-error",
		 submitHandler: function(form) {
		   $("#contact-form").ajaxSubmit({target: '#contact_output_div'});
		 },
		 highlight: function(element, errorClass) {
		    $(element).fadeOut(function() {
		      $(element).css("border", "1px solid red");
		      $(element).fadeIn();
		    });
		},
		rules: {
			    // simple rule, converted to {required:true}
			    yname: "required",
			    yemail: {
			      required: true,
			      email: true
			    },
			    ysubject: "required",
			    ymessage: "required",
			  },
		errorPlacement: function(error, element) {}
	});


	$("#login-form").validate({
		 errorClass: "alert alert-error",
		 submitHandler: function(form) {
		   $("#login-form").ajaxSubmit({target: '#login_output_div'});
		 },
		 highlight: function(element, errorClass) {
		    $(element).fadeOut(function() {
		      $(element).css("border", "1px solid red");
		      $(element).fadeIn();
		    });
		},
		rules: {
			    // simple rule, converted to {required:true}
			    uname: "required",
			    upwd: "required"
			  },
		errorPlacement: function(error, element) {}
	});
	
	$("#signup-form").validate({
		 errorClass: "alert alert-error",
		 submitHandler: function(form) {
		   $("#signup-form").ajaxSubmit({target: '#signup_output_div'});
		 },
		 highlight: function(element, errorClass) {
		    $(element).fadeOut(function() {
		      $(element).css("border", "1px solid red");
		      $(element).fadeIn();
		    });
		},
		rules: {
			    // simple rule, converted to {required:true}
			    username: "required",
			    email: {
			      required: true,
			      email: true
			    },
			    password: "required"
			  },
		errorPlacement: function(error, element) {}
	});
	
	$("#att").validate({
	 errorClass: "alert alert-error",
	 submitHandler: function(form) {
	   $("#att").ajaxSubmit({target: '.att_rs'});
	 }
	});
	
	function showRequest(formData, jqForm, options) {
		$('.ajax-modal-result').html('<img src="/img/ajax-loader.gif"/> Please wait.'); 
	} 
	

	// basic details form
	$(".frm-basic").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-basic').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});


	$(".frm-description, .frm-age, .frm-revenue, .frm-traffic, .frm-monetization").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-description').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	$(".frm-age").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-age').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	$(".frm-revenue").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-revenue').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	$(".frm-traffic").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-traffic').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	$(".frm-monetization").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-monetization').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	$(".frm-payments").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-payments').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	$(".frm-unique").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-unique').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});
		
	$(".frm-tags").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-tags').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});
	
	$(".frm-verify").validate({ errorClass: "alert alert-warning",
	 	submitHandler: function(form) {
	   		$('.frm-verify').ajaxSubmit({target: '.ajax-modal-result', beforeSubmit: showRequest});
	 	}
	});

	var options = {target: '#comment_output'}; 
    
	$("#comment-form").validate({
	 errorClass: "alert alert-error",
	 submitHandler: function(form) {
	   $("#comment-form").ajaxSubmit(options);
	   var lastID = $(".user_comments > li:last").attr('data-lastID');
	   var movID = $("#movID").html();
	   $.post('/listings/ajax_last_comment', {last : lastID, movie: movID}, function(data) {
	   		$('.user_comments').append(data);
	   		$("html,body").animate({scrollTop: $('.user_comments li:last').offset().top - 30});
	   });
	 }
	});
	
	$("#sbStep1").click(function() {
		$(this).after("<img src='/img/ajax-loader.gif' /> Please wait, verifying URL");
	});
	
	
	$("#ajax-div").dialog({autoOpen:false, minWidth:800, minheight:400});
	
	
	$(".iframe-dialog").click(function(e) {
		e.preventDefault();
		
		$("#ajax-l").show();
		
		var theSrc = $(this).attr("href");
		console.log(theSrc);
		
		$("#frame").attr("src", theSrc);
		
		$("#ajax-div").dialog("open");
		
		$("#ajax-l").hide();
		
		return false;
	});	
	
	$('.remove_c').click(function() {
		var remID = $(this).attr("id");
		var dosplit = remID.split("_");
		var theID = dosplit[1];
		var link = $(this);
		var listID = $("#movID").html(); 
		
		$.get('/listings/remove_c/' + theID + '/' + listID, function(data) {
			if(data != 'ok') {
				$(link).html(data);
			}else{
				$(link).parent().hide('slow');
			}
		});
		
	});
	
	$("a[data-target=#myModal]").click(function(ev) {
	    ev.preventDefault();
	    var target = $(this).attr("href");
	
	    // load the url and show modal on success
	    $("#myModal .modal-body").load(target, function() { 
	         $("#myModal").modal("show"); 
	    });
	    
	    return false;
	});
	
});
