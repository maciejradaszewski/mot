package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.api.request.CreateAedmRequest;
import uk.gov.dvsa.domain.api.request.CreateCscoRequest;
import uk.gov.dvsa.domain.api.request.CreateTesterRequest;
import uk.gov.dvsa.domain.api.request.CreateVehicleExaminerRequest;
import uk.gov.dvsa.domain.model.TestGroup;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

public class UserService extends BaseService{
    private static final String CREATE_TESTER_PATH = "/testsupport/tester";
    private static final String CREATE_AEDM_PATH = "/testsupport/aedm";
    private static final String CREATE_CSCO_PATH = "/testsupport/csco";
    private static final String CREATE_VEHICLE_EXAMINER_PATH = "/testsupport/vehicleexaminer";
    private AuthService authService = new AuthService();

    public UserService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    public User createUserAsTester(int siteId) throws IOException {
        return createUserAsTester(siteId, false);
    }

    public User createUserAsTester(int siteId, boolean accountClaimRequired) throws IOException {
        List<Integer> siteIdList = new ArrayList<>();
        siteIdList.add(siteId);

        return createUserAsTester(siteIdList, accountClaimRequired, null);
    }

    public User createUserAsTester(List<Integer> siteIdList,
                                   boolean accountClaimRequired, TestGroup testGroup) throws IOException {
        String userRequest =
                jsonHandler.convertToString(new CreateTesterRequest(siteIdList, accountClaimRequired, testGroup));

        Response response = motClient.createUser
                (userRequest, CREATE_TESTER_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }

    public User createUserAsAedm(int aeId,String namePrefix, boolean accountClaimRequired) throws IOException {
        List<Integer> aeIdList = new ArrayList<>();
        aeIdList.add(aeId);

        String aedmRequest = jsonHandler.convertToString(new CreateAedmRequest(aeIdList, accountClaimRequired));
        Response response = motClient.createUser(aedmRequest, CREATE_AEDM_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }

    public User createUserAsCsco(boolean claimAccount) throws IOException {
        String cscoRequest = jsonHandler.convertToString(new CreateCscoRequest(claimAccount));
        Response response = motClient.createUser(cscoRequest, CREATE_CSCO_PATH);

        return userResponse(response);
    }

    public User createUserAsVE(String namePrefix, boolean accountClaimRequired) throws IOException {
        String vehicleExaminerRequest = jsonHandler.convertToString(new CreateVehicleExaminerRequest(namePrefix, accountClaimRequired));
        Response response = motClient.createUser(vehicleExaminerRequest, CREATE_VEHICLE_EXAMINER_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }

    private User userResponse(Response response) throws IOException {
        return jsonHandler.hydrateObject(
                jsonHandler.convertToString(response.body().path("data")), User.class);
    }
}
