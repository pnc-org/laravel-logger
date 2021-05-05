@php

$drilldownStatus = config('LaravelLogger.enableDrillDown');
$prependUrl = '/activity/log/';

if (isset($hoverable) && $hoverable === true) {
    $hoverable = true;
} else {
    $hoverable = false;
}

if (Request::is('activity/cleared')) {
    $prependUrl = '/activity/cleared/log/';
}

@endphp

<div class="table-responsive activity-table {{ isset($customClass) ? $customClass . ' mt-5' : '' }}">
    <table class="table  table-condensed table-sm data-table">
        <thead>
            <tr>
                <th>
                    {!! trans('LaravelLogger::laravel-logger.dashboard.labels.item') !!}
                </th>
                <th>
                    {!! trans('LaravelLogger::laravel-logger.dashboard.labels.description') !!}
                </th>
                <th>
                    {!! trans('LaravelLogger::laravel-logger.dashboard.labels.module') !!}
                </th>
                <th>
                    {!! trans('LaravelLogger::laravel-logger.dashboard.labels.time') !!}
                </th>

                @if(Request::is('activity/cleared'))
                    <th>
                        <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                        {!! trans('LaravelLogger::laravel-logger.dashboard.labels.deleteDate') !!}
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr class="clickable-row" data-href="{{ url($prependUrl . $activity->id) }}" >
                    <td class="font-weight-bold">
                        @php
                            switch ($activity->userType) {
                                case trans('LaravelLogger::laravel-logger.userTypes.registered'):
                                    // $userTypeClass = 'success';
                                    $userLabel = $activity->userDetails['first_name']. ' '.$activity->userDetails['last_name'];
                                    break;

                                case trans('LaravelLogger::laravel-logger.userTypes.externalSource'):
                                    // $userTypeClass = 'danger';
                                    $userLabel = $activity->userType;
                                    break;

                                case trans('LaravelLogger::laravel-logger.userTypes.guest'):
                                default:
                                    // $userTypeClass = 'warning';
                                    $userLabel = $activity->userType;
                                    break;
                            }

                        @endphp
                        <span class="{{--badge badge-{{$userTypeClass}}--}}">
                            {{$userLabel}}
                        </span>
                    </td>
                    <td class="font-weight-bold">
                        {{ $activity->description }}
                    </td>
                    <td title="{{ $activity->created_at }}" class="font-weight-bold">
                        @if(config('LaravelLogger.hideLogRequest'))
                        @lang(strtolower(class_basename($activity->contentType)).'.title')
                        @endif
                    </td>
                    <td title="{{ $activity->created_at }}" class="font-weight-bold">
                        {{ $activity->created_at->format('d.m.Y. / H:i') }}
                    </td>

                    @if(Request::is('activity/cleared'))
                        <td>
                            {{ $activity->deleted_at }}
                        </td>
                    @endif
                </tr>
                {{-- @dump($activity->toArray()) --}}
            @endforeach
        </tbody>
    </table>
</div>

@if(config('LaravelLogger.loggerPaginationEnabled'))
    <div class="text-center">
        <div class="d-flex justify-content-start activity-pagination">
            {!! $activities->links('LaravelLogger::logger.partials.pagination')->render() !!}

        </div>

        <p>
            {!! trans('LaravelLogger::laravel-logger.pagination.countText', ['firstItem' => $activities->firstItem(), 'lastItem' => $activities->lastItem(), 'total' => $activities->total(), 'perPage' => $activities->perPage()]) !!}
        </p>
    </div>
@endif
