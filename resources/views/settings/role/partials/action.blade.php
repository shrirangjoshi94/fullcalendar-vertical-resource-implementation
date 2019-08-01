<div class="row pl-3">
    <a href="#" class="btn btn-outline-primary editClass"><i class="fa fa-edit"></i></a> 
	<form action="{{ route('role.destroy', $id )}}" method="POST" class="pl-2">
		@csrf
		@method('Delete')
		<button type="submit" class="btn btn-outline-danger deleteRoleButton" id="deleteRoleButton">
			<i class="fa fa-trash"></i>
		</button>
	</form>
</div>