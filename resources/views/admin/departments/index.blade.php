@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Departments</div>
    				<div class="panel-body table-responsive">    
                        <a class="btn btn-success" href = "{{ route('departments.create') }}">Create new department</a>
                        <departments></departments>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
