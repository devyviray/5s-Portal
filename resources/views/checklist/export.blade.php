<table>
    <thead>
    <tr>
        <th style="font-weight: bold">NAME</th>
        <th style="font-weight: bold">BATCH</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $item)
        <tr>
		    <td style="width: 250px">{{ $item[0]->name }}</td>
		    <td style="width: 75px; text-align: left;">{{ $item[0]->batch }}</td>
        </tr>
    @endforeach
    </tbody>
</table>