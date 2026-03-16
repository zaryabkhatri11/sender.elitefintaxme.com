<!-- Id Field -->
<dt>{!! Form::label('id', 'Id:') !!}</dt>
<dd>{!! $merchant->id !!}</dd>

<!-- Payment Account Id Field -->
<dt>{!! Form::label('payment_account_id', 'Payment Account:') !!}</dt>
<dd>{!! $merchant->paymentAccount ? $merchant->paymentAccount->name : $merchant->payment_account_id !!}</dd>

<!-- Customer Id Field -->
<dt>{!! Form::label('customer_id', 'Customer:') !!}</dt>
<dd>{!! $merchant->customer ? $merchant->customer->owner_name : 'N/A' !!}</dd>

<!-- Name Field -->
<dt>{!! Form::label('name', 'Name:') !!}</dt>
<dd>{!! $merchant->name !!}</dd>

<!-- Email Field -->
<dt>{!! Form::label('email', 'Email:') !!}</dt>
<dd>{!! $merchant->email !!}</dd>

<!-- Created At Field -->
<dt>{!! Form::label('created_at', 'Created At:') !!}</dt>
<dd>{!! $merchant->created_at !!}</dd>

<!-- Updated At Field -->
<dt>{!! Form::label('updated_at', 'Updated At:') !!}</dt>
<dd>{!! $merchant->updated_at !!}</dd>