@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @role('admin')
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body table-responsive">
                        <dashboard></dashboard>
                    </div>
                @endrole

                @role('cashier')
                    Welcome
                @endrole
            </div>
        </div>
    </div>
</div>
@endsection
