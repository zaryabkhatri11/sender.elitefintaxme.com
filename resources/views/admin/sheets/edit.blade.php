@extends('admin.layouts.app')

@section('title')
    {{ $sheet->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($sheet, ['route' => ['admin.sheets.update', $sheet->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.sheets.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection