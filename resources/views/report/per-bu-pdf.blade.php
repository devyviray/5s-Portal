<html lang="en">

<head>
    <title>PDF Download</title>
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
</head>

<body>
    <main role="main">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <td colspan="14" style="text-align: center">5s Rating for year {{ getYear($data) }}</td>
                </tr>
                <tr>
                    <th scope="col">Business Unit</th>
                    <th scope="col">JAN</th>
                    <th scope="col">FEB</th>
                    <th scope="col">MAR</th>
                    <th scope="col">APR</th>
                    <th scope="col">MAY</th>
                    <th scope="col">JUN</th>
                    <th scope="col">JUL</th>
                    <th scope="col">AUG</th>
                    <th scope="col">SEP</th>
                    <th scope="col">OCT</th>
                    <th scope="col">NOV</th>
                    <th scope="col">DEC</th>
                    <th scope="col">AVG</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <td>
                            {{ $d[0]['company']['name'] }} <br>
                            {{ $d[0]['location']['name'] }} <br>
                            {{ $d[0]['category']['name'] }}<br>
                            @if($d[0]['operation_line'])
                            {{ $d[0]['operation_line']['name'] }}<br>
                            @endif
                            {{ $d[0]['area']['name'] }}
                        </td>
                        <td>{{ getRating($d, 1) }}</td>
                        <td>{{ getRating($d, 2) }}</td>
                        <td>{{ getRating($d, 3) }}</td>
                        <td>{{ getRating($d, 4) }}</td>
                        <td>{{ getRating($d, 5) }}</td>
                        <td>{{ getRating($d, 6) }}</td>
                        <td>{{ getRating($d, 7) }}</td>
                        <td>{{ getRating($d, 8) }}</td>
                        <td>{{ getRating($d, 9) }}</td>
                        <td>{{ getRating($d, 10) }}</td>
                        <td>{{ getRating($d, 11) }}</td>
                        <td>{{ getRating($d, 12) }}</td>
                        <td>{{ getAverage($d) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>

<?php

function getYear($reports){
    $year = '';
    if($reports){
        foreach($reports as $report){
            if($report){
                foreach($report as $r){
                    if($r['reporting_year']){
                        $year =  $r['reporting_year'];
                    }
                }
            }
        }
    }
    return $year;
}

function getRating($reports, $item){
    $rating = '-';
    if($reports){
        foreach ($reports as $report) {
            if($report['reporting_month'] == $item && $report['ratings']){
                $rating =  $report['ratings'];
            }
        }
        return $rating; 
    }else{
        return $rating;
    }
}
function getAverage($reports){
    $reporting_month_count = 0;
    $total_points = 0;

    foreach ($reports as $report) {
        $reporting_month_count = $reporting_month_count + 1;
        $total_points = $total_points + $report['ratings'];
    }
    return $total_points / $reporting_month_count;
}
?>