<?php
ini_set('memory_limit', '2048M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "file.php";
require "database.php";

$conn = connect();
$stmt = $conn->prepare("SELECT * FROM `WELLSFARGO` WHERE 1 ORDER BY `cust_num` ASC, `month` ASC");
$stmt->execute();
$result = $stmt->fetchAll();
$rows = $stmt->rowCount();
//10,000 customer ids, 12 months, normal_tot_bal
$cols = array(
"normal_tot_bal",
"cust_demographics_ai",
"cust_demographics_aii",
"typeA_ct",
"typeB_ct",
"typeC_flag",
"typeD_flag",
"typeE_flag",
"typeF_flag",
"typeG_flag",
"typeA_bal_cat",
"typeB_bal_cat",
"typeC_bal_cat",
"typeD_bal_cat",
"typeE_bal_cat",
"cust_outreach_ai",
"cust_outreach_aii",
"cust_outreach_aiii",
"cust_outreach_aiv",
"cust_outreach_av",
"cust_outreach_avi",
"cust_outreach_avii",
"cust_outreach_aviii",
"wf_outreach_flag_chan_i",
"wf_outreach_flag_chan_ii",
"wf_outreach_flag_chan_iii",
"wf_outreach_flag_chan_iv"
);
$stmt = $conn->prepare("DELETE FROM `WFCHARTS` WHERE 1");
$stmt->execute();
$stmt = $conn->prepare("DELETE FROM `WF1VARSTATS` WHERE 1");
$stmt->execute();
$stmt = $conn->prepare("DELETE FROM `WF2VARSTATS` WHERE 1");
$stmt->execute();
$stmt = $conn->prepare("DELETE FROM `WFGROWTHMODEL` WHERE 1");
$stmt->execute();

// Calculate basic stats for all areas such as mean, std. deviation, variance, sum
// Start with Sum variables at 0, add to Stats Database
$count = 0;
foreach($cols as $c){
	$sum = 0;
	$mean = 0;
	foreach($result as $r){
		//aggregate here
		$count++;
		$sum += $r["$c"];
	}
	$mean = $sum / $count;
	//insert into database 
	$stmt = $conn->prepare("INSERT INTO `WF1VARSTATS`(`VAR`, `STAT`, `VALUE`) VALUES ('$c','SUM',$sum)");
	$stmt->execute();
	$stmt = $conn->prepare("INSERT INTO `WF1VARSTATS`(`VAR`, `STAT`, `VALUE`) VALUES ('$c','MEAN',$mean)");
	$stmt->execute();
	$variance = 0;
	foreach($result as $r){
		//calculate variance and std dev
		$variance += pow(($r["$c"] - $mean),2);
	}
	$variance = $variance / $count;
	$stddev = sqrt($variance);
	$stmt = $conn->prepare("INSERT INTO `WF1VARSTATS`(`VAR`, `STAT`, `VALUE`) VALUES ('$c','VARIANCE',$variance)");
	$stmt->execute();
	$stmt = $conn->prepare("INSERT INTO `WF1VARSTATS`(`VAR`, `STAT`, `VALUE`) VALUES ('$c','STDDEV',$stddev)");
	$stmt->execute();
}

// Calculate covariance for correlation coefficient for each column and enter into WF2VARSTATS table
foreach($cols as $c){
	foreach($cols as $d){
		$stmt = $conn->prepare("SELECT * FROM `WF1VARSTATS` WHERE `STAT` = 'MEAN' AND `VAR` = '$c'");
		$stmt->execute();
		$cmeanfetch = $stmt->fetchAll();
		$cmean = $cmeanfetch[0]['VALUE'];
		$stmt = $conn->prepare("SELECT * FROM `WF1VARSTATS` WHERE `STAT` = 'STDDEV' AND `VAR` = '$c'");
		$stmt->execute();
		$cstdfetch = $stmt->fetchAll();
		$cstd = $cstdfetch[0]['VALUE'];
		//covariance for each variable pair with exception to itself
		if($c != $d){
			//Covariance = aggregate sum of (c - cmean) * (d - dmean) / n 
			//derive mean from previous database inserts for general variables
			$stmt = $conn->prepare("SELECT * FROM `WF1VARSTATS` WHERE `STAT` = 'MEAN' AND `VAR` = '$d'");
			$stmt->execute();
			$dmeanfetch = $stmt->fetchAll();
			$dmean = $dmeanfetch[0]['VALUE'];
			$stmt = $conn->prepare("SELECT * FROM `WF1VARSTATS` WHERE `STAT` = 'STDDEV' AND `VAR` = '$d'");
			$stmt->execute();
			$dstdfetch = $stmt->fetchAll();
			$dstd = $dstdfetch[0]['VALUE'];
			$covariance = 0;
			foreach($result as $r){
				//aggregate here
				$covariance += ($r["$c"] - $cmean) * ($r["$d"] - $dmean);
			}
			$covariance = $covariance / $count;
			//insert into database table 
			$stmt = $conn->prepare("INSERT INTO `WF2VARSTATS`(`VAR1`, `VAR2`, `STAT`, `VALUE`) VALUES ('$c','$d','COVARIANCE',$covariance)");
			$stmt->execute();
			//prepare correlation coefficient
			// p = covariance / (stddevc * stddevd)
			if($dstd * $cstd == 0){
				$corrcoef = 0;
			}else{
				$corrcoef = $covariance / ($dstd * $cstd);
			}
			$stmt = $conn->prepare("INSERT INTO `WF2VARSTATS`(`VAR1`, `VAR2`, `STAT`, `VALUE`) VALUES ('$c','$d','CORRELATION',$corrcoef)");
			$stmt->execute();
		}
	}
}

//create growth model - Analyze events separately and track growth of customers over a month
/*		
	Growth Model Table Columns:
	Category | Delta | Slope | Y-intercept
	
	Create a linear regression of each delta (# accounts, balance, products) based on outreach, channel, balance category, and demographic

*/
$categories = array(
"cust_demographics_ai",
"cust_demographics_aii",
"typeA_bal_cat",
"typeB_bal_cat",
"typeC_bal_cat",
"typeD_bal_cat",
"typeE_bal_cat",
"cust_outreach_ai",
"cust_outreach_aii",
"cust_outreach_aiii",
"cust_outreach_aiv",
"cust_outreach_av",
"cust_outreach_avi",
"cust_outreach_avii",
"cust_outreach_aviii",
"wf_outreach_flag_chan_i",
"wf_outreach_flag_chan_ii",
"wf_outreach_flag_chan_iii",
"wf_outreach_flag_chan_iv"
);
$deltas = array(
"normal_tot_bal",
"typeA_ct",
"typeB_ct",
"typeC_flag",
"typeD_flag",
"typeE_flag",
"typeF_flag",
"typeG_flag",
);
/**
 * linear regression function
 * @param $x array x-coords
 * @param $y array y-coords
 * @returns array() m=>slope, b=>intercept
 */
function linear_regression($x, $y) {

  // calculate number points
  $n = count($x);
  // calculate sums
  $x_sum = array_sum($x);
  $y_sum = array_sum($y);

  $xx_sum = 0;
  $xy_sum = 0;
  
  for($i = 0; $i < $n; $i++) {
  
    $xy_sum+=($x[$i]*$y[$i]);
    $xx_sum+=($x[$i]*$x[$i]);
    
  }
  
  // calculate slope
  if((($n * $xx_sum) - ($x_sum * $x_sum)) == 0){
		$m = 0;
  }else{
		$m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
  }
  // calculate intercept
  $b = ($y_sum - ($m * $x_sum)) / $n;
    
  // return result
  return array("m"=>$m, "b"=>$b);

}
//calculate based on 12 month period with deltas calculated between months that vary with categories
//note the sql was filtered based on customer id and month id ASC
//Delta calculated using previous month with current month
//X variable is the category variable, Y variable is the delta variable
foreach($categories as $c){
	foreach($deltas as $d){
		$x = array();
		$y = array();
		foreach($result as $r){
			if($r["month"] == 1){
				$prevy = $r["$d"];
				$prevx = $r["$c"];
			}else{
				if($prevy == 0){
					$y[] = ($r["$d"] - $prevy) * 100; //% change
				}else{
					$y[] = (($r["$d"] - $prevy)/ $prevy) * 100; //% change
				}
				$x[] = $prevx;
				$prevy = $r["$d"];
				$prevx = $r["$c"];
			}
		}
		$reg = linear_regression($x, $y);
		$slope = $reg["m"];
		$int = $reg["b"];
		$stmt = $conn->prepare("INSERT INTO `WFGROWTHMODEL`(`CATEGORY`, `DELTA`, `SLOPE`, `INTERCEPT`) VALUES ('$c','$d',$slope,$int)");
		$stmt->execute();
	}
}

//Create bubble chart for Demographics based on total growth based on Chart JS library
/*

data: [
	{
		x: 20,
		y: 30,
		r: 15
	},
	{
		x: 40,
		y: 10,
		r: 10
	}
]
x: demographic A category
y: demographic B category
r: total % growth avg from 0 to 12
*/
// r in chart js is the raw pixel value, this is scaled in two datasets
// one dataset for positive growths and one for negative growths between 5 pixels to 50 pixels
$demo = array(array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0)); //create 2D array then parse into data to insert into database, Demo A 0-5, Demo B 1-5
$cust = 0; //track customer
foreach($result as $r){
	if($r["cust_num"] != $cust){ //new customer
		if($cust != 0){ //do not add first cust 0 because it doesn't exist
			$end = $r["normal_tot_bal"];
			if($start == 0){
				$growth = (($end - $start) / 1) * 100;
			}else{
				$growth = (($end - $start) / $start) * 100;
			}
			$da = intval($demoA);
			$db = intval($demoB);
			if($demo[$da][$db] == 0){
				$demo[$da][$db] = $growth;
			}else{
				$demo[$da][$db] = ($growth + $demo[$da][$db])/ 2;	
			}
		}
		$start = $r["normal_tot_bal"];
		$demoA = $r["cust_demographics_ai"];
		$demoB = $r["cust_demographics_aii"];
		$cust = $r["cust_num"];
	}
}
//scale r
$min = 1000000000;
$max = -100000000;
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$val = $demo[$i][$j];
		if($val > 300){
			$val = 300;
		}
		if($val < -300){
			$val = -300;
		}
		if($val < $min){
			$min = $val;
		}
		if($val > $max){
			$max = $val;
		}
	}
}
$scale = $max - $min;
$poxs = ($max - 0) / $scale; //% positive or negative
$negs = (0 - $min) / $scale;
//parse $demo into data JSON
$bubble = "[";
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$tot = $demo[$i][$j];
		//scaling based on 45 width with interval 5 to 50
		if($tot >= 0){
			if($tot > 300){
				$tot = 300;
			}
			$tot = (($tot / $max) * $poxs * 25) + 5;
			$bubble .= "{x:$i,y:$j,r:$tot},";
		}
	}
}
//remove last comma and finish the JSON then enter into database
$bubble = substr($bubble,0,strlen($bubble)-1);
$bubble .= "]";
$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('positive','Compare Growth of Balance By Each Demographic','$bubble')");
$stmt->execute();
//parse $demo into data JSON
$bubble = "[";
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$tot = $demo[$i][$j];
		//scaling based on 45 width with interval 5 to 50
		if($tot < 0){
			if($tot < -300){
				$tot = -300;
			}
			$tot = (($tot / $min) * $negs * 25) + 5;
			$bubble .= "{x:$i,y:$j,r:$tot},";
		}
	}
}
//remove last comma and finish the JSON then enter into database
$bubble = substr($bubble,0,strlen($bubble)-1);
$bubble .= "]";
$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('negative','Compare Growth of Balance By Each Demographic','$bubble')");
$stmt->execute();

$demo = array(array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0)); //create 2D array then parse into data to insert into database, Demo A 0-5, Demo B 1-5
$cust = 0; //track customer
foreach($result as $r){
	if($r["cust_num"] != $cust){ //new customer
		if($cust != 0){ //do not add first cust 0 because it doesn't exist
			$end = $r["typeA_ct"];
			if($start == 0){
				$growth = (($end - $start) / 1) * 100;
			}else{
				$growth = (($end - $start) / $start) * 100;
			}
			$da = intval($demoA);
			$db = intval($demoB);
			if($demo[$da][$db] == 0){
				$demo[$da][$db] = $growth;
			}else{
				$demo[$da][$db] = ($growth + $demo[$da][$db])/ 2;	
			}
		}
		$start = $r["typeA_ct"];
		$demoA = $r["cust_demographics_ai"];
		$demoB = $r["cust_demographics_aii"];
		$cust = $r["cust_num"];
	}
}
//scale r
$min = 1000000000;
$max = -100000000;
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$val = $demo[$i][$j];
		if($val > 300){
			$val = 300;
		}
		if($val < -300){
			$val = -300;
		}
		if($val < $min){
			$min = $val;
		}
		if($val > $max){
			$max = $val;
		}
	}
}
$scale = $max - $min;
$poxs = ($max - 0) / $scale; //% positive or negative
$negs = (0 - $min) / $scale;
//parse $demo into data JSON
$bubble = "[";
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$tot = $demo[$i][$j];
		//scaling based on 45 width with interval 5 to 50
		if($tot >= 0){
			if($tot > 300){
				$tot = 300;
			}
			$tot = (($tot / $max) * $poxs * 25) + 5;
			$bubble .= "{x:$i,y:$j,r:$tot},";
		}
	}
}
//remove last comma and finish the JSON then enter into database
$bubble = substr($bubble,0,strlen($bubble)-1);
$bubble .= "]";
$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('positive','Compare Growth of Accounts A By Each Demographic','$bubble')");
$stmt->execute();
//parse $demo into data JSON
$bubble = "[";
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$tot = $demo[$i][$j];
		//scaling based on 45 width with interval 5 to 50
		if($tot < 0){
			if($tot < -300){
				$tot = -300;
			}
			$tot = (($tot / $min) * $negs * 25) + 5;
			$bubble .= "{x:$i,y:$j,r:$tot},";
		}
	}
}
//remove last comma and finish the JSON then enter into database
$bubble = substr($bubble,0,strlen($bubble)-1);
$bubble .= "]";
$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('negative','Compare Growth of Accounts A By Each Demographic','$bubble')");
$stmt->execute();
$demo = array(array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0),array(0,0,0,0,0,0)); //create 2D array then parse into data to insert into database, Demo A 0-5, Demo B 1-5
$cust = 0; //track customer
foreach($result as $r){
	if($r["cust_num"] != $cust){ //new customer
		if($cust != 0){ //do not add first cust 0 because it doesn't exist
			$end = $r["typeB_ct"];
			if($start == 0){
				$growth = (($end - $start) / 1) * 100;
			}else{
				$growth = (($end - $start) / $start) * 100;
			}
			$da = intval($demoA);
			$db = intval($demoB);
			if($demo[$da][$db] == 0){
				$demo[$da][$db] = $growth;
			}else{
				$demo[$da][$db] = ($growth + $demo[$da][$db])/ 2;	
			}
		}
		$start = $r["typeB_ct"];
		$demoA = $r["cust_demographics_ai"];
		$demoB = $r["cust_demographics_aii"];
		$cust = $r["cust_num"];
	}
}
//scale r
$min = 1000000000;
$max = -100000000;
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$val = $demo[$i][$j];
		if($val > 300){
			$val = 300;
		}
		if($val < -300){
			$val = -300;
		}
		if($val < $min){
			$min = $val;
		}
		if($val > $max){
			$max = $val;
		}
	}
}
$scale = $max - $min;
$poxs = ($max - 0) / $scale; //% positive or negative
$negs = (0 - $min) / $scale;
//parse $demo into data JSON
$bubble = "[";
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$tot = $demo[$i][$j];
		//scaling based on 45 width with interval 5 to 50
		if($tot >= 0){
			if($tot > 300){
				$tot = 300;
			}
			$tot = (($tot / $max) * $poxs * 25) + 5;
			$bubble .= "{x:$i,y:$j,r:$tot},";
		}
	}
}
//remove last comma and finish the JSON then enter into database
$bubble = substr($bubble,0,strlen($bubble)-1);
$bubble .= "]";
$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('positive','Compare Growth of Accounts B By Each Demographic','$bubble')");
$stmt->execute();
//parse $demo into data JSON
$bubble = "[";
for($i = 0; $i < 6; $i++){
	for($j = 1; $j < 6; $j++){
		$tot = $demo[$i][$j];
		//scaling based on 45 width with interval 5 to 50
		if($tot < 0){
			if($tot < -300){
				$tot = -300;
			}
			$tot = (($tot / $min) * $negs * 25) + 5;
			$bubble .= "{x:$i,y:$j,r:$tot},";
		}
	}
}
//remove last comma and finish the JSON then enter into database
$bubble = substr($bubble,0,strlen($bubble)-1);
$bubble .= "]";
$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('negative','Compare Growth of Accounts B By Each Demographic','$bubble')");
$stmt->execute();

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
//create another bubble chart for outreach types with outreach channels based on total growth
//Calculate a persistent model by averaging the outreach per month coupled on total growth during the 12 month period
foreach($category as $c){
	$cat = array();
	foreach($channels as $ch){
		foreach($measure as $m){
			$cust = 0;
			foreach($result as $r){
				if($r["cust_num"] != $cust){ //new customer
					if($cust != 0){ //do not add first cust 0 because it doesn't exist
						$end = $r["$m"];
						if($start == 0){
							$growth = (($end - $start) / 1) * 100;
						}else{
							$growth = (($end - $start) / $start) * 100;
						}
						$addc += $r["$c"];
						$avgc = $addc / 12;
						$addch += $r["$ch"];
						$avgch = $addch / 12;
						//add into $cat array for x -> type ,y -> channel,and r -> % total growth avg
						if(!isset($cat["$avgc"]["$avgch"])){
							$cat["$avgc"]["$avgch"] = $growth;
						}else{
							$cat["$avgc"]["$avgch"] = ($growth + $cat["$avgc"]["$avgch"]) / 2;
						}
					}
					$start = $r["$m"];
					$addc = $r["$c"];
					$addch = $r["$ch"];
					$cust = $r["cust_num"];
				}else{
					$addc += $r["$c"];
					$addch += $r["$ch"];
				}
			}
			//scale r
			$min = 1000000000;
			$max = -100000000;
			foreach($cat as $val => $arr){
				foreach($arr as $v => $t){
					$val2 = $t;
					if($val2 > 300){
						$val2 = 300;
					}
					if($val2 < -300){
						$val2 = -300;
					}
					if($val2 < $min){
						$min = $val2;
					}
					if($val2 > $max){
						$max = $val2;
					}
				}
			}
			$scale = $max - $min;
			$poxs = ($max - 0) / $scale; //% positive or negative
			$negs = (0 - $min) / $scale;
			
			$bubble = "[";
			foreach($cat as $val => $arr){
				foreach($arr as $v => $t){
					$tot = $t;
					if($tot >= 0){
						if($tot > 300){
							$tot = 300;
						}
						$tot = (($tot / $max) * $poxs * 25) + 5;
						$bubble .= "{x:$val,y:$v,r:$tot},";
					}
				}
			}
			$bubble = substr($bubble,0,strlen($bubble)-1);
			$bubble .= "]";
			$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('positive','Compare Growth of $m based on Outreach Type $c with Outreach Channel $ch','$bubble')");
			$stmt->execute();
			
			$bubble = "[";
			foreach($cat as $val => $arr){
				foreach($arr as $v => $t){
					$tot = $t;
					if($tot < 0){
						if($tot < -300){
							$tot = -300;
						}
						$tot = (($tot / $min) * $negs * 25) + 5;
						$bubble .= "{x:$val,y:$v,r:$tot},";
					}
				}
			}
			$bubble = substr($bubble,0,strlen($bubble)-1);
			$bubble .= "]";
			$stmt = $conn->prepare("INSERT INTO `WFCHARTS`(`TYPE`, `DESCRIPTION`, `DATA`) VALUES ('negative','Compare Growth of $m based on Outreach Type $c with Outreach Channel $ch','$bubble')");
			$stmt->execute();
			
		}
	}
}

?>