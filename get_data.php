<?php
	session_start ();    
	
	$type = $_POST['type'];  
	$gene_symbol = $_POST['gene_symbol'];

	
	$host = 'XXXXXX';
	$user = 'XXXXXX';
	$pass = 'XXXXXX';
	$db = 'XXXXXX';
	$table = 'IFN_Network';
	$table1 = 'IFN_Network_Maximum';
	
	$con = mysql_connect($host, $user, $pass) or die("Can not connect." . mysql_error());
	$db_selected = mysql_select_db($db,$con);
	if($type==1){
		$query = "SELECT Target, Value FROM ".$table." WHERE Regulator = '".$gene_symbol."' ORDER BY Value DESC";    
	}
	else{
		$query = "SELECT Regulator, Value FROM ".$table." WHERE Target = '".$gene_symbol."' ORDER BY Value DESC";
	}
	//echo $query;                                                                    
	$result = mysql_query($query,$con);
	//echo $result;
	
	$query1 = "SELECT Gene_Symbol, Value, Cluster FROM ".$table1." WHERE 1";   
	$result1 = mysql_query($query1,$con);
	
	
	$max_v = array();
	$cluster = array();
	$json = array();
	while ($r1 = mysql_fetch_row($result1)) {
		$max_v[$r1[0]] = floatval($r1[1]);
		$cluster[$r1[0]] = intval($r1[2]);
	} 
	
	while ($r = mysql_fetch_row($result)) {
		array_push($json, array('gene' => $r[0], 'start' => 0, 'value' => floatval($r[1]), 'type' => 'data', 'cluster' => $cluster[$r[0]]));	
		array_push($json, array('gene' => $r[0], 'start' => floatval($r[1]), 'value' => $max_v[$r[0]], 'type' => 'max', 'cluster' => 0));	
	} 
	
	echo json_encode($json);    

?>
