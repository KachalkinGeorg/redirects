<div class="navbar-default navbar-component">
<ul class="nav nav-tabs nav-fill mb-3 d-md-flex d-block">
	<li class="nav-item"><a class="nav-link {{active1}}" href="?mod=extra-config&plugin=redirects"><i class="fa fa-cog"></i>&nbsp;Общие</a></li>
	<li class="nav-item"><a class="nav-link {{active2}}" href="?mod=extra-config&plugin=redirects&action=list_redirects"><i class="fa fa-tasks"></i>&nbsp;Список</a></li>
	<li class="nav-item"><a class="nav-link {{active3}}" href="?mod=extra-config&plugin=redirects&action=about"><i class="fa fa-exclamation-circle"></i>&nbsp;О плагине</a></li>
	</ul>
</div>

<div class="panel panel-default">
	<div class="panel-heading">{{global}}
		<div class="panel-head-right">{{header}}</div>
	</div>
	<div class="panel-body">
		{{entries}}
	</div>
</div>