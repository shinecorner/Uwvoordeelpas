@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')

   <div class="ui normal icon search selection fluid dropdown">
        <input type="hidden" name="companiesId">
        <i class="filter icon"></i>
                              
        <span class="text">Sectie</span>

        <i class="dropdown icon"></i>

        <div class="menu">
            <a href="{{ url('admin/statistics?'.http_build_query(array_add($queryString, 'section', 'search'))) }}" data-value="search" class="item">Zoekpagina</a>
            <a href="{{ url('admin/statistics?'.http_build_query(array_add($queryString, 'section', 'tegoed-sparen'))) }}" data-value="tegoed-sparen" class="item">Tegoed sparen</a>
            <a href="{{ url('admin/statistics?'.http_build_query(array_add($queryString, 'section', 'faq'))) }}" data-value="faq" class="item">Veelgestelde vrageb</a>
        </div>
    </div>
    
    <?php echo Form::open(array('id' => 'formList', 'method' => 'post')) ?>
    <table class="ui very basic collapsing sortable celled table list" style="width: 100%;">
        <thead>
            <tr>
                <th data-slug="term">Term</th>
                <th data-slug="count">Aantal keeur gebruikt</th>
                <th data-slug="page">Pagina</th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($data) >= 1)
                @include('admin/'.$slugController.'.list')
            @else
                <tr>
                    <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                </tr>
            @endif
            </tbody>
        </tbody>
    </table>
    <?php echo Form::close(); ?>

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}

</div>
<div class="clear"></div>
@stop