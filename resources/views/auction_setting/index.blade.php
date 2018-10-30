<div class="container">

    <div class="">
        {{ Session::get('message') }}
    </div>

    <div class="row">
        <div class="pull-right">
            {!! Form::open(['route' => 'aution_settings.search']) !!}
            <input class="form-control form-inline pull-right" name="search" placeholder="Search">
            {!! Form::close() !!}
        </div>
        <h1 class="pull-left">AutionSettings</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('aution_settings.create') !!}">Add New</a>
    </div>

    <div class="row">
        @if($aution_settings->isEmpty())
            <div class="well text-center">No autionSettings found.</div>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th width="50px">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($aution_settings as $aution_setting)
                    <tr>
                        <td>
                            <a href="{!! route('aution_settings.edit', [$aution_setting->id]) !!}">{{ $aution_setting->name }}</a>
                        </td>
                        <td>
                            <a href="{!! route('aution_settings.edit', [$aution_setting->id]) !!}"><i class="fa fa-pencil"></i> Edit</a>
                            <form method="post" action="{!! route('aution_settings.destroy', [$aution_setting->id]) !!}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this aution_setting?')"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="row">
                {!! $aution_settings; !!}
            </div>
        @endif
    </div>
</div>