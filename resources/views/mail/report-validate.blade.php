<div>
    <p>
    Good Day!<br><br>

    {{ $inspector->name }} has submitted reviewed your comments. Please see updated report in the portal.    
            
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}"></a><br>
    <p> Thank you for using this application. </p><br><br>
</div>