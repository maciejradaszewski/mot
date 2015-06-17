package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementHomePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.VtsNumberEntryPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class EnforcementHomePageTests extends BaseTest {
    @Test(description = "Verify all MOT links on UserDashboard page.", groups = {"VM-2191",
            "Sprint20", "Enf", "Test 01", "short", "slice_A"}) public void testMOTlinks() {
        VtsNumberEntryPage enforcementAdvanceSearchPage =
                new LoginPage(driver).loginAsEnforcementUser(Login.LOGIN_ENFTESTER)
                        .goToVtsNumberEntryPage();
        Assert.assertTrue(driver.getCurrentUrl().matches(baseUrl() + "/mot-test-search"),
                "Assert page URL.");

        enforcementAdvanceSearchPage.clickHomeExpectingEnforcementHomePage()
                .clickDetailedSiteInfo();
        Assert.assertTrue(driver.getCurrentUrl()
                .matches(baseUrl() + "/vehicle-testing-station/search"),
                "Assert page URL.");

        enforcementAdvanceSearchPage.clickHomeExpectingEnforcementHomePage().clickListAllAEs();
        Assert.assertTrue(driver.getCurrentUrl().matches(baseUrl() + "/authorised-examiner/search"),
                "Assert page URL.");

        enforcementAdvanceSearchPage.clickHomeExpectingEnforcementHomePage()
                .clickVehicleInformation();
        Assert.assertTrue(driver.getCurrentUrl().matches(baseUrl() + "/vehicle/search"),
                "Assert page URL.");


        //clickListContingencyTests()
        enforcementAdvanceSearchPage.clickLogout();

    }

    @Test(description = "Verify  MOT links on UserDashboard page.", groups = {"VM-4825", "Sprint05",
            "V", "Test 01", "short", "slice_A"}) public void testcontingencylink() {

        EnforcementHomePage homePage =
                EnforcementHomePage.navigateHereFromLoginPage(driver, Login.LOGIN_TESTER1);
        homePage.clickListContingencyTests();
        Assert.assertTrue(driver.getCurrentUrl().matches(baseUrl() + "/contingency"),
                "Assert page URL.");
    }
}
