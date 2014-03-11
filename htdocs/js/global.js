toastr.options = {
	"closeButton": true,
	"debug": false,
	"positionClass": "toast-bottom-left",
	"onclick": null,
	"showDuration": "300",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "fadeIn",
	"hideMethod": "fadeOut"
}



// Enable bootstrap tooltips
$(function() {
	$('.tippy').tooltip();
});



$(".a_run").click(function(){
	var request_reply = json_req("/backend/run_action.php?a=" + $(this).attr('data-actionid'));
	if (request_reply.status == "ok") {
		toastr.success("Successfully executed action \"" + $(this).attr('data-actionname') + "\"","Success");
	} else {
		toastr.error(request_reply,"Fail");
	}
});


function json_req(urli){
	var rdata = "Request failed";
	$.ajax({
		url: urli,
		dataType: 'json',
		async: false,
		data: "",
		success: function(data) {
			rdata = data;
		}
	});
	return rdata;
}