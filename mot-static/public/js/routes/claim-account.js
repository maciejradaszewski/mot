var app_service_name = '[Service Name]';

app.get('/claim-account', function (req, res) {
    renderPage('claim-account', {
        page_title: 'Example - Claim account'
        , task_title: 'Sign in'
        , task_step: 'Claim account'
        , next_action: '/claim-account-questions'
        , task_flow: 'Step 1 of 3'
        , service_name: app_service_name
    }, res);
});

app.get('/claim-account-questions', function (req, res) {
    renderPage('claim-account-questions', {
        page_title: 'Example - Claim account'
        , task_title: 'Sign in'
        , task_step: 'Confirm your details'
        , next_action: '/claim-account-pin'
        , back_action: '/claim-account'
        , task_flow: 'Step 2 of 3'
        , service_name: app_service_name
    }, res);
});

app.get('/claim-account-pin', function (req, res) {
    renderPage('claim-account-pin', {
        page_title: 'Example - Claim account'
        , task_title: 'Sign in'
        , task_step: 'Confirm your details'
        , next_action: '/example-user-home'
        , back_action: '/claim-account-questions'
        , task_flow: 'Step 3 of 3'
        , service_name: app_service_name
    }, res);
});
