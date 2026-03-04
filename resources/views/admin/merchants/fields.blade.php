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

<!-- Submit Field -->
<div class="form-group col-sm-12 mt-3">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!isset($merchant))
        {!! Form::submit(__('Save And Add Translations'), ['class' => 'btn btn-primary', 'name' => 'translation']) !!}
    @endif
    {!! Form::submit(__('Save And Add More'), ['class' => 'btn btn-primary', 'name' => 'continue']) !!}
    <a href="{!! route('admin.merchants.index') !!}" class="btn btn-default">Cancel</a>
</div>