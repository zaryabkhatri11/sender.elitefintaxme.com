<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control', 'placeholder'=>'Enter id']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder'=>'Enter email']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', 'Phone:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder'=>'Enter phone']) !!}
</div>

<!-- Owner Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('owner_name', 'Owner Name:') !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control', 'placeholder'=>'Enter owner_name']) !!}
</div>

<!-- Entity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('entity', 'Entity:') !!}
    {!! Form::text('entity', null, ['class' => 'form-control', 'placeholder'=>'Enter entity']) !!}
</div>

<!-- Owner Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('owner_address', 'Owner Address:') !!}
    {!! Form::text('owner_address', null, ['class' => 'form-control', 'placeholder'=>'Enter owner_address']) !!}
</div>

<!-- Subject Mark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('subject_mark', 'Subject Mark:') !!}
    {!! Form::text('subject_mark', null, ['class' => 'form-control', 'placeholder'=>'Enter subject_mark']) !!}
</div>

<!-- Case Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('case_number', 'Case Number:') !!}
    {!! Form::text('case_number', null, ['class' => 'form-control', 'placeholder'=>'Enter case_number']) !!}
</div>

<!-- Created At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('created_at', 'Created At:') !!}
    {!! Form::text('created_at', null, ['class' => 'form-control', 'placeholder'=>'Enter created_at']) !!}
</div>

<!-- Updated At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    {!! Form::text('updated_at', null, ['class' => 'form-control', 'placeholder'=>'Enter updated_at']) !!}
</div>

<!-- Deleted At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    {!! Form::text('deleted_at', null, ['class' => 'form-control', 'placeholder'=>'Enter deleted_at']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!isset($customer))
        {!! Form::submit(__('Save And Add Translations'), ['class' => 'btn btn-primary', 'name'=>'translation']) !!}
    @endif
    {!! Form::submit(__('Save And Add More'), ['class' => 'btn btn-primary', 'name'=>'continue']) !!}
    <a href="{!! route('admin.customers.index') !!}" class="btn btn-default">Cancel</a>
</div>