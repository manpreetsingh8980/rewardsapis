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
                                <h3 class="mb-0">All Users</h3>
                            </div>
                           
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
									<th scope="col">S.no.</th>
                                    <th scope="col">Device Type</th>
                                    <th scope="col">Device Id</th>
                                    <th scope="col">Date/time</th>
									<th scope="col">User Detail</th>
                                </tr>
                            </thead>
                            <tbody>
							@if(!empty($all_users))
								<?php $count=1; ?>
								@foreach($all_users as $key=>$val)
                                <tr>
                                    <th scope="row">
									{{$count}}
                                    </th>
									<td>{{$val['device_type']}}</td>
                                    <td>
                                        {{$val['device_id']}}
                                    </td>
                                    <td>
									@if($val['datetime'] != '')
                                        {{ \Carbon\Carbon::createFromTimestamp($val['datetime'])->format('d-m-Y H:i:s')}}
									@else
										
									@endif
                                    </td>
									<td><a target="_blank" href="{{ URL::to('user/' . $val['id']) }}">Click here</a></td>
                                    
                                </tr>
								<?php $count++;?>
								@endforeach
							@else
								<tr><td>No User Found</td></tr>
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