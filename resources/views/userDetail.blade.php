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
                                <h3 class="mb-0">Rewards</h3>
                            </div>
                           
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
									<th scope="col">S.no.</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Coins</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
							@if(!empty($user_details))
								
								<?php $count=1; ?>
								@foreach($user_details->getUserRewards as $key=>$val)
								
                                <tr>
                                    <th scope="row">
									{{$count}}
                                    </th>
									<td>{{$val->getRewardDetail->reward_title}}</td>
                                    <td>{{$val->getRewardDetail->reward_coins}}</td>
									<td>{{$val->reward_status}}</td>
                                    
                                </tr>
								<?php $count++;?>
								@endforeach
							
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