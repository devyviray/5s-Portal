<div>
    <p>
    Good Day!<br><br>

    {{ $processOwner->name }} has already approved the submitted 5S Report.         
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">Thank you for using this application. </a>
</div>