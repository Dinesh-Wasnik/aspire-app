@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>        
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Loans') }}</div>

                <div class="card-body">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">User</th>
                          <th scope="col">amount</th>
                          <th scope="col">term</th>
                          <th scope="col">Installment</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(!empty($loans))
                            @foreach ($loans as $key => $loan) 
                                <tr>
                                  <th scope="row">{{$key+1}}</th>
                                  <td>{{$loan->user['name']}}</td>
                                  <td>{{$loan->amount}}</td>
                                  <td>{{$loan->term}}</td>
                                  <td>{{$loan->installment}}</td>
                                  <td role="cell">
                                        @if($loan->is_approved == '1')
                                            <button type="button" class="btn btn-success">Approved</button>
                                        @elseif($loan->is_approved == '0')
                                            <button type="button" class="btn btn-danger">Rejected</button>
                                        @else
                                        <a href="{{ route('loan-status', ['status'=>1, 'id'=>$loan->id]) }}">
                                            <button type="button" class="btn btn-success">
                                                Approve
                                            </button>
                                        </a>
                                        <a href="{{ route('loan-status', ['status'=> 0, 'id'=>$loan->id]) }}">
                                            <button type="button" class="btn btn-danger">
                                                Reject
                                            </button>
                                        </a>
                                        @endif
                                   </td>
                                </tr>
                            @endforeach
                        @else       
                            <tr>
                              <td colspan='6'>No Loans found.</td>
                            </tr>
                        @endif 
                      </tbody>
                    </table>     
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection
