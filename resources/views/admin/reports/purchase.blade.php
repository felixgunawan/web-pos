@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Purchase Report by item</div>
            		<div class="panel-body table-responsive">
                        <div class = "btn-group">
                        </div>
                        <reportsperpurchase></reportsperpurchase>
            		</div>
                </div>
            </div>
        </div>
    </div>
@endsection
