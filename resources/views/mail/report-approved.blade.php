<div>
    <p>
    Good Day!<br><br>

    {{ $processOwner->name }} has already approved the submitted 5S report for: <br><br>
    
    Company: {{ $report->company->name. ' '.$report->location->name }}<br>
    {{ $report->area->name }} - {{ $report->ratings }}%<br>
    Inspection Date: {{ \Carbon\Carbon::parse($report->date_of_inspection)->isoFormat('Do MMM YYYY') }}<br>
    Inspector: {{ $report->inspector->name }} <br>
    </p><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">View report</a><br><br>
</div>