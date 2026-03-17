@extends('admin.layouts.app')

@section('title')
    {{ $messagesLog->customer ? $messagesLog->customer->owner_name : 'Conversation' }}
@endsection

@push('css')
<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 250px);
        background: #efe7de;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: relative;
    }
    .chat-container::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0.05;
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        pointer-events: none;
    }
    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 1;
    }
    .message-bubble {
        max-width: 75%;
        padding: 6px 12px 8px;
        border-radius: 8px;
        font-size: 14.5px;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        word-wrap: break-word;
        line-height: 1.4;
    }
    .message-outbound {
        align-self: flex-end;
        background: #d9fdd3;
        color: #111b21;
        border-top-right-radius: 0;
    }
    .message-inbound {
        align-self: flex-start;
        background: #fff;
        color: #111b21;
        border-top-left-radius: 0;
    }
    .message-time {
        font-size: 11px;
        color: #667781;
        margin-top: 2px;
        text-align: right;
        margin-left: 20px;
        float: right;
    }
    .chat-footer {
        background: #f0f2f5;
        padding: 10px 15px;
        border-top: 1px solid #ddd;
        z-index: 1;
    }
    .chat-header-custom {
        background: #f0f2f5;
        padding: 10px 15px;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .avatar-small {
        width: 40px;
        height: 40px;
        background: #008069;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .message-call {
        align-self: center;
        background: #e1f5fe;
        color: #01579b;
        border-radius: 8px;
        font-size: 13px;
        padding: 6px 12px;
        margin: 10px 0;
        border: 1px solid #b3e5fc;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .call-icon {
        background: #008069;
        color: #fff;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
</style>
@endpush

@section('content')
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body no-padding">
                <div class="chat-header-custom">
                    <a href="{{ route('admin.messages-logs.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <div class="avatar-small">
                        {{ substr($messagesLog->customer ? $messagesLog->customer->owner_name : 'U', 0, 1) }}
                    </div>
                    <div>
                        <h4 style="margin: 0; font-weight: 600;">{{ $messagesLog->customer ? $messagesLog->customer->owner_name : 'Unknown Contact' }}</h4>
                        <small class="text-muted" id="contact-phone">{{ $messagesLog->customer ? $messagesLog->customer->phone : ($messagesLog->direction == 'outbound' ? $messagesLog->to_num : $messagesLog->from_num) }}</small>
                    </div>
                    <div style="margin-left: auto;">
                        <button type="button" class="btn btn-success btn-sm" id="btn-call" title="Call Contact">
                            <i class="fa fa-phone"></i>
                        </button>
                    </div>
                </div>
                
                <div class="chat-container">
                    <div class="chat-messages" id="chat-messages">
                        @forelse($messages as $msg)
                            @if($msg instanceof \App\Models\CallLog)
                                <div class="message-bubble message-call">
                                    <div class="call-icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div>
                                        <strong>Voice Call</strong><br>
                                        <small>{{ ucfirst($msg->status) }} @if($msg->duration) • {{ $msg->duration }}s @endif</small>
                                    </div>
                                    <span class="message-time" style="margin-top: 10px;">
                                        {{ $msg->created_at->format('H:i') }}
                                    </span>
                                </div>
                            @else
                                <div class="message-bubble {{ $msg->direction == 'outbound' ? 'message-outbound' : 'message-inbound' }}">
                                    {{ $msg->body }}
                                    <span class="message-time">
                                        {{ $msg->created_at->format('H:i') }}
                                    </span>
                                </div>
                            @endif
                        @empty
                            <div class="text-center text-muted" style="margin-top: 50px;">
                                No messages in this conversation.
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="chat-footer">
                        <form action="{{ route('admin.messages-logs.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $messagesLog->customer_id }}">
                            <input type="hidden" name="to_num[]" value="{{ $messagesLog->customer ? $messagesLog->customer->phone : ($messagesLog->direction == 'outbound' ? $messagesLog->to_num : $messagesLog->from_num) }}">
                            <input type="hidden" name="from_num" value="{{ $messagesLog->from_num }}">
                            <input type="hidden" name="direction" value="outbound">
                            <input type="hidden" name="status" value="pending">
                            
                            <div class="input-group">
                                <input type="text" name="body" placeholder="Type a message..." class="form-control" autocomplete="off" required>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-flat">
                                        <i class="fa fa-paper-plane"></i> Send
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

{{-- WhatsApp-Style Call Overlay --}}
<div id="call-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.82); align-items:center; justify-content:center; flex-direction:column;">
    <div style="background:#1a1a2e; border-radius:24px; padding:40px 50px; text-align:center; color:#fff; min-width:300px; box-shadow:0 20px 60px rgba(0,0,0,0.6);">
        <div id="call-avatar" style="width:80px;height:80px;border-radius:50%;background:#3498db;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:bold;margin:0 auto 16px;">
            <span id="call-avatar-letter">?</span>
        </div>
        <h3 id="call-contact-name" style="margin:0 0 4px; font-size:1.4rem;">Loading...</h3>
        <p id="call-contact-number" style="color:#aaa; margin:0 0 8px; font-size:0.9rem;"></p>
        <p id="call-status-text" style="color:#2ecc71; font-size:0.9rem; margin:0 0 20px;">Connecting…</p>
        <p id="call-timer" style="font-size:2rem; font-weight:600; letter-spacing:4px; margin:0 0 30px; display:none;">0:00</p>

        <div style="display:flex; gap:24px; justify-content:center; align-items:center;">
            {{-- End Call --}}
            <div style="text-align:center;">
                <button onclick="hideCallOverlay()" title="Close Overlay"
                    style="width:70px;height:70px;border-radius:50%;border:none;background:#e74c3c;color:#fff;font-size:1.6rem;cursor:pointer;box-shadow:0 0 20px rgba(231,76,60,0.4);">
                    <i class="fa fa-times"></i>
                </button>
                <p style="margin:6px 0 0; font-size:0.75rem; color:#e74c3c;">Close</p>
            </div>
        </div>
    </div>
</div>


<script>
    // ─── Chat Scroll ───────────────────────────────────────────────────────────
    var chatMessages = document.getElementById('chat-messages');
    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;

    // ─── Initiate Call (Click-to-Call Bridge) ──────────────────────────────────
    $('#btn-call').on('click', function(){
        var phone      = $('#contact-phone').text().trim();
        var name       = '{{ $messagesLog->customer ? $messagesLog->customer->owner_name : "Contact" }}';
        var customerId = '{{ $messagesLog->customer_id }}';

        if (!phone) { alert('No phone number found.'); return; }

        var adminPhone = prompt("Enter your phone number to receive the call bridge:", localStorage.getItem('admin_phone') || "");
        if (!adminPhone) return;
        localStorage.setItem('admin_phone', adminPhone);

        showCallOverlay(name, phone);
        setCallStatus('Requesting bridge...', '#f39c12');

        $.post('{{ route('admin.messages-logs.initiate-call') }}', {
            _token: '{{ csrf_token() }}',
            to_num: phone,
            admin_phone: adminPhone,
            customer_id: customerId
        }, function(res) {
            if (res.success) {
                setCallStatus(res.message, '#2ecc71');
                // Removed automatic reload to keep status visible
            } else {
                setCallStatus('Error: ' + res.message, '#e74c3c');
                setTimeout(hideCallOverlay, 4000);
            }
        }).fail(function(){
            setCallStatus('Server Error', '#e74c3c');
            setTimeout(hideCallOverlay, 2000);
        });
    });

    // ─── End Call ────────────────────────────────────────────────────────────
    function endCall() {
        hideCallOverlay();
    }

    // ─── Overlay Helpers ─────────────────────────────────────────────────────
    function showCallOverlay(name, phone) {
        var letter = name ? name.charAt(0).toUpperCase() : '?';
        document.getElementById('call-avatar-letter').textContent = letter;
        document.getElementById('call-contact-name').textContent  = name;
        document.getElementById('call-contact-number').textContent = phone;
        document.getElementById('call-timer').style.display = 'none';
        document.getElementById('call-overlay').style.display = 'flex';
    }

    function hideCallOverlay() {
        document.getElementById('call-overlay').style.display = 'none';
    }

    function setCallStatus(text, color) {
        var el = document.getElementById('call-status-text');
        el.textContent = text;
        el.style.color = color || '#2ecc71';
    }
</script>
@endpush