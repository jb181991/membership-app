@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach

                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                                
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            
                            {{-- Custom select Customers --}}
                            <div class="form-group col-md-6">
                                <label for="">Customer</label>
                                <select name="customer_id" id="customer_id" class="form-control select2">
                                    <option value="" disabled selected>None</option>
                                    <option value="create_new">Create New Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ isset($dataTypeContent->customer_id) && $customer->id == $dataTypeContent->customer_id ? 'selected' : '' }}>{{ $customer->first_name.' '.$customer->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Custom select Sales Rep --}}
                            @if (\Auth::user()->role_id != 5)
                                <div class="form-group col-md-6">
                                    <label for="">Sales Rep</label>
                                    <select name="sales_rep_id" id="sales_rep_id" class="form-control select2">
                                        <option value="" disabled selected>None</option>
                                        <option value="create_new">Create New Sales Rep</option>
                                        @foreach ($sales_reps as $sales_rep)
                                            <option value="{{ $sales_rep->id }}" {{ isset($dataTypeContent->sales_rep_id) && $sales_rep->id == $dataTypeContent->sales_rep_id ? 'selected' : '' }}>{{ $sales_rep->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif (\Auth::user()->role_id == 5)
                                {{-- <input type="hidden" name="sales_rep_id" value="{{ \Auth::user()->id }}"> --}}
                                <div class="form-group col-md-6">
                                    <label for="">Sales Rep</label>
                                    <select name="sales_rep_id" class="form-control select2">
                                        <option value="" disabled selected>None</option>
                                        {{-- <option value="create_new">Create New Sales Rep</option> --}}
                                        @foreach ($sales_reps as $sales_rep)
                                            <option value="{{ $sales_rep->id }}" {{ $sales_rep->id == \Auth::user()->id ? 'selected' : '' }}>{{ $sales_rep->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->

    <div class="modal fade modal-primary" id="add_new_rep_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create new Sales Rep</h4>
                </div>

                <div class="modal-body">
                    <div class="panel-body">
                        <form class="form-edit-add" role="form" action="" id="form_add_sales_rep" method="POST" enctype="multipart/form-data" autocomplete="off">

                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-bordered">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="" required maxlength="50">
                                            </div>

                                            <div class="form-group">
                                                <label for="email">E-mail</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" value="" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="company">Company</label>
                                                <input type="text" class="form-control" id="company" name="company" value="" placeholder="Company" required maxlength="100">
                                            </div>

                                            @if (Auth::user()->role_id != 4)
                                                <div class="form-group">
                                                    <label for="">Coach</label>
                                                    <select name="coach_id" id="coach_id" class="form-group select2">
                                                        <option value="" disabled selected>Select Coach</option>
                                                        @foreach ($coaches as $coach)
                                                            <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <input type="hidden" name="coach_id" value="{{ Auth::user()->id }}">
                                            @endif

                                            <input type="hidden" name="role_id" value="5">
                                            <input type="hidden" name="locale" value="en">
                                            <input type="hidden" name="form_type" value="modal">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="panel panel panel-bordered panel-warning">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <img src="http://localhost:8001/storage/users/default.png" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                                <input type="file" data-name="avatar" name="avatar">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right save">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Create New Sales Rep Modal -->

    <div class="modal fade modal-primary" id="add_new_customer_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create New Customer</h4>
                </div>

                <div class="modal-body">
                    <div class="panel-body">
                        <form class="form-edit-add" role="form" action="" id="form_add_customer" method="POST" enctype="multipart/form-data" autocomplete="off">

                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-bordered">
                                        <div class="panel-body">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="" required maxlength="50">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name">First Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="" required maxlength="50">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" value="" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <input type="text" class="form-control" id="state" name="state" placeholder="State" value="" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company</label>
                                                    <input type="text" class="form-control" name="company" value="" placeholder="Company" required maxlength="100">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>E-mail</label>
                                                    <input type="email" class="form-control" name="email" placeholder="E-mail" value="" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer_type">Customer Type</label>
                                                    <select class="form-control" id="customer_type" name="customer_type" value="" required>
                                                        <option value="NA">N/A</option>
                                                        <option value="Real Estate Agent">Real Estate Agent</option>
                                                        <option value="Real Estate Broker">Real Estate Broker</option>
                                                        <option value="Lender">Lender</option>
                                                    </select>
                                                </div>
                                            </div>

                                            @if (\Auth::user()->role_id != 5)
                                                <div class="form-group col-md-6">
                                                    <label for="">Sales Rep</label>
                                                    <select name="sales_rep_id" class="form-control select2">
                                                        <option value="" disabled selected>None</option>
                                                        {{-- <option value="create_new">Create New Sales Rep</option> --}}
                                                        @foreach ($sales_reps as $sales_rep)
                                                            <option value="{{ $sales_rep->id }}" {{ isset($dataTypeContent->sales_rep_id) && $sales_rep->id == $dataTypeContent->sales_rep_id ? 'selected' : '' }}>{{ $sales_rep->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif (\Auth::user()->role_id == 5)
                                                {{-- <input type="hidden" name="sales_rep_id" value="{{ \Auth::user()->id }}"> --}}
                                                <div class="form-group col-md-6">
                                                    <label for="">Sales Rep</label>
                                                    <select name="sales_rep_id" class="form-control select2">
                                                        <option value="" disabled selected>None</option>
                                                        {{-- <option value="create_new">Create New Sales Rep</option> --}}
                                                        @foreach ($sales_reps as $sales_rep)
                                                            <option value="{{ $sales_rep->id }}" {{ $sales_rep->id == \Auth::user()->id ? 'selected' : '' }}>{{ $sales_rep->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                            <input type="hidden" name="form_type" value="modal">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right save">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Create New Customer Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $("#customer_id").on("change", function() {
                if($(this).val() == "create_new") {
                    $("#add_new_customer_modal").modal('show');
                }
            });

            $("#sales_rep_id").on("change", function() {
                if($(this).val() == "create_new") {
                    $("#add_new_rep_modal").modal('show');
                }
            });

            $("#form_add_customer").submit(function(event){
                event.preventDefault();
                var values = $(this).serialize();
                $.ajax({
                    url: "{{ route('voyager.customers.store') }}",
                    type: 'POST',
                    data: values,
                    success: function (result) {
                        // console.log(data);
                        var data = {
                            id: result['data'].id,
                            text: result['data'].first_name +" "+ result['data'].last_name,
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $('#customer_id').append(newOption).val(data.id).trigger('change');

                        toastr.success(result['message']);

                        $("#add_new_customer_modal").modal('hide');
                        $("#form_add_customer").trigger("reset");
                    }
                });
            });

            $("#form_add_sales_rep").submit(function(event){
                event.preventDefault();
                var values = $(this).serialize();
                $.ajax({
                    url: "{{ route('voyager.users.store') }}",
                    type: 'POST',
                    data: values,
                    success: function (result) {
                        // console.log(data);
                        var data = {
                            id: result['data'].id,
                            text: result['data'].name,
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $('#sales_rep_id').append(newOption).val(data.id).trigger('change');

                        toastr.success(result['message']);

                        $("#add_new_rep_modal").modal('hide');
                        $("#form_add_sales_rep").trigger("reset");
                    }
                });
            });

            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
