@extends('template.theme')


@section('scripts')
@include('admin.template.remove_alert')
@stop

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="row">                
                <div class="sixteen wide mobile four wide computer column">
                    <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>

                    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon grey button">
                        <i class="trash icon"></i> Verwijderen
                    </button>
                </div>

                <div class="sixteen wide mobile twelve wide computer column">
                    <div class="ui grid">
                        <div class="three column row">
                            <div class="sixteen wide mobile four wide computer column">
                                <div class="ui normal  search selection fluid dropdown"> <!-- icon class deleted-->
                                    <input type="hidden" name="companiesId">
                                    <i class="filter icon"></i>

                                    <span class="text">Bedrijf</span>

                                    <i class="dropdown icon"></i>

                                    <div class="menu">
                                        @foreach ($companies as $company)
                                        <a class="item" href="{{ url('admin/guests/'.$company->slug) }}" data-value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="sixteen wide mobile four wide computer column">
                                <?php echo Form::select('city', (isset($preference[9]) ? $preference[9] : array()), Request::input('city'), array('id' => 'cityRedirect', 'class' => 'ui normal search fluid dropdown')); ?>
                            </div>

                            <div class="sixteen wide mobile four wide computer column">
                                <div class="ui normal floating basic search selection dropdown">
                                    <input type="hidden" name="source" value="{{ Request::input('source') }}">

                                    <div class="text">Partij</div>
                                    <i class="dropdown icon"></i>

                                    <div class="menu">
                                        <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}" data-value="seatme" class="item">SeatMe</a>
                                        <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}" data-value="eetnu" class="item">EetNU</a>
                                        <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}" data-value="couverts" class="item">Couverts</a>
                                        <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}" data-value="wifi" class="item">Wi-Fi</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="sixteen wide mobile three wide computer column">
                                @include('admin.template.limit')
                            </div>

                            <div class="sixteen wide mobile one wide computer column">
                                @include('admin.template.search.form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="row">                
                <div class="sixteen wide mobile four wide computer column">
                    <div class="ui normal floating basic search selection dropdown">
                        <input type="hidden" name="has_saving" value="{{ Request::input('has_saving') }}">

                        <div class="text">Spaarhulp</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">                            
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'has_saving', '1'))) }}" data-value="1" class="item">Ja</a>
                            <a href="{{ url('admin/users?'.http_build_query(array_add($queryString, 'has_saving', '0'))) }}" data-value="0" class="item">Nee</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br />

    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/' . $slugController . '/delete', 'method' => 'post')) ?>
    <table class="ui very basic sortable collapsing celled table list" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="disabled">
        <div class="ui master checkbox">
            <input type="checkbox">
            <label></label>
        </div>
        </th>
        <th data-slug="name">Naam</th>
        <th data-slug="city">Stad</th>
        <th data-slug="gender">Geslacht</th>
        <th data-slug="phone">Telefoon</th>
        <th data-slug="email">E-mailadres</th>
        <th data-slug="newsletter">Nieuwsbrief</th>
        <th data-slug="saldo">Saldo</th>
        <th data-slug="created_at">Geregistreerd op</th>
        <th data-slug="updated_at">Laatst ingelogd</th>
        <th data-slug="disabled"></th>
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
                    <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}">
                        {{ $result->name }}
                    </a>
                </td>
                <td>
                    @if(is_array(json_decode($result->city)) >= 1)
                    @foreach(json_decode($result->city) as $city)
                    @if(isset($regio[$city]))
                    {{ $regio[$city] }}
                    @endif
                    @endforeach
                    @else
                    Geen stad opgegeven
                    @endif
                </td>
                <td>{{ ($result->gender == 1 ? 'Man' : 'Vrouw') }}</td>
                <td>{{ $result->phone }}</td>
                <td>{{ $result->email }}</td>
                <td class="text-center"><i class="icon checkmark {{ $result->newsletter == 1 ? 'green' : 'red' }}"></i></td>
                <td>
                    <a href="{{ url('admin/users/saldo/reset/'.$result->id) }}" class="ui mini blue fluid button">Reset</a>
                    <a href="{{ url('account/reservations/saldo/'.$result->id) }}" class="ui fluid text aligned center label">&euro;{{ $result->saldo }}</a>
                </td>
                <td>{{ date('d-m-Y H:i', strtotime($result->created_at)) }}</td>
                <td>{{ date('d-m-Y H:i', strtotime($result->updated_at)) }}</td>
                <td>
                    <a href="{{ url('admin/'.$slugController.'/update/'.$result->id) }}" class="ui icon tiny button">
                        <i class="pencil icon"></i>
                    </a>

                    <a href="{{ url('admin/'.$slugController.'/login/'.$result->id) }}" class="ui icon tiny orange button loginAs" data-content="Inloggen als {{ $result->name }}">
                        <i class="key icon"></i>
                    </a>
                </td>
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