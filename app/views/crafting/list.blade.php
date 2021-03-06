@extends('layout')

@section('vendor-css')
	<link href='/css/bootstrap-switch.css' rel='stylesheet'>
	<link href='/css/bootstrap-tour.min.css' rel='stylesheet'>
@stop

@section('javascript')
	<script type='text/javascript' src='http://xivdb.com/tooltips.js'></script>
	<script type='text/javascript' src='/js/crafting.js'></script>
	<script type='text/javascript' src='/js/bootstrap-tour.min.js'></script>
	<script type='text/javascript' src='/js/bootstrap-switch.js'></script>
@stop

@section('content')

<a href='#' id='start_tour' class='start btn btn-primary pull-right' style='margin-top: 12px;'>
	<i class='glyphicon glyphicon-play'></i>
	Start Tour
</a>

<h1>
	@if(isset($job))
	{{ implode(' ', explode(',', $desired_job)) }} 
	Recipes between Levels {{ $start }} and {{ $end }}
	@else
	Custom Recipe List
	@endif
</h1>

<div class='row'>
	<div class='col-sm-8 col-sm-push-4 item-list'>
		<h3>
			But first, obtain these items
		</h3>
		<table class='table table-bordered table-striped table-responsive text-center' id='obtain-these-items'>
			<thead>
				<tr>
					<th class='invisible'>&nbsp;</th>
					<th class='text-center'>Item</th>
					<th class='text-center'>Needed</th>
					<th class='text-center'>Can be Bought</th>
					<th class='text-center'>Source</th>
					<th class='text-center'><input type='checkbox' disabled='disabled' checked='checked'></th>
				</tr>
			</thead>
			@foreach($reagent_list as $section => $list)
			<?php if (empty($list)) continue; ?>
			<tbody id='{{ $section }}-section'>
				<tr>
					<th colspan='6'>
						<button class='btn btn-default pull-right glyphicon glyphicon-chevron-down collapse'></button>
						Origin: {{ $section }}
					</th>
				</tr>
				@foreach($list as $level => $reagents)
				<?php $i = 0; ?>
				@foreach($reagents as $reagent)
				<?php $item =& $reagent['item']; ?>
				<tr class='reagent'>
					@if($i++ == 0)
					<th rowspan='{{ count($reagents) }}' class='text-center' style='vertical-align: middle; background-color: #f5f5f5;'>
						@if($level != 0 && $section != 'Bought')
						{{ $level }}
						@endif
					</th>
					@endif
					<td class='text-left'>
						@if($section == 'Crafted')
						@foreach($item->recipes as $recipe)
						<a href='http://xivdb.com/?recipe/{{ $recipe->id }}' target='_blank'>
							<img src='/img/items/{{ $recipe->icon ?: '../noitemicon.png' }}' style='margin-right: 5px;'>{{ $recipe->name }}
						</a>
						<?php break; ?>
						@endforeach
						@else
						<a href='http://xivdb.com/{{ $item->href }}' target='_blank'>
							<img src='/img/items/{{ $item->icon ?: '../noitemicon.png' }}' style='margin-right: 5px;'>{{ $item->name }}
						</a>
						@endif
					</td>
					<td>
						{{ $reagent['make_this_many'] }}
					</td>
					<td>
						@if($item->vendors)
						<div{{ $reagent['self_sufficient'] ? ' class="opaque"' : '' }}>
							<img src='/img/coin.png' class='stat-vendors' width='24' height='24'>
							{{ number_format($item->gil) }}
						</div>
						@endif
					</td>
					<td class='crafted_gathered'>
						@foreach($item->jobs as $reagent_job)
						<?php if ( ! in_array($reagent_job->disciple, array('DOH', 'DOL'))) continue; ?>
						<span class='alert alert-{{ $reagent_job->abbreviation == $reagent['self_sufficient'] ? 'success' : 'info' }}'>
							<img src='/img/classes/{{ $reagent_job->abbreviation }}.png' rel='tooltip' title='{{ $job_list[$reagent_job->abbreviation] }}'>
						</span>
						@endforeach
						@foreach($item->recipes as $recipe)
						<span class='alert alert-{{ $recipe->job->abbreviation == $reagent['self_sufficient'] ? 'success' : 'warning' }}'>
							<img src='/img/classes/{{ $recipe->job->abbreviation }}.png' rel='tooltip' title='{{ $job_list[$recipe->job->abbreviation] }}'>
						</span>
						@endforeach
					</td>
					<th class='text-center'>
						<input type='checkbox'>
					</th>
				</tr>
				@endforeach
				@endforeach
			</tbody>
			@endforeach
		</table>
	</div>
	<div class='col-sm-4 crafting-list col-sm-pull-8'>
		<h3>
			<button class='btn btn-default pull-right glyphicon glyphicon-chevron-down collapse'></button>
			What you'll be crafting
		</h3>

		<div class='recipe-holder' id='recipe-list'>
			@foreach($recipes as $recipe)
			<div class='well'>
				<a class='close' rel='tooltip' title='Level'>
					{{ $recipe->level }}
				</a>
				<div class='pull-right' style='clear: right;'>
				@if( ! isset($job))
					<img src='/img/classes/{{ $recipe['abbreviation'] }}.png' rel='tooltip' title='{{ $job_list[$recipe['abbreviation']] }}'>
				@endif
				@if($include_quests && isset($recipe->item->quest[0]))
					<img src='/img/{{ $recipe->item->quest[0]->quality ? 'H' : 'N' }}Q.png' rel='tooltip' title='Turn in {{ $recipe->item->quest[0]->amount }}{{ $recipe->item->quest[0]->quality ? ' (HQ)' : '' }} to the Guildmaster{{ $recipe->item->quest[0]->notes ? ', see bottom for note' : '' }}' width='24' height='24'>
				@endif
				</div>
				<a href='http://xivdb.com/?recipe/{{ $recipe->id }}' class='recipe-name' target='_blank'>
					<img src='/img/items/{{ $recipe->icon ?: '../noitemicon.png' }}' style='margin-right: 5px;'>{{ $recipe->name }}
				</a>
				<p><small>Yields: {{ $recipe->yields }}</small></p>
				{{--@foreach($recipe->reagents as $reagent)

				@endforeach--}}
			</div>
			@endforeach
		</div>
	</div>
