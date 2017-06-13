<?php
setlocale(LC_TIME, 'Dutch');
?>
@foreach($data as $result)
<?php 
$date = \Carbon\Carbon::create(date('Y', strtotime($result->date)), date('m', strtotime($result->date)), date('d', strtotime($result->date))); 

?>
<tr>
    <td>
        <i class="calendar icon"></i>
        {{ $date->formatLocalized('%d %B %Y') }}<br>

        <i class="clock icon"></i>
        {{ date('H:i', strtotime($result->time)) }}
    </td>
    <td>
        {{ $result->companyName }}
    </td>
    <td>
        <a href="{{ url('account/reservations/'.$result->companySlug.'/user/'.$result->user_id) }}"> 
            {{ $result->name }}
        </a>
    </td>
    <td>
        <a href="{{ url('account/reservations/'.$result->companySlug.'/user/'.$result->user_id) }}">{{ $result->email }}</a>
    </td>
    <td>
        <a href="{{ url('account/reservations/'.$result->companySlug.'/user/'.$result->user_id) }}">{{ $result->phone }}</a>
    </td>
    <td>{{ $result->persons }}</td>
    <td class="text-aligned center">{!! ($result->restaurant_is_paid == 1 ? '<i class="green icon checkmark"></i>' : '<i class="red remove icon"></i>') !!}</td>
    <td><i class="euro icon"></i> {{ (float)$result->saldo }}</td>
</tr>
@endforeach