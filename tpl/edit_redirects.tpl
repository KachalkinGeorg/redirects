<form method='post' action="">
	<div class="tab-content">
		<table class="table table-sm mb-0">
			<tr>
				<td><label style="padding: .375rem 0rem .75rem 0rem;">Старый адрес:</label></td>
				<td>
					<div class="input-group mb-3">
						<div class="input-group-prepend input-group-append">
							<label class="input-group-text"><i class="fa fa-thumbs-down"></i></label>
						</div>
						<input type="text" name="old_url" placeholder="старый адрес..." value="{{ old_url }}" class="form-control" style="max-width:350px;" required="required" />
					</div>
				</td>
				<td><label style="padding: .375rem 0rem .75rem 0rem;">Новый адрес:</label></td>
				<td>
					<div class="input-group mb-3">
						<div class="input-group-prepend input-group-append">
							<label class="input-group-text"><i class="fa fa-thumbs-up"></i></label>
						</div>
						<input type="text" name="new_url" placeholder="новый адрес..." value="{{ new_url }}" class="form-control" style="max-width:350px;" required="required" />
					</div>
				</td>
				<td><label style="padding: .375rem 0rem .75rem 0rem;">Редирект активен</label></td>
				<td>
					<div class="input-group mb-3">
						<input class="icheck" type="checkbox" name="active" id="active" value="1" {{ active }}>
					</div>
				</td>
			</tr>				
		</table>
			
		<div class="panel-footer form-group text-center" style="margin: 0 -20px -20px;">
			<input type="submit" value="Сохранить" name="submit" class="btn btn-outline-success">
		</div>		
	</div>
</form>