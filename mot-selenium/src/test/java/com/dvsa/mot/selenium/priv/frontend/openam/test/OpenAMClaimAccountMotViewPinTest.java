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
import org.testng.Assert;
import org.testng.annotations.Test;

/**
 * Created by jotsnan on 18/12/2014.
 */
public class OpenAMClaimAccountMotViewPinTest extends BaseTest {

    @Test(groups = {"Regression", "VM-4716"}) public void testTesterViewPinPageWording() {

        Login login = createTester(true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, login);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_1, "loginIntoOpenAMClaimAccount"))
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2).clickOnSubmitButton()
                .setSecurityQuestionAndAnswersSuccessfully().clickOnSubmitButton();
        OpenAMClaimAccountMotTestPinPage openAMClaimAccountMotTestPinPage =
                new OpenAMClaimAccountMotTestPinPage(driver);
        Assert.assertEquals(openAMClaimAccountMotTestPinPage.getCommonPinLeadText(),
                Assertion.ASSERTION_COMMON_VIEW_PIN_WORDING.assertion, "common message displayed");
        Assert.assertEquals(openAMClaimAccountMotTestPinPage.getTestersOnlyPinText(),
                Assertion.ASSERTION_TESTER_VIEW_PIN_WORDING.assertion, "tester message displayed");
        Assert.assertEquals(openAMClaimAccountMotTestPinPage.getWornPinText(),
                Assertion.ASSERTION_WORN_PIN_WORDING.assertion, "worn pin message displayed");
        openAMClaimAccountMotTestPinPage.clickSaveAndContinue();
    }

    @Test(groups = {"Regression", "VM-4716"}) public void testNonTesterViewPinPageWording() {

        int aeId = EntityManager.createAe(1000);

        Login aedmLogin = createAEDM(aeId, Login.LOGIN_AREA_OFFICE2, true);

        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, aedmLogin);
        openAMClaimAccountSignInPage.submitEmailSuccessfully(
                Person.getUnique(Person.PERSON_2, "loginIntoOpenAMClaimAccount"))
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2)
                .clickOnSubmitButton().setSecurityQuestionAndAnswersSuccessfully()
                .clickOnSubmitButton();
        OpenAMClaimAccountMotTestPinPage openAMClaimAccountMotTestPinPage =
                new OpenAMClaimAccountMotTestPinPage(driver);
        Assert.assertEquals(openAMClaimAccountMotTestPinPage.getCommonPinLeadText(),
                Assertion.ASSERTION_COMMON_VIEW_PIN_WORDING.assertion, "common message displayed");
        Assert.assertFalse(openAMClaimAccountMotTestPinPage.isTesterMessageDisplayed(),
                "testers message not displayed");
        Assert.assertEquals(openAMClaimAccountMotTestPinPage.getWornPinText(),
                Assertion.ASSERTION_WORN_PIN_WORDING.assertion, "worn pin message displayed");
        String getClaimPinNum = openAMClaimAccountMotTestPinPage.getClaimAccountPinNumber();
        openAMClaimAccountMotTestPinPage.goToSecurityQuestionsPage()
                .changeSecurityQuestionAndAnswers().clickOnSubmitButton();
        Assert.assertTrue(
                openAMClaimAccountMotTestPinPage.isSamePinNumberDisplayed(getClaimPinNum));
        openAMClaimAccountMotTestPinPage.clickSaveAndContinue();
    }
}
