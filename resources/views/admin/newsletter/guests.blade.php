@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
	
	<div class="ui three mini steps">
		<a href="{{ url('admin/newsletter?id='.Request::input('id').'&step=1') }}" class="link step">
			<i class="paint brush icon"></i>
			<div class="content">
				<div class="title">Lay-out</div>
			</div>
		</a>

		<a href="{{ url('admin/newsletter/guests?id='.Request::input('id').'&step=1') }}" class="link active step">
			<i class="users icon"></i>
			<div class="content">
				<div class="title">Gasten</div>
			</div>
		</a>

		<a href="{{ url('admin/newsletter/example?id='.Request::input('id').'&step=3') }}" class="link step">
			<i class="image icon"></i>
			<div class="content">
				<div class="title">Voorbeeld</div>
			</div>
		</a>
	</div><br>
	
	<div class="ui grid">
		<div class="four column row">
            <div class="column">
                <div class="ui normal icon search selection fluid dropdown">
                     <input type="hidden" value="{{ Request::input('gender') }}" name="gender">
                     <i class="filter icon"></i>
                              
					<span class="text">Geslacht</span>
					<i class="dropdown icon"></i>

                    <div class="menu">
						<a href="{{ url('admin/newsletter/guests?id='.Request::input('id')) }}" class="item">Alles</a>
						<a href="{{ url('admin/newsletter/guests?id='.Request::input('id').'&gender=1') }}" data-value="1" class="item">Man</a>
						<a href="{{ url('admin/newsletter/guests?id='.Request::input('id').'&gender=2') }}" data-value="2" class="item">Vrouw</a>
					</div>
                </div>
            </div>

       	</div>
    </div>

	<span class="ui red label">Rood: Geen nieuwsbrief ontvangen</span>

	<div id="formList">
	    <table class="ui very basic collapsing  sortable celled table list" style="width: 100%;">
	       	<thead>
				<tr>
					<th data-slug="disabled" class="disabled one wide">
						<div class="ui master checkbox">
							<input type="checkbox">
						</div>
					</th>
					<th data-slug="name">Naam</th>
					<th data-slug="email">E-mail</th>
					<th data-slug="companyName">Bedrijf</th>
					<th data-slug="age">Leeftijd</th>
					<th data-slug="gender">Geslacht</th>
				</tr>
			</thead>
	        <tbody class="list search">
				@if(count($guests) >= 1)
					@foreach($guests as $fetch)
					<tr class="{{ $fetch['no_show'] == 1 ? 'negative' : '' }}">
	                   	<td>
	                   		<div class="ui child newsletter checkbox" data-id="{{ $fetch['id'] }}" data-newsletter-id="{{ $fetch['newsletterId'] }}" data-company-id="{{ $fetch['companyId'] }}">
	                   			<input type="checkbox" name="id[]" value="{{ $fetch['id'] }}" {{ $fetch['no_show'] == 0 ? 'checked' : '' }}>
	                   			<label></label>
	                   		</div>
						</td>
						<td>{{ $fetch['name'] }}</td>
						<td>{{ $fetch['email'] }}</td>
						<td>{{ $fetch['companyName'] }}</td>
						<td>{{ $fetch['age'] }}</td>
						<td><i class="icon {{ ($fetch['gender'] == 1 ? 'male' : ($fetch['gender'] == 2 ? 'female' : 'male disabled')) }}"></i> {{ ($fetch['gender'] == 1 ? 'Man' : ($fetch['gender'] == 2 ? 'Vrouw' : 'Niet opgegeven')) }}</td>
					</tr>
					@endforeach
				@else
					<tr>
	                    <td colspan="2">
	                    	<div class="ui error message">Er is geen data gevonden.</div>
	                    </td>
	            	</tr>
				@endif
	        </tbody>
	   	</table>
   	</div>

   	{!! with(new \App\Presenter\Pagination($guests->appends($paginationQueryString)))->render() !!}
<br>
	<a class="ui button" href="{{ url('admin/newsletter/example?id='.Request::input('id').'&step=3') }}">
		<i class="arrow right icon"></i> Volgende
	</a>
		
</div>
<div class="clear"></div>
@stop