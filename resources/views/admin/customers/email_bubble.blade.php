@php 
    $isIncoming = ($email->direction == 'incoming');
@endphp

<div class="direct-chat-msg {{ $isIncoming ? '' : 'right' }} clearfix">
    <div class="direct-chat-info">
        <span class="direct-chat-name">
            {{ $isIncoming ? $email->from_email : 'Admin (From: ' . $email->from_email . ')' }}
        </span>
        <span class="direct-chat-timestamp">
            {{ $email->created_at->format('M d, Y h:i A') }}
        </span>
        @if(!$isIncoming)
            @if($email->status == 'sent')
                <i class="fa fa-check-circle text-success" title="Sent Successfully"></i>
            @else
                <i class="fa fa-exclamation-triangle text-danger" title="Failed: {{ $email->error }}"></i>
            @endif
        @endif
    </div>

    <div class="direct-chat-text">
        @if($email->subject)
            <div class="chat-subject"><strong>Sub:</strong> {{ $email->subject }}</div>
            <hr style="margin: 5px 0; border-color: rgba(0,0,0,0.1);">
        @endif
        <div class="chat-body-content">
            @if(strip_tags($email->message) != $email->message)
                <iframe srcdoc="{{ $email->message }}" style="width: 100%; border: none; height: 300px; background: white;"
                    onload="this.style.height=this.contentWindow.document.body.scrollHeight + 'px';"></iframe>
            @else
                {!! nl2br(e($email->message)) !!}
            @endif
        </div>

        @if($email->attachment)
            <div class="chat-attachment"
                style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed rgba(0,0,0,0.1);">
                <a href="{{ asset('public/attachments/' . basename($email->attachment)) }}" target="_blank" download
                    class="btn btn-default btn-xs" style="border-radius: 12px; font-size: 13px; font-weight: 600;">
                    <i class="fa fa-download"></i> {{ basename($email->attachment) }}
                </a>
            </div>
        @endif
    </div>
</div>