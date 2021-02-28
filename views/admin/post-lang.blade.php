@extends('package-acl::admin.layouts.base-2cols')

@section('title')
    {{ trans($plang_admin.'.pages.title-lang') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <!--LIST OF ITEMS-->
            <div class="col-md-8">

                <div class="panel panel-info">

                    <!--HEADING-->
                    <div class="panel-heading">
                        <h3 class="panel-title bariol-thin">
                            <i class="fa fa-language" aria-hidden="true"></i>
                            {!! trans($plang_admin.'.pages.title-lang') !!}
                        </h3>
                    </div>

                    <!--DESCRIPTION-->
                    <div class='panel-info panel-description'>
                        {!! trans($plang_admin.'.descriptions.lang') !!}</h4>
                    </div>
                    <!--/DESCRIPTION-->

                    <!--MESSAGE-->
                    <?php $message = Session::get('message'); ?>
                    @if( isset($message) )
                        <div class="panel-info alert alert-success flash-message">{!! $message !!}</div>
                    @endif
                    <!--/MESSAGE-->

                    <!--ERRORS-->
                    @if($errors && ! $errors->isEmpty() )
                        @foreach($errors->all() as $error)

                            <div class="alert alert-danger flash-message">{!! $error !!}</div>

                        @endforeach
                    @endif
                    <!--/ERRORS-->

                    <!--BODY-->
                    <div class="panel-body">
                        {!! Form::open(['route'=>['posts.lang'], 'method' => 'post'])  !!}

                            <div class='btn-form'>

                                <!-- SAVE BUTTON -->
                                {!! Form::submit(trans($plang_admin.'.buttons.save'), array("class"=>"btn btn-info pull-right ")) !!}
                                <!-- /SAVE BUTTON -->

                            </div>

                        <!--TAB MENU-->
                        @if(isset($langs))
                        <ul class="nav nav-tabs">
                            @foreach($langs as $key => $value)
                            <!--LANG TAB-->
                            <li class="{!! ($key==$lang)?'active':'' !!}">
                                <a data-toggle="tab" href="#{{$key}}">
                                    {!! $value !!}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        <!--/TAB MENU-->

                        <div class="tab-content">

                        <!--LANG CONTENT-->
                        @foreach($lang_contents as $key => $content)
                            <div id="{{$key}}" class="tab-pane fade {!! ($key==$lang)?'in active':'' !!}">
                                {!! Form::textarea($key, $content, ['class' => 'form-control textarea-margin', 'size' => '30x50']) !!}
                            </div>
                        @endforeach

                        </div>


                            {!! Form::close() !!}
                    </div>
                    <!--/BODY-->

                </div>
            </div>
            <!--/LIST OF ITEMS-->

            <!--SEARCH-->
            <div class="col-md-4">
                @include('package-post::admin.post-lang-backup')
            </div>
            <!--/SEARCH-->

        </div>
    </div>
@stop