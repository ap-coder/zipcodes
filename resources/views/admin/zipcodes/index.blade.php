@extends('layouts.admin')
@section('content')


<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.zipcode.title_singular') }}
    </div>
    <div class="row col-md-12" style="margin-top:3%;">
        <div id="result" class="col-md-6"></div>
    </div>

    <div class="card-body">
        <form id="zipsearch" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="city">{{ trans('cruds.zipcode.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', '') }}">
                @if($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.zipcode.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="state">{{ trans('cruds.zipcode.fields.state') }}</label>
                <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', '') }}">
                @if($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.zipcode.fields.state_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-default" type="submit">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-header">
        {{ trans('cruds.zipcode.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Zipcode">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            Zipcodes
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {

        console.clear();

        $('#zipsearch').on('submit', function(event)
        {
            event.preventDefault();

            $.ajax({
                url:'{{ route("admin.zipcodes.connect") }}',
                method : 'POST',
                data:$(this).serialize(),
                dataType: 'json',
                beforeSend:function()
                {},
                success:function(data)
                {
                    if(data.error)
                    {
                        var error_html ='';
                        for (
                            var count = 0;
                            count < data.error.length;
                            count++
                            )
                        {
                            error_html += '<p>'+data.error[count]+'</p>';
                        }
                        $('#result').html('<div class="alert alert-danger">'+ error_html  +'</div>');
                    }
                    else
                    {
                        console.log(data);

                        $('#result').html('<div class="alert alert-success">Api Query Successful</div>');

                        var res='';

                        $.each (data, function () {
                            $.each(this, function(index, item) {
                                res += '<tr><td></td><td><strong>'+item+'</strong></td></tr>';
                            });
                        });

                        $('tbody').html(res);

                    }


                }
            });
        });



    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

      $.extend(true, $.fn.dataTable.defaults, {
        order: [[ 1, 'desc' ]],
        pageLength: 100,
      });
      $('.datatable-Zipcode:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
})

</script>
@endsection
