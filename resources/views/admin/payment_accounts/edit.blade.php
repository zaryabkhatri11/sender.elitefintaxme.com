@extends('admin.layouts.app')

@section('title')
    {{ $paymentAccount->name }} <small>{{ $title }}</small>
@endsection

@section('content')
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($paymentAccount, ['route' => ['admin.payment-accounts.update', $paymentAccount->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('admin.payment_accounts.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection