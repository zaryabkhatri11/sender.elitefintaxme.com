@extends('admin.layouts.app')

@section('title')
    {{ $customer->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($customer, ['route' => ['admin.customers.update', $customer->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.customers.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection