@extends('admin.layouts.app')

@section('title')
    {{ $title }}
@endsection

@push('css')
<style>
    .chat-list {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .chat-item {
        display: flex;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none !important;
        color: inherit !important;
    }
    .chat-item:hover {
        background: #f9f9f9;
        text-decoration: none;
    }
    .chat-item:last-child {
        border-bottom: none;
    }
    .chat-avatar {
        width: 50px;
        height: 50px;
        background: #008069;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: bold;
        margin-right: 15px;
        flex-shrink: 0;
    }
    .chat-info {
        flex-grow: 1;
        overflow: hidden;
    }
    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 4px;
    }
    .chat-name {
        font-weight: 600;
        font-size: 16px;
        color: #111b21;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-time {
        font-size: 12px;
        color: #667781;
        flex-shrink: 0;
    }
    .chat-preview {
        font-size: 14px;
        color: #667781;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-phone {
        font-size: 13px;
        color: #8696a0;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Conversations</h3>
                <div class="box-tools pull-right">
                    <a class="btn btn-primary pull-right" style="margin-top: -5px;margin-bottom: 5px" href="{{ route('admin.messages-logs.create') }}">Add New</a>
                </div>
            </div>
            <div class="box-body no-padding">
                <div class="chat-list">
                    @forelse($threads as $thread)
                        @php($name = $thread->customer ? $thread->customer->owner_name : 'Unknown')
                        @php($phone = $thread->customer ? $thread->customer->phone : ($thread->direction == 'outbound' ? $thread->to_num : $thread->from_num))
                        <a href="{{ route('admin.messages-logs.show', $thread->id) }}" class="chat-item">
                            <div class="chat-avatar">
                                {{ substr($name, 0, 1) }}
                            </div>
                            <div class="chat-info">
                                <div class="chat-header">
                                    <span class="chat-name">{{ $name }}</span>
                                    <span class="chat-time">{{ $thread->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="chat-preview">
                                    {{ $thread->body }}
                                </div>
                                <div class="chat-phone">
                                    {{ $phone }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center" style="padding: 40px;">
                            <i class="fa fa-comments-o" style="font-size: 48px; color: #ddd; margin-bottom: 10px;"></i>
                            <p style="color: #999;">No conversations found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

