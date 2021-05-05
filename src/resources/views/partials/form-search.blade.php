@php
    $userIdField = config('LaravelLogger.defaultUserIDField')
@endphp

<form action="{{route('activity')}}" method="get">
    <div class="row mb-3">
        @if(in_array('description',explode(',', config('LaravelLogger.searchFields'))))
            <div class="col-12 col-sm-4 col-lg-2 mb-2">
                <div class="input-icon">
                    <input type="text" name="description" value="{{request()->get('description') ? request()->get('description'):null}}" class="form-control" placeholder="{{ trans('LaravelLogger::laravel-logger.dashboard.search.placeholder') }}">
                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                </div>
            </div>
        @endif

        @if(in_array('description',explode(',', config('LaravelLogger.searchFields'))))
            <div class="col-12 col-sm-4 col-lg-1 mb-2 ">
                <input type="submit" class="btn btn-info btn-block" value="{{ trans('LaravelLogger::laravel-logger.dashboard.search.search') }}">
            </div>
        @endif
    </div>
</form>
