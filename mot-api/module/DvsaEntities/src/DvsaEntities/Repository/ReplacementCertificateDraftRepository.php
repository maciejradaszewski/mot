<?php

namespace DvsaEntities\Repository;

use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\ReplacementCertificateDraft;

/**
 * Class ReplacementCertificateDraftRepository
 *
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class ReplacementCertificateDraftRepository extends AbstractMutableRepository
{

    /**
     * @param string $draftId
     *
     * @return ReplacementCertificateDraft
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function get($draftId)
    {
        $result = $this->find($draftId);
        if (!$result) {
            throw new NotFoundException("Replacement certificate draft", $draftId);
        }
        return $result;
    }
}
