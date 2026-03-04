@extends('admin.layouts.app')

@section('title')
    {{ $invoice->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($invoice, ['route' => ['admin.invoices.update', $invoice->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.invoices.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection