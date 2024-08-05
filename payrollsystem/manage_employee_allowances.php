<?php include 'db_connect.php' ?>

<?php ?>

<div class="container-fluid">
	<form action="" id="employee-allowance">
		<input type="hidden" name="employee_id" value="<?php echo $_GET['id'] ?>">
		<div class="row form-group">
			<div class="col-md-5">
				<label for="" class="control-label">Allowance</label>
				<select id="allowance_id" class="borwser-default select2">
					<option value=""></option>
					<?php 
					$allowance = $conn->query("SELECT * FROM allowances order by allowance asc");
					while($row = $allowance->fetch_assoc()):
					?>
						<option value="<?php echo $row['id'] ?>"><?php echo $row['allowance'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="col-md-3">
				<label for="" class="control-label">Type</label>
				<select id="type" class="borwser-default custom-select">
					<option value="1">Monthly</option>
					<option value="2">Semi-Monthly</option>
					<option value="3">Once</option>
				</select>
			</div>
			<div class="col-md-3" style="display: none" id="dfield">
				<label for="" class="control-label">Effective Date</label>
				<input type="date" id="edate" class="form-control">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-5">
				<label for="" class="control-label">Amount</label>
				<input type="number" id="amount" class="form-control text-right" step="any" >
			</div>	
			<div class="col-md-2 offset-md-2">
				<label for="" class="control-label">&nbsp</label>
				<button class="btn btn-primary btn-block btn-sm" type="button" id="add_list"> Add to List</button>
			</div>	
		</div>
		<hr>
		<div class="row">
			<table class="table table-bordered" id="allowance-list">
				<thead>
					<tr>
						<th class="text-center">
							Allowance
						</th>
						<th class="text-center">
							Type
						</th>
						<th class="text-center">
							Amount
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
<div id="tr_clone" style="display: none">
	<table>
		<tr>
			<td>
				<input type="hidden" name="allowance_id[]">
				<p class="allowance"></p>
			</td>
			<td>
				<input type="hidden" name="type[]">
				<p class="type"></p>
			</td>
			<td>
				<input type="hidden" name="amount[]">
				<p class="amount"></p>
			</td>
			<td>
				<input type="hidden" name="effective_date[]">
				<p class="edate"></p>
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
	$('#type').change(function(){
		if($(this).val() == 3){
			$('#dfield').show()
		}else{
			$('#dfield').hide()
		}
	})
	$('#add_list').click(function(){
		var allowance_id = $('#allowance_id').val(),
			type = $('#type').val(),
			amount = $('#amount').val(),
			edate = $('#edate').val();
			console
		var tr = $('#tr_clone tr').clone()
		tr.find('[name="allowance_id[]"]').val(allowance_id)
		tr.find('[name="type[]"]').val(type)
		tr.find('[name="effective_date[]"]').val(edate)
		tr.find('[name="amount[]"]').val(amount)
		tr.find('.allowance').html($('#allowance_id option[value="'+allowance_id+'"]').html())
		tr.find('.type').html($('#type option[value="'+type+'"]').html())
		tr.find('.amount').html(amount)
		tr.find('.edate').html(edate)
		$('#allowance-list tbody').append(tr)
		$('#allowance_id').val('').select2({
			placeholder:"Select here",
			width:"100%"
		})
		$('#type').val('')
		$('#amount').val('')
		$('#edate').val('')

	})
	$(document).ready(function(){
		$('#employee-allowance').submit(function(e){
				e.preventDefault()
				start_load();
			$.ajax({
				url:'ajax.php?action=save_employee_allowance',
				method:"POST",
				data:$(this).serialize(),
				error:err=>console.log(),
				success:function(resp){
						if(resp == 1){
							alert_toast("Employee's data successfully saved","success");
							end_load()
							uni_modal("Employee Details",'view_employee.php?id=<?php echo $_GET['id'] ?>','mid-large')
						}
				}
			})
		})
	})
</script>