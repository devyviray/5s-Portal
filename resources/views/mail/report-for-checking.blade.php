<div>
    <p>
    Good Day!<br><br>

    {{ $processOwner->name }} has already checked and verified the 5S report for {{ $area->name }}. <br>
    The 5s champion has comments on the non-conformance. Please verify this comments.
    </p><br>
    <a href="{{ url('validate-report', ['reportId' => $report->id]) }}">PLEASE VISIT THE 5S PORTAL</a><br>
    <p>Thank you for using this application.</p><br><br>
    <img src="{{ $message->embed($pathToImage) }}"  alt="QR" title="QR" style="width="200" height=200" data-auto-embed="attachment">
</div>