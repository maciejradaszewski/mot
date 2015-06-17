<?php

namespace MotFitnesse\Util;

abstract class RetrieveCheckingHelper
{
    private $id;
    /**
     * @var CredentialsProvider
     */
    protected $credentialsProvider;

    public function __construct($id)
    {
        $this->credentialsProvider = new Tester1CredentialsProvider();
        $this->id = $id;
    }

    abstract protected function retrieve($id);

    /**
     * Re-reads the saved entity and checks against the expected values.
     *
     * @param   array     $expected     expected array
     * @param   \stdClass $result       unmarshalled JSON object of the previous update's response
     *                                  (e.g. { 'data' : 'bla', 'errors': 'foo' } )
     * @param   mixed     $arrayTrimmer key in the retrieved JSON to compare with the array,
     *                                  or a callback function to do the extraction
     *                                  (if not present, the entire 'data' contents are compared)
     * @param array       $keySubset    if present, only this subset of fields will be compared
     *
     * @return true, or a list of the differences, or 'N/A' if the previous update response wasn't a success
     */
    public function savedCorrectly($expected, $result, $arrayTrimmer = null, array $keySubset = [])
    {
        if (TestShared::resultIsSuccess($result)) {

            $fullApiResult = $this->retrieve($this->id);

            $fullApiResultArray = $this->convertToArray($fullApiResult);
            $result = $this->trimRetrievedData($fullApiResultArray, $arrayTrimmer, $keySubset);

            return $this->printOutDiffOrReturnTrue($expected, $result);
        }

        return 'N/A';
    }

    /**
     * @param array $keySubset e.g. [ 'a', 'b' ]
     *
     * @return array e.g. [ 'a' => '', 'b' => '' ]
     */
    private function convertArrayToAssocArray($keySubset)
    {
        $keySubsetForIntersect = [];
        foreach ($keySubset as $key) {
            $keySubsetForIntersect[$key] = '';
        }

        return $keySubsetForIntersect;
    }

    /*
     * from http://stackoverflow.com/a/3877494/116509
     */
    private function arrayRecursiveDiff($aArray1, $aArray2)
    {
        $aReturn = [];

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) {
                        $aReturn[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }

        return $aReturn;
    }

    /**
     * Converts \stdClass to array
     *
     * @param \stdClass|array $retrievedEntity
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function convertToArray($retrievedEntity)
    {
        /*
         * Ensure we have an array rather than a stdClass.
         * This is a bit of a waste, but the alternative requires changing all
         * the tests to use the ",true" version to retrieve an array.
         */
        $retrievedEntity = json_decode(json_encode($retrievedEntity), true);

        return $retrievedEntity;
    }

    /**
     * Returns trimmed data
     *
     * @param array           $data
     * @param string|callable $arrayTrimmer
     * @param array           $keySubset
     *
     * @return mixed
     */
    private function trimRetrievedData($data, $arrayTrimmer, $keySubset = [])
    {
        $result = $data;

        // get only one value form array
        if (is_scalar($arrayTrimmer)) {
            $result = $data[$arrayTrimmer];
        }

        // more sophisticated operations on array - call function and return result
        if (is_callable($arrayTrimmer)) {
            $result = $arrayTrimmer($data);
        }

        return $this->getSubsetFromArray($keySubset, $result);
    }

    /**
     * Gets subset from array
     *
     * @param array $keySubset
     * @param mixed $retrievedArray
     *
     * @return array
     */
    private function getSubsetFromArray(array $keySubset, $retrievedArray)
    {
        if (count($keySubset) > 0) {
            $keySubsetForIntersect = $this->convertArrayToAssocArray($keySubset);
            $retrievedArray = array_intersect_key($retrievedArray, $keySubsetForIntersect);

            return $retrievedArray;
        }

        return $retrievedArray;
    }

    /**
     * Compares two passed arrays and returns true if they are same or string with diff
     *
     * @param array $expected
     * @param mixed $result
     *
     * @return bool|string
     */
    private function printOutDiffOrReturnTrue($expected, $result)
    {
        if (is_scalar($result)) {
            $differences = array_diff($expected, (array)$result);
        } else {
            $differences = $this->arrayRecursiveDiff($expected, $result);
        }

        if (count($differences) > 0) {
            return (
                'expected: ' . print_r($expected, true) . "\n"
                . 'result: ' . print_r($result, true) . "\n"
                . 'diff:  ' . print_r($differences, true) . "\n"
            );
        }

        return true;
    }
}
