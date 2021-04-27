<?php

namespace pncOrg\LaravelLogger\App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait ModelEventLogger
{

    /**
     * Automatically boot with Model, and register Events handler.
     */
    protected static function bootModelEventLogger()
    {

        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $reflect = new \ReflectionClass($model);
                    $data = [
                        'user_id'     => Auth::user()->id,
                        'contentId'   => $model->id,
                        'contentType' => get_class($model),
                        'action'      => static::getActionName($eventName),
                        'description' => ucfirst($eventName) . " a " . $reflect->getShortName(),
                        'details'     => json_encode($model->getDirty())
                    ];
                    ActivityLogger::activity(ucfirst($eventName) . " a " . $reflect->getShortName(),json_encode($model->getDirty()));
                    /*return Activity::create([
                        'user_id'     => \Auth::user()->id,
                        'contentId'   => $model->id,
                        'contentType' => get_class($model),
                        'action'      => static::getActionName($eventName),
                        'description' => ucfirst($eventName) . " a " . $reflect->getShortName(),
                        'details'     => json_encode($model->getDirty())
                    ]);
                    */
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
