<!-- Link Field -->
<div class="form-group col-sm-6">
    {!! Form::label('link', 'Link:') !!}
    {!! Form::file('link', [
         'class' => 'form-control-file form-control',
         isset($sheet) && $sheet->link ? '' : 'required' => true
     ]) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!isset($sheet))
        {!! Form::submit(__('Save And Add Translations'), ['class' => 'btn btn-primary', 'name'=>'translation']) !!}
    @endif
    <div class="form-group col-sm-6">
    {!! Form::label('examining_attorney', 'Examining Attorney:') !!}
    {!! Form::text('examining_attorney', null, ['class' => 'form-control', 'required' => true]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('direct_phone', 'Direct Phone:') !!}
    {!! Form::text('direct_phone', null, ['class' => 'form-control', 'required' => true]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('appointment_date', 'Appointment Date:') !!}
    {!! Form::text('appointment_date', null, ['class' => 'form-control', 'placeholder'=>'Wednesday, January 21, 2026', 'required' => true]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('appointment_time', 'Appointment Time:') !!}
    {!! Form::text('appointment_time', null, ['class' => 'form-control', 'placeholder'=>'10:30 AM CST', 'required' => true]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('appointment_number', 'Appointment Number:') !!}
    {!! Form::text('appointment_number', null, ['class' => 'form-control', 'placeholder'=>'#9892', 'required' => true]) !!}
</div>

    {!! Form::submit(__('Save And Add More'), ['class' => 'btn btn-primary', 'name'=>'continue']) !!}
    <a href="{!! route('admin.sheets.index') !!}" class="btn btn-default">Cancel</a>
</div>