<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-search"></i>
            <?php echo trans($plang_admin.'.labels.title-search') ?>
        </h3>
    </div>
    <div class="panel-body">

        {!! Form::open(['route' => 'posts.list','method' => 'get']) !!}

            <!--BUTTONS-->
            <div class="form-group">
                <a href="{!! URL::route('posts.list', ['context' => @$params['context']]) !!}" class="btn btn-default search-reset">
                    {!! trans($plang_admin.'.buttons.reset') !!}
                </a>
                {!! Form::submit(trans($plang_admin.'.buttons.search').'', ["class" => "btn btn-info", 'id' => 'search-submit']) !!}
            </div>

            <!-- KEYWORD -->
            @include('package-category::admin.partials.input_text', [
                'name' => 'keyword',
                'label' => trans($plang_admin.'.labels.keyword'),
                'value' => @$params['keyword'],
            ])

            <!-- STATUS -->
            @include('package-category::admin.partials.select_single', [
                'name' => 'status',
                'label' => trans($plang_admin.'.columns.status'),
                'value' => @$params['status'],
                'items' => $status,
            ])

            <!-- CATEGORIES -->
            @include('package-category::admin.partials.select_single', [
                'name' => 'category',
                'label' => trans($plang_admin.'.labels.category'),
                'value' => @$params['category'],
                'items' => $categories,
            ])

            <!--SORTING-->
            @include('package-category::admin.partials.sorting')

            <div class='hidden-field'>
                {!! Form::hidden('context',@$request->get('context',null)) !!}
                {!! csrf_field() !!}
            </div>

        {!! Form::close() !!}
    </div>
</div>