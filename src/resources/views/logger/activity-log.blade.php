@extends(config('LaravelLogger.loggerBladeExtended'))

@if(config('LaravelLogger.bladePlacement') == 'yield')
@section(config('LaravelLogger.bladePlacementCss'))
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
@push(config('LaravelLogger.bladePlacementCss'))
@endif
@section('styles')
    {{-- @include('LaravelLogger::partials.styles') --}}
@endsection

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


@if(config('LaravelLogger.enableDrillDown'))
    @include('LaravelLogger::scripts.clickable-row')
@endif

@if(config('LaravelLogger.bladePlacement') == 'yield')
@endsection
@elseif (config('LaravelLogger.bladePlacement') == 'stack')
@endpush
@endif

@section('template_title')
    {{ trans('LaravelLogger::laravel-logger.dashboard.title') }}
@endsection

@php
    switch (config('LaravelLogger.bootstapVersion')) {
        case '4':
        $containerClass = 'card';
        $containerHeaderClass = 'card-header border-0';
        $containerBodyClass = 'card-body';
        break;
        case '3':
        default:
        $containerClass = 'panel panel-default';
        $containerHeaderClass = 'panel-heading';
        $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('LaravelLogger.bootstrapCardClasses')) ? '' : config('LaravelLogger.bootstrapCardClasses'));
@endphp
@section('breadcrumbs', true)
@section('replacementMenu')
    @if(config('LaravelLogger.enableSubMenu'))
        <div class="btn-group dropdown pull-right btn-group-xs" role="group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle logger-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('LaravelLogger::laravel-logger.dashboard.menu.actions')
                <span class="sr-only">
                    {!! trans('LaravelLogger::laravel-logger.dashboard.menu.alt') !!}
                </span>
            </button>
            @if(config('LaravelLogger.bootstapVersion') == '4')
            <div class="dropdown-menu dropdown-menu-right">
                @include('LaravelLogger::forms.clear-activity-log')
                <a href="{{route('cleared')}}" class="dropdown-item">
                    {!! trans('LaravelLogger::laravel-logger.dashboard.menu.show') !!}
                </a>
            </div>
            @else
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-item">
                    @include('LaravelLogger::forms.clear-activity-log')
                </li>
                <li class="dropdown-item">
                    <a href="{{route('cleared')}}">
                        {!! trans('LaravelLogger::laravel-logger.dashboard.menu.show') !!}
                    </a>
                </li>
            </ul>
            @endif
        </div>


    @endif
@endsection
@section('content')

    <div class="container-fluid">

       @if(config('LaravelLogger.enablePackageFlashMessageBlade'))
       @include('LaravelLogger::partials.form-status')
       @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="{{ $containerClass }} {{ $bootstrapCardClasses }}">
                    <div class="{{ $containerHeaderClass }}">
                        @if(config('LaravelLogger.enableSearch'))
                        @include('LaravelLogger::partials.form-search')
                        @endif

                    </div>
                    <div class="{{ $containerBodyClass }}">
                        @include('LaravelLogger::logger.partials.activity-table', ['activities' => $activities, 'hoverable' => true])
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('LaravelLogger::modals.confirm-modal', ['formTrigger' => 'confirmDelete', 'modalClass' => 'danger', 'actionBtnIcon' => 'fa-trash-o'])

@endsection

@section('scripts')
    @include('LaravelLogger::partials.scripts', ['activities' => $activities])
    @include('LaravelLogger::scripts.confirm-modal', ['formTrigger' => '#confirmDelete'])
    @if(config('LaravelLogger.enableDrillDown'))
        @include('LaravelLogger::scripts.clickable-row')
    @endif

@endsection
