<?php

return [
    'service_manager' => [
        'invokables' => [
            'slot-api\entity\slot-transaction'                  => DvsaEntities\Entity\TestSlotTransaction::class,
            'slot-api\entity\slot-transaction-status'           => DvsaEntities\Entity\TestSlotTransactionStatus::class,
            'slot-api\entity\slot-transaction-amendment'        => DvsaEntities\Entity\TestSlotTransactionAmendment::class,
            'slot-api\entity\slot-transaction-amendment-type'   => DvsaEntities\Entity\TestSlotTransactionAmendmentType::class,
            'slot-api\entity\slot-transaction-amendment-reason' => DvsaEntities\Entity\TestSlotTransactionAmendmentReason::class,
            'slot-api\entity\configuration'                     => DvsaEntities\Entity\Configuration::class,
            'slot-api\entity\organisation'                      => DvsaEntities\Entity\Organisation::class,
            'slot-api\entity\payment'                           => DvsaEntities\Entity\Payment::class,
            'slot-api\entity\person'                            => DvsaEntities\Entity\Person::class,
            'slot-api\entity\payment-type'                      => DvsaEntities\Entity\PaymentType::class,
            'slot-api\entity\payment-status'                    => DvsaEntities\Entity\PaymentStatus::class,
            'slot-api\entity\auth-examiner'                     => DvsaEntities\Entity\AuthorisationForAuthorisedExaminer::class,
            'slot-api\entity\site'                              => DvsaEntities\Entity\Site::class,
            'slot-api\entity\mot-test'                          => DvsaEntities\Entity\MotTest::class,
            'slot-api\entity\direct-debit'                      => DvsaEntities\Entity\DirectDebit::class,
            'slot-api\entity\direct-debit-status'               => DvsaEntities\Entity\DirectDebitStatus::class,
            'slot-api\entity\vehicle'                           => DvsaEntities\Entity\Vehicle::class,
        ]
    ]
];
