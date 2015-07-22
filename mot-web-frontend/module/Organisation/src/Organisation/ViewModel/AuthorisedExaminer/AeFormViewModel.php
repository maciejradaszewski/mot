<?php

namespace Organisation\ViewModel\AuthorisedExaminer;

use Organisation\Form\AeCreateForm;

class AeFormViewModel
{
    /**
     * @var  AeCreateForm
     */
    private $form;

    private $cancelUrl;

    /**
     * @return AeCreateForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param AeCreateForm $dto
     *
     * @return $this
     */
    public function setForm(AeCreateForm $dto = null)
    {
        $this->form = $dto;
        return $this;
    }

    /**
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;
        return $this;
    }

}
