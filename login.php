<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
if(isUserLoggedIn()) { header("Location: account.php"); die(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Thoroughwiz | Member Access Page</title>
<!-- Meta Data -->
<?php include("meta.php"); ?>
<!-- CSS Import -->
<?php include("style_block.php"); ?>
</head>
<body>
<div class="wrapper">
  <?php include("header.php"); ?>
  <!--=== Breadcrumbs ===-->
  <div class="breadcrumbs">
    <div class="container">
      <h1 class="pull-left">Thoroughwiz Member Access</h1>
      <ul class="pull-right breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li class="active">Member Access</li>
      </ul>
    </div>
  </div>
  <!--/breadcrumbs-->
  <!--=== End Breadcrumbs ===-->
  <!--=== Content Part ===-->
  <div class="container content">
    <div class="row">
            
    <div class="col-md-4">
        <h3>Please provide credentials</h3>
		 <form id="login-form" name="login" action="user-login.php" method="post"> 
        <label>Email Address:</label>
        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" id="username" name="email" class="form-control" required/>
        </div>  
        <label>Password:</label>                  
        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control" required/>
        </div>
        <div class="row">
            <div class="sign-in-trouble-wrap col-md-6">
                                <a href="forgot-password.php">Forgot Password?</a><br>
                                <a href="resend-activation.php">Resend Activation Email</a><br>
                            </div>
                            <div class="log-in-wrap col-md-6">
                            <button type="submit" class="btn btn-success pull-center"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;Log In</button>
                            </div>
        </div>
        </form>
        <!--<div id="form-messages" title="Thoroughwiz says..." style="text-align:center;padding:5px;"></div>
        <div id="logInDiv" title="Logging In" style="display:none;text-align:center;padding:5px;">
        	<img src="img/loading11.gif" alt="loading" />
        </div>
        <div id="logInGood" title="You're In!" style="text-align:center;padding:5px;"></div>-->
    </div>
      <!--/col-md-4-->
      <div class="col-md-1 mobile-sep"></div>
      <div class="col-md-3 mobile-sep">
        <h3>Not a member?</h3>
			<a href="register.php" class="btn btn-success"><span class='glyphicon glyphicon-user'></span>&nbsp;&nbsp;Register Now!</a>
        <!--<h3>Trouble Signing In?</h3>
			<a href='forgot-password.php' class='btn btn-success'>Forgot Password?</a><br /><br />
			<a href='resend-activation.php' class='btn btn-success'>Resend Activation Email</a>-->
      </div>
      <!--/col-md-3-->
    </div>
    <!--/row-->
		<hr>
		<div class="title-box">
		  <!--  <p><img alt="stripe payments" border="0" src="img/stripe-payments.png" class="img-responsive pp" /></p>-->
			<p><script type='text/javascript' src='https://ko-fi.com/widgets/widget_2.js'></script><script type='text/javascript'>kofiwidget2.init('Buy Me a Coffee', '#46b798', 'M4M6BWTB');kofiwidget2.draw();</script> </p>
		</div>
  </div>
  <!--/container-->
  <!--=== End Content Part ===-->
  <?php include("footer.php"); ?>
    <?php include("modals.php"); ?>
</div><!--/wrapper-->
<script>
$(document).ready(function(){
	
$("#logInDiv").dialog({
	modal: true,
	draggable: false,
	autoOpen: false,
	closeOnEscape: false,
	dialogClass: "no-close",
	height: 100
});
	
$(document).ajaxStart(function(){ 
	$("#logInDiv").dialog("open"); 
});

$(document).ajaxStop(function(){ 
    $('#logInDiv').dialog("close");
});
	
// new HTML5 'required' attribute is not supported in legacy browsers (IE9 and earlier); this provides a graceful fallback to alert users of required fields
$(function () {
	
    // feature detection for required attribute
    var supportsRequired = 'required' in document.createElement('input')
	
        // if 'required' isn't supported
        if (!supportsRequired) {

    		// loop through each element with a 'required' attribute
    		$('[required]').each(function () {

            	// this
            	var self = $(this)

            	// swap attribute for class
            	self.removeAttr('required').addClass('required');

            	// append an error message
            	self.parent().append('<span class="form-error">Required</span>')

    		}); //end each loop
	
        }; // end if supportsrequired
		
		

    // submit the form
    $('#login-form').on('submit', function (e) {
		
        // if 'required' isn't supported
		if (!supportsRequired) {
		
			// loop through class name required
			$('.required').each(function () {
	
				// this
				var self = $(this)
	
				// check shorthand if statement for input[type] detection
				var checked = (self.is(':checkbox') || self.is(':radio')) 
				? self.is(':not(:checked)') && $('input[name=' + self.attr('name') + ']:checked').length === 0 
				: false
	
				// run the empty/not:checked test
				if (self.val() === '' || checked) {
	
					// show error if the values are empty still (or re-emptied)
					// this will fire after it's already been checked once
					self.siblings('.form-error').show()
	
					// stop form submitting
					e.preventDefault()
	
				} 
				
			}) // close each loop
		
			// if required is not supported, then we have to check to make sure the fields are not empty for front-end validation
            if ($("#username").val().length !==0 && $("#password").val().length !==0){	
				// Get the form.
				var form = $('#login-form');
			
				// Get the messages div.
				var formMessages = $('#form-messages');
				var logInGood = $('#logInGood');
				
				// Stop the browser from submitting the form.
    			e.preventDefault();
				
				// Serialize the form data.
				var formData = $(form).serialize();
				
				$.ajax({
				  url: $(form).attr('action'),
				  type: 'POST',
				  data: formData,
				  dataType: 'json',
				  success: function(data) {
					  if(data[0] == "match"){
						  $(logInGood).html("Retrieving Membership Data...");
						  $(logInGood).dialog({
							modal: true,
							draggable: false,
							autoOpen: false,
							closeOnEscape: false,
							dialogClass: "no-close",
							height: 100
						});
						$(logInGood).dialog("open");
						setTimeout(function(){ document.location.href = 'account.php'; }, 3000);
						//document.location.href = 'account.php';
					  }else{
						$(formMessages).html(data[0]);
						$(formMessages).dialog({
							autoOpen: false,
							modal: true,
							buttons: [ { 
								text: "Ok", click: function() { 
								$( this ).dialog( "close" );
							} 
							} ]
						});
						$(formMessages).dialog("open");
					  }
				  },
				  error: function(xhr, desc, err) {
						//$(formMessages).html("Details: " + desc + "\nError:" + err + "\nError:" + xhr);
						$(formMessages).html("The system has encountered an error");
						$(formMessages).dialog({
							autoOpen: false,
							modal: true,
							buttons: [ { 
								text: "Ok", click: function() { 
								$( this ).dialog( "close" );
							} 
							} ]
						});
						$(formMessages).dialog("open");
				  }
				}); // end ajax call

            } // close if

  		} else { // if required attribute IS supported, we can let HTML 5 handle the validation and jump right to the ajax
		
				// Get the form.
				var form = $('#login-form');
			
				// Get the messages div.
				var formMessages = $('#form-messages');
				var logInGood = $('#logInGood');
				
				// Stop the browser from submitting the form.
    			e.preventDefault();
				
				// Serialize the form data.
				var formData = $(form).serialize();
				
				$.ajax({
				  url: $(form).attr('action'),
				  type: 'POST',
				  data: formData,
				  dataType: 'json',
				  success: function(data) {
					  if(data[0] == "match"){
						  $(logInGood).html("Retrieving Membership Data...");
						  $(logInGood).dialog({
							modal: true,
							draggable: false,
							autoOpen: false,
							closeOnEscape: false,
							dialogClass: "no-close",
							height: 100
						});
						$(logInGood).dialog("open");
						setTimeout(function(){ document.location.href = 'account.php'; }, 3000);
						//document.location.href = 'account.php';
					  }else{
						$(formMessages).html(data[0]);
						$(formMessages).dialog({
							autoOpen: false,
							modal: true,
							buttons: [ { 
								text: "Ok", click: function() { 
								$( this ).dialog( "close" );
							} 
							} ]
						});
						$(formMessages).dialog("open");
					  }
				  },
				  error: function(xhr, desc, err) {
						//$(formMessages).html("Details: " + desc + "\nError:" + err + "\nError:" + xhr);
						$(formMessages).html("The system has encountered an error");
						$(formMessages).dialog({
							autoOpen: false,
							modal: true,
							buttons: [ { 
								text: "Ok", click: function() { 
								$( this ).dialog( "close" );
							} 
							} ]
						});
						$(formMessages).dialog("open");
				  }
				}); // end ajax call
				
		}

    }) // close submit function

    // key change on all form inputs i.e user enters and exits a form field
    $('input').on('blur change', function () {

        // this
        var self = $(this)

        // check shorthand if statement for input[type] detection
        var checked = (self.is(':checkbox') || self.is(':radio')) 
        ? self.is(':not(:checked)') && $('input[name=' + self.attr('name') + ']:checked').length === 0 
        : false

        // if empty on change, i.e. if data is removed
        if (self.val() === '' || checked) {

            // show/keep the error in view
            self.siblings('.form-error').show()

        // if there's a value or checked
        } else {

            // hide the error
            self.siblings('.form-error').hide()

        }

    }) // close input blur/change

}) // close main function

}); //jquery
</script>
</body>
</html>