@extends('admin.layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.sheets.store', 'files' => true, 'class' => 'ajax-post']) !!}

                    @include('admin.sheets.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div id="sendBox" style="display:none; margin-top:15px;">
        <div class="progress">
            <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" style="width:0%">
                0%
            </div>
        </div>

        <div class="well" style="max-height:250px; overflow:auto;">
            <ul id="logList" style="margin:0; padding-left:18px;"></ul>
        </div>
    </div>
    <div id="sendPanel" class="box box-success" style="display:none;">
        <div class="box-header with-border">
            <h3 class="box-title">Email Sending Progress</h3>
        </div>

        <div class="box-body">
            <div class="row" style="margin-bottom:10px;">
                <div class="col-sm-3"><b>Total:</b> <span id="st_total">0</span></div>
                <div class="col-sm-3"><b>Sent:</b> <span id="st_sent">0</span></div>
                <div class="col-sm-3"><b>Success:</b> <span id="st_success">0</span></div>
                <div class="col-sm-3"><b>Failed:</b> <span id="st_failed">0</span></div>
            </div>

            <div class="progress">
                <div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" style="width:0%">
                    0%
                </div>
            </div>

            <hr>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="logsTable">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Email</th>
                            <th style="width:90px;">Status</th>
                            <th>Error</th>
                            <th style="width:170px;">Time</th>
                        </tr>
                    </thead>
                    <tbody id="logsTbody"></tbody>
                </table>
            </div>
        </div>
    </div>

@endsection