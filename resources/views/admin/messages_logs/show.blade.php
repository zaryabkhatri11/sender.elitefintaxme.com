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
                            <div class="message-bubble {{ $msg->direction == 'outbound' ? 'message-outbound' : 'message-inbound' }}">
                                {{ $msg->body }}
                                <span class="message-time">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
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

        <div style="display:flex; gap:24px; justify-content:center; align-items:center; flex-wrap:wrap;">
            {{-- Mute --}}
            <div style="text-align:center;">
                <button id="btn-mute" onclick="toggleMute()" title="Mute"
                    style="width:60px;height:60px;border-radius:50%;border:none;background:#2c2c4e;color:#fff;font-size:1.3rem;cursor:pointer;transition:all .3s;">
                    <i class="fa fa-microphone"></i>
                </button>
                <p style="margin:6px 0 0; font-size:0.75rem; color:#aaa;">Mute</p>
            </div>

            {{-- End Call --}}
            <div style="text-align:center;">
                <button id="btn-end-call" onclick="endCall()" title="End Call"
                    style="width:70px;height:70px;border-radius:50%;border:none;background:#e74c3c;color:#fff;font-size:1.6rem;cursor:pointer;box-shadow:0 0 20px rgba(231,76,60,0.6);">
                    <i class="fa fa-phone" style="transform:rotate(135deg); display:inline-block;"></i>
                </button>
                <p style="margin:6px 0 0; font-size:0.75rem; color:#e74c3c;">End</p>
            </div>

            {{-- Speaker (placeholder) --}}
            <div style="text-align:center;">
                <button onclick="" title="Speaker"
                    style="width:60px;height:60px;border-radius:50%;border:none;background:#2c2c4e;color:#fff;font-size:1.3rem;cursor:pointer;">
                    <i class="fa fa-volume-up"></i>
                </button>
                <p style="margin:6px 0 0; font-size:0.75rem; color:#aaa;">Speaker</p>
            </div>
        </div>
    </div>
</div>
{{-- Twilio Voice JS SDK (official media CDN) --}}
<script src="https://media.twiliocdn.com/sdk/js/client/v1.14/twilio.min.js"></script>

<script>
    // ─── Chat Scroll ───────────────────────────────────────────────────────────
    var chatMessages = document.getElementById('chat-messages');
    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;

    // ─── Call State ────────────────────────────────────────────────────────────
    var twilioDevice  = null;
    var activeCall    = null;
    var isMuted       = false;
    var callTimerInt  = null;
    var callSeconds   = 0;

    // ─── Fetch Access Token & Setup Device ────────────────────────────────────
    function setupTwilioDevice(onReady) {
        $.getJSON('{{ route('api.twilio.token') }}', function(res){
            if (!res.success) {
                alert('Twilio not configured: ' + res.message);
                hideCallOverlay();
                return;
            }

            twilioDevice = new Twilio.Device(res.token, {
                codecPreferences: ['opus', 'pcmu'],
                allowIncomingWhileBusy: false,
                logLevel: 0
            });

            twilioDevice.on('ready', function() {
                setCallStatus('Ringing…', '#f39c12');
                if (onReady) onReady();
            });

            twilioDevice.on('error', function(err) {
                // Ignore non-critical audio device enumeration warnings
                var ignoreMessages = ['Devices not found', 'Unable to set audio output', 'InvalidArgumentError'];
                var isNonCritical = ignoreMessages.some(function(msg){ return err.message && err.message.indexOf(msg) !== -1; });
                if (isNonCritical) { return; }

                setCallStatus('Error: ' + err.message, '#e74c3c');
                setTimeout(hideCallOverlay, 3000);
            });

            twilioDevice.on('disconnect', function() {
                clearInterval(callTimerInt);
                setCallStatus('Call ended', '#e74c3c');
                setTimeout(hideCallOverlay, 1500);
            });

            twilioDevice.on('connect', function() {
                setCallStatus('Connected', '#2ecc71');
                document.getElementById('call-timer').style.display = 'block';
                callSeconds = 0;
                callTimerInt = setInterval(function(){
                    callSeconds++;
                    var m = Math.floor(callSeconds / 60);
                    var s = callSeconds % 60;
                    document.getElementById('call-timer').textContent = m + ':' + (s < 10 ? '0' : '') + s;
                }, 1000);
            });
        }).fail(function(){
            alert('Failed to reach token endpoint. Check live server routes.');
            hideCallOverlay();
        });
    }


    // ─── Initiate Call ────────────────────────────────────────────────────────
    $('#btn-call').on('click', function(){
        var phone      = $('#contact-phone').text().trim();
        var name       = '{{ $messagesLog->customer ? $messagesLog->customer->owner_name : "Contact" }}';
        var customerId = '{{ $messagesLog->customer_id }}';

        if (!phone) { alert('No phone number found.'); return; }
        if (!confirm('Start a voice call to ' + phone + '?')) return;

        showCallOverlay(name, phone);
        setCallStatus('Connecting…', '#f39c12');

        setupTwilioDevice(function(){
            activeCall = twilioDevice.connect({ To: phone, customer_id: customerId });
        });
    });

    // ─── Mute Toggle ─────────────────────────────────────────────────────────
    function toggleMute() {
        if (!activeCall) return;
        isMuted = !isMuted;
        activeCall.mute(isMuted);
        var btn = document.getElementById('btn-mute');
        btn.style.background = isMuted ? '#e74c3c' : '#2c2c4e';
        btn.innerHTML = '<i class="fa fa-microphone' + (isMuted ? '-slash' : '') + '"></i>';
    }

    // ─── End Call ────────────────────────────────────────────────────────────
    function endCall() {
        if (twilioDevice) twilioDevice.disconnectAll();
        clearInterval(callTimerInt);
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
        isMuted = false;
        document.getElementById('btn-mute').style.background = '#2c2c4e';
        document.getElementById('btn-mute').innerHTML = '<i class="fa fa-microphone"></i>';
    }

    function hideCallOverlay() {
        document.getElementById('call-overlay').style.display = 'none';
        document.getElementById('call-timer').textContent = '0:00';
        activeCall = null;
    }

    function setCallStatus(text, color) {
        var el = document.getElementById('call-status-text');
        el.textContent = text;
        el.style.color = color || '#2ecc71';
    }
</script>
@endpush