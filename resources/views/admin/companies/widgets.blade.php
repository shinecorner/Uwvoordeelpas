@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    @if($userAdmin)
	<div class="ui normal icon search selection fluid dropdown">
       	<div class="text">Filter op bedrijf</div>
        <i class="dropdown icon"></i>

		<div class="menu">
            @foreach($companies as $data)
            <a class="item" href="{{ url('admin/widgets/'.$data->slug) }}">{{ $data->name }}</a>
            @endforeach
       </div>
    </div>
    @endif

    <h3>Reserveer kalender</h3>
    <div class="ui form">
	    <div class="two fields">
		    <div class="field">
			  	<textarea><iframe src="{{ url('widget/calendar/restaurant/'.$company->slug) }}" width="500" height="550" frameborder="0"></iframe></textarea><br />
			  	<h5>Voorbeeld</h5>

				<iframe src="{{ url('widget/calendar/restaurant/'.$company->slug) }}" 
						width="100%" 
						height="550" 
						frameborder="0">
				</iframe>

               </div>
		    <div class="field">
		    	<table class="ui table">
		    		<tr>
			    		<td><strong>Width:</strong></td>
			    		<td>Bepaal de breedte van de widget</td>
			    	</tr>
			    	<tr>
			    		<td><strong>Height:</strong></td>
			    		<td>Bepaal de hoogte van de widget</td>
			    	</td>
			    	<tr>
			    		<td><strong>Frameborder:</strong></td>
			    		<td>1 = Rand weergeven om de widget, 0 = Geen rand weergeven om de widget</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>
@stop
