@if($data['status'] == 'error')
    <table>
        <tr>
            <td style="color: red"> {{ $data['message'] }} </td>
        </tr>
    </table>
@else

    <div class="widget-wrap material-table-widget">
        <div class="widget-container margin-top-0">
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table dataTable" id="reward_history" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['history'] as $history)
                                <tr>
                                    <td> {{ $history['txn_date'] }} </td>
                                    <td> {{ $history['debit'] }} </td>
                                    <td> {{ $history['credit'] }} </td>
                                    <td> {{ $history['balance'] }} </td>
                                    <td> {{ $history['desc'] }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endif

<script>

    $(document).ready(function() {
        //$("#reward_history").DataTable();

    });

</script>