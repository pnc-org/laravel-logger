{!! Form::open(array('route' => 'restore-activity', 'method' => 'POST', 'class' => 'mb-0')) !!}
    {!! Form::button(trans('LaravelLogger::laravel-logger.dashboardCleared.menu.restoreAll'), array('type' => 'button', 'class' => 'dropdown-item', 'data-toggle' => 'modal', 'data-target' => '#confirmRestore', 'data-title' => trans('LaravelLogger::laravel-logger.modals.restoreLog.title'),'data-message' => trans('LaravelLogger::laravel-logger.modals.restoreLog.message'))) !!}
{!! Form::close() !!}
