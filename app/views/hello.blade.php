@extends('layout')

@section('vendor-css')
	<link href='//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' rel='stylesheet'>
@stop

@section('javascript')
	<script src='/js/home.js'></script>
@stop

@section('content')

@if($host_warning)

<div class="panel panel-danger" style='margin-bottom: 60px;'>
	<div class="panel-heading">
		<h3 class="panel-title">Domain Notificiation</h3>
	</div>
	<div class="panel-body">
		You're currently using caas.thokk.com.  Please use <a href='http://craftingasaservice.com/'>http://craftingasaservice.com/</a> instead.  Thanks!
	</div>
</div>
@endif

<div class='home jumbotron'>
	<h1>Crafting as a Service</h1>
	<p>Crafting information and planning for FFXIV: ARR</p>
</div>

<div class='row'>
	<div class='col-lg-4'>
		<h2>Equipment Calculator</h2>
		<p>
			Want to know what equipment you can craft at a certain level for your class?  
			Use this tool to select your disciple and level range.
		</p>
		<a href='/equipment' class='btn btn-primary'>Gear me out &raquo;</a>
	</div>
	<div class='col-lg-4'>
		<h2>Crafting Calculator</h2>
		<p>
			Want to get everything you need before trying to level Weaver from levels 5 to 10?  
			Use this tool to select your craft and level range.
		</p>
		<a href='/crafting' class='btn btn-primary'>Vocationalize &raquo;</a>
	</div>
	<div class='col-lg-4'>
		<h2>Gathering Calculator</h2>
		<p>
			Ever ask yourself when mining "What should I be digging up?".  
			Find the most efficient use of your pickaxe and hatchet with this tool!
		</p>
		<a href='/gathering' class='btn btn-primary'>Gather-rific &raquo;</a>
	</div>
</div>

@stop