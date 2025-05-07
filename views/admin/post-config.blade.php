@extends('package-acl::admin.layouts.base-2cols')

@section('title')
    {{ trans($plang_admin.'.pages.title-config') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">

            <!--LIST OF ITEMS-->
            <div class="col-md-8">

                <div class="panel panel-info">

                    <!--HEADING-->
                    <div class="panel-heading">
                        <h3 class="panel-title bariol-thin"><i class="fa fa-braille" aria-hidden="true"></i>
                            {!! trans($plang_admin.'.pages.title-config') !!}
                        </h3>
                    </div>

                    <!--DESCRIPTION-->
                    <div class='panel-info panel-description'>
                        {!! trans($plang_admin.'.descriptions.config') !!}</h4>
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
                        {!! html()->form('POST', route('posts.config'))->open() !!}

                        <div class='btn-form'>

                            <!-- SAVE BUTTON -->
                            {!! html()->submit(trans($plang_admin.'.buttons.save'))->class('btn btn-info pull-right') !!}
                            <!-- /SAVE BUTTON -->

                        </div>

                        {!! html()->label(trans($plang_admin.'.labels.config'))->for('content') !!}
                        {!! html()->textarea('content', $content)->class('form-control textarea-margin')->attribute('size', '30x50') !!}
                        {!! html()->closeForm() !!}
                    </div>
                    <!--/BODY-->

                </div>
            </div>
            <!--/LIST OF ITEMS-->

            <!--SEARCH-->
            <div class="col-md-4">
                @include('package-post::admin.post-config-backup')
            </div>
            <!--/SEARCH-->

        </div>
    </div>
@stop
