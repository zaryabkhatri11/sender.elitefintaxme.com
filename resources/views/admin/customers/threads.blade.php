@extends('admin.layouts.app')

@section('title')
    {{ $customer->owner_name }} - Conversations
@endsection

@push('css')
    <style>
        .thread-list {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .thread-item {
            padding: 20px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            transition: background 0.2s;
            cursor: pointer;
            text-decoration: none !important;
            color: inherit !important;
        }

        .thread-item:hover {
            background: #f7fafc;
        }

        .thread-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ebf4ff;
            color: #3182ce;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .thread-content {
            flex: 1;
            min-width: 0;
        }

        .thread-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .thread-subject {
            font-weight: 700;
            font-size: 16px;
            color: #2d3748;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .thread-date {
            font-size: 12px;
            color: #a0aec0;
            flex-shrink: 0;
            margin-left: 10px;
        }

        .thread-snippet {
            font-size: 14px;
            color: #718096;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .thread-badge {
            margin-left: 15px;
        }

        .empty-state {
            padding: 100px 20px;
            text-align: center;
            color: #a0aec0;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <h1>
            Conversations: {{ $customer->owner_name }}
            <small>{{ $customer->email }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.customers.index') }}"><i class="fa fa-dashboard"></i> Customers</a></li>
            <li class="active">Conversations</li>
        </ol>
    </section>

    <div class="content">
        <div class="box box-primary">
            <div class="box-body no-padding">
                <div class="thread-list">
                    @if($threads->isEmpty())
                        <div class="empty-state">
                            <i class="fa fa-envelope-o fa-5x"></i>
                            <h3>No conversations found</h3>
                            <p>Emails sent or received for this customer will appear here.</p>
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-primary" style="margin-top: 20px;">
                                Back to Customers
                            </a>
                        </div>
                    @else
                        @foreach($threads as $threadId => $messages)
                            @php
                                $latest = $messages->first();
                                $count = $messages->count();
                            @endphp
                            <a href="{{ route('admin.customers.thread_detail', [$customer->id, $threadId]) }}" class="thread-item">
                                <div class="thread-icon">
                                    <i class="fa {{ $latest->direction === 'incoming' ? 'fa-reply' : 'fa-paper-plane' }}"></i>
                                </div>
                                <div class="thread-content">
                                    <div class="thread-header">
                                        <div class="thread-subject">{{ $latest->subject ?: '(No Subject)' }}</div>
                                        <div class="thread-date">{{ $latest->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="thread-snippet">
                                        <strong>{{ $latest->direction === 'incoming' ? 'Recipient' : 'You' }}:</strong>
                                        {{ str_limit(strip_tags($latest->message), 100) }}
                                    </div>
                                </div>
                                <div class="thread-badge">
                                    <span class="badge bg-light-blue">{{ $count }}</span>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="box-footer">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to Customers
                </a>
            </div>
        </div>
    </div>
@endsection