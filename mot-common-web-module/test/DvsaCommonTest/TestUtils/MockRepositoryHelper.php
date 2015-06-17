<?php

namespace DvsaCommonTest\TestUtils;

/**
 * Holds useful functions for mock repositories
 */
class MockRepositoryHelper
{

    /**
     * Adds an assertion on mock repository(ies) that no persist
     * method can be called
     *
     * Accepts any number of arguments that are repository mocks
     */
    public static function assertNoPersist()
    {
        $testCase = BacktraceTestCaseFinder::find();
        $repos = func_get_args();

        foreach ($repos as $repo) {
            $repo->expects($testCase->never())->method('persist');
        }
    }

    /**
     * Asserts persist method to be executed on the repo and returns
     * the ArgCapture handle to the persist function argument that
     * is available for inspection after SUT method call
     * @param      $repo
     * @param null $expects
     *      additional expects() function arguments where an extra modifier can be specified
     *      By default, atLeastOnce() is used
     *
     * @return ArgCapture
     */
    public static function mockPersist($repo, $expects = null)
    {
        $capture = ArgCapture::create();
        $testCase = BacktraceTestCaseFinder::find();
        $expects = $expects ? : $testCase->atLeastOnce();
        $repo->expects($expects)->method('persist')->with($capture());

        return $capture;
    }

}
