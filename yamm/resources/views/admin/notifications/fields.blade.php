<!-- Sender Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sender_id', 'Send To:') !!}
    {!! Form::select('send_to[]', $users, null, ['class' => 'form-control select2', 'multiple'=>'multiple']) !!}
</div>

<!-- Action Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('device_type', 'Device Type:') !!}
    {!! Form::select('device_type', \App\Models\Notification::$RECEIVER_TYPES, null, ['class' => 'form-control']) !!}
</div>

<!-- Broadcast Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('broadcast_type', 'Broadcast Type:') !!}
    {!! Form::select('broadcast_type', \App\Models\Notification::$BROADCAST_TYPES, null, ['class' => 'form-control']) !!}
</div>
<!-- Ref Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ref_id', 'Ref Id:') !!}
    {!! Form::number('ref_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Message Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('message', 'Message:') !!}
    {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.notifications.index') !!}" class="btn btn-default">Cancel</a>
</div>
