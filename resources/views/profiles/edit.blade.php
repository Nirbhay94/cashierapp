@extends('layouts.master')
@section('page_title', __('Edit Profile'))
@push('css')
    <link href="{{asset('plugins/dropzone/dropzone.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/cropper/dist/cropper.min.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
	<div class="block-header">
        <h2>
            {{__('PROFILE')}}
            <small>{{__('Edit')}}</small>
        </h2>
	</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <b>{{__('EDIT PROFILE')}}</b>
                        <small>{{__('You can change your profile details and configurations from here')}}</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="{{route('profile.show', ['username' => $user->name])}}">
                                <i class="material-icons">reply</i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs tab-nav-right tab-col-purple" role="tablist">
                        <li role="presentation"  class="active">
                            <a href="#profile" data-toggle="tab">
                                <span class="material-icons">account_box</span> {{__('Profile')}}
                            </a>
                        </li>
                        <li role="presentation" >
                            <a href="#account" data-toggle="tab">
                                <span class="material-icons">work</span> {{__('Account')}}
                            </a>
                        </li>
                        <li role="presentation" >
                            <a href="#administration" data-toggle="tab">
                                <span class="material-icons col-orange">fingerprint</span> {{__('Administration')}}
                            </a>
                        </li>
                        <li role="presentation" class="pull-right">
                            <a href="#delete" data-toggle="tab">
                                <span class="material-icons col-red">delete_sweep</span> {{__('Delete')}}
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        @if ($user->profile)
                            <div role="tabpanel" class="tab-pane active fade in  animated fadeIn" id="profile">
                                {!! Form::model($user->profile, ['method' => 'PATCH', 'route' => ['profile.update', $user->name],  'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data', 'id' => 'profile-form']) !!}
                                {{csrf_field()}}
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-col-grey">
                                        <div class="panel-heading" role="tab" id="gravatar-heading">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#gravatar" aria-expanded="true" aria-controls="gravatar">
                                                    <input type="radio" name="avatar_status" value="0" {{($user->profile->avatar_status == 0)? 'checked': ''}}>
                                                    <i class="material-icons">face</i> <span>{{__('Use Gravatar')}}</span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="gravatar" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="gravatar-heading">
                                            <div class="panel-body text-center">
                                                <img src="{{Gravatar::get($user->email)}}" alt="{{ $user->name }}" class="img-circle" width="150">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-col-grey">
                                        <div class="panel-heading" role="tab" id="picture-heading">
                                            <h4 class="panel-title">
                                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#picture" aria-expanded="false" aria-controls="picture">
                                                    <input type="radio" name="avatar_status" value="1" {{($user->profile->avatar_status == 1)? 'checked': ''}}>
                                                    <i class="material-icons">add_a_photo</i> <span>{{__('Use My Photo')}}</span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="picture" class="panel-collapse collapse" role="tabpanel" aria-labelledby="picture-heading">
                                            <div class="panel-body">
                                                <div id="file-upload" class="dropzone">
                                                    <div class="dz-message">
                                                        <div class="drag-icon-cph">
                                                            <i class="material-icons">touch_app</i>
                                                        </div>
                                                        <h3>{{__('Drop photo here or click to upload.')}}</h3>
                                                        <em>({{__('Attach a square size, portrait photo of your self.')}})</em>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <h2 class="card-inside-title">{{__('Profile Information')}}</h2>
                                <div class="form-group">
                                    {!! Form::label('location', __('Location:') , array('class' => 'col-sm-3')); !!}
                                    <div class="form-float col-sm-9">
                                        <div class="form-line {{ $errors->has('location') ? 'error' : '' }}">
                                            {!! Form::text('location', null, array('id' => 'location', 'class' => 'form-control', 'placeholder' => __('Enter your location'))) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('bio', __('About me:') , array('class' => 'col-sm-3')); !!}
                                    <div class="form-float col-sm-9">
                                        <div class="form-line {{ $errors->has('bio') ? ' error ' : '' }}">
                                            {!! Form::textarea('bio', null, array('id' => 'bio', 'class' => 'form-control', 'placeholder' => __('Tell us a about yourself...'))) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-9 col-sm-offset-3">
                                        {!! Form::button(__('Save Changes'), ['class' => 'btn bg-purple btn-flat', 'type' => 'submit']) !!}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <!-- /.tab-pane -->
                            <div role="tabpanel" class="tab-pane fade animated fadeIn" id="account">
                                <h2 class="card-inside-title">{{__('Account Information')}}</h2>
                                {!! Form::model($user, ['route' => ['profile.update-account', $user->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'account-form']) !!}
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    {!! Form::label('name', 'Username' , array('class' => 'col-md-3')); !!}
                                    <div class="form-float col-md-9">
                                        <div class="form-line {{ $errors->has('name') ? ' error ' : '' }}">
                                            {!! Form::text('name', null, array('id' => 'name', 'class' => 'form-control', 'placeholder' => __('Username'), 'readonly' => (!$user->can('alter users')), 'required')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', 'E-mail' , array('class' => 'col-md-3')); !!}
                                    <div class="form-float col-md-9">
                                        <div class="form-line {{ $errors->has('email') ? ' error ' : '' }}">
                                            {!! Form::email('email', null, array('id' => 'email', 'class' => 'form-control', 'placeholder' => __('User Email'), 'readonly' => (!$user->can('alter users')), 'required')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('first_name', __('First Name'), array('class' => 'col-md-3')); !!}
                                    <div class="form-float col-md-9">
                                        <div class="form-line {{ $errors->has('first_name') ? ' error ' : '' }}">
                                            {!! Form::text('first_name', NULL, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => __('First Name'), 'required')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('last_name', __('Last Name'), array('class' => 'col-md-3')); !!}
                                    <div class="form-float col-md-9">
                                        <div class="form-line {{ $errors->has('last_name') ? ' error ' : '' }}">
                                            {!! Form::text('last_name', NULL, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => __('Last Name'), 'required')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-9 col-md-offset-3">
                                        {!! Form::button(__('Save Changes'), ['class' => 'btn bg-purple btn-flat', 'type' => 'submit', 'id' => 'account-form-submit', 'disabled' => true]) !!}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        
                            <div role="tabpanel"  class="tab-pane fade animated fadeIn" id="administration">
                                <div class="tab-content">
                                    <h2 class="card-inside-title">{{__('Change Password')}}</h2>
                                    {!! Form::model($user, ['route' => ['profile.update-password', $user->id], 'class' => 'form-horizontal', 'method' => 'PUT', 'id' => 'administration-form']) !!}
                                    <div class="form-group">
                                        {!! Form::label('old_password', __('Password'), array('class' => 'col-md-3')); !!}
                                        <div class="form-float col-md-9">
                                            <div class="form-line {{ $errors->has('old_password') ? ' error ' : '' }}">
                                                {!! Form::password('old_password', array('id' => 'old_password', 'class' => 'form-control ', 'placeholder' => __('Password'), 'required')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('password', __('New Password'), array('class' => 'col-md-3')); !!}
                                        <div class="form-float col-md-9">
                                            <div class="form-line {{ $errors->has('password') ? ' error ' : '' }}">
                                                {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => __('Password'), 'required')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('password_confirmation') ? ' error ' : '' }}">
                                        {!! Form::label('password_confirmation', __('Confirm Password'), array('class' => 'col-md-3')); !!}
                                        <div class="form-float col-md-9">
                                            <div class="form-line {{ $errors->has('password') ? ' error ' : '' }}">
                                                {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => __('Confirm Password'), 'required')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-9 col-md-offset-3">
                                            {!! Form::button(__('Update Password'), ['class' => 'btn btn-warning btn-flat', 'id' => 'pw_save_trigger', 'type' => 'submit']) !!}
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div role="tabpanel"  class="tab-pane fade animated fadeIn" id="delete">
                                <div class="tab-content text-center">
                                    <h3 class="margin-bottom-1 text-danger"> {{ __('Delete Account') }} </h3>
                                    
                                    <p class="col-sm-8 col-md-offset-2 margin-bottom-2">
                                        {{__('Deleting your account is permanent and cannot be undone. Kindly tell us the reason why you want your account deleted below.')}}
                                    </p>
                                    
                                    {!! Form::model($user, ['route' => ['profile.delete-account', $user->id], 'method' => 'DELETE', 'id' => 'delete-form']) !!}
                                    
                                    <div class="form-group">
                                        <div class="form-float col-sm-8 col-md-offset-2">
                                            <div class="form-line {{ $errors->has('reason') ? ' error ' : '' }}">
                                                <textarea class="form-control" rows="5" name="reason" id="reason" placeholder="{{__('Make it short and precise...')}}" maxlength="500" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3 margin-bottom-3">
                                            {!! Form::button(__('PROCEED'), ['class' => 'btn btn-danger btn-flat', 'type' => 'submit']) !!}
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        @else
                            <h3 class="text-center">{{__('No Profile Yet')}}</h3>
                        @endif
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>
    <div id="cropper-custom" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="cropper-content">
                <div class="modal-header"><span>{{__('Crop and Upload Photo')}}</span></div>
                <div class="modal-body image-container" style="text-align:center;"></div>
                <div class="modal-footer">
                    <button class="btn btn-success pull-right crop-upload" type="submit"><span id="category_button" class="content">{{__('Upload')}}</span></button>
                </div>
            </div>
        </div>
    </div>
	@include('modals.modal-form')
@endsection

@push('js')
    <!-- DropZone -->
    <script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
    <!-- Cropper -->
    <script src="{{asset('plugins/cropper/dist/cropper.min.js')}}" type="text/javascript"></script>
    <!-- Actual JS -->
    <script src="{{asset('plugins/jquery.actual.min.js')}}" type="text/javascript"></script>
    
    @if(config('settings.googleMapsAPIStatus'))
        {!! HTML::script('//maps.googleapis.com/maps/api/js?key='.env("GOOGLEMAPS_API_KEY").'&libraries=places&dummy=.js', array('type' => 'text/javascript')) !!}
        @include('scripts.gmaps-address-lookup-api3')
	@endif
    
    <script type="text/javascript">
        var Page = function(){
            var dropzone;
            
            var handleAccordionSelect = function(){
                $('.panel-heading a').on('click',function(e) {
                    if ($(this).parents('.panel').children('.panel-collapse').hasClass('in')) {
                        e.stopPropagation();
                    }else{
                        $(this).find('input[type="radio"]').prop("checked", true);
                    }
                });
                
                // Initialize defualt accordion...
                $('input[type="radio"][name="avatar_status"]:checked').closest('a').trigger('click');
            };
            
            var handleFormValidation = function(){
                $('#account-form').validate({
                    highlight: function (input) {
                        $(input).parents('.form-line').addClass('error');
                    },
                    unhighlight: function (input) {
                        $(input).parents('.form-line').removeClass('error');
                    },
                    errorPlacement: function (error, element) {
                        $(element).parents('.form-float').append(error);
                    }
                });
                
                $('#administration-form').validate({
                    rules : {
                        password : {
                            minlength : 6
                        },
                        password_confirmation : {
                            minlength : 6,
                            equalTo : "#password"
                        }
                    },
                    
                    highlight: function (input) {
                        $(input).parents('.form-line').addClass('error');
                    },
                    unhighlight: function (input) {
                        $(input).parents('.form-line').removeClass('error');
                    },
                    errorPlacement: function (error, element) {
                        $(element).parents('.form-float').append(error);
                    }
                });
            };
            
            var handleDeleteTrigger = function () {
                $('#delete-form').submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    swal({
                        title: "{{__('Are you sure?')}}",
                        text: "{{__('You will lose all your records!')}}",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "{{__('Yes, remove it!')}}",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    }, function () {
                        form.unbind().submit();
                    });
                    
                    return false;
                });
            };
            
            var handleDropzone = function(){
                Dropzone.autoDiscover = false;
                dropzone = new Dropzone(
                    "div#file-upload",
                    {
                        url: "{{route('avatar.upload')}}" ,
                        maxFiles: 1,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        acceptedFiles: 'image/*',
                        dictDefaultMessage: '{{__('Drop any of your pictures here!')}}',
                        addRemoveLinks: false,
                        autoProcessQueue: false,
                        init: function(){
                            // Initialize dropzone...
                        },
                        drop: function(event){
                            this.removeAllFiles();
                        },
                        success: function(file, response){
                            Global.notifySuccess(response);
                        },
                        error: function(file, response){
                            if($.isPlainObject(response)){
                                var timeout = 1000;
                                $.each(response.errors, function(key, value){
                                    setTimeout(function(){Global.notifyDanger(value[0]);}, timeout);
                                    timeout += 1000;
                                });
                            }else{
                                Global.notifyDanger(response);
                            }
                            console.log(response);
                        }
                    }
                );
            
                @if($user->profile->avatar_status == 1 && $user->profile->avatar)
                    var mockFile = { name: "Avatar" };
                    dropzone.emit("addedfile", mockFile);
                    // ..and optionally show the thumbnail of the file:
                    dropzone.emit("thumbnail", mockFile, "{{$user->profile->avatar}}");
                    // Tag it as complete
                    dropzone.emit("complete", mockFile);
                @endif
                
                dropzone.on('thumbnail', function (file) {
                    // ignore files which were already cropped and re-rendered
                    // to prevent infinite loop
                    if (file.cropped) {
                        return;
                    }
                    // cache filename to re-assign it to cropped file
                    var cachedFilename = file.name;
                    // remove not cropped file from dropzone (we will replace it later)
                    dropzone.removeFile(file);

                    // dynamically create modals to allow multiple files processing
                    var $cropperModal = $('#cropper-custom');
                    // 'Crop and Upload' button in a modal
                    var $uploadCrop = $cropperModal.find('.crop-upload');

                    var $img = $('<img style="max-width:100%;"/>');
                    // initialize FileReader which reads uploaded file
                    var reader = new FileReader();
                    reader.onloadend = function () {
                        // add uploaded and read image to modal
                        $cropperModal.find('.image-container').html($img);
                        $img.attr('src', reader.result);

                        // initialize cropper for uploaded image
                        $img.cropper({
                            aspectRatio: 1 / 1,
                            autoCropArea: 1,
                            movable: false,
                            cropBoxResizable: true,
                            minContainerWidth:  $('.image-container').actual('width'),
                            minContainerHeight: 500,
                            viewMode: 1,
                        });
                    };
                    // read uploaded file (triggers code above)
                    reader.readAsDataURL(file);

                    $cropperModal.modal('show');

                    // listener for 'Crop and Upload' button in modal
                    $uploadCrop.on('click', function() {
                        // get cropped image data
                        var blob = $img.cropper('getCroppedCanvas').toDataURL();
                        // transform it to Blob object
                        var newFile = Page.dataURItoBlob(blob);
                        // set 'cropped to true' (so that we don't get to that listener again)
                        newFile.cropped = true;
                        // assign original filename
                        newFile.name = cachedFilename;

                        // add cropped file to dropzone
                        dropzone.addFile(newFile);
                        // upload cropped file with dropzone
                        dropzone.processQueue();
                        $cropperModal.modal('hide');

                    });
                });
            };
            
            var handleFormChangeListeners = function(){
                $('#account-form').on('keyup change', 'input, select, textarea', function(){
                    $('#account-form-submit').attr('disabled', false);
                });
            };
            
            return {
                init: function(){
                    handleAccordionSelect();
                    handleDropzone();
                    handleFormValidation();
                    handleFormChangeListeners();
                    handleDeleteTrigger();
                },

                dataURItoBlob: function (dataURI) {
                    var byteString = atob(dataURI.split(',')[1]);
                    var ab = new ArrayBuffer(byteString.length);
                    var ia = new Uint8Array(ab);
                    for (var i = 0; i < byteString.length; i++) {
                        ia[i] = byteString.charCodeAt(i);
                    }
                    return new Blob([ab], { type: 'image/jpeg' });
                }
            }
        }();
        
        $(document).ready(function(){
            Page.init();
        });
    </script>
@endpush
