<?php
namespace Dvsa\Mot\Frontend\Test\HttpRestJson;

use Dvsa\Mot\Frontend\Template\FileTemplate;
use Zend\Http\Response;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Assert;

class FileTemplateTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateFileOk()
    {
        $fileTemplate = new FileTemplate();
        $template = $fileTemplate->generateTemplate("<% test1 %> is the best and <% test2 %> not", array('test1' => 'Tester', 'test2' => 'Lesser'));
        $this->assertEquals('Tester is the best and Lesser not', $template);
    }

    public function testGenerateFileNotExists()
    {
        $fileTemplate = new FileTemplate();
        $fileTemplate->generateTemplate(false, array('test1' => 'Tester', 'test2' => 'Lesser'));
    }
}