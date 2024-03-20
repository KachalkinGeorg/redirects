<table class="table table-sm mb-0">
	<thead>
	<tr>
		<td style="padding:4px;" class="navigation" nowrap><strong>#</strong></td>
		<td style="padding:4px;" class="navigation"><strong>Старый адрес</strong></td>
		<td style="padding:4px;" class="navigation"><strong>Новый адрес</strong></td>
		<td style="padding:4px;" class="navigation"><center><strong>Статус</strong></center></td>
		<td style="padding:4px;" class="navigation"><center><strong>Действие</strong></center></td>
	</tr>
	</thead>
</table>

		<div class="card-footer" style="margin: 0 -20px -20px;">
			<div class="row">
				<div class="col-lg-9 mb-2 mb-lg-0">{{ pagesss }}</div>
						<div class="input-group-append">
							<!-- <button type="submit" class="btn btn-outline-warning">{{ lang.editnews['submit'] }}</button> -->
							<input type="button" class="btn btn-outline-success" onmousedown="javascript:window.location.href='{{admin_url}}/admin.php?mod=extra-config&plugin=redirects&action=add_redirects'" value="Добавить редирект" />
						</div>
		</div>
		</div>