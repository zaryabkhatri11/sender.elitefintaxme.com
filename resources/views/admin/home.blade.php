@extends('admin.layouts.app')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                @include("admin.counter_widget", [
                    "bgColor" => "aqua",
                    "counter" => $totalCustomers,
                    "title" => "Total Customers",
                    "icon" => 'fa fa-users',
                    "route" => route('admin.customers.index')
                ])
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                @include("admin.counter_widget", [
                    "bgColor" => "green",
                    "counter" => $activeCustomers,
                    "title" => "Active Customers",
                    "icon" => 'fa fa-user-plus',
                    "route" => route('admin.customers.index')
                ])
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                @include("admin.counter_widget", [
                    "bgColor" => "blue",
                    "counter" => $android,
                    "title" => "Android Users",
                    "icon" => 'fa fa-android',
                    "route" => route('admin.users.index')
                ])
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                @include("admin.counter_widget", [
                    "bgColor" => "yellow",
                    "counter" => $ios,
                    "title" => "iOS Users",
                    "icon" => 'fa fa-apple',
                    "route" => route('admin.users.index')
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Latest Registered Devices</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-hover dashboard-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Device Type</th>
                                    <th>Token Preview</th>
                                    <th class="text-right">Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestDevices as $device)
                                    <tr>
                                        <td>
                                            <div class="user-avatar-text mr-2" style="display:inline-block; width: 30px; height: 30px; line-height: 30px; border-radius: 50%; background: #eee; text-align: center; font-weight: bold; margin-right: 10px;">
                                                {{ $device->user ? strtoupper(substr($device->user->name, 0, 1)) : 'G' }}
                                            </div>
                                            <span style="font-weight: 600;">{{ $device->user ? $device->user->name : 'Guest' }}</span>
                                        </td>
                                        <td>
                                            @if(strtolower($device->device_type) == 'android')
                                                <span class="label label-info"><i class="fa fa-android"></i> Android</span>
                                            @elseif(strtolower($device->device_type) == 'ios')
                                                <span class="label label-warning"><i class="fa fa-apple"></i> iOS</span>
                                            @else
                                                <span class="label label-default"><i class="fa fa-globe"></i> {{ $device->device_type }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code title="{{ $device->device_token }}" style="cursor: help; background: #f4f4f4; color: #555; padding: 2px 6px; border-radius: 4px;">{{ substr($device->device_token, 0, 30) }}...</code>
                                        </td>
                                        <td class="text-right">
                                            <span class="text-muted" style="font-size: 13px;">
                                                <i class="fa fa-clock-o"></i> 
                                                {{ $device->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
@endsection
@push("scripts")
    <script>

        var deviceData =
                {!! json_encode($deviceGraph) !!}
        var device = new Morris.Bar({
                element: 'deviceGraph',
                resize: true,
                deviceData,
                barColors: ['#00a65a', '#f56954'],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Android', 'iOS'],
                hideHover: 'auto'
            });

    </script>
@endpush