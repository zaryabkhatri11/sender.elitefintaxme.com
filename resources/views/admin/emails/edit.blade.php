@extends('admin.layouts.app')

@section('title')
    {{ $email->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($email, ['route' => ['admin.emails.update', $email->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.emails.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection