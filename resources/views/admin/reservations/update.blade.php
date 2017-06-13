@extends('template.theme')

@section('fixedMenu')
<a href="{{ url('faq/8/reserveren') }}" class="ui black icon big launch right attached fixed button">
    <i class="question mark icon"></i>
    <span class="text">Veelgestelde vragen</span>
</a><br />
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
		    closeBrowser();  
		});
	</script>
	
	@if (Request::has('add'))
	<script>
	var i  = 0;

	$('#days0').select({
		maxOptionsInLabel: 1
	});

	$('#dateAppend').repeater({
		btnAddClass: 'r-btnAdd',
		btnRemoveClass: 'r-btnRemove',
		groupClass: 'r-group',
		minItems: 0,
		maxItems: 0,
		startingIndex: 0,
		reindexOnDelete: true,
		repeatMode: 'append',
		animation: null,
		animationSpeed: 400,
		animationEasing: 'swing',
		clearValues: true,
		afterAdd: function() {
			i++;

		    $('.timepicker').pickatime({
		        format: 'H:i',
		        formatLabel: 'H:i',
		        formatSubmit: 'H:i',
		        interval: 15,
		        clear: 'verwijderen'
		    });

			$('#days' + i).select({
				maxOptionsInLabel: 1
			});
		}
	});
	</script>
	@endif
@stop

@section('content')
<div class="content">
    @if($data != '')
	    @if (isset($success))
	    <div class="ui success message">{{ $success }}</div> 
	    @elseif (count($errors) > 0)
		<div class="ui error message">
			<div class="header">Er zijn fouten opgetreden</div>

			<ul class="list">
			    @foreach ($errors->all() as $error)
			    <li>{{ $error }}</li>
			    @endforeach
			</ul>
		</div>
		@endif

		<div class="ui breadcrumb">
        	<a href="#" class="sidebar open">Admin</a>
	        <i class="right chevron icon divider"></i>
	        
	        <a href="{{ url('admin/reservations/'.$data->slug) }}" class="section">Reserveringen - {{ $data->companyName }}</a>

			<i class="right arrow icon divider"></i>

			<a href="" class="active section">
	            <div class="ui normal scrolling bread pointing dropdown item">
	                <div class="text">Instellingen</div>

	                <div class="menu">
	                    @if($userCompanies)
	                         @include('template/navigation/company')
	                    @endif
	                    
	                    @include('template/navigation/admin')
	                </div>
	            </div>
	        </a>
	    </div>

    	<div class="ui divider"></div>

		<?php echo Form::open(array('method' => 'post', 'class' => 'ui edit-changes form')) ?>
			@if (Request::has('add'))
			<h4>Welke dagen / tijden wilt u toevoegen?</h4>
			Let op: Bij het toevoegen van nieuwe reserveringsdagen worden de oude reserverings dagen verwijderd.<br>

			<?php echo Form::hidden('add', 1); ?>
			<?php echo Form::hidden('start_date', date('Y-m-d')); ?>
			<?php echo Form::hidden('end_date', date('Y-m-d', strtotime('+1 year'))); ?>
			<?php echo Form::hidden('per_time', 15); ?>

			<div id="dateAppend">
				@for ($i = 0; $i < 1; $i++) 
				<div class="r-group four fields">
					<div class="field">
						<label>Dag(en)</label>
						<?php 
						echo Form::select(
							'days[0][]', 
							Config::get('preferences.days'), 
							'', 
							array(
								'multiple' => true, 
								'class' => 'multipleSelect',
								'id' => 'days_0',
								'data-pattern-name' => 'days[++][]',
								'data-pattern-id' => 'days++'
							)
						) ?>
					</div>	

					<div class="field">
						<label>Begin tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('start_time[0]', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd', 'data-pattern-name' => 'start_time[++]','data-pattern-id' => 'start_time++')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	

					<div class="field">
						<label>Eind tijd</label>
						<div class="ui icon input">
							<?php echo Form::text('end_time[0]', '', array('class' => 'timepicker', 'placeholder' => 'Selecteer een tijd', 'data-pattern-name' => 'end_time[++]','data-pattern-id' => 'end_time++')); ?>
							<i class="clock icon"></i>
						</div>
					</div>	

					<div class="field">
						<label>Opties</label>
						<div class="ui buttons">
							<button type="button" class="r-btnAdd ui icon button">
								<i class="add icon"></i>
							</button>
							
							<button type="button" class="r-btnRemove ui red button icon">
								<i class="trash icon"></i>
							</button>
						</div>	
					</div>	
				</div>	
				@endfor
			</div>

			<div class="field">
				<label>Aantal plekken beschikbaar</label>
				<?php echo Form::number('available_persons', 1, array('min' => 2)) ?>
			</div>
			@else
			<h4>Instellingen</h4>
			<div class="two fields">
				<div class="field">
					<label>Periode dat een gast vooraf moet reserveren</label>
					<?php 
					echo Form::select(
						'closed_before_time', 
						Config::get('preferences.closed_time'), 
						$data->closed_before_time, 
						array(
							'class' => 'ui normal search dropdown'
						)
					) 
					?>
				</div>

				<div class="field">
					<label>Periode dat een gast vooraf een reservering mag annuleren</label>
					<?php 
					echo Form::select(
						'cancel_before_time', 
						Config::get('preferences.closed_time'), 
						$data->cancel_before_time, 
						array(
							'class' => 'ui normal search dropdown'
						)
					) 
					?>
				</div>
			</div>	

			<div class="two fields">
				<div class="field">
					<label>Periode dat een gast vooraf een reservering mag wijzigen</label>
					<?php 
					echo Form::select(
						'update_before_time', 
						Config::get('preferences.closed_time'), 
						$data->update_before_time, 
						array(
							'class' => 'ui normal search dropdown'
						)
					) 
					?>
				</div>

				<div class="field">
					<label>Periode voor de reservering een herinnering ontvangen (klant)?</label>
					<?php 
					echo Form::select(
						'reminder_before_date', 
						array(
							0 => 'Uit',
							1 => '1 uur',
							2 => '2 uur',
							3 => '3 uur',
							4 => '4 uur',
							5 => '5 uur'
						), 
						$data->reminder_before_date, 
						array(
							'class' => 'ui normal search dropdown'
						)
					) 
					?>
				</div>
			</div>

			<div class="ui divider"></div>

			<div class="two fields">
				<div class="field">
					<label>Plaatsen per tijd</label>
					<?php echo Form::select('per_time',  array(60 => 'Per uur', 30 => 'Per halfuur', 15 => 'Per kwartier'), 15, array('class' => 'ui normal search dropdown')) ?>
				</div>	

				<div class="field">
					<label>Aantal actie plaatsen</label>
					<?php echo Form::number('available_deals', 1, array('min' => 1)) ?>
				</div>	
			</div>	

			<div class="two fields">
				<div class="field">
					<label>Wilt u de reserveringen automatisch goedkeuren?</label><br>
					<?php 
					echo Form::select(
						'is_manual', 
						array(
							0 => 'Ja',
							1 => 'Nee'
						),
						$data->is_manual, 
						array(
							'class' => 'ui normal search dropdown'
						)
					) 
					?>
				</div>
				
				<div class="field">
					<label>Wilt u als de beschikbare plekken op zijn eventuele extra reserveringen als handmatige goedkeuring ontvangen?</label>
					<?php 
					echo Form::select(
						'extra_reservations', 
						array(
							1 => 'Ja',
							0 => 'Nee'
						),
						$data->extra_reservations, 
						array(
							'class' => 'ui normal search dropdown'
						)
					) 
					?>
				</div>
			</div>	

			<div class="two fields">

				<div class="field">
					<label>Dagelijks reserveren kan tot:</label>
					<div class="ui icon input">
						<?php 
						echo Form::text(
							'closed_at_time', 
							$data->closed_at_time != null ? date('H:i', strtotime($data->closed_at_time)) : '', 						
							array(
								'class' => 'timepicker', 
								'placeholder' => 'Selecteer een tijd'
							)
						);
						?>
						<i class="clock icon"></i>
					</div>
				</div>	

				<div class="field">
					<label>Minimum spaartegoed</label>
					<?php echo Form::text('min_saldo', $data->min_saldo) ?>
				</div>
			</div>
			@endif

			<button class="ui tiny button" type="submit"><i class="pencil icon"></i> Wijzigen</button>
	
		<?php echo Form::close(); ?>
	@else
		<div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet gevonden.</div>
	@endif
</div>
<div class="clear"></div>
@stop