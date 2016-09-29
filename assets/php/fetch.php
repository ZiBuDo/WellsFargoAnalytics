<?php

require "file.php";
require "database.php";
$conn = connect();

$request = $_GET["request"];

if($request == "1var"){
	//create table that is sortable for the web page
	$stmt = $conn->prepare("SELECT * FROM `WF1VARSTATS` WHERE 1 ORDER BY `VAR` ASC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	$table = "<table id='Onevartable' class='sortable' border='1'><tr><th>Column</th><th>Sum</th><th>Mean</th><th>Variance</th><th>Standard Deviation</th></tr>";
	foreach($result as $r){
		$val = $r["VALUE"];
		$name = $r["VAR"];
		if($r["STAT"] == "SUM"){
			$table .= "<tr><td>$name</td><td>$val</td>";
		}else if($r["STAT"] == "STDDEV"){
			$table .= "<td>$val</td></tr>";
		}else{
			$table .= "<td>$val</td>";
		}
	}
	$table .= "</table>";
	echo $table;
}else if($request == "2var"){
	//create form and table with javascript buttons based on WF2VARSTATS table
	$data = "<center>
		<h2>Check Correlation Between Two Columns</h2>
		<form id='correlation'>
			<p><b>Variable 1: </b><select id='corr1'>";
	$stmt = $conn->prepare("SELECT DISTINCT `VAR1` FROM `WF2VARSTATS` WHERE 1 ORDER BY `VAR1` ASC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $r){
		$name = $r["VAR1"];
		$data .= "<option value='$name'>$name</option>";
	}
	$data .="</select>
	<b>Variable 2: </b><select id='corr2'>";
	$stmt = $conn->prepare("SELECT DISTINCT `VAR2` FROM `WF2VARSTATS` WHERE 1 ORDER BY `VAR2` ASC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $r){
		$name = $r["VAR2"];
		$data .= "<option value='$name'>$name</option>";
	}
	$data.="</select>
	<input class='btn btn-primary' type='submit' value='Check'>
	</p><br>
		</form>
		
	<p><b>Correlation: </b>";
	//create hidden spans for fast checking, handle all loading at start of webpage
	$stmt = $conn->prepare("SELECT * FROM `WF2VARSTATS` WHERE `STAT` = 'CORRELATION' ORDER BY `VAR1` ASC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $r){
		$v1 = $r["VAR1"];
		$v2 = $r["VAR2"];
		$val = $r["VALUE"];
		if($val > 0){
			$style = "color: green; display: none;";
		}else{
			$style = "color: red; display: none;";
		}
		$data .= "<span id='$v1-$v2' style='$style'>$val</span>";
	}
	$data .= "
	</p>
	<button id='showTwovar' class='btn btn-default' onclick='showTwoTable()'>Show Table</button><br>
	<div id='Twovartablediv' style='display:none;'>
	<button class='btn btn-default' onclick='hideTwoTable()'>Hide Table</button><br>
		<table id='Twovartable' class='sortable fixedheader' border='1'><thead><tr><th>Var 1</th><th>Var 2</th><th>Correlation</th></tr></thead><tbody>";
	foreach($result as $r){
		$v1 = $r["VAR1"];
		$v2 = $r["VAR2"];
		$val = $r["VALUE"];
		if($val > 0){
			$style = "color: green;";
		}else{
			$style = "color: red;";
		}
		$data .= "<tr><td>$v1</td><td>$v2</td><td style='$style'>$val</td></tr>";
	}	
	$data .="</tbody></table>
	</div>
	</center>";
	
	echo $data;
}else if($request == "growthinit"){
	$data = "<center>
		<h2>Independent Growth Linear Regression Model Monthly</h2>
		<form id='growthForm'>
			<p><b>Category: </b><select id='growthcorr1'>";
	$stmt = $conn->prepare("SELECT DISTINCT `CATEGORY` FROM `WFGROWTHMODEL` WHERE 1 ORDER BY `CATEGORY` ASC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $r){
		$name = $r["CATEGORY"];
		$data .= "<option value='$name'>$name</option>";
	}
	$data .="</select>
	<b>Measure: </b><select id='growthcorr2'>";
	$stmt = $conn->prepare("SELECT DISTINCT `DELTA` FROM `WFGROWTHMODEL` WHERE 1 ORDER BY `DELTA` ASC");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $r){
		$name = $r["DELTA"];
		$data .= "<option value='$name'>$name</option>";
	}
	$data.="</select>
	<input class='btn btn-primary' type='submit' value='Graph'>
	</p><br>
		</form>";
	
	echo $data;
	
}else if($request == "growth"){
	$lin1 = $_GET["var1"];
	$lin2 = $_GET["var2"];
	$stmt = $conn->prepare("SELECT * FROM `WFGROWTHMODEL` WHERE `CATEGORY` = '$lin1' AND `DELTA` = '$lin2'");
	$stmt->execute();
	$result = $stmt->fetchAll();
	$line = $result[0];
	$slope = $line["SLOPE"];
	$int = $line["INTERCEPT"];
	
	//create labels and data based on category to graph on web page
	//create x-axis depending on the category
	if($lin1 == "cust_demographics_ai"){
		$min = 0;
		$max = 5;
	}else if($lin1 == "cust_demographics_aii"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "typeA_bal_cat"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "typeB_bal_cat"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "typeC_bal_cat"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "typeD_bal_cat"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "typeE_bal_cat"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "wf_outreach_flag_chan_i"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "wf_outreach_flag_chan_ii"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "wf_outreach_flag_chan_iii"){
		$min = 1;
		$max = 5;
	}else if($lin1 == "wf_outreach_flag_chan_iv"){
		$min = 1;
		$max = 5;
	}else{
		$min = 0;
		$max = 400;
	}
	
	//create 400 steps between min and max with labels separate by pipe character for front end using y = mx + b
	$step = ($max - $min) / 400;
	$labels = "[";
	$data = "[";
	for($i = 0; $i < 400; $i++){
		$new = $step * $i;
		if($i == 399){
			$labels .= "\"$new\"]";
		}else if($i == 0){
			$labels .= "\"$new\",";
		}else{
			$labels .= "\"\",";
		}
		$val = $slope * $new + $int;
		if($i != 399){
			$data .= "$val,";
		}else{
			$data .= "$val]";
		}
	}
	$output = $labels . "|" . $data;
	echo $output;
}else if($request == "demo"){
	$metric = $_GET["metric"];
	//get bubble chart data for negative and positive with output as positive|negative
	
	if($metric == "balance"){
		$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = 'Compare Growth of Balance By Each Demographic' AND `TYPE` = 'positive'");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$res = $result[0];
		$output = $res["DATA"] . "|";
		$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = 'Compare Growth of Balance By Each Demographic' AND `TYPE` = 'negative'");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$res = $result[0];
		$output .= $res["DATA"];
		echo $output;
	}else if($metric == "accountA"){
		$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = 'Compare Growth of Accounts A By Each Demographic' AND `TYPE` = 'positive'");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$res = $result[0];
		$output = $res["DATA"] . "|";
		$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = 'Compare Growth of Accounts A By Each Demographic' AND `TYPE` = 'negative'");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$res = $result[0];
		$output .= $res["DATA"];
		echo $output;
	}else{
		$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = 'Compare Growth of Accounts B By Each Demographic' AND `TYPE` = 'positive'");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$res = $result[0];
		$output = $res["DATA"] . "|";
		$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = 'Compare Growth of Accounts B By Each Demographic' AND `TYPE` = 'negative'");
		$stmt->execute();
		$result = $stmt->fetchAll();
		$res = $result[0];
		$output .= $res["DATA"];
		echo $output;
	}
}else if($request == "channelinit"){
	$category = array("cust_outreach_ai",
	"cust_outreach_aii",
	"cust_outreach_aiii",
	"cust_outreach_aiv",
	"cust_outreach_av",
	"cust_outreach_avi",
	"cust_outreach_avii",
	"cust_outreach_aviii");
	$channels = array(
	"wf_outreach_flag_chan_i",
	"wf_outreach_flag_chan_ii",
	"wf_outreach_flag_chan_iii",
	"wf_outreach_flag_chan_iv"
	);
	$measure = array(
	"normal_tot_bal",
	"typeA_ct",
	"typeB_ct"
	);	
	$out = "
	<center>
		<h3>Outreach Effectiveness In Different Channels</h3>
		<p><b>Outreach Type: </b><select id='outType'>";
	foreach($category as $c){
		$out .= "<option value='$c'>$c</option>";
	}
	$out .= "</select><b>Channel: </b><select id='outChannel'>";
	foreach($channels as $c){
		$out .= "<option value='$c'>$c</option>";
	}
	$out .= "</select><b>Metric: </b><select id='outMetric'>";
	foreach($measure as $c){
		$out .= "<option value='$c'>$c</option>";
	}
	$out .= "</select> <button onclick='graphOut();' class='btn btn-primary'>Graph</button></p></center>";
	echo $out;
}else if($request == "channel"){
	$metric = $_GET["metric"];
	$channel = $_GET["channel"];
	$type = $_GET["type"];
	//get bubble chart data for negative and positive with output as positive|negative
	$desc = "Compare Growth of $metric based on Outreach Type $type with Outreach Channel $channel";
	$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = '$desc' AND `TYPE` = 'positive'");
	$stmt->execute();
	$result = $stmt->fetchAll();
	$res = $result[0];
	$output = $res["DATA"] . "|";
	$stmt = $conn->prepare("SELECT * FROM `WFCHARTS` WHERE `DESCRIPTION` = '$desc' AND `TYPE` = 'negative'");
	$stmt->execute();
	$result = $stmt->fetchAll();
	$res = $result[0];
	$output .= $res["DATA"];
	echo $output;
		
}

?>