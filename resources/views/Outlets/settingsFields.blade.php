<form id="account-settings">

    <div class="col-md-12">
        <div class="col-md-4">
            <label class="checkbox">
                <input type="checkbox" name="active" value="1"  @if(isset($account) && $account->active == 1) checked @endif>
                <i></i> Active
            </label>
        </div>

        <div class="col-md-4">
            <label class="checkbox">
                <input type="checkbox" name="enable_inventory" value="1" @if(isset($account) && $account->enable_inventory == 1) checked @endif>
                <i></i> Enable Inventory
            </label>
        </div>

        <div class="col-md-4">
            <label class="checkbox">
                <input type="checkbox" name="enable_cancellation_report" value="1" @if(isset($account) && $account->enable_cancellation_report == 1) checked @endif>
                <i></i> Enable Cancellation Report
            </label>
        </div>

        <div class="col-md-4">
            <label class="checkbox">
                <input type="checkbox" name="allow_order_delete" value="1" @if(isset($account) && $account->allow_order_delete == 1) checked @endif>
                <i></i> Allow Order Delete
            </label>
        </div>

        <div class="col-md-4">
            <label class="checkbox">
                <input type="checkbox" name="enable_feedback" value="1" @if(isset($account) && $account->enable_feedback == 1) checked @endif>
                <i></i> Enable Feedback
            </label>
        </div>

        <div class="col-md-4">
            <label class="checkbox">
                <input type="checkbox" name="can_invoice_reset" value="1" @if(isset($account) && $account->can_invoice_reset == 1) checked @endif>
                <i></i> Reset InvoiceNumber
            </label>
        </div>
    </div>
    <div class="form-footer">
        <div class="col-md-8">
            <button class="btn primary-btn btn-success" type="button" title="Save Settings" id="submit_btn" onclick="storeSetting()">Submit</button>
        </div>
    </div>

</form>
