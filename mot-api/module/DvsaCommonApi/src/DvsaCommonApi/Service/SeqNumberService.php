<?php

namespace DvsaCommonApi\Service;

class SeqNumberService extends AbstractService
{
    public function getNextSeqNumber($seqCode)
    {
        $sql = 'call sp_sequence(:CODE);';

        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $stmt->bindValue('CODE', $seqCode);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($stmt->rowCount() === 0 || !isset($result['sequence'])) {
            return null;
        }

        return $result['sequence'];
    }
}
