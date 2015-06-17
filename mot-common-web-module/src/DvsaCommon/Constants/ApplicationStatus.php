<?php

namespace DvsaCommon\Constants;

class ApplicationStatus extends BaseEnumeration
{
    const IN_PROGRESS = 'IN PROGRESS';
    const ACCEPTED = 'ACCEPTED';
    const APPROVED = 'APPROVED';
    const REJECTED = 'REJECTED';
    const SUBMITTED = 'SUBMITTED';
    const IN_REVIEW = 'IN REVIEW';
    const REFERRED_TO_APPLICANT = 'REFERRED TO APPLICANT';
    const TRAINING_COMPLETED = 'TRAINING COMPLETED';
    const DEMO_COMPLETED = 'DEMO COMPLETED';

    private static $friendlyNames
        = [
            self::IN_PROGRESS           => 'In progress',
            self::SUBMITTED             => 'Submitted',
            self::ACCEPTED              => 'Accepted',
            self::REFERRED_TO_APPLICANT => 'Refer to applicant',
            self::REJECTED              => 'Rejected',
            self::IN_REVIEW             => 'In review',
            self::TRAINING_COMPLETED    => 'Training completed',
            self::DEMO_COMPLETED        => 'Demo completed',
            self::APPROVED              => 'Approved',
        ];

    public static function getStatusFriendlyName($status)
    {
        if (array_key_exists($status, self::$friendlyNames)) {
            return self::$friendlyNames[$status];
        }

        return $status;
    }
}
