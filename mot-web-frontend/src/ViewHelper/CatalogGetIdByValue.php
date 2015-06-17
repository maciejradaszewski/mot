<?php
namespace Dvsa\Mot\Frontend\ViewHelper;


use Zend\View\Helper\AbstractHelper;

class CatalogGetIdByValue extends AbstractHelper
{
    public function __invoke($array, $value, $index = 'name') {
        $result = current(array_filter($array, function ($item) use ($value, $index) {
                return $item[$index] === $value;
            }));
        return $result['id'];
    }
}
