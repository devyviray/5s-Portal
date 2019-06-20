<div>
    <p>
        Good Day!<br><br>

        {{ $inspector->name }} has submitted a 5S report for {{ $area->name }}. You still have 3 hours to check and verify the report. 
        If you are unable to check and verify your grade will be final.            
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">PLEASE VISIT THE 5S PORTAL</a><br>
    <p>Thank you for using this application.</p>
</div>