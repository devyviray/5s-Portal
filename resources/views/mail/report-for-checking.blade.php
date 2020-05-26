<div>
    <p>
        Dear 5S Inspector,<br><br>

        Good Day!<br><br>

        Your inspection to below area has been rejected by the process owner:<br><br>

        Inspection date	: {{ \Carbon\Carbon::parse($report->date_of_inspection)->isoFormat('Do MMM YYYY') }}<br>
        Area	: {{ $area->name }} <br>
        Reason of rejection	: {{ $report->non_acceptance_reason }}
    </p><br>
    <h1>
        <span style="color:#fa3a0e">
            You have 24 – 72 hours to respond to the report otherwise, final rating will be posted.
        </span>
    </h1><br>
    <a href="{{ url('validate-report', ['reportId' => $report->id]) }}">View Your report here</a><br>
</div>