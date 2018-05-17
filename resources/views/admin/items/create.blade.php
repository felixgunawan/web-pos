@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Create new item
                    </div>
            		<div class="panel-body table-responsive">
                        <a class = "btn btn-default" href="{{ route('items.index') }}">Back</a>
                        &nbsp;<br>&nbsp;<br>
                        <itemscreate></itemscreate>
            		</div>
                </div>
            </div>
        </div>
    </div>
@endsection
