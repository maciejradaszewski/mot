package com.dvsa.mot.selenium.priv.frontend.Permissions;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import org.apache.commons.lang3.RandomStringUtils;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.Vehicle;
import uk.gov.dvsa.domain.service.ServiceLocator;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.PermissionPage;
import uk.gov.dvsa.ui.pages.TestResultsEntryPage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class UserPermissionTest extends BaseTest {

    @DataProvider(name = "testdata") public Object[][] creatTestData()
            throws URISyntaxException, IOException {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe(RandomStringUtils.randomAlphabetic(6));
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        RandomStringUtils.randomAlphabetic(6));
        User testerLogin = TestDataHelper.createTester(site.getId());
        User aedmLogin = ServiceLocator.getUserService()
                .createUserAsAedm(aeDetails.getId(), RandomStringUtils.randomAlphabetic(6), false);
        Vehicle vehicle = TestDataHelper.getNewVehicle();

        return new Object[][] {{testerLogin, aedmLogin, vehicle}};
    }


    @Test(groups = {"Regression", "VM-7223"}, dataProvider = "testdata")
    public void testUserCanNavigateAwayFromPermissionsPage(User testerLogin, User aedmLogin,
            Vehicle vehicle) throws URISyntaxException, IOException {


        TestResultsEntryPage testResultsEntryPage =
                pageNavigator().gotoTestResultsEntryPage(testerLogin, vehicle);
        String url = testResultsEntryPage.returnCurrentUrl();
        PermissionPage permissionPage = pageNavigator().gotoPermissionPage(aedmLogin, url);
        HomePage homePage = permissionPage.navigateToHomePage();

        assertThat(homePage.getTitle(), is("MOT modernisation\n" + "Your home"));

    }

}
