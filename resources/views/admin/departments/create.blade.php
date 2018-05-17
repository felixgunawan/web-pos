@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Create new department
                    </div>
                    <div class="panel-body table-responsive">
                        <a class = "btn btn-default" href="{{ route('departments.index') }}">Back</a>
                        &nbsp;<br>&nbsp;<br>
                        <departmentscreate></departmentscreate>
            		</div>
                </div>
            </div>
        </div>
    </div>
@endsection
