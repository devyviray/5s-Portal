<table>
    <thead>
    <tr>
        <th style="font-weight: bold">ID</th>
        <th style="font-weight: bold">Created by</th>
        <th style="font-weight: bold">Question</th>
        <th style="font-weight: bold">Answer</th>
        <th style="font-weight: bold">Creation date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $item)
        <tr style="height: 50px">
		    <td style="width: 50px">{{ $item->id }}</td>
		    <td style="width: 200px">{{ $item->user->name }}</td>
		    <td style="width: 400px; text-align: justify;">{{ $item->question }}</td>
		    <td style="width: 400px; text-align: justify;">{{ $item->answer }}</td>
			<td style="width: 150px">{{ $item->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>