@php
    $userIdField = config('LaravelLogger.defaultUserIDField')
@endphp

@extends(config('LaravelLogger.loggerBladeExtended'))

@if(config('LaravelLogger.bladePlacement') == 'yield')
    @section(config('LaravelLogger.bladePlacementCss'))
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
    @push(config('LaravelLogger.bladePlacementCss'))
@endif

@if(config('LaravelLogger.bladePlacement') == 'yield')
    @endsection
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
    @endpush
@endif

@if(config('LaravelLogger.bladePlacement') == 'yield')
    @section(config('LaravelLogger.bladePlacementJs'))
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
    @push(config('LaravelLogger.bladePlacementJs'))
@endif

@section('scripts')
    @include('LaravelLogger::partials.scripts', ['activities' => $userActivities])
    @include('LaravelLogger::scripts.clickable-row')

@endsection
@if(config('LaravelLogger.bladePlacement') == 'yield')
    @endsection
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
    @endpush
@endif

@section('template_title')
    {{ trans('LaravelLogger::laravel-logger.drilldown.title', ['id' => $activity->id]) }}
@endsection

@php
    switch (config('LaravelLogger.bootstapVersion')) {
        case '4':
            $containerClass = 'card card-custom card-custom-narrow h-100';
            $containerHeaderClass = 'card-header';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-default';
            $containerHeaderClass = 'panel-heading';
            $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('LaravelLogger.bootstrapCardClasses')) ? '' : config('LaravelLogger.bootstrapCardClasses'));

    switch ($activity->userType) {
        case trans('LaravelLogger::laravel-logger.userTypes.registered'):
            $userLabel = $userDetails->first_name. ' '.$userDetails->last_name; //$activity->userDetails['first_name']. ' '.$activity->userDetails['last_name'];
            break;

        case trans('LaravelLogger::laravel-logger.userTypes.externalSource'):
            $userLabel = $activity->userType;
            break;

        case trans('LaravelLogger::laravel-logger.userTypes.guest'):
        default:
            $userLabel = $activity->userType;
            break;
    }
    $details = json_decode($activity->details, true);
    $isViewActivity = $activity->description;

@endphp
@section('breadcrumbs', true)
@section('breadcrumbItem')
    <li class="breadcrumb-item">
        <a href="{{ url('/activity/log/' . $activity->id) }}" class="">
            {!! trans('LaravelLogger::laravel-logger.drilldown.title', ['id' => $activity->id]) !!}
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    @if(config('LaravelLogger.enablePackageFlashMessageBlade'))
        @include('LaravelLogger::partials.form-status')
    @endif

    <div class="panel @if($isClearedEntry) panel-danger @else panel-default @endif">
        <div class="{{ $containerClass }} @if($isClearedEntry) panel-danger @else panel-default @endif">
            <div class="card-header">
                <h3>@lang('general.basic_change_info')</h3>
            </div>
            <div class="card-body">
                <div class="row mt-5">
                    @if($activity->contentId)
                    <div class="col-6">
                        <div class="form-group">
                            <label>@lang('general.module')</label>

                                <input type="text" class="form-control " name="module" disabled
                                       value="@lang(strtolower(class_basename($activity->contentType)).'.title')" />

                        </div>
                    </div>
                    @endif

                    <div class="col-6">
                        <div class="form-group">
                            <label>@lang('general.user')</label>
                            <input type="text" class="form-control " name="user" disabled
                                value="{{ $userLabel }}" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>@lang('general.description')</label>
                            <input type="text" class="form-control " name="description" disabled
                                value="{{ $activity->description }}" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>@lang('general.time')</label>
                            <input type="text" class="form-control " name="time" disabled
                                value="  {{ $activity->created_at->format('d.m.Y. / H:i') }}" />
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    @if ($activity->contentId)
                    <div class="col-6">
                        <div class="form-group">
                            <label>@lang('general.details')</label>
                            <textarea type="text" class="form-control form-control--preLine" name="details" rows="5" disabled>
                                @foreach ($details as $key => $detail)
                                    @if (!is_iterable($detail))
                                        @if ($key == "disabled_at")
                                            @if ($detail)
                                                @lang('general.' .$key) &#13;
                                            @else
                                                @lang('general.enabled') &#13;
                                            @endif
                                        @elseif ($key !== "updated_at" && $key !== "created_at")
                                            @lang(strtolower(class_basename($activity->contentType)). '.' .$key) : {{$detail}} &#13;
                                        @endif
                                    @else
                                        @foreach ($detail as $i => $item)
                                            @if ($item)
                                                @lang(strtolower(class_basename($activity->contentType)). '.' .$i) : {{$item}} &#13;
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </textarea>
                        </div>
                    </div>
                    @endif

                </div>
            </div>


            @if(!$isClearedEntry)
            <div class="card-header">
                <h3>@lang('general.other_activity')</h3>
            </div>

            <div class="activity-log-table-container card-body card-body-custom">
                @include('LaravelLogger::logger.partials.activity-table', ['activities' => $userActivities, 'customClass' => 'logs-table'])
            </div>

            @endif

        </div>
    </div>
</div>
@endsection
