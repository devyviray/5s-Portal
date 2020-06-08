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
                    <td colspan="14" style="text-align: center">5s Ranking for year {{ getYear($data) }}</td>
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
                    <tr style="font-size: 15px">
                        <td>
                            {{ $d[0]['company']['name'] }} <br>
                            {{ $d[0]['location']['name'] }}
                        </td>
                        <td>{!! getRanking($data,$d, 1) !!}</td>
                        <td>{!! getRanking($data,$d, 2) !!}</td>
                        <td>{!! getRanking($data,$d, 3) !!}</td>
                        <td>{!! getRanking($data,$d, 4) !!}</td>
                        <td>{!! getRanking($data,$d, 5) !!}</td>
                        <td>{!! getRanking($data,$d, 6) !!}</td>
                        <td>{!! getRanking($data,$d, 7) !!}</td>
                        <td>{!! getRanking($data,$d, 8) !!}</td>
                        <td>{!! getRanking($data,$d, 9) !!}</td>
                        <td>{!! getRanking($data,$d, 10) !!}</td>
                        <td>{!! getRanking($data,$d, 11) !!}</td>
                        <td>{!! getRanking($data,$d, 12) !!}</td>
                        <td>{!! getRankingAverage($data,$d) !!}</td>
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

function getRanking($data,$reports, $month){
    $total_rating = 0;
    $rankings = [];
    if($reports){
        $total_rating = getRating($reports,$month);
        if($total_rating !== '-'){
            foreach ($data as $report) {
                if($result = getRating($report, $month)){
                    if($result !== '-'){
                        array_push($rankings,$result);
                    }
                }
            }
            if($rankings){
                arsort($rankings);
                return  NumConvert::numberOrdinal(array_search($total_rating,array_values($rankings),true) + 1);
            }
        }
    }
}
function getRankingAverage($data,$reports){
    $reporting_month = [];
    $total_rankings = 0;

    if($reports){
        foreach ($reports as $report) {  // Get month that has report
            if(!in_array($report['reporting_month'],$reporting_month)){
                array_push($reporting_month,$report['reporting_month']);
            }
        }

        for($i = 1; $i < 13; $i++){
            $monthly_ranking = getRanking($data,$reports,$i);
            if($monthly_ranking){
                $total_rankings = $total_rankings == 0 ?  $monthly_ranking : floatval($total_rankings) + floatval($monthly_ranking);
            }
        }
        if($total_rankings){
            return numberFormat(floatval($total_rankings) / count($reporting_month));
        }
    }
    return '-';
}
function getRating($reports, $item){
    $total_areas = 0;
    $total_ratings = 0;

    if($reports){
        foreach ($reports as $report) {
            if($report['reporting_month'] == $item && $report['ratings']){
                $total_areas = $total_areas + 1;
                $total_ratings = $total_ratings + $report['ratings'];
            }
        }
        return  $total_areas ? numberFormat($total_ratings / $total_areas)  : '-';
    }
    return '-';
}

function numberFormat($number){
    return round($number,2);
}

?>