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

    /* Dial Pad Styles */
    .dial-pad-container {
        width: 100%;
        max-width: 320px;
        margin: 0 auto;
        padding: 20px;
    }
    .dial-display {
        width: 100%;
        font-size: 28px;
        text-align: center;
        border: none;
        background: none;
        margin-bottom: 25px;
        letter-spacing: 2px;
        font-weight: 500;
        color: #111b21;
    }
    .dial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        justify-items: center;
    }
    .dial-btn {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: 1px solid #e9edef;
        background: #fff;
        font-size: 22px;
        font-weight: 400;
        color: #111b21;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }
    .dial-btn:hover {
        background: #f0f2f5;
    }
    .dial-btn:active {
        background: #dfe5e7;
        transform: scale(0.95);
    }
    .dial-btn small {
        font-size: 10px;
        color: #667781;
        text-transform: uppercase;
        margin-top: -2px;
    }
    .dial-btn.btn-call {
        background: #008069;
        color: #fff;
        border: none;
        width: 70px;
        height: 70px;
        font-size: 28px;
    }
    .dial-btn.btn-call:hover {
        background: #00a884;
    }
    .dial-btn.btn-delete {
        border: none;
        color: #667781;
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
                <div class="box-tools pull-right" style="padding-top: 5px; display: flex; align-items: center; gap: 8px;">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dialPadModal">
                        <i class="fa fa-th"></i> Dial Pad
                    </button>
                    <a class="btn btn-primary" href="{{ route('admin.messages-logs.create') }}">Add New</a>
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

@push('scripts')
{{-- Dial Pad Modal --}}
<div class="modal fade" id="dialPadModal" tabindex="-1" role="dialog" aria-labelledby="dialPadModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="width: 360px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #008069; color: #fff; border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="dialPadModalLabel" style="font-weight: 600;">Dial Pad</h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div class="dial-pad-container">
                    <input type="text" id="dial-input" class="dial-display" placeholder="Enter number" readonly>
                    
                    <div class="dial-grid">
                        <button type="button" class="dial-btn" data-val="1">1</button>
                        <button type="button" class="dial-btn" data-val="2">2<small>ABC</small></button>
                        <button type="button" class="dial-btn" data-val="3">3<small>DEF</small></button>
                        
                        <button type="button" class="dial-btn" data-val="4">4<small>GHI</small></button>
                        <button type="button" class="dial-btn" data-val="5">5<small>JKL</small></button>
                        <button type="button" class="dial-btn" data-val="6">6<small>MNO</small></button>
                        
                        <button type="button" class="dial-btn" data-val="7">7<small>PQRS</small></button>
                        <button type="button" class="dial-btn" data-val="8">8<small>TUV</small></button>
                        <button type="button" class="dial-btn" data-val="9">9<small>WXYZ</small></button>
                        
                        <button type="button" class="dial-btn" data-val="*">*</button>
                        <button type="button" class="dial-btn" data-val="0">0<small>+</small></button>
                        <button type="button" class="dial-btn" data-val="#">#</button>
                        
                        <div></div>
                        <button type="button" class="dial-btn btn-call" id="dial-call-btn" title="Call">
                            <i class="fa fa-phone"></i>
                        </button>
                        <button type="button" class="dial-btn btn-delete" id="dial-backspace" title="Delete">
                            <i class="fa fa-long-arrow-left"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- WhatsApp-Style Call Overlay (Shared logic) --}}
<div id="call-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.85); align-items:center; justify-content:center; flex-direction:column; color: #fff;">
    <div style="background:#1a1a2e; border-radius:24px; padding:40px 50px; text-align:center; min-width:300px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
        <div style="width:80px;height:80px;border-radius:50%;background:#3498db;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:bold;margin:0 auto 16px;">
            <i class="fa fa-phone"></i>
        </div>
        <h3 id="call-overlay-number" style="margin:0 0 10px; font-size: 1.6rem;"></h3>
        <p id="call-overlay-status" style="color:#2ecc71; margin-bottom:25px; font-size: 1.1rem;">Initiating bridge...</p>
        <button onclick="document.getElementById('call-overlay').style.display='none'" class="btn btn-danger" style="width:65px; height:65px; border-radius:50%; font-size: 1.5rem;">
            <i class="fa fa-times"></i>
        </button>
    </div>
</div>

<script>
    $(function(){
        var $dialInput = $('#dial-input');

        // Number keys
        $('.dial-btn[data-val]').on('click', function(){
            $dialInput.val($dialInput.val() + $(this).data('val'));
        });

        // Backspace
        $('#dial-backspace').on('click', function(){
            var val = $dialInput.val();
            $dialInput.val(val.substring(0, val.length - 1));
        });

        // Long press backspace to clear
        var clearTimer;
        $('#dial-backspace').on('mousedown touchstart', function(){
            clearTimer = setTimeout(function(){ $dialInput.val(''); }, 600);
        }).on('mouseup mouseleave touchend', function(){
            clearTimeout(clearTimer);
        });

        // Initiate Call
        $('#dial-call-btn').on('click', function(){
            var phone = $dialInput.val();
            if (!phone) { alert('Please enter a number.'); return; }

            var adminPhone = prompt("Enter your phone number to receive the call bridge:", localStorage.getItem('admin_phone') || "");
            if (!adminPhone) return;
            localStorage.setItem('admin_phone', adminPhone);

            $('#dialPadModal').modal('hide');
            $('#call-overlay-number').text(phone);
            $('#call-overlay').css('display', 'flex');
            $('#call-overlay-status').text('Requesting bridge...').css('color', '#f39c12');

            $.post('{{ route('admin.messages-logs.initiate-call') }}', {
                _token: '{{ csrf_token() }}',
                to_num: phone,
                admin_phone: adminPhone
            }, function(res) {
                if (res.success) {
                    $('#call-overlay-status').text(res.message).css('color', '#2ecc71');
                    // Clear input after success
                    $dialInput.val('');
                    // Removed automatic reload to keep status visible
                } else {
                    $('#call-overlay-status').text('Error: ' + res.message).css('color', '#e74c3c');
                }
            }).fail(function(){
                $('#call-overlay-status').text('Server Error').css('color', '#e74c3c');
            });
        });
    });
</script>
@endpush


