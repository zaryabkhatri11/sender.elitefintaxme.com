@extends('admin.layouts.app')

@section('title')
    {{ $video->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($video, ['route' => ['admin.videos.update', $video->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.videos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection