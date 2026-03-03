{!! Form::open(['route' => ['admin.customers.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @ability('super-admin', 'customers.show')
    <a href="{{ route('admin.customers.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @endability
    @ability('super-admin', 'customers.edit')
    <a href="{{ route('admin.customers.threads', $id) }}" class='btn btn-default btn-xs' title="Conversations">
        <i class="glyphicon glyphicon-envelope"></i>
    </a>
    <a href="{{ route('admin.customers.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endability
    @ability('super-admin', 'customers.destroy')
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
    'type' => 'submit',
    'class' => 'btn btn-danger btn-xs',
    'onclick' => "confirmDelete($(this).parents('form')[0]); return false;"
]) !!}
    @endability
</div>
{!! Form::close() !!}