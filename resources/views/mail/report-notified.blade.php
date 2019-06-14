<div>
    <p>Your report for the month of ..... is about to finalize  <br>
        Please click the link to view report
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">{{ url('view-report', ['reportId' => $report->id]) }}</a>
</div>