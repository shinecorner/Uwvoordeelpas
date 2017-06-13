@extends('template.theme')

@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="left floated sixteen wide mobile seven wide computer column">
                <a href="{{ url('admin/reservations-options/create'.($slug != NULL ? '/'.$slug : '')) }}" class="ui icon blue button">
                    <i class="plus icon"></i> Nieuw
                </a>

                <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                    <i class="trash icon"></i> Verwijderen
                </button>
            </div>

            <div class="right floated sixteen wide mobile six wide computer column">
                <div class="ui grid">
                    <div class="two column row">
                        <div class="column">
                            @include('admin.template.limit')
                        </div>

                        <div class="column">
                            @include('admin.template.search.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    <table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
        <thead>
            <tr>
                <th class="two wide" data-slug="disabled">
        <div class="ui master checkbox">
            <input type="checkbox">
            <label></label>
        </div>
        </th>
        <th data-slug="name" class="three wide">Naam</th>
        <th data-slug="total_amount" class="four wide">Aantal plaatsen</th>
        <th data-slug="total_res" class="four wide">Aantal gasten</th>
        <th data-slug="date_from" class="four wide">Online van</th>
        <th data-slug="date_to" class="four wide">Online tot</th>
        <th data-slug="price_from" class="four wide">prijs van</th>
        <th data-slug="price" class="four wide">prijs tot</th>
        <th data-slug="total_res" class="four wide">Staat</th>
        <th data-slug="disabled">online</th>
        </tr>
        </thead>
        <tbody class="list">
            @if(count($data) >= 1)
            @foreach($data as $result)
        
            <tr>
                <td>
                    <div class="ui child checkbox">
                        <input type="checkbox" name="id[]" value="{{ $result->id }}">
                        <label></label>
                    </div>
                </td>
                <td>
                    {{ $result->name }}
                </td>
                <td>
                    {{ $result->total_amount }}
                </td>
                <td>
                    {{ ($result->total_res)?$result->total_res:0 }}
                </td>
                <td>
                    {{ ($result->date_from)?$result->date_from:'' }}
                </td>
                <td>
                    {{ ($result->date_to)?$result->date_to:'' }}
                </td>
                <td>
                    {{ ($result->price_from)?$result->price_from:'' }}
                </td>
                <td>
                    {{ ($result->price)?$result->price:'' }}
                </td>
                <td>
                    <?php
                    $currentDate = date('Y-m-d', strtotime(date('Y-m-d')));
                    ;
                    $contractDateBegin = date('Y-m-d', strtotime($result->date_from));
                    $contractDateEnd = date('Y-m-d', strtotime($result->date_to));
                    $result->date_from . '-' . $result->date_to;
                    if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)) {
                        echo "Actief";
                    } else {
                        echo "Deactiveren";
                    }
                    ?>
                </td>
               
                <td>
                    <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}" class="ui icon tiny button">
                        <i class="pencil icon"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
            </tr>
            @endif
        </tbody>
    </table>
<?php echo Form::close(); ?>

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
<div class="clear"></div>
@stop