package com.dvsa.mot.selenium.priv.frontend.feedback;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementVTSSearchPage;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;


public class FeedbackLinkTests extends BaseTest {

    private String feedbackLink = "mailto:mot.modernisation@vosa.gsi.gov.uk?subject=MOT%20testing%20service%20feedback";

    @Test(groups = {"VM-7201", "slice_A", "W-Sprint14"},
            description = "As a AEDM user I verify if the Dashboard site feedback link contains correct link, then I navigate to Site Details page and verify the feedback link there")
    public void testDashboardAndVTSFeedbackLink() {

        String vtsName = "ArtGarageNew";
        String aeName = "ATRWheelsNew";
        int aeId = createAE(aeName);
        int vtsId = createVTS(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1, vtsName);
        Login aedm = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, false);
        UserDashboardPage userDashboardPage = LoginPage.loginAs(driver, aedm);
        Assert.assertEquals(feedbackLink, userDashboardPage.getFeedbackLink());
        SiteDetailsPage siteDetailsPage = userDashboardPage.clickOnSiteLink(vtsName);
        Assert.assertEquals(feedbackLink, siteDetailsPage.getFeedbackLink());
    }

    @Test(groups = {"VM-7201", "slice_A", "W-Sprint14"},
            description = "As a ENFTESTER user I verify if the MOT Search site feedback link contains correct link")
    public void testMOTSearchFeedbackLink() {
        //EnforcementHomePage.navigateHereFromLoginPage(driver, createVE()).clickMOTLink()
        EnforcementVTSSearchPage enforcementVTSSearchPage = new EnforcementVTSSearchPage(driver);
        enforcementVTSSearchPage.navigateHereFromLoginPage(driver, createVE());
        Assert.assertEquals(feedbackLink, enforcementVTSSearchPage.getFeedbackLink());
    }

}
