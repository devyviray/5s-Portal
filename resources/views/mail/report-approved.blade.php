<div>
     <p>
        Good Day!<br><br>

        Here is the result of your area!<br><br>
        
        Area: {{  $report->area->name  }}<br>
        Date of inspection: {{ \Carbon\Carbon::parse($report->date_of_inspection)->isoFormat('Do MMM YYYY') }}<br>
        Rate	: {{ $report->ratings }}%<br>
        {{-- Total NC	: <br>
        Total Improvement	: <br>
        Total Critical NC	: <br> --}}
        Inspector	:  {{ $report->inspector->name }}
    </p>
    <h1>
        @if($report->ratings < 85)
            <span style="color:#a94442">
                <strong>
                    <i>Whoops! You need to keep up and work double time!<br>
                    You can do it :) 
                    </i>
                </strong>
            </span>
        @else
            <span style="color:#3c763d"><strong><i>Congratulations for a job well done!<br>
                Consistency is the key!</i></strong>
            </span>
        @endif
    </h1><br>
    <a href="{{ url('view-report', ['reportId' => $report->id]) }}">View your report here</a><br><br>

</div>