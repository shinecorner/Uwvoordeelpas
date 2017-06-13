@foreach($data as $fetch)
<tr>
    <td>
        <div class="ui child checkbox">
        	<input type="checkbox" name="id[]" value="{{ $fetch->id }}">
        	<label></label>
        </div>
    </td>
    <td>{{ $fetch->subject }}</td>
    <td>{{ $fetch->type }}</td>
    <td>{{ $fetch->name }}</td>
    <td>
        <div class="ui toggle checkbox activeChange" data-id="{{ $fetch->id }}">
            <input type="checkbox" name="public" {!! ($fetch->is_active == 0 ? 'checked="checked"' : '') !!}>
        </div>
    </td>
    <td>
        <a href="{{ url('admin/mailtemplates/update/'.$fetch->id) }}" class="ui button">
            <i class="pencil icon"></i> Bewerk
        </a>
    </td>
</tr>
@endforeach