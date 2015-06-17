<?php

namespace Application\Listener;

final class WebListenerEventsPriorities
{
    const DISPATCH_CHANGE_TEMP_PASSWORD = 1;
    const DISPATCH_CLAIM_ACCOUNT = 2;
    const DISPATCH_ERROR_HANDLE_ERROR = 0;
    const ROUTE_STOP_PROPAGATION = -10000;
}
