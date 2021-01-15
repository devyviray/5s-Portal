<div>
    <p>
    Good Day!<br><br>

    {{ $processOwner->name }} has already checked and verified the 5S report for {{ $area->name }}. <br>
    The 5s champion has comments on the non-conformance. Please verify this comments.
    </p><br>
    <a href="{{ url('validate-report', ['reportId' => $report->id]) }}">PLEASE VISIT THE 5S PORTAL</a><br>
    <p>Thank you for using this application.</p><br><br>
</div>