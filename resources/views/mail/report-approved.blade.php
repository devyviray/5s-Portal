<div>
    <p>Process owner {{ $processOwner->name }} approved report for the month of <br>
        Please click the link to view report
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">{{ url('view-report', ['reportId' => $report->id]) }}</a>
</div>