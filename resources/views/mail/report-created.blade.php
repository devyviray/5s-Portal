<div>
    <p>
        Good Day!<br><br>

        {{ $inspector->name }} has submitted a 5S report for {{ $area->name }}. You have 72 hours to check and verify the noted non-conformances.<br>
        If you are unable to verify the non-conformances within the allotted time the grade reflected in the report will be the final grade.
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">PLEASE VISIT THE 5S PORTAL</a><br> 
    <p>Thank you for using this application.</p><br>
</div>