@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Suppliers</div>
            		<div class="panel-body table-responsive">
                        <a class="btn btn-success" href = "{{ route('suppliers.create') }}">Create new supplier</a>
                        <suppliers></suppliers>
            		</div>
                </div>
            </div>
        </div>
    </div>
@endsection
