<table>
    <thead>
    <tr>
		<th style="font-weight: bold">ID</th>
		<th style="font-weight: bold">NAME</th>
		<th style="font-weight: bold">EMAIL</th>
		<th style="font-weight: bold">COMPANIES</th>
		<th style="font-weight: bold">DEPARTMENT</th>
		<th style="font-weight: bold">ROLE</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
		    <td style="width: 50px">{{ $user->id }}</td>
		    <td style="width: 200px">{{ $user->name }}</td>
		    <td style="width: 300px">{{ $user->email }}</td>
		    <td style="width: 500px">
                @foreach($user->companies as $company)
		    	<span>{{ $company->name }} - {{ $user->location->name }}<br></span>
                @endforeach
		    </td>
		    <td style="width: 450px">{{ $user->department->name }}</td>
		    <td style="width: 200px">
                @foreach($user->roles as $role)
		    	<span>{{ $role->name }}<br></span>
                @endforeach
		    </td>
        </tr>
    @endforeach
    </tbody>
</table>