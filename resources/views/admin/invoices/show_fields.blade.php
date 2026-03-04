<!-- Id Field -->
<dt>{!! Form::label('id', 'Id:') !!}</dt>
<dd>{!! $invoice->id !!}</dd>

<!-- Merchant Id Field -->
<dt>{!! Form::label('merchant_id', 'Merchant Id:') !!}</dt>
<dd>{!! $invoice->merchant_id !!}</dd>

<!-- Uuid Field -->
<dt>{!! Form::label('uuid', 'Uuid:') !!}</dt>
<dd>{!! $invoice->uuid !!}</dd>

<!-- Amount Field -->
<dt>{!! Form::label('amount', 'Amount:') !!}</dt>
<dd>{!! $invoice->amount !!}</dd>

<!-- Currency Field -->
<dt>{!! Form::label('currency', 'Currency:') !!}</dt>
<dd>{!! $invoice->currency !!}</dd>

<!-- Description Field -->
<dt>{!! Form::label('description', 'Description:') !!}</dt>
<dd>{!! $invoice->description !!}</dd>

<!-- Status Field -->
<dt>{!! Form::label('status', 'Status:') !!}</dt>
<dd>{!! $invoice->status !!}</dd>

<!-- Created At Field -->
<dt>{!! Form::label('created_at', 'Created At:') !!}</dt>
<dd>{!! $invoice->created_at !!}</dd>

<!-- Updated At Field -->
<dt>{!! Form::label('updated_at', 'Updated At:') !!}</dt>
<dd>{!! $invoice->updated_at !!}</dd>