</div>

<a name='options'></a>

<div class='panel panel-info'>
	<div class='panel-heading'>
		<h3 class='panel-title'>Options</h3>
	</div>
	<div class='panel-body'>
		@if( ! isset($item_ids))
		<form action='/crafting' method='post' role='form' class='form-horizontal' id='self-sufficient-form'>
			<input type='hidden' name='class' value='{{ $desired_job }}'>
			<input type='hidden' name='start' value='{{ $start }}'>
			<input type='hidden' name='end' value='{{ $end }}'>
			<label>
				Self Sufficient
			</label>
			<div class='make-switch' data-on='success' data-off='warning' data-on-label='Yes' data-off-label='No'>
				<input type='checkbox' id='self_sufficient_switch' name='self_sufficient' value='1' {{ $self_sufficient ? " checked='checked'" : '' }}>
			</div>
			<small><em>* Refreshes page</em></small>
		</form>
		@else
		<form action='/crafting/list' method='post' role='form' class='form-horizontal' id='self-sufficient-form'>
			<input type='hidden' name='List:::{{ $self_sufficient ? 0 : 1 }}' value=''>
			<label>
				Self Sufficient
			</label>
			<div class='make-switch' data-on='success' data-off='warning' data-on-label='Yes' data-off-label='No'>
				<input type='checkbox' id='self_sufficient_switch' value='1' {{ $self_sufficient ? " checked='checked'" : '' }}>
			</div>
			<small><em>* Refreshes page</em></small>
		</form>
		@endif
	</div>
</div>

<div class='row'>
	@if(isset($job))
	<div class='col-sm-6'>
		@if($end - $start >= 4)
		<div class='panel panel-primary' id='leveling-information'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Leveling Information</h3>
			</div>
			<div class='panel-body'>
				<p>Be efficient, make quest items in advance!</p>
				<p>Materials needed already reflected in lists above.</p>

				<ul>
					@foreach($quest_items as $quest)
					<li>
						@if(count($job) > 2)
						{{ $quest->job->abbreviation }} 
						@endif
						Level {{ $quest->level }}: 
						@if ( ! $quest->item)
							No data! Please help complete the list.
						@else
							{{ $quest->item->name }} 
							<small>x</small>{{ $quest->amount }}
							@if($quest->quality)
							<strong>(HQ)</strong>
							@endif
						@endif
						@if($quest->notes)
						({{ $quest->notes }})
						@endif
					</li>
					@endforeach
				</ul>

				<p><em>Repeatable Turn-in Leve information coming soon.</em></p>
			</div>
		</div>
		@endif
	</div>
	@endif
	<div class='col-sm-{{ isset($job) ? '6' : '12' }}'>
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Tips</h3>
			</div>
			<div class='panel-body text-center'>
				<p>Get extras in case of a failed synthesis.</p>

				<p>Improve your chances for HQ items by using the <a href='/equipment'>equipment calculator</a>.</p>

				<p>Don't forget the <a href='/food'>food</a> or <a href='/materia'>materia</a>!</p>
			</div>
		</div>
	</div>
</div>

{{--
<div class='well'>
	<p>
		<strong>Crafting as a Service</strong> couldn't have done it without these resources.  Please support them!
	</p>
	<p>
		<small><em>
			<a href='http://xivdb.com/' target='_blank'>XIVDB</a> for their tooltips, data and icons.
			<a href='http://www.daevaofwar.net/index.php?/topic/628-all-crafting-turn-ins-to-50/' target='_blank'>These</a>
			<a href='http://www.daevaofwar.net/index.php?/topic/629-all-gathering-turn-ins-to-50/' target='_blank'>Posts</a>
			for some quest knowledge.
			And of course <a href='http://www.finalfantasyxiv.com/' target='_blank'>Square Enix</a>.
		</em></small>
	</p>
</div>
--}}

@stop