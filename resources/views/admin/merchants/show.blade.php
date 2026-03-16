@extends('admin.layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <dl class="dl-horizontal">
                        @include('admin.merchants.show_fields')
                    </dl>
                    {!! Form::open(['route' => ['admin.merchants.destroy', $merchant->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        @ability('super-admin', 'merchants.show')
                        <a href="{!! route('admin.merchants.index') !!}" class="btn btn-default">
                            <i class="glyphicon glyphicon-arrow-left"></i> Back
                        </a>
                        @endability
                    </div>
                    <div class='btn-group'>
                        @ability('super-admin', 'merchants.edit')
                        <a href="{{ route('admin.merchants.edit', $merchant->id) }}" class='btn btn-default'>
                            <i class="glyphicon glyphicon-edit"></i> Edit
                        </a>
                        @endability
                    </div>
                    <div class='btn-group'>
                        @ability('super-admin', 'merchants.destroy')
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> Delete', [
        'type' => 'submit',
        'class' => 'btn btn-danger',
        'onclick' => "confirmDelete($(this).parents('form')[0]); return false;"
    ]) !!}
                        @endability
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Invoices</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>UUID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchant->invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->uuid }}</td>
                                <td>{{ $invoice->amount }} {{ $invoice->currency }}</td>
                                <td>
                                    @php
                                        $color = $invoice->status == 'paid' ? 'success' : ($invoice->status == 'pending' ? 'warning' : 'danger');
                                    @endphp
                                    <span class="label label-{{ $color }}">{{ ucfirst($invoice->status) }}</span>
                                </td>
                                <td>{{ $invoice->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection