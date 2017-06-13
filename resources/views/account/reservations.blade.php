@extends('template.theme')

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
	$('tbody.list.search tr').each(function() {
		var trElement = $(this);

		trElement.find('.removeButton').click(function() {
			trElement.find('.checkbox').checkbox('set checked');
		});
	});

  	$('.removeButton').click(function() {
  		if ($('[name="id[]"]:checked').length == 0) {
  			swal({   
				title: "Er is een fout opgetreden",   
				text: "U bent vergeten om een optie te selecteren.",   
				type: "warning"
			});
		} else {
			swal({   
				title: "Weet u het zeker?",   
				text: "Weet u zeker dat u uw reservering(en) wil annuleren?",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",  
				cancelButtonText: "Nee",   
				confirmButtonText: "Ja, ik weet het zeker!",   
				closeOnConfirm: false 
			}, 
			function() { 
				$('.ui.form').submit(); 
			});
		}
	});
});
</script>
@stop

@section('content')
<div class="content">
	<div class="ui breadcrumb">
		<a href="{{ url('/') }}" class="section">Home</a>
		<i class="right chevron icon divider"></i>

		<a href="#" class="sidebar open">Menu</a>
	    <i class="right chevron icon divider"></i>

		<div class="active section">Mijn reserveringen</div>
	</div>

    <div class="ui divider"></div>

    @if(isset($reservationDate) && count($reservationDate) >= 1)
	    <?php echo Form::open(array('id' => 'formList', 'class' => 'ui form', 'method' => 'post')) ?>
	        <div class="ui grid">
	            <div class="left floated six wide column">
	                <button class="removeButton ui icon grey button" type="button" name="action" value="remove">
	                    <i class="minus circle icon"></i> Annuleren
	                </button>
	            </div>
	        </div>

	    	<table id="account_reservations" class="ui very basic collapsing sortable celled table list" style="width: 100%;">
	            <thead>
	            	<tr>
	            		<th data-slug="disabled" class="one wide">
	            			<div class="ui master checkbox">
	    					  	<input type="checkbox">
	    					  	<label></label>
	    					</div>
	    				</th>
	                    <th data-slug="company" class="four wide">Bedrijf</th>
                            <th data-slug="dealname" class="four wide">Gereserveerd Deal</th>
	                    <th data-slug="created_at" class="four wide">Datum en tijd</th>
	                    <th data-slug="persons" class="two wide">Personen</th>
	                    <th data-slug="disabled" class="four wide">Persoonsgegevens</th>
	                    <th data-slug="saldo" class="eight wide">Saldo</th>
	                    <th data-slug="disabled" class="eight wide">Korting</th>
	                    <th data-slug="allergies" class="one wide">Allergie&euml;n</th>
	                    <th data-slug="preferences" class="one wide">Voorkeuren</th>
	                    <th data-slug="disabled" class="three wide">Opmerking</th>
	                    <th data-slug="disabled" class="one wide"></th>
	            	</tr>
	            </thead>
	            <tbody class="list search">
	                @if(isset($reservationDate))
	                	@foreach($reservationDate as $data)
		                <?php
		                $date = \Carbon\Carbon::create(
		                	date('Y', strtotime($data->date)), 
		                	date('m', strtotime($data->date)), 
		                	date('d', strtotime($data->date)), 
		                	date('H', strtotime($data->time)), 
		                	date('i', strtotime($data->time)), 
		                	date('s', strtotime($data->time))
		                );
		                ?>
						<tr>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								@if (new DateTime() < new DateTime($data->cancelBeforeTime) && $data->is_cancelled == 0) 
								<div class="ui child checkbox">
									<input type="checkbox" name="id[]" value="{{ $data->id }}">
									<label></label>
								</div>
								@endif
							</td>
							<td><a href="{{ url('restaurant/'.$data->slug) }}">{{ $data->company }}</a></td>
                                                        <td>{{ $data->dealname }}</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								<i class="calendar icon"></i> {{ $date->formatLocalized('%d %B %Y') }}<br />
								<i class="clock icon"></i> {{ date('H:i', strtotime($data->time)) }}
							</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								{{ $data->persons }} personen
							</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								<i class="user icon"></i> {{ $data->name }}<br />
								<i class="envelope icon"></i> {{ $data->email }}<br />
								<i class="phone icon"></i> {{ $data->phone }}
							</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								<i class="euro icon"></i>{{ $data->saldo }} korting
							</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								@if ($data->barcode == 1) 
									@if ($data->discount != 'null' && $data->discount != NULL && $data->discount != '[""]')
										{{ urldecode(json_decode($data->discount)[0]) }}
									@endif
								@endif
        					</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								@if($data->allergies != 'null' && $data->allergies != NULL && $data->allergies != '[""]')   
									@foreach(json_decode($data->allergies) as $allergies)
										<span class="ui label">{{ $allergies }}</span>
									@endforeach
								@endif
							</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								@if($data->preferences != 'null' && $data->preferences != NULL && $data->preferences != '[""]')
									@foreach(json_decode($data->preferences) as $pref)
										{{ $pref }}
									@endforeach
								@endif
							</td>
							<td {!! $data->is_cancelled ? 'class="disabled"' : '' !!}>
								<div style="width: 80px; word-wrap: break-word;">{{ $data->comment }}</div>
							</td>
							<td>
								@if (new DateTime() < new DateTime($data->updateBeforeTime)) 
									@if(!$date->isPast() && $data->is_cancelled == 0)
										<a href="{{ url('reservation/edit/'.$data->id) }}" class="ui fluid tiny button">Wijzigen</a><br />
											
										<button class="ui fluid grey button tiny removeButton" type="button" name="action" value="remove">
											Annuleren
										</button>
									@endif
								@endif

								@if($data->is_cancelled == 1)
									<span class="ui red label">Geannuleerd</span>
								@endif

								@if($data->status == 'refused')
									<span class="ui red label">Geweigerd</span>
								@endif

								@if($data->status == 'iframe-pending' OR $data->status == 'reserved-pending' && $data->is_cancelled == 0)
									<span class="ui orange label">Aanvraag</span>
								@endif
							</td>
						</tr>
	                	@endforeach
	           		@endif
	        	</tbody>
	    	</table>
		<?php echo Form::close(); ?>
	    {!! with(new \App\Presenter\Pagination($reservationDate->appends($paginationQueryString)))->render() !!}


	@else
	Er zijn nog geen reserveringen door u geplaatst.
	@endif
</div>
<div class="clear"></div>
@stop