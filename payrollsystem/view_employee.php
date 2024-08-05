<?php include 'db_connect.php' ?>

<?php
$emp = $conn->query("SELECT e.*,d.name as dname,p.name as pname FROM employee e inner join department d on e.department_id = d.id inner join position p on e.position_id = p.id where e.id =".$_GET['id'])->fetch_array();
	foreach($emp as $k=>$v){
		$$k=$v;
	}
?>

<div class="contriner-fluid">
	<div class="col-md-12">
		<h5><b><small>Employee ID :</small><?php echo $employee_no ?></b></h5>
		<h4><b><small>Name: </small><?php echo ucwords($lastname.", ".$firstname." ",$middlename) ?></b></h4>
		<p><b>Department : <?php echo ucwords($dname) ?></b></p>
		<p><b>Position : <?php echo ucwords($pname) ?></b></p>
		<hr class="divider">
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<span><b>Allowances</b></span>
						<button class="btn btn-primary btn-sm float-right" style="padding: 3px 5px" type="button" id="new_allowance"><i class="fa fa-plus"></i></button>
					</div>
					<div class="card-body">
						<ul class="list-group">
							<?php
							$allowances = $conn->query("SELECT ea.*,a.allowance as aname FROM employee_allowances ea inner join allowances a on a.id = ea.allowance_id where ea.employee_id=".$_GET['id']." order by ea.type asc,date(ea.effective_date) asc, a.allowance asc ");
							$t_arr = array(1=>"Monthly",2=>"Semi-Monthly",3=>"Once");
							while($row=$allowances->fetch_assoc()):
							?>
						  <li class="list-group-item d-flex justify-content-between align-items-center alist" data-id="<?php echo $row['id'] ?>">
						  	<span>
						    <p><small><?php echo $row['aname'] ?> Allowance</small></p>
						    <p><small>Type: <?php echo $t_arr[$row['type']] ?></small></p>
						    <?php if($row['type'] == 3): ?>
						    <p><small>Effective: <?php echo date("M d,Y",strtotime($row['effective_date'])) ?></small></p>
						    <?php endif; ?>
						    </span>
						    <button class="badge badge-danger badge-pill btn remove_allowance" type="button"  data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
						  </li>
						<?php endwhile; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<span><b>Deductions</b></span>
						<button class="btn btn-primary btn-sm float-right" style="padding: 3px 5px" type="button" id="new_deduction"><i class="fa fa-plus"></i></button>
					</div>
					<div class="card-body">
						<ul class="list-group">
							<?php
							$deductions = $conn->query("SELECT ea.*,d.deduction as dname FROM employee_deductions ea inner join deductions d on d.id = ea.deduction_id where ea.employee_id=".$_GET['id']." order by ea.type asc,date(ea.effective_date) asc, d.deduction asc ");
							$t_arr = array(1=>"Monthly",2=>"Semi-Monthly",3=>"Once");
							while($row=$deductions->fetch_assoc()):
							?>
						  <li class="list-group-item d-flex justify-content-between align-items-center dlist" data-id="<?php echo $row['id'] ?>">
						  	<span>
						    <p><small><?php echo $row['dname'] ?></small></p>
						    <p><small>Type: <?php echo $t_arr[$row['type']] ?></small></p>
						    <?php if($row['type'] == 3): ?>
						    <p><small>Effective: <?php echo date("M d,Y",strtotime($row['effective_date'])) ?></small></p>
						    <?php endif; ?>
						    </span>
						    <button class="badge badge-danger badge-pill btn remove_deduction" type="button"  data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
						  </li>
						<?php endwhile; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		</div>
	</div>

</div>
<style type="text/css">
	.list-group-item>span>p{
		margin:unset;
	}
	.list-group-item>span>p>small{
		font-weight: 700
	}
</style>
<script>
	$('#new_allowance').click(function(){
		uni_modal("New Allowace for <?php echo $employee_no.' - '.ucwords($lastname.", ".$firstname." ",$middlename) ?>",'manage_employee_allowances.php?id=<?php echo $_GET['id'] ?>','mid-large')
	})
	$('#new_deduction').click(function(){
		uni_modal("New Deduction for <?php echo $employee_no.' - '.ucwords($lastname.", ".$firstname." ",$middlename) ?>",'manage_employee_deductions.php?id=<?php echo $_GET['id'] ?>','mid-large')
	})
	$('.remove_allowance').click(function(){
				_conf("Are you sure to delete this allowance?","remove_allowance",[$(this).attr('data-id')])
			})
function remove_allowance(id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_employee_allowance',
				method:"POST",
				data:{id:id},
				error:err=>console.log(err),
				success:function(resp){
						if(resp == 1){
							alert_toast("Selected allowance successfully deleted","success");
							$('.alist[data-id="'+id+'"]').remove()
							$('#confirm_modal').modal('hide')
							end_load()
						}
					}
			})
		}
		$('.remove_deduction').click(function(){
				_conf("Are you sure to delete this deduction?","remove_deduction",[$(this).attr('data-id')])
			})
function remove_deduction(id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_employee_deduction',
				method:"POST",
				data:{id:id},
				error:err=>console.log(err),
				success:function(resp){
						if(resp == 1){
							alert_toast("Selected deduction successfully deleted","success");
							$('.dlist[data-id="'+id+'"]').remove()
							$('#confirm_modal').modal('hide')
							end_load()
						}
					}
			})
		}
</script>