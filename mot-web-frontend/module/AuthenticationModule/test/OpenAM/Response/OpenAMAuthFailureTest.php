<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModuleTest\OpenAM\Response;

use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailure;
use Zend\View\Model\ViewModel;

class OpenAMAuthFailureTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCode()
    {
        $code = 1;
        $viewModel = new ViewModel();

        $authFailure = new OpenAMAuthFailure(1, $viewModel);
        $this->assertEquals($code, $authFailure->getCode());
    }

    public function testGetViewModel()
    {
        $viewModel = (new ViewModel(['a' => 'b']))->setTemplate('template');

        $authFailure = new OpenAMAuthFailure(1, $viewModel);
        $this->assertEquals($viewModel, $authFailure->getViewModel());
    }
}
