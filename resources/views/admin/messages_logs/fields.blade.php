<!-- User Id Field (Hidden) -->
{!! Form::hidden('user_id', Auth::id()) !!}

<!-- Customer Selection -->
<div class="form-group col-sm-12">
    {!! Form::label('customer_id', 'Select Customers:') !!}
    <select name="customer_id[]" id="customer_id" class="form-control select2" multiple="multiple">
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}">{{ $customer->owner_name }} ({{ $customer->phone }})</option>
        @endforeach
    </select>
</div>

<!-- Recipients (Phone Numbers) -->
<div class="form-group col-sm-12">
    {!! Form::label('to_num', 'Recipients (Phone Numbers):') !!}
    <select name="to_num[]" id="to_num" class="form-control" multiple="multiple">
    </select>
    <small class="text-muted">Select customers above or type phone numbers and press Enter.</small>
</div>

<!-- Direction Field (Hidden) -->
{!! Form::hidden('direction', 'outbound') !!}

<!-- Body Field -->
<div class="form-group col-sm-12">
    {!! Form::label('body', 'Message Body:') !!}
    {!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => 3, 'required', 'placeholder'=>'Type your message here...']) !!}
</div>

<!-- Hidden / Automated Fields (Populated by Backend) -->
{!! Form::hidden('status', 'pending') !!}

<!-- Submit Field -->
<div class="form-group col-sm-12">
    <hr>
    {!! Form::submit('Send Message(s)', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.messages-logs.index') !!}" class="btn btn-default">Cancel</a>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var $customerSelect = $('#customer_id');
        var $phoneSelect = $('#to_num');

        // Initialize Customer Select
        $customerSelect.select2({
            placeholder: "Select customers",
            allowClear: true,
            width: '100%'
        });

        // Initialize Phone Select with Tags
        function initPhoneSelect() {
            if ($phoneSelect.data('select2')) {
                $phoneSelect.select2('destroy');
            }
            $phoneSelect.select2({
                placeholder: "Type phone and press Enter",
                tags: true,
                tokenSeparators: [',', ' '],
                width: '100%',
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') return null;
                    return {
                        id: term,
                        text: term,
                        newTag: true
                    }
                }
            });
        }
        
        initPhoneSelect();

        // Sync logic: Customer selection -> Tags in Select2
        $customerSelect.on('change', function() {
            var selectedCustomers = $(this).find('option:selected');
            var currentTags = $phoneSelect.val() || [];
            
            // Collect customer phones
            var customerPhones = [];
            selectedCustomers.each(function() {
                var p = $(this).attr('data-phone') || $(this).data('phone');
                if (p) {
                    var sanitized = p.toString().trim();
                    if (sanitized) customerPhones.push(sanitized);
                }
            });

            // We want to keep manual tags that are NOT in the customer list
            // 1. Identify which tags are already manual
            // To be robust, let's just clear and rebuild the option list for Select2
            $phoneSelect.empty();
            
            // 2. Add all customer phones as selected options
            customerPhones.forEach(function(phone) {
                if ($phoneSelect.find("option[value='" + phone + "']").length === 0) {
                    var newOption = new Option(phone, phone, true, true);
                    $phoneSelect.append(newOption);
                }
            });

            // 3. Add back any manual tags that aren't customer phones
            currentTags.forEach(function(tag) {
                if (customerPhones.indexOf(tag) === -1) {
                    if ($phoneSelect.find("option[value='" + tag + "']").length === 0) {
                        var newOption = new Option(tag, tag, true, true);
                        $phoneSelect.append(newOption);
                    }
                }
            });

            $phoneSelect.trigger('change');
        });

        // Ensure initialization after theme scripts
        setTimeout(initPhoneSelect, 1500);
    });
</script>
@endpush