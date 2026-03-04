<!-- Id Field -->
<dt>{!! Form::label('id', 'Id:') !!}</dt>
<dd>{!! $paymentAccount->id !!}</dd>

<!-- Name Field -->
<dt>{!! Form::label('name', 'Name:') !!}</dt>
<dd>{!! $paymentAccount->name !!}</dd>

<!-- Gateway Field -->
<dt>{!! Form::label('gateway', 'Gateway:') !!}</dt>
<dd>{!! $paymentAccount->gateway !!}</dd>

<!-- Credentials Field -->
<dt>{!! Form::label('credentials', 'Credentials:') !!}</dt>
<dd><pre>{!! json_encode($paymentAccount->credentials, JSON_PRETTY_PRINT) !!}</pre></dd>

<!-- Is Active Field -->
<dt>{!! Form::label('is_active', 'Is Active:') !!}</dt>
<dd>{!! $paymentAccount->is_active !!}</dd>

<!-- Created At Field -->
<dt>{!! Form::label('created_at', 'Created At:') !!}</dt>
<dd>{!! $paymentAccount->created_at !!}</dd>

<!-- Updated At Field -->
<dt>{!! Form::label('updated_at', 'Updated At:') !!}</dt>
<dd>{!! $paymentAccount->updated_at !!}</dd>