<?php

namespace Notus\LaravelLogger\App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use ReflectionClass;

/**
 * Automatically Log Add, Update, Delete events of Model.
 */
trait ModelEventLogger
{
    /**
     * Boot and register event handler
     *
     * @return void
     */
    protected static function bootModelEventLogger()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $reflect = new ReflectionClass($model);

                    $userType = trans('LaravelLogger::laravel-logger.userTypes.externalSource');
                    $userId = null;

                    if (Auth::check()) {
                        $userType = trans('LaravelLogger::laravel-logger.userTypes.registered');
                        $userIdField = config('LaravelLogger.defaultUserIDField');
                        $userId = Request::user()->{$userIdField};
                    }

                    $data = [
                        'description'   => ucfirst($eventName) . " a " . $reflect->getShortName(),
                        'details'       => json_encode($model->getDirty()),
                        'userType'      => $userType,
                        'userId'        => $userId,
                        'contentId'       => $model->id,
                        'contentType'     => get_class($model),
                        'route'         => Request::fullUrl(),
                        'ipAddress'     => Request::ip(),
                        'locale'        => Request::header('accept-language'),
                        'referer'       => Request::header('referer'),
                        'methodType'    => Request::method(),
                    ];

                    config('LaravelLogger.defaultActivityModel')::create($data);
                } catch (\Exception $e) {
                    return true;
                }
            });
        }
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created',
            'updated',
            'deleted',
        ];
    }

    /**
     * Return Suitable action name for Supplied Event
     *
     * @param $event
     * @return string
     */
    protected static function getActionName($event)
    {
        switch (strtolower($event)) {
            case 'created':
                return 'create';
                break;
            case 'updated':
                return 'update';
                break;
            case 'deleted':
                return 'delete';
                break;
            default:
                return 'unknown';
        }
    }
}
