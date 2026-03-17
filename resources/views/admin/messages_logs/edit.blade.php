@extends('admin.layouts.app')

@section('title')
    {{ $messagesLog->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($messagesLog, ['route' => ['admin.messages-logs.update', $messagesLog->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.messages_logs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection