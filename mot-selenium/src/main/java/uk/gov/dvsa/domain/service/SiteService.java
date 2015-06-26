package uk.gov.dvsa.domain.service;

import com.jayway.restassured.response.Response;
import uk.gov.dvsa.domain.api.request.CreateSiteRequest;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.WebDriverConfigurator;

import java.io.IOException;

public class SiteService extends BaseService{
    private static final String CREATE_PATH = "/testsupport/vts";
    User areaOfficer = new User("areaoffice1user", "Password1");

    public SiteService() {
        super(WebDriverConfigurator.testSupportUrl());
    }

    public Site createSite(int aeId, String siteName) throws IOException {
        String request = jsonHandler.convertToString(new CreateSiteRequest(aeId, areaOfficer, siteName));

        Response response = motClient.createSite(request, CREATE_PATH);

        return jsonHandler.hydrateObject(
                jsonHandler.convertToString(response.body().path("data")), Site.class);
    }
}
