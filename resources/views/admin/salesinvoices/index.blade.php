@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Sales Invoices</div>
            		<div class="panel-body table-responsive">
                        <salesinvoices></salesinvoices>
            		</div>
                </div>
            </div>
        </div>
    </div>
@endsection
