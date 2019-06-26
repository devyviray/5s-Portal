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
                    <td rowspan="2">Area Inspected  {{ getYear($data) }}</td>
                    <td colspan="14" style="text-align: center">5s Rating</td>
                </tr>
                <tr>
                    <td scope="col">Jan</td>
                    <td scope="col">Feb</td>
                    <td scope="col">Mar</td>
                    <td scope="col">Apr</td>
                    <td scope="col">May</td>
                    <td scope="col">Jun</td>
                    <td scope="col">Jul</td>
                    <td scope="col">Aug</td>
                    <td scope="col">Sep</td>
                    <td scope="col">Oct</td>
                    <td scope="col">Nov</td>
                    <td scope="col">Dec</td>
                    <td scope="col">Ave.(ytd)</td>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #EDEDED"><td colspan="14">Operations</td></tr>
                @foreach($data as $d)
                    <tr>
                        <td>{{ $d->company->name . ' ' . $d->location->name . ' - ' .  $d->operationLine->name }}</td>
                        <td>{{ getRating($d->reports, 1) }}</td>
                        <td>{{ getRating($d->reports, 2) }}</td>
                        <td>{{ getRating($d->reports, 3) }}</td>
                        <td>{{ getRating($d->reports, 4) }}</td>
                        <td>{{ getRating($d->reports, 5) }}</td>
                        <td>{{ getRating($d->reports, 6) }}</td>
                        <td>{{ getRating($d->reports, 7) }}</td>
                        <td>{{ getRating($d->reports, 8) }}</td>
                        <td>{{ getRating($d->reports, 9) }}</td>
                        <td>{{ getRating($d->reports, 10) }}</td>
                        <td>{{ getRating($d->reports, 11) }}</td>
                        <td>{{ getRating($d->reports, 12) }}</td>
                        <td>{{ getAverage($d->reports) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>

<?php

function getYear($data){
    $year = '';
    foreach($data as $trend){
        if(!$trend->reports->isEmpty()){
            $year = $trend->reports[0]->reporting_year;
        }
    }
    return $year;
}

function getRating($reports, $item){
    if($reports){
        $rating = '-';
        foreach ($reports as $report) {
            if($report->reporting_month == $item && $report->ratings){
                $rating =  $report->ratings;
            }
        }
        return $rating; 
    }else{
        return '-';
    }

    return $reports[''];
}
function getAverage($reports){
    $average = 0;
    $count = 0;
    foreach ($reports as $report) {
        if($report->ratings){
            $average = $average + $report->ratings;
            $count = $count + 1;
        }
    }
    $count ? $total = $average / $count : $total = '-';
    if($count){
        $total = $average / $count;
    }
    return $total;
}
?>