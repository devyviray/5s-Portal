<table>
    <thead>
    <tr>
    <th style="font-weight: bold">BATCH NAME</th>
        <th style="font-weight: bold">REQUIREMENT</th>
        <th style="font-weight: bold">DESCRIPTION</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $item)
        <tr>
            <td style="width: 180px">{{ $item->name }}</td>
		    <td style="width: 180px">{{ $item->requirement }}</td>
		    <td style="width: 650px; text-align: justify;">{{ $item->description }}</td>
        </tr>
    @endforeach
    </tbody>
</table>