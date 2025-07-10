<table>
    <thead>
    <tr>
        <th style="font-weight: bold">ID</th>
        <th style="font-weight: bold">BATCH</th>
        <th style="font-weight: bold">REQUIREMENT</th>
        <th style="font-weight: bold">DESCRIPTION</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $item)
        <tr>
            <td style="width: 50px">{{ $item->id }}</td>
            <td style="width: 180px">{{ $item->batch }}- {{ $item->name }}</td>
		    <td style="width: 180px; text-align: justify;">{{ $item->requirement }}</td>
		    <td style="width: 650px; text-align: justify;">{{ $item->description }}</td>
        </tr>
    @endforeach
    </tbody>
</table>