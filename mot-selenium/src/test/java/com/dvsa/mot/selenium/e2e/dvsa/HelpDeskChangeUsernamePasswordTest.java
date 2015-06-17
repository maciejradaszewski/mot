package com.dvsa.mot.selenium.e2e.dvsa;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskRecoverUsernameSuccessPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskResetPasswordSuccessPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserResultsPage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpdeskUserSearchPage;

import org.testng.Assert;
import org.testng.annotations.Test;

import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BaseTest;


public class HelpDeskChangeUsernamePasswordTest extends BaseTest {

    /*Disabling this test as there is change in functionality. Will revert back once 7724 resolved*/
    /*@Test(enabled = false, groups = {"VM-4880", "VM-4881", "W-Sprint5", "E2E", "VM-7266","slice_A"})
    public void testHelpDeskResetPasswordAndRecoverUsernameSuccessfully() {
        Login user = createTester(Login.LOGIN_TESTER1);
        Person aed = Person.PAM_POOVEY;

        String userName = HelpdeskUserSearchPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE)
                .enterUsername(user.username)
                .search().getResultName(0);
        HelpdeskUserResultsPage helpdeskUserResultsPage = new HelpdeskUserResultsPage(driver);
        String getConfirmationText = helpdeskUserResultsPage.clickOnUserName(0)
                .clickResetPassword().getFirstTimeResetPasswordConfirmationText();

        Assert.assertEquals(getConfirmationText,"A letter will be sent to " + userName +
                " giving instructions on how to reset the password for their account.",
                "confirmation message for reset password unsuccessful");

        HelpdeskResetPasswordSuccessPage helpdeskResetPasswordSuccessPage =
                new HelpdeskResetPasswordSuccessPage(driver);
             helpdeskResetPasswordSuccessPage.
                     returnToUserProfilePage()
                .backToSearchResults().backToUserSearch()
                .enterFirstName(aed.getName())
                .search().getResultName(0);
        String userFullName = helpdeskUserResultsPage.clickOnUserName(0)
                .clickRecoverUsername().getRecoverUsernameConfirmationText();
        HelpdeskRecoverUsernameSuccessPage helpdeskRecoverUsernameSuccessPage =
                new HelpdeskRecoverUsernameSuccessPage(driver);
        helpdeskRecoverUsernameSuccessPage.returnToHome();
        Assert.assertEquals
                (userFullName,"A letter will be sent to " + aed.getFullName() + " containing the username for their MOT account.",
                        "confirmation message for recover username unsuccessful");
    }*/
}
