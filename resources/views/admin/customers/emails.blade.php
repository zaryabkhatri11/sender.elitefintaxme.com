@extends('admin.layouts.app')

@section('title')
    {{ $customer->owner_name }} - Conversation History
@endsection

@push('css')
    <style>
        /* Radical Full-Screen Force */
        html,
        body,
        .wrapper,
        .content-wrapper {
            height: 100vh !important;
            min-height: 100vh !important;
            overflow: hidden !important;
            margin-bottom: 0 !important;
        }

        /* Eliminate all AdminLTE vertical spacing */
        .main-header,
        .main-footer,
        .content-header,
        .breadcrumb {
            display: none !important;
        }

        .content-wrapper {
            background: #fff !important;
            padding: 0 !important;
            margin: 0 !important;
            margin-left: 230px !important;
            /* Sidebar width */
            height: 100vh !important;
            position: relative !important;
        }

        .sidebar-collapse .content-wrapper {
            margin-left: 50px !important;
        }

        .content {
            padding: 0 !important;
            margin: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
        }

        .direct-chat-container {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            width: 100% !important;
            background: #f4f7f6 !important;
        }

        .box.direct-chat {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            margin: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .box-header {
            background: #fff !important;
            border-bottom: 2px solid #dee2e6 !important;
            padding: 20px 30px !important;
            flex-shrink: 0 !important;
        }

        .box-body {
            flex: 1 !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 0 !important;
        }

        .direct-chat-messages {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 40px !important;
            background: #f4f7f6 !important;
        }

        .box-footer {
            flex-shrink: 0 !important;
            padding: 30px 40px !important;
            background: #fff !important;
            border-top: 1px solid #dee2e6 !important;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.05) !important;
        }

        /* Bubble Styles */
        .direct-chat-text {
            border-radius: 12px;
            padding: 15px 25px;
            background: #fff;
            border: 1px solid #e1e4e8;
            max-width: 95%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            font-size: 16px;
            word-wrap: break-word;
            /* Prevent overflow */
            word-break: break-all;
            /* For super long strings without spaces */
        }

        .chat-body-content {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .direct-chat-msg {
            margin-bottom: 40px;
        }

        #messageInput {
            height: 80px;
            /* Slightly taller for textarea */
            border-radius: 20px;
            padding: 15px 20px;
            font-size: 16px;
            border: 2px solid #eee;
            resize: none;
            /* Disable manual resize to keep UI clean */
        }

        #sendBtn {
            border-radius: 35px;
            padding: 0 50px;
            height: 65px;
            font-weight: 700;
            font-size: 16px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-flat-round {
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 13px;
        }

        iframe {
            max-width: 100%;
            border-radius: 8px;
            background: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="direct-chat-container">
        <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Conversation: {{ $customer->owner_name }} ({{ $customer->email }})</h3>
                <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="{{ $emails->count() }} Messages"
                        class="badge bg-light-blue">{{ $emails->count() }}</span>
                </div>
            </div>

            <div class="box-body">
                <div class="direct-chat-messages">
                    @if($emails->isEmpty())
                        <div class="text-center" style="padding: 100px 0;">
                            <i class="fa fa-comments-o fa-4x text-muted"></i>
                            <p style="margin-top: 15px; color: #777; font-size: 18px;">Start a conversation with
                                {{ $customer->owner_name }}
                            </p>
                        </div>
                    @else
                        @foreach($emails->sortBy('created_at') as $email)
                            @include('admin.customers.email_bubble', ['email' => $email])
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="box-footer">
                <form id="sendEmailForm">
                    @csrf
                    <input type="hidden" name="thread_id" value="{{ $thread_id ?? '' }}">
                    <input type="hidden" name="in_reply_to"
                        value="{{ isset($emails) && $emails->isNotEmpty() ? $emails->last()->message_id : '' }}">
                    <input type="hidden" name="subject"
                        value="{{ isset($emails) && $emails->isNotEmpty() ? $emails->first()->subject : '' }}">

                    <div class="form-group" style="margin-bottom: 10px;">
                        @php
                            $attachmentFiles = glob(public_path('attachments/*.*'));
                        @endphp
                        <select name="attachment" id="attachmentSelect" class="form-control" style="border-radius: 20px;">
                            <option value="">-- No Attachment --</option>
                            @if($attachmentFiles)
                                @foreach($attachmentFiles as $file)
                                    @php $filename = basename($file); @endphp
                                    <option value="{{ $filename }}">{{ $filename }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="input-group">
                        <textarea name="message" id="messageInput" class="form-control"
                            placeholder="Type your reply here... (Press Enter for new line, Shift+Enter to send)"
                            required></textarea>
                        <span class="input-group-btn">
                            <button type="submit" id="sendBtn" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i> Send
                            </button>
                        </span>
                    </div>
                </form>
                <div id="statusMsg" style="margin-top: 10px; display: none; font-size: 14px; font-weight: 600;"></div>

                <div class="action-buttons">
                    <a href="{{ route('admin.customers.threads', $customer->id) }}" class="btn btn-default btn-flat-round">
                        <i class="fa fa-comments"></i> Back to Conversations
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-default btn-flat-round">
                        <i class="fa fa-arrow-left"></i> Back to Customers
                    </a>
                    <button onclick="window.location.reload();" class="btn btn-default btn-flat-round">
                        <i class="fa fa-refresh"></i> Refresh Thread
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            var chatBox = $(".direct-chat-messages");

            // Scroll to bottom on load
            function scrollToBottom() {
                chatBox.animate({ scrollTop: chatBox[0].scrollHeight }, 500);
            }

            // Initial scroll
            setTimeout(scrollToBottom, 100);

            $('#sendEmailForm').on('submit', function (e) {
                e.preventDefault();

                var message = $('#messageInput').val().trim();
                var btn = $('#sendBtn');
                var status = $('#statusMsg');

                if (!message) return;

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                status.hide().removeClass('text-danger text-success');

                var formData = {
                    _token: "{{ csrf_token() }}",
                    message: message,
                    attachment: $('#attachmentSelect').val(),
                    thread_id: $('input[name="thread_id"]').val(),
                    in_reply_to: $('input[name="in_reply_to"]').val(),
                    subject: $('input[name="subject"]').val()
                };

                $.ajax({
                    url: "{{ route('admin.customers.send_email', $customer->id) }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        console.log("AJAX Success. Response:", response);
                        btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Send');

                        if (response.success) {
                            $('#messageInput').val('');
                            $('.direct-chat-messages .text-center').remove();

                            if (response.html) {
                                console.log("Appending HTML:", response.html.substring(0, 100) + "...");
                                $('.direct-chat-messages').append(response.html);
                                scrollToBottom();
                            } else {
                                console.error("Success: true but html is empty or missing!");
                            }

                            status.addClass('text-success').text(response.message).show().fadeOut(3000);
                        } else {
                            console.error("Success: false. Message:", response.message);
                            status.addClass('text-danger').text(response.message).show();
                        }
                    },
                    error: function (xhr) {
                        console.error("AJAX Error details:", xhr);
                        btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Send');
                        var error = "Failed to send email. Check configuration.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            error = xhr.responseJSON.message;
                        }
                        status.addClass('text-danger').text(error).show();
                    }
                });
            });

            // Enter to send, Shift+Enter for new line
            $('#messageInput').on('keydown', function (e) {
                if (e.which == 13 && !e.shiftKey) {
                    e.preventDefault(); // Prevent inserting new line
                    $('#sendEmailForm').submit();
                }
            });
        });
    </script>
@endpush