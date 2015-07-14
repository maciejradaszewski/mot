package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.api.request.CreateAeRequest;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;

public class AeService extends Service {

    private static final String CREATE_AE_PATH = "/testsupport/ae";
    private AuthService authService = new AuthService();
    private User areaOfficeUser = new User("areaoffice1user", "Password1");

    protected AeService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    protected AeDetails createAe(String namePrefix) throws IOException{
       return createAe(namePrefix, areaOfficeUser, 0);
    }

    protected AeDetails createAe(String namePrefix, int slots) throws IOException{
       return createAe(namePrefix, areaOfficeUser, slots);
    }

    protected AeDetails createAe(String namePrefix, User user, int slots) throws IOException{
        String request =
                jsonHandler.convertToString(new CreateAeRequest(namePrefix, user, slots));

        String token = authService.createSessionTokenForUser(areaOfficeUser);
        Response response = motClient.createAe(request, CREATE_AE_PATH, token);

        return jsonHandler.hydrateObject(
                jsonHandler.convertToString(response.body().path("data")), AeDetails.class);
    }
}
