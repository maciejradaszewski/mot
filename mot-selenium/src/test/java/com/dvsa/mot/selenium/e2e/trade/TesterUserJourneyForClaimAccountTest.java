package com.dvsa.mot.selenium.e2e.trade;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EventDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class TesterUserJourneyForClaimAccountTest extends BaseTest {

    @Test(groups = {"Regression", "VM-2335", "E2E"})
    public void testConfirmOpenAMClaimAccountSuccessfully() {

        Person claimsTester = createTesterAsPerson(Collections.singletonList(1), true);
        Login claimsTesterLogin = claimsTester.getLogin();
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, claimsTester.login);

        UserDashboardPage userDashboardPage =
                openAMClaimAccountSignInPage.submitEmailSuccessfully(claimsTester)
                        .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2)
                        .clickOnSubmitButton().submitSecurityQuestionAndAnswersSuccessfully()
                        .clickSaveAndContinue();

        assertThat("Check that the start MOT test button is displayed",
                userDashboardPage.isStartMotTestDisplayed(), is(true));

        userDashboardPage.clickOnSiteLink(Site.POPULAR_GARAGES).clickFooterManualAndGuidesLink()
                .clickLogout();
        EventDetailsPage eventDetailsPage =
                userDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_ENFTESTER)
                        .clickUserSearch().enterUsername(claimsTesterLogin.username).search()
                        .clickUserName(0).clickEventHistoryLink()
                        .clickUserClaimsAccount(claimsTesterLogin.username,
                                claimsTester.getNamesAndSurname());

        assertThat("Assert that the event details page is displayed",
                eventDetailsPage.getEventType(),
                is(Assertion.ASSERTION_USER_CLAIMS_ACCOUNT.assertion));
        assertThat("Assert that the event details page is displayed",
                eventDetailsPage.getDescription(),
                is(Assertion.ASSERTION_ACCOUNT_CLAIMED_BY.assertion + claimsTester.login.username));

        eventDetailsPage.clickLogout();

        eventDetailsPage =
                userDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1)
                        .clickUserSearch().enterUsername(claimsTester.login.username).search()
                        .clickUserName(0).clickEventHistoryLink()
                        .clickUserClaimsAccount(claimsTesterLogin.username,
                                claimsTester.getNamesAndSurname());

        assertThat("Assert that the event details page is displayed",
                eventDetailsPage.getEventType(),
                is(Assertion.ASSERTION_USER_CLAIMS_ACCOUNT.assertion));
        assertThat("Assert that the event details page is displayed",
                eventDetailsPage.getDescription(),
                is(Assertion.ASSERTION_ACCOUNT_CLAIMED_BY.assertion + claimsTesterLogin.username));

        eventDetailsPage.clickLogout();

        eventDetailsPage =
                userDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                        .clickUserSearch().enterUsername(claimsTesterLogin.username).search()
                        .clickUserName(0).clickEventHistoryLink()
                        .clickUserClaimsAccount(claimsTesterLogin.username,
                                claimsTester.getNamesAndSurname());

        assertThat("Assert that the event details page is displayed",
                eventDetailsPage.getEventType(),
                is(Assertion.ASSERTION_USER_CLAIMS_ACCOUNT.assertion));
        assertThat("Assert that the event details page is displayed",
                eventDetailsPage.getDescription(),
                is(Assertion.ASSERTION_ACCOUNT_CLAIMED_BY.assertion + claimsTesterLogin.username));

        eventDetailsPage.clickLogout();
    }
}
