<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter name', 'required']) !!}
</div>

<!-- Gateway Field -->
<div class="form-group col-sm-6">
    {!! Form::label('gateway', 'Gateway:') !!}
    {!! Form::select('gateway', ['square' => 'Square', 'paypal' => 'PayPal'], null, ['class' => 'form-control', 'placeholder' => 'Select gateway', 'required']) !!}
</div>

<!-- Credentials Field -->
<div class="form-group col-sm-12">
    {!! Form::label('credentials', 'Credentials:') !!}
    <div id="credentials-wrapper"></div>
    <button type="button" class="btn btn-sm btn-success mt-2" onclick="addCredentialField()">Add Credential</button>
</div>

<!-- Is Active Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_active', 'Is Active:') !!}
    {!! Form::select('is_active', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!isset($paymentAccount))
        {!! Form::submit(__('Save And Add Translations'), ['class' => 'btn btn-primary', 'name' => 'translation']) !!}
    @endif
    {!! Form::submit(__('Save And Add More'), ['class' => 'btn btn-primary', 'name' => 'continue']) !!}
    <a href="{!! route('admin.payment-accounts.index') !!}" class="btn btn-default">Cancel</a>
</div>

@push('scripts')
    <script>
        function addCredentialField(key = '', value = '') {
            const html = `
                <div class="row credential-row mb-2" style="margin-bottom: 5px;">
                    <div class="col-sm-5">
                        <input type="text" class="form-control cred-key" placeholder="Key (e.g. client_id, secret, access_token)" value="${key}" required>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control cred-val" placeholder="Value" value="${value}" required>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('.credential-row').remove()"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#credentials-wrapper').append(html);
        }

        $(document).ready(function () {
            // Load existing Credentials if any
            let existingCredentials = null;
            @if(isset($paymentAccount) && $paymentAccount->credentials)
                existingCredentials = {!! json_encode($paymentAccount->credentials) !!};
            @endif

            if (existingCredentials && typeof existingCredentials === 'object' && Object.keys(existingCredentials).length > 0) {
                $.each(existingCredentials, function (key, value) {
                    addCredentialField(key, value);
                });
            } else {
                addCredentialField(); // Add one empty row by default
            }

            // On form submit, map the visible inputs to name="credentials[key]"
            $('form').on('submit', function () {
                $('.dynamic-cred').remove(); // Clear previous
                let valid = true;
                $('#credentials-wrapper .credential-row').each(function () {
                    let key = $(this).find('.cred-key').val().trim();
                    let val = $(this).find('.cred-val').val().trim();
                    if (key) {
                        $(this).append(`<input type="hidden" class="dynamic-cred" name="credentials[${key}]" value="${val}" />`);
                    } else {
                        valid = false;
                    }
                });
                if (!valid) {
                    alert('Please ensure all credential keys are filled.');
                    return false;
                }
            });
        });
    </script>
@endpush