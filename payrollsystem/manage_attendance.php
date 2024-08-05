<?php include 'db_connect.php' ?>

<?php ?>

<div class="container-fluid">
	<div class="col-lg-12">
	<form action="" id="employee-attendance">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label">Employee</label>
				<select id="employee_id" class="borwser-default select2">
					<option value=""></option>
					<?php 
					$employee = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as ename FROM employee order by concat(lastname,', ',firstname,' ',middlename) asc");
					while($row = $employee->fetch_assoc()):
					?>
						<option value="<?php echo $row['id'] ?>"><?php echo $row['ename'] . ' | '. $row['employee_no'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="" class="control-label">Type</label>
				<select id="type" class="borwser-default custom-select">
					<option value="1">Time-in AM</option>
					<option value="2">Time-out AM</option>
					<option value="3">Time-in PM</option>
					<option value="4">Tim-out PM</option>
				</select>
			</div>
			<div class="col-md-3">
				<label for="" class="control-label">Date</label>
				<input type="text" id="adate" class="form-control datetimepicker" autocomplete="off">
			</div>
			<div class="col-md-2">
				<label for="" class="control-label">&nbsp</label>
				<button class="btn btn-primary btn-block btn-sm" type="button" id="add_list"> Add to List</button>
			</div>	
		</div>
		
		<hr>
		<div class="row">
			<table class="table table-bordered" id="attendance-list">
				<thead>
					<tr>
						<th class="text-center">
							Employee
						</th>
						<th class="text-center">
							Type
						</th>
						<th class="text-center">
							Date
						</th>
						<th class="text-center">
							
						</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</form>
	</div>
</div>
<div id="tr_clone" style="display: none">
	<table>
		<tr>
			<td>
				<input type="hidden" name="employee_id[]">
				<p class="attendance"></p>
			</td>
			<td>
				<input type="hidden" name="log_type[]">
				<p class="type"></p>
			</td>
			
			<td>
				<input type="hidden" name="datetime_log[]">
				<p class="adate"></p>
			</td>
			<td class="text-center">
				<button class="btn-sm btn-danger" type="button" onclick="$(this).closest('tr').remove()"><i class="fa fa-trash"></i></button>
			</td>
		</tr>
	</table>
</div>

<script>
	$('.select2').select2({
		placeholder:"Select here",
		width:"100%"
	})
	$('.datetimepicker').datetimepicker({
		format:"y-m-d H:i "
	})
	
	$('#add_list').click(function(){
		var employee_id = $('#employee_id').val(),
			type = $('#type').val(),
			adate = $('#adate').val();
			console
		var tr = $('#tr_clone tr').clone()
		tr.find('[name="employee_id[]"]').val(employee_id)
		tr.find('[name="log_type[]"]').val(type)
		tr.find('[name="datetime_log[]"]').val(adate)
		tr.find('.attendance').html($('#employee_id option[value="'+employee_id+'"]').html())
		tr.find('.type').html($('#type option[value="'+type+'"]').html())
		tr.find('.adate').html(adate)
		$('#attendance-list tbody').append(tr)
		$('#employee_id').val('').select2({
			placeholder:"Select here",
			width:"100%"
		})
		$('#type').val('')
		$('#adate').val('')

	})
	$(document).ready(function(){
		$('#employee-attendance').submit(function(e){
				e.preventDefault()
				start_load();
			$.ajax({
				url:'ajax.php?action=save_employee_attendance',
				method:"POST",
				data:$(this).serialize(),
				error:err=>console.log(),
				success:function(resp){
						if(resp == 1){
							alert_toast("Attendance data successfully saved","success");
							setTimeout(function(){
								location.reload()
							},1000)
						}
				}
			})
		})
	})
</script>