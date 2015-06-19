package com.dvsa.mot.selenium.priv.frontend.organisation.management;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.FindAUserPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class AuthorisedExaminerOverviewTest extends BaseTest {

    @Test(groups = {"VM-2218", "VM-2226", "VM-2368", "slice_A", "VM-2223", "VM-2296"})
    public void testAllAEDetailsDisplayedOnThePage() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe(RandomStringUtils.randomAlphabetic(6));
        createVTS(aeDetails.getId(), null, Login.LOGIN_AREA_OFFICE1,
                "testAllAEDetailsDisplayedOnThePage");
        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin);

        //testAuthorisedExaminerAddress
        assertThat("address present",
                authorisedExaminerOverviewPage.isAuthorisedExaminerDetailsPresent(), is(true));

        //testAuthorisedExaminerVTSDetails
        assertThat("VTS details present", authorisedExaminerOverviewPage
                .isAuthorisedExaminerVehicleTestingStationDetailsPresent(), is(true));

        //testViewSlotsBalance
        assertThat("View Available slots",
                authorisedExaminerOverviewPage.isSlotsZeroOrMoreAvailable(), is(true));

        //testAssignARoleToAEAndVerifyThatLinkExists
        assertThat("AE management Role exists",
                authorisedExaminerOverviewPage.verifyAeManagementRolesExists(), is(true));


        //testToCheckInvalidUserCannotBeAssignedARole
        FindAUserPage findAUserPage = authorisedExaminerOverviewPage.clickAssignRole()
                .enterUsername(Login.LOGIN_INVALID_USERNAME.username).searchExpectingError();
        Assert.assertEquals(findAUserPage.getInvalidUserMessage(),
                Assertion.ASSERTION_NO_USER_EXISTS.assertion + Login.LOGIN_INVALID_USERNAME.username
                        + ".");
    }

}    
