<?php
include_once "include/logincheck.inc";
include_once "include/common.inc";
include_once "include/dbconn.inc";
include_once "include/myfunc.inc";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Transaction</title>
<link rel="stylesheet" type="text/css" href="include/default.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.selectmenu.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="js/jquery.ui.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-en-GB.js"></script>
<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="js/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="js/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="js/jquery.ui.dialog.js"></script>
<script type="text/javascript" src="js/jquery.ui.button.js"></script>
<script type="text/javascript" src="js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="js/jquery.effects.core.js"></script>
<script type="text/javascript" src="js/jquery.ui.autocomplete.js"></script>
</head>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$(window.opener.document).find('body').attr('disabled','disabled');
	
	$( "input:button, button").button();
	
	$('#processing').hide();
		
	$("#transaction_date").datepicker($.datepicker.regional['en-GB']);
	$("#transaction_date").datepicker( "option", "firstDay", 1 );
	$("#transaction_date").datepicker();
	
	var fullDate = new Date();
	var twoDigitDays = twodigits(fullDate.getDate());
	var twoDigitMonth = twodigits(fullDate.getMonth() + 1); 
	var currentDate = twoDigitDays + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
	$("#transaction_date").val(currentDate);

	function twodigits(digits) {    return (digits > 9) ? digits : '0' + digits;}
	
	$("#add").click(function() {
				
		if (!$('#net_wages').val()) {
			alert("Please insert net wages");
			$('#net_wages').focus();
			return;
		}
		
		$('#processing').fadeIn(500);
		
		$.post("add_trans_popup_ok.php", {
			action_type : "insert",
			transaction_date: $('#transaction_date').val(),
			transaction_period_start : $('#transaction_period_start').val(),
			employee_id : $('#employee_id').val(),
			net_wages : $('#net_wages').val(),	
			deductions : $('#deductions').val(),
			remarks : $('textarea[name=remarks]').val()
		}, function(data) { 
			if (data != 'ERROR') {
				//alert(data);
				p_refresh();
				// Close the window
				window.close();
			} else {
				alert('Please try again');
			}
		});
		
		$('#processing').fadeOut(800);
		
		return false;
	});
	
});

$(window).unload(function() {
	window.opener.document.body.disabled=false;
});

function p_refresh()
{
    window.opener.location.reload();
    return false;
}

</script>
<body>
<form name="job_frm" method="post">
<div id="processing" style="display:inline">
	<img src="images/ajax-loader.gif" alt="loading" style="width:18px;vertical-align:middle;margin-left:10px;" /> PROCESSING
</div>
<input type="hidden" name="action_type" value="<?=$action_type?>">
<table border="0" cellpadding="0" cellspacing="0" width="500">
<tr>
	<td class='ui-widget-header left' width="120" height="30" ><span class='red'>*</span> Transaction Date</td>
	<td class='ui-widget-content left' >
	<input type="text" name="transaction_date" id="transaction_date" size="10" value="" />
	<input type="hidden" name="transaction_period_start" id="transaction_period_start" size="10" value="<?php echo getAUDate($_REQUEST['re_date']);?>" /> </td>
</tr>
<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $_REQUEST['id'];?>">
<tr>
	<td class='ui-widget-header left' ><span class='red'>*</span> Net wages</td>
	<td class='ui-widget-content left' >
	<input type="text" name="net_wages" id="net_wages" >
	</td>
</tr>
<tr>
	<td class='ui-widget-header left' >Deductions</td>
	<td class='ui-widget-content left' >
	<input type="text" name="deductions" id="deductions" >
	</td>
</tr>
<tr>
	<td class='ui-widget-header left' height="30"> Remarks</td>
	<td class='ui-widget-content left' >
	<textarea name="remarks" rows="4" cols="45"></textarea> </td>	
</tr>	
</table>
<input id="add" type="button" value="Add" />
</form>
</body>
</html>