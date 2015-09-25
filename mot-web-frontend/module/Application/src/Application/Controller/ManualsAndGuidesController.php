<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Application\Controller;

use Core\Controller\AbstractAuthActionController;
use InvalidArgumentException;
use OutOfBoundsException;
use Zend\Http\PhpEnvironment\Request;

/**
 * Controller to render a page with a list of links to manuals, guides and standards.
 */
class ManualsAndGuidesController extends AbstractAuthActionController
{
    const ROUTE = 'manuals';
    const PAGE_TITLE_INDEX_ACTION = 'Manuals and guides';
    const PAGE_SUBTITLE_INDEX_ACTION = 'Resources';

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $this->layout()->setVariables([
            'pageTitle'    => self::PAGE_TITLE_INDEX_ACTION,
            'pageSubTitle' => self::PAGE_SUBTITLE_INDEX_ACTION,
        ]);

        return [
            'resourceLinks' => $this->processDocumentsConfig(),
        ];
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        $request = $this->getRequest();
        if ($request instanceof Request) {
            return $request->getBasePath();
        }

        return '/';
    }

    /**
     * @return array
     */
    private function processDocumentsConfig()
    {
        $documents = [];

        foreach (array_keys($this->config) as $k) {
            foreach (['name', 'url', 'help_text'] as $attr) {
                if (!isset($this->config[$k][$attr])) {
                    throw new OutOfBoundsException(sprintf('Attribute "%s" is missing in the documents configuration.',
                        $attr));
                }

                if (!is_string($this->config[$k][$attr])) {
                    throw new InvalidArgumentException(sprintf('Attribute "%s" in the documents configuration should be of string type, got "%s" instead',
                        $attr, gettype($this->config[$k][$attr])));
                }

                $documents[$k][$attr] = trim($this->config[$k][$attr]);
            }

            // Prepend base path if the URL provided is relative. We flag the URL as relative if it starts with '/'.
            if ($documents[$k]['url'][0] && $documents[$k]['url'][0] === '/') {
                $documents[$k]['url'] = $this->getBasePath() . ltrim($documents[$k]['url']);
            }
        }

        return $documents;
    }
}
