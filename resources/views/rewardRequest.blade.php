@extends('layouts.app')

@section('content')
    
    <div class="container-fluid mt--7">
       
        <div class="row mt-5">
            <div class="row mt-5">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
					
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">All Reward Requests</h3>
								
                            </div>
							
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
									<th scope="col">S.no.</th>
                                    <th scope="col">User Device ID</th>
                                    <th scope="col">Reward Title</th>
                                    <th scope="col">Reward Coins</th>
									<th scope="col">Status</th>
									<th scope="col" colspan="3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
							@if(!empty($reward_req))
								<?php $count=1; ?>
								@foreach($reward_req as $key=>$val)
                                <tr>
                                    <th scope="row">{{$count}}</th>
									<td>{{$val['get_user']['device_id']}}</td>
                                    <td>{{$val['get_reward']['reward_title']}}</td>
                                    <td>{{$val['get_reward']['reward_coins']}}</td>
									<td>
									@if($val['reward_status'] == 0)
										Pending
									@elseif($val['reward_status'] == 1)
										Approved
									@else
										Denied
									@endif
									</td>
									<td><a href="{{ URL::to('user/' . $val['reward_user_id']) }}">User Detail</a></td>
									
									@if($val['reward_status'] == 0)
										<td><a onclick="return confirm('Are you sure?')" href="{{ URL::to('reward_request_approve/' . $val['id']) }}">Approve</a></td>
										<td><a onclick="return confirm('Are you sure?')" href="{{ URL::to('reward_request_deny/' . $val['id']) }}">Deny</a></td>
									@elseif($val['reward_status'] == 1)
										<td><a onclick="return confirm('Are you sure?')" href="{{ URL::to('reward_request_deny/' . $val['id']) }}">Deny</a></td>
									@else
										<td><a onclick="return confirm('Are you sure?')" href="{{ URL::to('reward_request_approve/' . $val['id']) }}">Approve</a></td>	
									@endif
									
									
                                </tr>
								<?php $count++;?>
								@endforeach
							@else
								<tr><td>No Reward Request Found!</td></tr>
							@endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           
        </div>


        <!--@include('layouts.footers.auth')-->
    </div>
	

@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush