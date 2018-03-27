<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin">
            <i class="fa fa-history" aria-hidden="true"></i>
            <?php echo trans($plang_admin . '.labels.title-backup') ?>
        </h3>
    </div>
    <div class="panel-body">

        <table class="table table-hover">

            <thead>
                <tr style="height: 50px;">

                    <!--VERSION-->
                    <th style='width:10%'>
                        {{ trans($plang_admin.'.columns.order') }}
                    </th>

                    <!--FILENAME-->
                    <th style='width:90%'>
                        {{ trans($plang_admin.'.columns.filename') }}
                    </th>


                </tr>

            </thead>

            <tbody>
                <?php $version = count($backups) ?>
                @foreach($backups as  $backup)
                <tr>
                    <!--COUNTER-->
                    <td> {!! 'v.'.$version; $version-- !!}  </td>

                    <!--NAME-->
                    <td>
                        <a href="{!! URL::route('posts.config', ['v' => base64_encode($backup)]) !!}">
                            {!! basename($backup) !!}
                        </a>
                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

    </div>
</div>