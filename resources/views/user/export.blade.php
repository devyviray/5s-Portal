<table>
    <thead>
    <tr>
		<th>ID</th>
		<th colspan="2" style="font-weight: bold">Name</th>
		<th colspan="2" style="font-weight: bold">Email</th>
		<th colspan="4" style="font-weight: bold">Companies</th>
		<th colspan="4" style="font-weight: bold">Department</th>
		<th>Role</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
		    <td>{{ $user->id }}</td>
		    <td colspan="2">{{ $user->name }}</td>
		    <td colspan="2">{{ $user->email }}</td>
		    <td colspan="4">
                @foreach($user->companies as $company)
		    	<span>{{ $company->name }} - {{ $user->location->name }}<br></span>
                @endforeach
		    </td>
		    <td colspan="4">{{ $user->department->name }}</td>
		    <td>
                @foreach($user->roles as $role)
		    	<span>{{ $role->name }}<br></span>
                @endforeach
		    </td>
        </tr>
    @endforeach
    </tbody>
</table>