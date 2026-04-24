@extends('admin.layouts.app')

@section('title')
    Create Campaign
@endsection

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create New Campaign</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.campaigns.store', 'files' => true]) !!}


                        @include('admin.campaign.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
