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
                                <h3 class="mb-0">All Rewards</h3>
								
                            </div>
							
                           <button><a href="{{route('admin.addReward')}}">Add Reward</a></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
									<th scope="col">S.no.</th>
                                    <th scope="col">Reward Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Coins</th>
									<th scope="col">Icon</th>
									<th scope="col">Legal Text</th>
									<th scope="col" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
							@if(!empty($all_rewards))
								<?php $count=1; ?>
								@foreach($all_rewards as $key=>$val)
                                <tr>
                                    <th scope="row">{{$count}}</th>
									<td>{{$val['reward_title']}}</td>
                                    <td>{{$val['reward_description']}}</td>
                                    <td>{{$val['reward_coins']}}</td>
									<td>
									@if($val['reward_icons']=='')
										<img id="output" src="{{ URL::asset('public/images/dummy.png') }}" width="50" height="50">
									@else
										<img id="output" src="{{ URL::asset('public/images/icon_reward').'/'.$val['reward_icons']  }}" width="50" height="50">
									@endif
									</td>
									<td>{{$val['legal_text']}}</td>
									<td><a href="{{ URL::to('edit_reward/' . $val['id']) }}">Edit</a></td>
									<td><a onclick="return confirm('Are you sure?')" href="{{ URL::to('delete_reward/' . $val['id']) }}">Delete</a></td>
                                    
                                </tr>
								<?php $count++;?>
								@endforeach
							@else
								<tr><td>No Reward Found!</td></tr>
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