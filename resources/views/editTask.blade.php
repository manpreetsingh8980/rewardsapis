@extends('layouts.app')

@section('content')
    @include('layouts.headers.innerpage')
    <div class="container mt--8 pb-5">
        <!-- Table -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary shadow border-0">
                   
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{ __('Edit Task') }}</small>
                        </div>
                        <form role="form" method="POST" action="{{ route('admin.submitTask') }}" enctype="multipart/form-data">
                            @csrf
							<input type="hidden" name="form_type" value="edit"/>
							<input type="hidden" name="task_id" value="{{$task_data->id}}"/>
                            <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" type="text" name="title" value="{{ $task_data->title }}" required autofocus>
                                </div>
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
									<textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" name="description" required>{{ $task_data->description }}</textarea>
                                   
                                </div>
                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('coins') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('coins') ? ' is-invalid' : '' }}" placeholder="{{ __('Coins') }}" type="number" name="coins" value="{{ $task_data->coins }}" required>
                                </div>
                                @if ($errors->has('coins'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('coins') }}</strong>
                                    </span>
                                @endif
                            </div>
							<div class="form-group{{ $errors->has('icon') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
									<img id="output" src="{{ URL::asset('public/images/icon_task').'/'.$task_data->icon  }}" width="50" height="50">
									
                                    <input onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" class="upload_img form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}" placeholder="{{ __('Icon') }}" type="file" name="icon" value="{{ old('icon') }}" required>
                                </div>
                                @if ($errors->has('icon'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('icon') }}</strong>
                                    </span>
                                @endif
                            </div>
							<div class="form-group{{ $errors->has('legal_text') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
									<input class="form-control{{ $errors->has('repeat') ? ' is-invalid' : '' }}" placeholder="{{ __('Repeat') }}" type="number" name="repeat" value="{{ $task_data->repeat }}" required>
                                    
                                </div>
                                @if ($errors->has('repeat'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('repeat') }}</strong>
                                    </span>
                                @endif
                            </div>
                           
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">{{ __('Edit Task') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	

@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush