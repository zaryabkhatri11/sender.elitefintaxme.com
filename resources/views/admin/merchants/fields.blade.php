<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter name', 'required']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter email', 'required']) !!}
</div>

<!-- Payment Account Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_account_id', 'Payment Account:') !!}
    {!! Form::select('payment_account_id', $paymentAccounts, null, ['class' => 'form-control', 'placeholder' => 'Select Payment Account', 'required']) !!}
</div>

<!-- Customer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_id', 'Customer:') !!}
    {!! Form::select('customer_id', $customers, null, ['class' => 'form-control', 'placeholder' => 'Select Customer', 'required']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Invoice Amount (USD):') !!}
    {!! Form::number('amount', null, ['class' => 'form-control', 'placeholder' => 'Enter amount e.g. 100.00', 'step' => '0.01', 'min' => '0.01', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 mt-3">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!isset($merchant))
        {!! Form::submit(__('Save And Add Translations'), ['class' => 'btn btn-primary', 'name' => 'translation']) !!}
    @endif
    {!! Form::submit(__('Save And Add More'), ['class' => 'btn btn-primary', 'name' => 'continue']) !!}
    <a href="{!! route('admin.merchants.index') !!}" class="btn btn-default">Cancel</a>
</div>