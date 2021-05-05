{!! Form::open(array('route' => 'destroy-activity', 'class' => 'mb-0')) !!}
    {!! Form::hidden('_method', 'DELETE') !!}
    {!! Form::button(trans('LaravelLogger::laravel-logger.dashboardCleared.menu.deleteAll'), array('type' => 'button', 'class' => 'dropdown-item', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => trans('LaravelLogger::laravel-logger.modals.deleteLog.title'),'data-message' => trans('LaravelLogger::laravel-logger.modals.deleteLog.message'))) !!}
{!! Form::close() !!}
