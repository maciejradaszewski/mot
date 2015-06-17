<?php
namespace DvsaCommonTest\Constants;

use DvsaCommon\Constants\BaseEnumeration;

/**
 * A mock for sample enum. It's not possible to add constants to mocks in PHP unit
 */
class MockEnum extends BaseEnumeration
{
    const A = 'A';
    const B = 'B';
    const C = 'C';
}
