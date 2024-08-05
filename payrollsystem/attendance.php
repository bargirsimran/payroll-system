<?php include('db_connect.php') ?>
		<div class="container-fluid " >
			<div class="col-lg-12">
				
				<br />
				<br />
				<div class="card">
					<div class="card-header">
						<span><b>Attendance List</b></span>
						<button class="btn btn-primary btn-sm btn-block col-md-3 float-right" type="button" id="new_attendance_btn"><span class="fa fa-plus"></span> Add Attendance</button>
					</div>
					<div class="card-body">
						<table id="table" class="table table-bordered table-striped">
							<colgroup>
								<col width="10%">
								<col width="20%">
								<col width="30%">
								<col width="30%">
								<col width="10%">
							</colgroup>
							<thead>
								<tr>
									<th>Date</th>
									<th>Employee No</th>
									<th>Name</th>
									<th>Time Record</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$att=$conn->query("SELECT a.*,e.employee_no, concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename FROM attendance a inner join employee e on a.employee_id = e.id order by UNIX_TIMESTAMP(datetime_log) asc  ") or die(mysqli_error());
									$lt_arr = array(1 => " Time-in AM",2=>"Time-out AM",3 => " Time-in PM",4=>"Time-out PM");
									while($row=$att->fetch_array()){
										$date = date("Y-m-d",strtotime($row['datetime_log']));
										$attendance[$row['employee_id']."_".$date]['details'] = array("eid"=>$row['employee_id'],"name"=>$row['ename'],"eno"=>$row['employee_no'],"date"=>$date);
										if($row['log_type'] == 1 || $row['log_type'] == 3){
											if(!isset($attendance[$row['employee_id']."_".$date]['log'][$row['log_type']]))
											$attendance[$row['employee_id']."_".$date]['log'][$row['log_type']] = array('id'=>$row['id'],"date" =>  $row['datetime_log']);
										}else{
											$attendance[$row['employee_id']."_".$date]['log'][$row['log_type']] =array('id'=>$row['id'],"date" =>  $row['datetime_log']);
										}
										}
										foreach ($attendance as $key => $value) {
								?>
								<tr>
									<td><?php echo date("M d,Y",strtotime($attendance[$key]['details']['date'])) ?></td>
									<td><?php echo $attendance[$key]['details']['eno'] ?></td>
									<td><?php echo $attendance[$key]['details']['name'] ?></td>
									<td>
										<div class="row">
											
									<?php 
									$att_ids = array();
									foreach($attendance[$key]['log'] as $k => $v) :
									 ?>
									 <div class="col-sm-6" style="">
										<p>
											<small><b><?php echo $lt_arr[$k].": <br/>" ?>
												

											<?php echo (date("h:i A",strtotime($attendance[$key]['log'][$k]['date'])))  ?> </b>
											<span class="badge badge-danger rem_att" data-id="<?php echo $attendance[$key]['log'][$k]['id'] ?>"><i class="fa fa-trash"></i></span>
										</small>
										</p>
										</div>
									<?php endforeach; ?>
										</div>

									</td>
									<td>
										<center>
										<button class="btn btn-sm btn-outline-danger remove_attendance" data-id="<?php echo $key ?>" type="button"><i class="fa fa-trash"></i></button>
										</center>
									</td>
								</tr>
								<?php
										}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<style>
			td p{
				margin: unset;
			}
			.rem_att{
				cursor: pointer;
			}
		</style>
			
		
		
	<script type="text/javascript">
		$(document).ready(function(){
			$('#table').DataTable();
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function(){

			

			
			$('.edit_attendance').click(function(){
				var $id=$(this).attr('data-id');
				uni_modal("Edit Employee","manage_attendance.php?id="+$id)
				
			});
			$('.view_attendance').click(function(){
				var $id=$(this).attr('data-id');
				uni_modal("Employee Details","view_attendance.php?id="+$id,"mid-large")
				
			});
			$('#new_attendance_btn').click(function(){
				uni_modal("New Time Record/s","manage_attendance.php",'mid-large')
			})
			$('.remove_attendance').click(function(){
				var d = '"'+($(this).attr('data-id')).toString()+'"';
				_conf("Are you sure to delete this employee's time log record?","remove_attendance",[d])
			})
			$('.rem_att').click(function(){
				var $id=$(this).attr('data-id');
				_conf("Are you sure to delete this time log?","rem_att",[$id])
			})
		});
		function remove_attendance(id){
				// console.log(id)
				// return false;
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_employee_attendance',
				method:"POST",
				data:{id:id},
				error:err=>console.log(err),
				success:function(resp){
						if(resp == 1){
							alert_toast("Selected employee's time log data successfully deleted","success");
								setTimeout(function(){
								location.reload();

							},1000)
						}
					}
			})
		}
		function rem_att(id){
				
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_employee_attendance_single',
				method:"POST",
				data:{id:id},
				error:err=>console.log(err),
				success:function(resp){
						if(resp == 1){
							alert_toast("Selected employee's time log data successfully deleted","success");
								setTimeout(function(){
								location.reload();

							},1000)
						}
					}
			})
		}
	</script>
