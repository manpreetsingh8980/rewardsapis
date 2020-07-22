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
                                <h3 class="mb-0">All Tasks</h3>
								
                            </div>
							
                           <button><a href="{{route('admin.addTask')}}">Add Task</a></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
									<th scope="col">S.no.</th>
                                    <th scope="col">Task Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Coins</th>
									<th scope="col">Icon</th>
									<th scope="col">Repeat</th>
									<th scope="col" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
							@if(!empty($get_tasks))
								<?php $count=1; ?>
								@foreach($get_tasks as $key=>$val)
                                <tr>
                                    <th scope="row">{{$count}}</th>
									<td>{{$val['title']}}</td>
                                    <td>{{$val['description']}}</td>
                                    <td>{{$val['coins']}}</td>
									<td>
									@if($val['icon']=='')
										<img id="output" src="{{ URL::asset('public/images/dummy.png') }}" width="50" height="50">
									@else
										<img id="output" src="{{ URL::asset('public/images/icon_task').'/'.$val['icon']  }}" width="50" height="50">
									@endif
									</td>
									<td>{{$val['repeat']}}</td>
									<td><a href="{{ URL::to('edit_task/' . $val['id']) }}">Edit</a></td>
									<td><a onclick="return confirm('Are you sure?')" href="{{ URL::to('delete_task/' . $val['id']) }}">Delete</a></td>
                                    
                                </tr>
								<?php $count++;?>
								@endforeach
							@else
								<tr><td>No Task Found!</td></tr>
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