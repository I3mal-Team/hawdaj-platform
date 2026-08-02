<?php

return [
    'name' => 'CarModel',
    'columns' => [
        0 => 'created_at',
        1 => 'notice_time',
        2 => 'detection_status',
        4 => 'id',
    ],
    'report' => [
        'total' => [
            'data' => ['count'],
            'unit' => 'number',
            'className' => 'TotalReport'
        ],
        'invitation_no_invitation' => [
            'data' => ['invitation', 'no_invitation'],
            'unit' => 'number',
            'className' => 'CompareInvitationReport'
        ],
        'actions' => [
            'data' => ['notice', 'not_notice'],
            'unit' => 'number',
            'className' => 'ActionReport'
        ],
        'notifications' => [
            'data' => ['duration_time', 'total_notification'],
            'unit' => 'mixed',
            'column_unit' => ['duration_time' => 'time', 'total_notification' => 'number'], //in mixed case only
            'className' => 'NotificationReport'
        ],
        'duration' => [
            'data' => ['risk_duration', 'no_risk_duration'],
            'unit' => 'time',
            'className' => 'DurationReport'
        ]
    ],
];
