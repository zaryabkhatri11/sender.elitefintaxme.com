@extends('admin.layouts.app')

@section('title')
    {{ $merchant->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($merchant, ['route' => ['admin.merchants.update', $merchant->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.merchants.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection