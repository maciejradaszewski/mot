package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class PersonalProfileTest extends BaseTest {

    @Test(groups = {"VM-3338", "bug-fix", "VM-4471", "slice_A"})
    public void testEditPersonProfileEmailDetails() {

        String emailAddress = RandomDataGenerator.generateEmail(20, System.nanoTime());

        UserPersonalProfilePage userPersonalProfilePage =
                EditPersonalProfilePage.navigateHereFromLoginPage(driver, login)
                        .setEmail(emailAddress).setEmailConfirmation(emailAddress)
                        .clickUpdateProfile();

        Assert.assertEquals(userPersonalProfilePage.getEmail(), emailAddress,
                "Email should be updated");
    }

    @Test(groups = {"VM-3338", "bug-fix", "VM-4471", "slice_A"})
    public void testEditPersonalProfileDetailsWithValidationError() {

        String emailAddress = RandomDataGenerator.generateEmail(20, System.nanoTime());
        String confirmationEmailAddress = RandomDataGenerator.generateEmail(21, System.nanoTime());

        EditPersonalProfilePage editPersonalProfilePage =
                EditPersonalProfilePage.navigateHereFromLoginPage(driver, login)
                        .setEmail(emailAddress).setEmailConfirmation(confirmationEmailAddress)
                        .clickUpdateProfileExpectingError();

        assertThat("error not displayed",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
        Assert.assertEquals(editPersonalProfilePage.getEmail(), emailAddress,
                "email not preserved");
        Assert.assertEquals(editPersonalProfilePage.getEmailConfirmation(),
                confirmationEmailAddress, "email confirmation not preserved");
    }

    @Test(groups = {"slice_A", "VM-5075"}) public void testResettingTesterSecurityPin() {
        Person claimsTester = createTesterAsPerson(Collections.singletonList(1), true);
        OpenAMClaimAccountSignInPage openAMClaimAccountSignInPage =
                new LoginPage(driver).navigateToClaimAccountPage(driver, claimsTester.login);

        openAMClaimAccountSignInPage.submitEmailSuccessfully(claimsTester)
                .submitPasswordSuccessfully(Text.TEXT_PASSWORD_2).clickOnSubmitButton()
                .submitSecurityQuestionAndAnswersSuccessfully().clickSaveAndContinue()
                .clickLogout();

        Login newLogin = new Login(claimsTester.login.username, Text.TEXT_PASSWORD_2);

        SecurityPinReissuedPage securityPinReissuedPage =
                SecurityPinReissuedPage.navigateHereFromLoginPage(driver, newLogin);
        assertThat("The new security pin is displayed correctly",
                securityPinReissuedPage.getNewPin().length(), is(6));
    }
}
