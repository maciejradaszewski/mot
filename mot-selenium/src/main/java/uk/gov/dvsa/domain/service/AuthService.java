package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;

public class AuthService extends Service {
    private static final String CREATE_SESSION_ENDPOINT = "/session";

    protected AuthService() {
        super(WebDriverConfigurator.apiUrl());
    }

    protected String createSessionTokenForUser(User user) throws IOException {
        return createSessionTokenForUser(user.getUsername(), user.getPassword());
    }

    protected String getDvsaTokenForAuthRequest() throws IOException {
       return createSessionTokenForUser("areaoffice1user", "Password1");
    }

    protected String createSessionTokenForUser(String username, String password) throws IOException {
        String request = jsonHandler.convertToString(new User(username, password));

        Response response = motClient.createSession(request, CREATE_SESSION_ENDPOINT);

        return response.path("data.accessToken");
    }
}

