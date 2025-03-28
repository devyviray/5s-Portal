<table>
    <thead>
		<tr>
			<td style="font-weight: bold">Total Users:</td>
			<td style="text-align: left">{{ count($users) }}</td>
		</tr>
		@if($filter)
		<tr>
			<td style="font-weight: bold">Filtered by:</td>
			<td colspan="2" style="text-align: left">
				{{ $filter["name"] }}, {{ $filter["company"] }}, {{ $filter["department"] }}, {{ $filter["role"] }}
			</td>
		</tr>
		<tr></tr>
		@endif
    <tr>
		<th style="font-weight: bold">ID</th>
		<th style="font-weight: bold">NAME</th>
		<th style="font-weight: bold">EMAIL</th>
		<th style="font-weight: bold">COMPANIES</th>
		<th style="font-weight: bold">DEPARTMENT</th>
		<th style="font-weight: bold">ROLE</th>
		<th style="font-weight: bold">CREATED AT</th>
		<th style="font-weight: bold">LAST LOGIN</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
		    <td style="width: 75px">{{ $user->id }}</td>
		    <td style="width: 200px">{{ $user->name }}</td>
		    <td style="width: 300px">{{ $user->email }}</td>
		    <td style="width: 500px">
                @foreach($user->companies as $company)
		    	<span>{{ $company->name }} - {{ $user->location->name }}<br></span>
                @endforeach
		    </td>
		    <td style="width: 450px">{{ $user->department->name }}</td>
		    <td style="width: 175px">
                @foreach($user->roles as $role)
		    	<span>{{ $role->name }}<br></span>
                @endforeach
		    </td>
			<td style="width: 150px">{{ $user->created_at }}</td>
			<td style="width: 150px">{{ $user->last_login_at? $user->last_login_at: '--' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>