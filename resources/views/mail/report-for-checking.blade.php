<div>
    <p>Process owner {{ $processOwner->name }} has resent report for the month of ...... for checking <br>
        Please click the link to view report
    </p><br>
    <a href="{{ url('validate-report', ['reportId' => $report->id]) }}">{{ url('validate-report', ['reportId' => $report->id]) }}</a>
</div>