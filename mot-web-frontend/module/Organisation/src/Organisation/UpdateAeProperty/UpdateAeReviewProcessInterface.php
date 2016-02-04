<?php

namespace Organisation\UpdateAeProperty;

use Core\ViewModel\Gds\Table\GdsTable;

interface UpdateAeReviewProcessInterface extends UpdateAePropertyProcessInterface
{
    /**
     * This will take the form and create a GdsTable that will be shown as summary
     * for user to review before completing the form.
     *
     * @param $aeId
     * @param array $formData
     * @return GdsTable
     */
    public function transformFormIntoGdsTable($aeId, array $formData);

    /**
     * The title that will be displayed on the review page
     *
     * @return string
     */
    public function getReviewPageTitle();

    /**
     * The page lede that will be displayed on the review page
     *
     * @return string
     */
    public function getReviewPageLede();

    /**
     * The text that will be displayed on the review page button text
     *
     * @return string
     */
    public function getReviewPageButtonText();

    /**
     * The Breadcrumb label that will be displayed on the page
     *
     * @return string
     */
    public function getBreadcrumbLabel();
}
