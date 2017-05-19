var cuclaims = 0; var prclaims = 0; var validclaims = 0;
var captchas = [];
var interval = 10;
var currTimer = interval;
var state = true;
Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
	c = isNaN(c = Math.abs(c)) ? 2 : c, 
	d = d == undefined ? "." : d, 
	t = t == undefined ? "," : t, 
	s = n < 0 ? "-" : "", 
	i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
	j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
function grabStat(){
	$.ajax({
		type: "GET",
		url: "https://faucet.raiblockscommunity.net/paylist.php",
		data: 'acc=' + xrb_address + '&json=1',
		dataType : "JSON",
		success: function(data){
			var poolClaim = parseFloat(data.pending[0].pending);
			var threshold = parseFloat(data.threshold.replace('.0', ''));
			var toGo = threshold - poolClaim;
			$("#displayPoolClaims").html( poolClaim.formatMoney(0, '.', ',') );
			$("#displayThreshold").html( threshold.formatMoney(0, '.', ',') );
			if(poolClaim < threshold)
				$("#displayTop60").html('<span class="text-danger">' + toGo.formatMoney(0, '.', ',') + ' to Top 60</span>');
			else
				$("#displayTop60").html('<span class="text-success">Yes <i class="fa fa-check" aria-hidden="true"></i></span>');

			$("#displayNextDistribution").html(Math.floor(data.eta / 60) + " <small>min</small>");
		}
	});
}

function doTick(){
	if(state){
		currTimer--;

		if(currTimer < 0){
			onFlush();
			currTimer = interval;
		}
	}
}

function onClaim(token){
	$("#claimButton").prop("disabled",true);   

	captchas.push( grecaptcha.getResponse() );
	cuclaims = captchas.length;

	$("#currentClaims").text( cuclaims );

	$("#claimButton").prop("disabled",false);

	$("#claimButton").trigger("click");
}

function onFlush(){
	if( cuclaims > 0 ){
		state = false;
		prclaims = cuclaims;

		$.ajax({
			type: "POST",
			url: base_url + "faucet/validate",
			dataType : "JSON",
			data: "captchas=" + JSON.stringify( captchas ),
			success: function(data){
				cuclaims = cuclaims - prclaims;
				captchas = captchas.slice( prclaims, captchas.length );

				state = true;
				$("#currentClaims").text( cuclaims );
				$("#validationMessage").css('display', 'none').removeClass('alert-success').addClass('alert-danger');
				updateStat();
			},
			error: function(data){
				$("#validationMessage").css('display', 'block').removeClass('alert-success').addClass('alert-danger').html("<b>Trouble processing claims.</b>");
				state = true;
			}
		});
	}else{
		updateStat();
	}
}

function updateStat(){
	$.ajax({
		type: "POST",
		url: base_url + "faucet/stat",
		dataType : "JSON",
		success: function(data){

			$("#pendingClaims").text( data.pendingClaims );
			$("#validatedClaims").text ( data.validatedClaims );

		}
	});
}

$(document).ready(function(){
	grabStat();
	setInterval(grabStat, 60000*5);
	setInterval(doTick, 1000);
	$("#claimButton").click(function(){
		grecaptcha.reset();
	});

	$("#nightToggle").change(function(){

		var val = $(this).is(":checked") ? '1' : '0';

		if(val == '1'){
			$("nav").removeClass('navbar-default').addClass('navbar-inverse');
			$("#claimStat, #donateInformation, #displayPoolClaims, #displayThreshold, #displayTop60, #displayNextDistribution, thead tr th").css("color", "#9d9d9d");
			$(this).parent().css("color", "#9d9d9d");
			$("body").css("background", "#222");
		}else{
			$("nav").removeClass('navbar-inverse').addClass('navbar-default');
			$("#claimStat, #donateInformation, #displayPoolClaims, #displayThreshold, #displayTop60, #displayNextDistribution, thead tr th").css("color", "#333");
			$(this).parent().css("color", "#333");
			$("body").css("background", "#fff");
		}

	});

});