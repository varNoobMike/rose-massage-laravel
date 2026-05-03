<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        activity_log(
            'created',
            $model,
            $this->message($model, 'created')
        );
    }

    public function updated(Model $model): void
    {
        activity_log(
            'updated',
            $model,
            $this->message($model, 'updated')
        );
    }

    public function deleted(Model $model): void
    {
        activity_log(
            'deleted',
            $model,
            $this->message($model, 'deleted')
        );
    }

    private function message($model, $action): string
    {
        $user = auth()->user()->name ?? 'System';
        $name = class_basename($model);

        $label = $model->title ?? $model->name ?? ('#' . $model->id);

        return "{$user} {$action} {$name}: {$label}";
    }
}