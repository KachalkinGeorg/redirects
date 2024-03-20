<form method="post" action="">
	
	<div class="table-responsive">
		<table class="table table-striped">
			<tr>
				<td width="50%">Количество записей<small class="form-text text-muted">Только для списка редиректов</small></td>
				<td width="50%">
					<div class="input-group mb-3">
						<input name="num_news" type="number" title="Количество записей в новостях" value="{{num_news.print}}" class="form-control" style="max-width:150px; text-align: center;"/>
						<div class="input-group-prepend input-group-append">
							<label class="input-group-text"><i class="fa fa-list"></i></label>
						</div>{{num_news.error}}
					</div>
				</td>
			</tr>
		</table>
	</div>

	<div class="panel-footer form-group text-center" style="margin: 0 -20px -20px;">
		<button type="submit" name="submit" class="btn btn-outline-success">сохранить</button>
	</div>	

</form>