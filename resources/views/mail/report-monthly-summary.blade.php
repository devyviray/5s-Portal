<div>
    <p>
        Dear 5s Partner,<br><br>

        Good day!<br><br>
       
        Business Unit: {{  $report['company']  }}<br>
        Ave. Rating for this month: {{ $report['rating'] }}<br>
        {{-- Ave. Rating last month: <br>
        Total Improvements: <br>
        Total Critical Issues: <br> --}}
        BU Ranking: {{ NumConvert::numberOrdinal($rank) . ' out of ' . $count_ranking }}<br>
   </p>

    @if($report['rating'] < 85)
        <span style="color:#a94442">
            <strong><i>
                <h1>We believe your team can improve.</h1>
                <h3>Our QM would be delighted to help you! Please advise convenient time to discuss with you  :)</h3>
            </i></strong>
        </span>
    @else
        <span style="color:#3c763d"><strong><h1><i>Hurray! Your team is doing great. Keep it up!</i></h1></strong>
        </span>
    @endif
   <br>
   <a href="{{ url('reports-summary') }}">View summary and ranking here</a><br>

</div>