package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EventHistoryPage;
import org.testng.Assert;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

public class EventHistoryTests extends BaseTest {

    @DataProvider(name = "EventHistoryLoginProvider")
    public Object[][] EventHistoryLoginProvider() {
        return new Object[][]{{Login.LOGIN_AREA_OFFICE1}, {Login.LOGIN_ENFTESTER}};
    }

    @Test(groups = {"VM-5153", "VM-5154", "Sprint09", "V", "Test 01", "current", "slice_A"},
            dataProvider = "EventHistoryLoginProvider")

    public void viewEventHistory(Login login) {
        SearchForAePage searchForAePage = SearchForAePage.navigateHereFromLoginPage(driver, login);
        searchForAePage.searchForAeAndSubmit("AE1438").clickAeEventsHistoryLink().verifyEventDetailsTableIsDisplayed();
        EventHistoryPage eventHistoryPage = new EventHistoryPage(driver);
        Assert.assertTrue(eventHistoryPage.getTypeColumnHeader().equals("Type"));
        Assert.assertTrue(eventHistoryPage.getDateColumnHeader().equals("Date"));
        Assert.assertTrue(eventHistoryPage.getDescColumnHeader().equals("Description"));
        eventHistoryPage.viewEventDetails().clickGoBackLink();
    }
}
