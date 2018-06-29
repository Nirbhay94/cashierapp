@extends('layouts.master')
@section('page_title', __('Import Invoices'))
@push('css')
	<link href="{{asset('plugins/dropzone/dropzone.css')}}" rel="stylesheet">
	
	<style rel="stylesheet">
		#terminal {
			border: 1px dashed #AFAFAF;
			font-family: Consolas, Menlo, Monaco, monospace;
			font-size: 12px;
		}
	</style>
@endpush
@section('content')
	<div class="block-header">
		<h2>
			{{__('INVOICES')}}
			<button class="btn btn-primary waves-effect pull-right" type="button" data-toggle="collapse" data-target="#instructions"
			        aria-expanded="false" aria-controls="instructions">
				{{__('INSTRUCTIONS')}}
			</button>
			<a href="{{route('invoice.import.sample')}}" class="btn bg-purple waves-effect pull-right">
				{{__('SAMPLE')}}
			</a>
			<small class="clearfix">
				{{__('Import')}}
			</small>
		</h2>
	</div>
	
	<div class="callout-block callout-info collapse" id="instructions">
		<div class="icon-holder">
			<i class="fa fa-info-circle"></i>
		</div>
		<div class="content">
			<p><code>id</code> (optional) {{__('This column is needed if you plan to modify your existing records')}}</p>
			<p><code>products</code> (required) {{__('Allowed Format:')}} &lt;product_id&gt;x&lt;quantity&gt; {{__('where product_id is the record id according to the products table.')}}</p>
			<p>{{__('Note: Multiple entries should be separated by comma without spaces')}} e.g 1x23,2x40</p>
			<p><code>note</code> (required) </p>
			<p><code>customer_id</code> (required) {{__('This column should contain only corresponding record id according to the customers table.')}}</p>
			<p><code>apply_balance</code>, <code>allow_partial</code>, <code>enable_repeat</code> (required) {{__('This column should contain one of the following (case sensitive):')}} yes, no</p>
			<p><code>repeat_type</code> (required with: enable_repeat) {{__('This column should contain one of the following (case sensitive):')}} daily,weekly,monthly,yearly</p>
			<p><code>repeat_interval</code> (required with: enable_repeat) </p>
			<p><code>repeat_until</code> (required with: enable_repeat) {{__('Allowed Format:')}} Y-m-d H:i:s (e.g {{date('Y-m-d H:i:s')}}) </p>
		</div>
	</div>
	
	<div class="card">
		<div class="header">
			<h2>
				{{__('IMPORT RECORDS')}}
				<small>{{__('Makes record updating faster and easier.')}}</small>
			</h2>
			<ul class="header-dropdown m-r--5">
				<li>
					<a href="{{url('filemanager')}}" target="_blank">
						<i class="material-icons">folder</i>
					</a>
				</li>
			</ul>
		</div>
		<div class="body">
			<div id="file-upload" class="dropzone">
				<div class="dz-message">
					<div class="drag-icon-cph">
						<i class="material-icons">touch_app</i>
					</div>
					<h3>{{__('Drop your prepared CSV file here or click to upload.')}}</h3>
					<em>({{__('Please make sure the csv file is UTF-8 encoded')}})</em>
				</div>
			</div>
		</div>
	</div>
	
	<div class="card">
		<div class="header">
			<h2>
				{{__('OUTPUT')}}
			</h2>
		</div>
		<div class="body">
			<div id="terminal"></div>
		</div>
	</div>
	
@endsection
@push('js')
	<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
	<script src="{{asset('plugins/terminal-js/dist/terminal.min.js')}}"></script>
	
	<script>
        var Page = function(){
	        var terminal;
         
	        var handleTerminal = function(){
                terminal = new Terminal({
                    dom: document.getElementById('terminal'),
                    speed: 100, // chars per second
	                cursor: {
                        width: 3,
		                color: '#fff',
	                }
                });
	        };

            var handleSlimScroll = function(){
                $('#terminal').slimScroll({
                    height: '150px'
                });
            };
            
            var handleDropzone = function(){
                Dropzone.autoDiscover = false;
                var dropzone = new Dropzone(
                    "div#file-upload",
                    {
                        url: "{{route('invoice.import')}}" ,
                        maxFiles: 1,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
	                    acceptedFiles: '.csv',
                        dictDefaultMessage: '{{__('Drop your prepared CSV file here!')}}',
                        addRemoveLinks: false,
                        autoProcessQueue: true,
                        init: function(){
                            this.on("addedfile", function(file) {
	                            this.emit("thumbnail", file, "{{url('images/csv-thumbnail.png')}}");
                            });
                        },
                        drop: function(event){
                            this.removeAllFiles();
                        },
                        success: function(file, response){
                            this.options.maxFiles = 1;
                            
                            terminal.run(function(o) {
                                o.output(response).newline();
                            });
                            
                            Global.notifySuccess(response);
                        },
                        error: function(file, response){
                            if($.isPlainObject(response)){
                                terminal.run(function(o) {
                                    $.each(response, function (row, errors) {
                                        var message = 'Parse error => Row #' + row + ': ';

                                        $.each(errors, function (key, value) {
                                            o.output(message + value).newline();
                                        });
                                    });
                                    
                                    o.newline();
                                });
                            }else{
                                Global.notifyDanger(response);
                            }
                        }
                    }
                );

            };
        
	        return {
                init: function(){
                    handleTerminal();
                    handleSlimScroll();
                    handleDropzone();
                    
                    terminal.run(function(o){
                       o.output('{{__('Drop a csv file to begin...')}}');
                       o.newline();
                    });
                }
            }
        }();

        $(document).ready(function () {
            Page.init();
        });
	</script>
@endpush