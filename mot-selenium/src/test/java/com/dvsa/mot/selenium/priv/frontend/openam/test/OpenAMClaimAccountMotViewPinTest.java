package com.dvsa.mot.selenium.priv.frontend.openam.test;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.e2e.support.EntityManager;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountMotTestPinPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

/**
 * Created by jotsnan on 18/12/2014.
 */
public class OpenAMClaimAccountMotViewPinTest extends BaseTest {

    @Test(groups = {"Regression", "VM-4716"}) public void testTesterViewPinPageWording() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver)
                        .navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage
                .submitEmailSuccessfully(Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount"))
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2)
                .clickOnSubmitButton()
                .submitSecurityQuestionAndAnswersSuccessfully()
                .clickClaimYourAccoutButton();
        OpenAMClaimAccountMotTestPinPage openAMClaimAccountMotTestPinPage =
                new OpenAMClaimAccountMotTestPinPage(driver);
        verifyPageContent(openAMClaimAccountMotTestPinPage);
    }

    @Test(groups = {"Regression", "VM-4716"}) public void testNonTesterViewPinPageWording() {

        int aeId = EntityManager.createAe(1000);
        Login aedmLogin = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver)
                        .navigateToClaimAccountPage(driver, aedmLogin);
        openAMClaimAccountSignInPage
                .submitEmailSuccessfully(Person.getUnique(Person.PERSON_2, "loginIntoOpenAMClaimAccount"))
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2)
                .clickOnSubmitButton()
                .submitSecurityQuestionAndAnswersSuccessfully()
                .clickClaimYourAccoutButton();
        OpenAMClaimAccountMotTestPinPage openAMClaimAccountMotTestPinPage =
                new OpenAMClaimAccountMotTestPinPage(driver);
        verifyPageContent(openAMClaimAccountMotTestPinPage);
}

    private void verifyPageContent(OpenAMClaimAccountMotTestPinPage page) {
        assertThat(page.getLeadHeadingText(), is(Assertion.ASSERTION_CLAIM_CONFIRMATION_MSG.assertion));
        assertThat(page.getPinHeadingText(), is(Assertion.ASSERTION_PIN_HEADING_MSG.assertion));
        assertThat(page.isPinNumberDisplayed(), is(true));
        assertThat(page.getPageContentText().contains(Assertion.ASSERTION_MESSAGE_HEADING_WHAT_NEXT.assertion), is(true));
        assertThat(page.getPageContentText().contains(Assertion.ASSERTION_MESSAGE_PROVIDED_DETAILS.assertion), is(true));
        assertThat(page.getPageContentText().contains(Assertion.ASSERTION_MESSAGE_MEMORISE_YOUR_PIN.assertion), is(true));
        assertThat(page.getPageContentText().contains(Assertion.ASSERTION_MESSAGE_RESET_PIN.assertion), is(true));
    }
}
