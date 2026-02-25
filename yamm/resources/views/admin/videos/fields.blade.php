<!-- Video 1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('video_1', 'Video 1:') !!}
    {!! Form::text('video_1', null, ['class' => 'form-control', 'placeholder'=>'Enter video_1']) !!}
</div>

<!-- Video 2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('video_2', 'Video 2:') !!}
    {!! Form::text('video_2', null, ['class' => 'form-control', 'placeholder'=>'Enter video_2']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder'=>'Enter email']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!isset($video))
        {!! Form::submit(__('Save And Add Translations'), ['class' => 'btn btn-primary', 'name'=>'translation']) !!}
    @endif
    {!! Form::submit(__('Save And Add More'), ['class' => 'btn btn-primary', 'name'=>'continue']) !!}
    <a href="{!! route('admin.videos.index') !!}" class="btn btn-default">Cancel</a>
</div>