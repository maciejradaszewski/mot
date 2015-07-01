<?php

namespace Core\Controller;

use DvsaFeature\Exception\FeatureNotAvailableException;
use DvsaFeature\FeatureToggleAwareInterface;
use Dvsa\OpenAM\OpenAMClientInterface;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use DvsaCommon\Utility\ArrayUtils;

abstract class AbstractDvsaActionController
    extends AbstractActionController
    implements FeatureToggleAwareInterface
{
    const FORM_ERROR_CONTAINER_NAMESPACE = 'formErrorMessages';
    const FORM_ERROR_CONTAINER_KEY       = 'errorData';
    const TEMPLATE_FLASH_ERROR           = 'error/flash-error';

    protected $form;
    protected $restClient;
    /**
     * @var OpenAMClientInterface
     */
    protected $openAMClient;

    /**
     * {@inheritdoc}
     */
    public function isFeatureEnabled($name)
    {
        return $this
            ->getServiceLocator()
            ->get('Feature\FeatureToggles')
            ->isEnabled($name);
    }

    /**
     * {@inheritdoc}
     */
    public function assertFeatureEnabled($name)
    {
        if (!$this->isFeatureEnabled($name)) {
            throw new FeatureNotAvailableException($name);
        }
    }

    protected function getLogger()
    {
        return $this->getServiceLocator()->get('Application/Logger');
    }

    /**
     * @return \DvsaCommon\HttpRestJson\Client|object
     * @deprecated Use mappers if it possible
     */
    protected function getRestClient()
    {
        if (!$this->restClient) {
            $sm = $this->getServiceLocator();
            $this->restClient = $sm->get(HttpRestJsonClient::class);
        }

        return $this->restClient;
    }

    /**
     * @return OpenAMClientInterface
     */
    protected function getOpenAmClient()
    {
        if (!$this->openAMClient) {
            $this->openAMClient = $this->getServiceLocator()->get(OpenAMClientInterface::class);
        }
        return $this->openAMClient;
    }

    protected function getForm($objectModel)
    {
        if (!$this->form) {
            $builder = new AnnotationBuilder();
            $this->form = $builder->createForm($objectModel);
        }

        return $this->form;
    }

    protected function addInfoMessages($messages)
    {
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $this->flashMessenger()->addInfoMessage($message);
            }
        } else {
            $this->flashMessenger()->addInfoMessage($messages);
        }
        return $this;
    }

    protected function addErrorMessages($messages)
    {
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $this->flashMessenger()->addErrorMessage($message);
            }
        } else {
            $this->flashMessenger()->addErrorMessage($messages);
        }
        return $this;
    }

    protected function addErrorMessage($message)
    {
        $this->flashMessenger()->addErrorMessage($message);
        return $this;
    }

    protected function addSuccessMessage($message)
    {
        $this->flashMessenger()->addSuccessMessage($message);
        return $this;
    }

    protected function getFlashErrorViewModel()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate(self::TEMPLATE_FLASH_ERROR);
        return $viewModel;
    }

    /**
     *
     * @param $errorData
     *
     * errorData should be returned from the DvsaCommon\HttpRestJson\Exception;->getExpandedData()
     *
     */
    protected function addFormErrorMessagesToSession($errorData)
    {
        $container = new Container(self::FORM_ERROR_CONTAINER_NAMESPACE);
        $container->offsetSet(self::FORM_ERROR_CONTAINER_KEY, $errorData);
    }

    /**
     * @return \Application\Service\CatalogService
     */
    public function getCatalogService()
    {
        return $this->getServiceLocator()->get('CatalogService');
    }

    /**
     * @param string $catalogName
     * @return array
     */
    public function getCatalogByName($catalogName)
    {
        return ArrayUtils::get($this->getCatalogService()->getData(), $catalogName);
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->getServiceLocator()->get('config');
    }
}
