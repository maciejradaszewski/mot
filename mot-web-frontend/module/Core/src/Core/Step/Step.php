<?php

namespace Core\Step;

/**
 * Interface for a Paged Step.
 */
interface Step
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param array $values
     *
     * @return bool
     */
    public function validate(array $values = []);

    /**
     * @return Step
     */
    public function load();

    /**
     * @return bool
     */
    public function save();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return array
     */
    public function toViewArray();

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function readFromArray(array $values);

    /**
     * The route for this step.
     *
     * @return string
     */
    public function route();

    /**
     * @return array
     */
    public function routeParams();

    /**
     * describes the steps progress in the process.
     *
     * Step 1 of 6
     * Step 2 of 6
     * etc
     *
     * @return string|null
     */
    public function getProgress();
}
