<!-- CSV File Field -->
<div class="form-group col-sm-6">
    {!! Form::label('csv_file', 'CSV File:') !!}
    {!! Form::file('csv_file', ['class' => 'form-control', 'accept' => '.csv']) !!}
</div>

<!-- Examining Attorney Field -->
<div class="form-group col-sm-6">
    {!! Form::label('examining_attorney', 'Examining Attorney:') !!}
    {!! Form::text('examining_attorney', null, ['class' => 'form-control']) !!}
</div>

<!-- Direct Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('direct_phone', 'Direct Phone:') !!}
    {!! Form::text('direct_phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Appointment Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appointment_date', 'Appointment Date:') !!}
    {!! Form::date('appointment_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Appointment Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appointment_time', 'Appointment Time:') !!}
    {!! Form::time('appointment_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Appointment Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('appointment_number', 'Appointment Number:') !!}
    {!! Form::text('appointment_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Customer Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_status', 'Customer Status:') !!}
    {!! Form::select('customer_status', ['Live' => 'Live', 'Pending' => 'Pending', 'Completed' => 'Completed'], null, ['class' => 'form-control']) !!}
</div>

<!-- Message Field -->
<div class="form-group col-sm-12">
    {!! Form::label('message', 'Message:') !!}
    {!! Form::textarea('message', null, ['class' => 'form-control', 'rows' => 5]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.dashboard') !!}" class="btn btn-default">Cancel</a>

</div>