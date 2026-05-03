<?php

use App\Models\ActivityLog;

function activity_log($action, $model = null, $message = null)
{
    $old = null;
    $new = null;

    if ($model) {

        // capture current state BEFORE anything is lost
        $attributes = $model->getAttributes();

        if ($action === 'created') {
            $new = $attributes;
        }

        if ($action === 'updated') {
            $old = $model->getOriginal();   // before update
            $new = $attributes;             // after update
        }

        if ($action === 'deleted') {
            $old = $attributes;             // FULL snapshot before delete
            $new = null;
        }
    }

    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'message' => $message,
        'subject_type' => $model ? get_class($model) : null,
        'subject_id' => $model?->id,

        'old_values' => $old,
        'new_values' => $new
    ]);
}