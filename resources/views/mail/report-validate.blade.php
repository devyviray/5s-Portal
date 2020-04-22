<div>
    <p>
    Good Day!<br><br>

    {{ $inspector->name }} has submitted reviewed your comments. Please see updated report in the portal.    
            
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}"></a><br>
    <p> Thank you for using this application. </p><br><br>
    <img src="{{ $message->embed($pathToImage) }}"  alt="QR" title="QR" style="width="200" height=200" data-auto-embed="attachment">
</div>