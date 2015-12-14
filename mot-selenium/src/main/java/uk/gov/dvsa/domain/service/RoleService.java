package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.framework.config.Configurator;

public class RoleService extends Service {

    private static final String PERSON_ROLE_RESOURCE_URL = "/testsupport/person/%d/role/%s";

    public RoleService() {
        super(Configurator.testSupportUrl());
    }

    public boolean addRole(int userId, String roleName){
        String resourceUrl = String.format(PERSON_ROLE_RESOURCE_URL, userId, roleName);

        Response response = motClient.addRoleToUser(resourceUrl);
        return response.body().path("data.success");
    }
}
