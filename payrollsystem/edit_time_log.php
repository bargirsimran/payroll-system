<?php include ("db_connect.php"); ?>
<?php
$date = explode('_',$id);
$lt_arr = array(1 => " Time-in AM",2=>"Time-out AM",3 => " Time-in PM",4=>"Time-out PM");
$dt = date("Y-m-d",strtotime($date[1]));
$emp = $conn->query("SELECT concat(lastname,', ',firstname,' ',middlename) as enamem,employee_no from employee where id =".$date[0])->fetch_array()['ename'];
$qry = $conn->db->query("SELECT * FROM attendance where employee_id = '".$date[0]."' and date(datetime_log) ='$dt' order by UNIX_TIMESTAMP(datetime_log) asc ");
while($row=$qry->fetch_assoc()){
	if($row['log_type'] == 1 || $row['log_type'] == 2){
		if(isset($att[$row['log_type']]))
			$att[$row['log_type']] = $row;
	}else{
			$att[$row['log_type']] = $row;
	}
}
?>
<div class="container-fluid">
	<div class="col-ld-12">
		<div class="row">
			<h4><b><?php echo ucwords($emp['ename']).' | '.$emp['employee_no'] ?></b></h4>
		</div>
		<hr>
		<?php foreach($att as $k => $v): ?>
		<div class="row">
			<p><b><?php echo $lt_arr[$k] ?></b></p>
		</div>
		<hr>
		<div class="row form-group">
			.col-md-4
		</div>
		<?php endforeach; ?>
	</div>
</div>

<style>
	#uni_modal .modal-header{
		display: none
	}
</style>