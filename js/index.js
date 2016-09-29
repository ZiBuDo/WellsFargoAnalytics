//global variables
var corr1 = "cust_demographics_ai";
var corr2 = "cust_demographics_aii";
var lin1 = "cust_demographics_ai";
var lin2 = "normal_tot_bal";
var demo = "Balance";
$(document).ready(function(){
	//populate single variable table
	$.ajax({
	   url: 'assets/php/fetch.php?request=1var',
	   error: function() {
		  console.log("The server is experiencing problems.");
	   },
	   dataType: 'text',
	   success: function(data) {
		  $("#var1").html(data);
		  var newTableObject = document.getElementById("Onevartable");
		  sorttable.makeSortable(newTableObject);
	   },
	   type: 'GET'
	});
	
	//create correlation for 2 var table and handle hiding and showing entire correlation table
	$.ajax({
	   url: 'assets/php/fetch.php?request=2var',
	   error: function() {
	   },
	   dataType: 'text',
	   success: function(data) {
		  $("#var2").html(data);
		  $("#corr2").val("cust_demographics_aii");
		  getCorr();
		  $("#correlation").submit(function(e){
			e.preventDefault();
			getCorr();
			});
		 var newTableObject = document.getElementById("Twovartable");
		 sorttable.makeSortable(newTableObject);
		 //$('#Twovartable').fixedHeaderTable({ footer: false, cloneHeadToFoot: false, fixedColumn: false });
	   },
	   type: 'GET'
	});
	$("#correlation").submit(function(e){
		e.preventDefault();
		getCorr();
	});
	
	//create linear regression graphs for independent growth model
	$.ajax({
	   url: 'assets/php/fetch.php?request=growthinit',
	   error: function() {
	   },
	   dataType: 'text',
	   success: function(data) {
		  $("#growth").html(data);
		  $("#growthForm").submit(function(e){
				e.preventDefault();
				graphGrowth();
			});
	   },
	   type: 'GET'
	});
	$.ajax({
	   url: 'assets/php/fetch.php?request=channelinit',
	   error: function() {
	   },
	   dataType: 'text',
	   success: function(data) {
		  $("#channels").html(data);
	   },
	   type: 'GET'
	});
	
	 $("#growthForm").submit(function(e){
		e.preventDefault();
		graphGrowth();
	});
});

function graphOut(){
	var type = $("#outType").val();
	var metric = $("#outMetric").val();
	var channel = $("#outChannel").val();
	$("#channelgraphs").html("<center><canvas id='channelChart' width='800' height='500' ></canvas></center>");
	$.ajax({
	   url: 'assets/php/fetch.php?request=channel&metric=' + metric + '&type='+type+'&channel='+channel,
	   error: function() {
	   },
	   dataType: 'text',
	   success: function(result) {
		   var arr = result.split("|");
		   //convert string to javascript array of data objects
		   eval("var positive = " + arr[0]);
		   eval("var negative = " + arr[1]);
		   var ctx = document.getElementById("channelChart");
			var data = {
				datasets: [
					{
						label: 'Positive Growth',
						data: positive,
						backgroundColor:"#027f0a",
						hoverBackgroundColor: "#027f0a",
					},
					{
						label: 'Negative Growth',
						data: negative,
						backgroundColor:"#a30d00",
						hoverBackgroundColor: "#a30d00",
					}
				]
			};
			var myBubbleChart = new Chart(ctx,{
				type: 'bubble',
				data: data,
				options: {
					responsive: false,
					scales: {
						yAxes: [{
						  scaleLabel: {
							display: true,
							labelString: 'Channel'
						  }
						}],
						xAxes: [{
						  scaleLabel: {
							display: true,
							labelString: 'Outreach Type'
						  }
						}]
					  }
				}
			});
	   },
	   type: 'GET'
	});
	
}

function graphDemo(){
	demo = $("#demoMetric").val();
	$("#demographs").html("<center><canvas id='demoChart' width='800' height='500' ></canvas></center>");
	$.ajax({
	   url: 'assets/php/fetch.php?request=demo&metric=' + demo,
	   error: function() {
	   },
	   dataType: 'text',
	   success: function(result) {
		   var arr = result.split("|");
		   //convert string to javascript array of data objects
		   eval("var positive = " + arr[0]);
		   eval("var negative = " + arr[1]);
		   var ctx = document.getElementById("demoChart");
			var data = {
				datasets: [
					{
						label: 'Positive Growth',
						data: positive,
						backgroundColor:"#027f0a",
						hoverBackgroundColor: "#027f0a",
					},
					{
						label: 'Negative Growth',
						data: negative,
						backgroundColor:"#a30d00",
						hoverBackgroundColor: "#a30d00",
					}
				]
			};
			var myBubbleChart = new Chart(ctx,{
				type: 'bubble',
				data: data,
				options: {
					responsive: false,
					legend: {
						display: false
					 },
					scales: {
						yAxes: [{
						  scaleLabel: {
							display: true,
							labelString: 'Demographic B'
						  }
						}],
						xAxes: [{
						  scaleLabel: {
							display: true,
							labelString: 'Demographic A'
						  }
						}]
					  }
				}
			});
	   },
	   type: 'GET'
	});
}




function graphGrowth(){
	lin1 = $("#growthcorr1").val();
	lin2 = $("#growthcorr2").val();
	$("#growthChartDiv").html("<center><h3>"+lin1+" VS "+lin2+"</h3><canvas id='growthChart' width='800' height='500' ></canvas></center>");
	$.ajax({
	   url: 'assets/php/fetch.php?request=growth&var1=' + lin1 + '&var2='+ lin2,
	   error: function() {
	   },
	   dataType: 'text',
	   success: function(data) {
		   var arr = data.split("|");
		   var labels = arr[0];
		   var datapoints = arr[1];
		   var datapointsarr = $.parseJSON(datapoints);
		    var labelsarr = $.parseJSON(labels);
			var ctx = document.getElementById("growthChart");
			
			var dataset = {
				labels: labelsarr,
				datasets: [
					{
						label: "% Growth",
						fill: false,
						lineTension: 0.1,
						backgroundColor: "rgba(75,192,192,0.4)",
						borderColor: "rgba(75,192,192,1)",
						borderCapStyle: 'butt',
						borderDash: [],
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: "rgba(75,192,192,1)",
						pointBackgroundColor: "#fff",
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: "rgba(75,192,192,1)",
						pointHoverBorderColor: "rgba(220,220,220,1)",
						pointHoverBorderWidth: 2,
						pointRadius: 1,
						pointHitRadius: 10,
						data: datapointsarr,
						spanGaps: false,
					}
				]
			};
			var myLineChart = new Chart(ctx, {
				type: 'line',
				data: dataset,
				options: {
					responsive: false
				}
			});
	   },
	   type: 'GET'
	});
}


//Change tab to toggle menu
$(document).keydown(function (e) 
{
    var keycode1 = (e.keyCode ? e.keyCode : e.which);
    if (keycode1 == 0 || keycode1 == 9) {
        e.preventDefault();
        e.stopPropagation();
		$("#wrapper").toggleClass("toggled");
    }
});

//Correlation form
function getCorr(){
	$("#"+corr1+"-"+corr2).hide();
	corr1 = $("#corr1").val();
	corr2 = $("#corr2").val();
	$("#"+corr1+"-"+corr2).show();
}
//show and hide two var table
function showTwoTable(){
	$("#showTwovar").hide();
	$("#Twovartablediv").show();
}
function hideTwoTable(){
	$("#Twovartablediv").hide();
	$("#showTwovar").show();
}