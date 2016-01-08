package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.api.request.*;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.TestGroup;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

public class UserService extends Service {
    private static final String CREATE_TESTER_PATH = "/testsupport/tester";
    private static final String CREATE_DVLA_OFFICER_PATH = "/testsupport/dvlaoperative";
    private static final String CREATE_AEDM_PATH = "/testsupport/aedm";
    private static final String CREATE_CSCO_PATH = "/testsupport/csco";
    private static final String CREATE_MANAGER_PATH = "/testsupport/vts/sm";
    private static final String CREATE_ADMIN_PATH = "/testsupport/vts/sa";
    private static final String CREATE_AREA_OFFICE_1_PATH = "/testsupport/areaoffice1";
    private static final String CREATE_AREA_OFFICE_2_PATH = "/testsupport/areaoffice2";
    private static final String CREATE_VEHICLE_EXAMINER_PATH = "/testsupport/vehicleexaminer";
    private static final String CREATE_FINANCE_USER_PATH = "/testsupport/financeuser";
    private static final String CREATE_SCHEME_USER = "/testsupport/schemeuser";
    private AuthService authService = new AuthService();

    protected UserService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    protected User createUserAsTester(int siteId) throws IOException {
        return createUserAsTester(siteId, false);
    }

    protected User createUserAsTester(int siteId, boolean accountClaimRequired) throws IOException {
        List<Integer> siteIdList = new ArrayList<>();
        siteIdList.add(siteId);

        return createUserAsTester(siteIdList, accountClaimRequired, null);
    }

    protected User createUserAsTester(List<Integer> siteIdList,
                                   boolean accountClaimRequired, TestGroup testGroup) throws IOException {
        String userRequest =
                jsonHandler.convertToString(new CreateTesterRequest(siteIdList, accountClaimRequired, testGroup));

        Response response = motClient.createUser
                (userRequest, CREATE_TESTER_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }

    protected User createUserAsAedm(int aeId, String namePrefix, boolean accountClaimRequired) throws IOException {
        List<Integer> aeIdList = new ArrayList<>();
        aeIdList.add(aeId);

        String aedmRequest = jsonHandler.convertToString(
                new CreateAedmRequest(aeIdList, namePrefix, accountClaimRequired)
        );
        Response response = motClient.createUser(aedmRequest, CREATE_AEDM_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }

    public User createUserAsAreaOfficeOneUser(String namePrefix) throws IOException {
        String aoRequest = jsonHandler.convertToString(new CreateAreaOfficeOneRequest(namePrefix, false));
        Response response = motClient.createUser(aoRequest, CREATE_AREA_OFFICE_1_PATH);

        return userResponse(response);
    }

    public User createUserAsAreaOfficeTwo(String namePrefix) throws IOException {
        String aoRequest = jsonHandler.convertToString(new CreateAreaOfficeTwoRequest(namePrefix, false));
        Response response = motClient.createUser(aoRequest, CREATE_AREA_OFFICE_2_PATH);

        return userResponse(response);
    }

    protected User createUserAsSiteManager(int siteId, boolean accountClaimRequired) throws IOException {
        List<Integer> siteIdList = new ArrayList<>();
        siteIdList.add(siteId);

        return createUserAsSiteManager(siteIdList, accountClaimRequired, null);
    }

    protected User createUserAsSiteManager(List<Integer> siteIdList,
                                     boolean accountClaimRequired, TestGroup testGroup) throws IOException {
        String userRequest =
                jsonHandler.convertToString(new CreateSiteManagerRequest(siteIdList, accountClaimRequired, testGroup));

        Response response = motClient.createUser
                (userRequest, CREATE_MANAGER_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }

    protected User createUserAsSiteAdmin(int siteId, boolean accountClaimRequired) throws IOException {
        List<Integer> siteIdList = new ArrayList<>();
        siteIdList.add(siteId);

        return createUserAsSiteAdmin(siteIdList, accountClaimRequired, null);
    }

    protected User createUserAsSiteAdmin(List<Integer> siteIdList,
                                      boolean accountClaimRequired, TestGroup testGroup) throws IOException {
        String userRequest =
                jsonHandler.convertToString(new CreateSiteAdminRequest(siteIdList, accountClaimRequired, testGroup));

        Response response = motClient.createUser
                (userRequest, CREATE_ADMIN_PATH, authService.getDvsaTokenForAuthRequest());

        return userResponse(response);
    }


    protected User createUserAsCsco(boolean claimAccount) throws IOException {
        String cscoRequest = jsonHandler.convertToString(new CreateCscoRequest(claimAccount));
        Response response = motClient.createUser(cscoRequest, CREATE_CSCO_PATH);

        return userResponse(response);
    }

    protected User createUserAsVE(String namePrefix, boolean accountClaimRequired) throws IOException {
        String vehicleExaminerRequest = jsonHandler.convertToString(
                new CreateVehicleExaminerRequest(namePrefix, accountClaimRequired)
        );
        Response response = motClient.createUser(vehicleExaminerRequest, CREATE_VEHICLE_EXAMINER_PATH, authService.getDvsaTokenForAuthRequest());
        return userResponse(response);
    }

    public User createAFinanceUser(String namePrefix, boolean accountClaimRequired) throws IOException {
        String createFianceUserRequest = jsonHandler.convertToString(new CreateFinanceUserRequest(namePrefix, accountClaimRequired));
        Response response = motClient.createUser(createFianceUserRequest, CREATE_FINANCE_USER_PATH, authService.getDvsaTokenForAuthRequest());
        return userResponse(response);
    }

    protected User createUserAsSchemeUser(boolean accountClaimRequired) throws IOException {
        String schemeUserRequest = jsonHandler.convertToString(new CreateSchemeUserRequest(accountClaimRequired));
        return userResponse(motClient.createUser(schemeUserRequest, CREATE_SCHEME_USER));
    }

    protected User createDvlaOfficerUser(String diff) throws IOException {
        String request = jsonHandler.convertToString(new CreateDvlaOfficerRequest(diff));
        return userResponse(motClient.createUser(request, CREATE_DVLA_OFFICER_PATH));
    }

    private User userResponse(Response response) throws IOException {
        if(response.statusCode() != 200){
            throw new IllegalStateException("Server error: could not create user");
        }

        return jsonHandler.hydrateObject(
                jsonHandler.convertToString(response.body().path("data")), User.class);
    }
}
