<table>
    <thead>
    <tr>
        <th style="font-weight: bold">ID</th>
        <th style="font-weight: bold">NAME</th>
        <th style="font-weight: bold">LOCATION</th>
        <th style="font-weight: bold">CATEGORY</th>
        <th style="font-weight: bold">OPERATION LINE</th>
        <th style="font-weight: bold">AREA</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $item)
        <tr>
		    <td style="width: 50px">{{ $item->id }}</td>
		    <td style="width: 360px">{{ $item->company->name }}</td>
		    <td style="width: 240px">{{ $item->location->name }}</td>
		    <td style="width: 240px">{{ $item->category->name }}</td>
		    <td style="width: 240px">{{ $item->operationLine? $item->operationLine->name: '--' }}</td>
		    <td style="width: 180px">
                @foreach($item->areas as $area)
                <span>{{ $area->name }}<br></span>
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>