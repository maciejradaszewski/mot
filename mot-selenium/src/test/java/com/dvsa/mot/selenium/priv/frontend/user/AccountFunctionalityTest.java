package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.AuthService;
import com.dvsa.mot.selenium.framework.util.helper.AccessResetPassWordLink;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.testng.Assert;
import org.testng.annotations.Test;

public class AccountFunctionalityTest extends BaseTest {

    AuthService authService = new AuthService();


    @Test(groups = {"VM-2111", "slice_A"}, description =
            "Verify reset password functionality by answering "
                    + "two security questions correctly")

    public void testResetPasswordSuccessfully() {
        Person tester = Person.BOB_THOMAS;
        UserNamePage userNamePage = LoginPage.forgottenPassWord(driver);
        Assert.assertEquals(userNamePage.getUserAccountMsg(),
                Assertion.ASSERTION_FORGOT_PASSWORD_USER_ACCOUNT_MSG.assertion,
                "Assert that message is displayed to user asking for user account");
        Assert.assertEquals(userNamePage.getUserAccount(),
                Assertion.ASSERTION_FORGOT_PASSWORD_USER_ACCOUNT_REQUIRED.assertion,
                "Assert that user account required message is displayed");

        userNamePage.submitValidUserName(tester.login.username);

        ForgotPwdSecurityQuesOnePage forgotPwdSecurityQuesOnePage =
                new ForgotPwdSecurityQuesOnePage(driver);
        Assert.assertEquals(forgotPwdSecurityQuesOnePage.getBeforePwdChangeMsg(),
                Assertion.ASSERTION_BEFORE_PASSWORD_CHANGE_MSG.assertion,
                "Assert that the message is displayed to user asking security questions");
        Assert.assertEquals(forgotPwdSecurityQuesOnePage.getLegendQuestion(),
                Assertion.ASSERTION_SECURITY_QUESTION_ONE.assertion,
                "Assert that the security question One is displayed");
        Assert.assertEquals(forgotPwdSecurityQuesOnePage.getValidationSummaryMessage(),
                Assertion.ASSERTION_BOTH_SECURITY_QUESTIONS_MSG.assertion,
                "Assert that both security questions must be answered message displayed");

        forgotPwdSecurityQuesOnePage.submitValidAnswer(tester.securityAnswer1);

        ForgotPwdSecurityQuesTwoPage forgotPwdSecurityQuesTwoPage =
                new ForgotPwdSecurityQuesTwoPage(driver);
        Assert.assertEquals(forgotPwdSecurityQuesTwoPage.getValidationMessageSuccess(),
                Assertion.ASSERTION_SECURITY_QUESTION_1_PASS.assertion,
                "Assert that success message for question1 is displayed");
        Assert.assertEquals(forgotPwdSecurityQuesTwoPage.getSecondQuestionMessage(),
                Assertion.ASSERTION_MSG_BEFORE_ANSWERING_SECOND_QUESTION.assertion,
                "Assert that the message is displayed to user asking to answer the second question");
        Assert.assertEquals(forgotPwdSecurityQuesTwoPage.getValidationSummaryMessage(),
                Assertion.ASSERTION_BOTH_SECURITY_QUESTIONS_MSG.assertion,
                "Assert that both security questions must be answered message displayed");

        forgotPwdSecurityQuesTwoPage.submitValidAnswer(tester.securityAnswer2);

        ForgotPwdConfirmationPage forgotPwdConfirmationPage = new ForgotPwdConfirmationPage(driver);
        Assert.assertTrue(forgotPwdConfirmationPage.getEmailConfirmationMsg()
                        .contains(Assertion.ASSERTION_EMAIL_LINK_MSG.assertion),
                "Assert the link message is displayed");
        Assert.assertTrue(forgotPwdConfirmationPage.getEmailConfirmationMsg()
                        .contains(Assertion.ASSERTION_EMAIL_EXPIRATION_MSG.assertion),
                "Assert the spam folder message is displayed");
        Assert.assertTrue(forgotPwdConfirmationPage.getEmailConfirmationMsg()
                        .contains(Assertion.ASSERTION_DVSA_HELPDESK_MSG.assertion),
                "Assert the helpdesk message is displayed");
        Assert.assertEquals(forgotPwdConfirmationPage.getEmailValidationMsg(),
                Assertion.ASSERTION_EMAIL_VALIDITY_MSG.assertion,
                "Assert the email validation message is displayed");
    }

    @Test(groups = {"VM-8717", "slice_A"}, description = "Verify Invalid userId")
    public void verifyInvalidUserId() {

        UserNamePage userNamePage = LoginPage.forgottenPassWord(driver);
        userNamePage.submitInvalidUserName(RandomDataGenerator.generateRandomString(5, 25));

        Assert.assertEquals(userNamePage.getValidationMessage(),
                Assertion.ASSERTION_INVALID_USER_ID.assertion,
                "Assert the invalid user id message displayed");
    }

    @Test(groups = {"VM-8717", "slice_A"}, description = "Verify Invalid security question 1")
    public void verifyInvalidSecurityQuestionOne() {
        UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_TESTER1);
        UserNamePage userNamePage = LoginPage.forgottenPassWord(driver);
        userNamePage.submitValidUserName(Login.LOGIN_TESTER1.username);

        ForgotPwdSecurityQuesOnePage forgotPwdSecurityQuesOnePage =
                new ForgotPwdSecurityQuesOnePage(driver);
        forgotPwdSecurityQuesOnePage
                .submitInvalidAnswer(RandomDataGenerator.generateRandomString(25, 5));
        Assert.assertEquals(forgotPwdSecurityQuesOnePage.getValidationMessage(),
                Assertion.ASSERTION_SECURITY_QUESTION_1_FAIL.assertion,
                "Assert that the error message displayed for invalid answer");

        forgotPwdSecurityQuesOnePage
                .submitInvalidAnswer(RandomDataGenerator.generateRandomString(10, 7));
        Assert.assertEquals(forgotPwdSecurityQuesOnePage.getValidationMessage(),
                Assertion.ASSERTION_SECURITY_QUESTION_2_FAIL.assertion,
                "Assert that the error message displayed for invalid answer");

        forgotPwdSecurityQuesOnePage
                .submitInvalidAnswer3(RandomDataGenerator.generateRandomString(5, 5));

        ForgotSecurityQuestionsPage forgotSecurityQuestionsPage =
                new ForgotSecurityQuestionsPage(driver);

        Assert.assertEquals(forgotSecurityQuestionsPage.getMsgToUser(),
                Assertion.ASSERTION_DVSA_CONTACT_MSG.assertion,
                "Assert that the DVSA contact message displayed after 3 incorrect attempts");
    }

    @Test(groups = {"VM-8717", "slice_A"}, description = "Verify Invalid security question 2")
    public void verifyInvalidSecurityQuestionTwo() {
        Person tester = Person.BOB_THOMAS;
        UserNamePage userNamePage = LoginPage.forgottenPassWord(driver);
        userNamePage.submitValidUserName(tester.login.username)
                .submitValidAnswer(tester.securityAnswer1);

        ForgotPwdSecurityQuesTwoPage forgotPwdSecurityQuesTwoPage =
                new ForgotPwdSecurityQuesTwoPage(driver);

        forgotPwdSecurityQuesTwoPage
                .submitInvalidAnswer(RandomDataGenerator.generateRandomString(7, 3));
        Assert.assertEquals(forgotPwdSecurityQuesTwoPage.getValidationMessage(),
                Assertion.ASSERTION_SECURITY_QUESTION_1_FAIL.assertion,
                "Assert that the error message displayed for incorrect answer");
        forgotPwdSecurityQuesTwoPage
                .submitInvalidAnswer(RandomDataGenerator.generateRandomString(4, 9));
        Assert.assertEquals(forgotPwdSecurityQuesTwoPage.getValidationMessage(),
                Assertion.ASSERTION_SECURITY_QUESTION_2_FAIL.assertion,
                "Assert that the error message displayed for incorrect answer");

        forgotPwdSecurityQuesTwoPage
                .submitInvalidAnswer3(RandomDataGenerator.generateRandomString(5, 5));

        ForgotSecurityQuestionsPage forgotSecurityQuestionsPage =
                new ForgotSecurityQuestionsPage(driver);

        Assert.assertEquals(forgotSecurityQuestionsPage.getMsgToUser(),
                Assertion.ASSERTION_DVSA_CONTACT_MSG.assertion,
                "Assert that the DVSA contact message displayed after 3 incorrect attempts");
    }


    @Test(groups = {"VM-8713", "VM-8718", "VM-8775", "slice_A"}, description = "Reset Password")

    public void resetPasswordAndLoginWithNewPassword() {
        Person tester = Person.testNameCertif4;

        UserNamePage userNamePage = LoginPage.forgottenPassWord(driver);
        userNamePage.submitValidUserName(Login.LOGIN_RESETUSERPWD.username);
        ForgotPwdSecurityQuesOnePage forgotPwdSecurityQuesOnePage =
                new ForgotPwdSecurityQuesOnePage(driver);
        forgotPwdSecurityQuesOnePage.submitValidAnswer(tester.securityAnswer1);
        ForgotPwdSecurityQuesTwoPage forgotPwdSecurityQuesTwoPage =
                new ForgotPwdSecurityQuesTwoPage(driver);
        forgotPwdSecurityQuesTwoPage.submitValidAnswer(tester.securityAnswer2);
        int testerId = Integer.valueOf(tester.getId());
        String token = authService.getResetPasswordToken(testerId);
        AccessResetPassWordLink.goToResetPassWordPage(driver, token);
        ResetPasswordPage resetPasswordPage = new ResetPasswordPage(driver);
        resetPasswordPage.enterPassword(Text.TEXT_RESET_PASSWORD)
                .enterConfirmPassword(Text.TEXT_RESET_PASSWORD).submitPassword();
        LoginPage loginPage = new LoginPage(driver);
        loginPage.typeUsername(Login.LOGIN_RESETUSERPWD.username);
        loginPage.typePassword(Text.TEXT_RESET_PASSWORD);
        loginPage.clickSubmit();
        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);
        userDashboardPage.verifyOnDashBoard();
        userDashboardPage.clickLogout();

    }

    @Test(groups = {"VM-8713", "VM-8718", "VM-8775",
            "slice_A"}, description = "Verify Invalid Password Validation Messages")

    public void testResetPasswordInvalidPasswordAndValidationMessages() {

        Person tester = Person.testNameCertif4;
        UserNamePage userNamePage = LoginPage.forgottenPassWord(driver);
        userNamePage.submitValidUserName(Login.LOGIN_RESETUSERPWD.username);
        ForgotPwdSecurityQuesOnePage forgotPwdSecurityQuesOnePage =
                new ForgotPwdSecurityQuesOnePage(driver);
        forgotPwdSecurityQuesOnePage.submitValidAnswer(tester.securityAnswer1);
        ForgotPwdSecurityQuesTwoPage forgotPwdSecurityQuesTwoPage =
                new ForgotPwdSecurityQuesTwoPage(driver);
        forgotPwdSecurityQuesTwoPage.submitValidAnswer(tester.securityAnswer2);
        ForgotPwdConfirmationPage forgotPwdConfirmationPage = new ForgotPwdConfirmationPage(driver);
        Assert.assertEquals(forgotPwdConfirmationPage.getEmailConfirmationMsg(),
                Assertion.ASSERTION_RESET_PASSWORD_ARRIVAL_MESSAGE.assertion,
                "Assert that Reset Password Page Displayed ");
        Assert.assertEquals(forgotPwdConfirmationPage.getEmailValidationMsg(),
                Assertion.ASSERTION_RESET_PASSWORD_VALIDATION_MESSAGE.assertion,
                "Password Warning Message Displayed ");
        int testerId = Integer.valueOf(tester.getId());
        String token = authService.getResetPasswordToken(testerId);
        AccessResetPassWordLink.goToResetPassWordPage(driver, token);
        ResetPasswordPage resetPasswordPage = new ResetPasswordPage(driver);
        //Verify password mismatch
        resetPasswordPage.enterPassword(Text.TEXT_RESET_PASSWORD)
                .enterConfirmPassword(Text.TEXT_RESET_INVALID_PASSWORD).submitPassword();
        Assert.assertEquals(resetPasswordPage.passwordValidation(),
                Assertion.ASSERTION_PASSWORD_MISMATCH.assertion, "Password is mismatched");

        //Verify OpenDj illegal characters message
        resetPasswordPage.clearPasswordFields();
        resetPasswordPage.enterPassword(Text.ILLEGAL_PASSWORD)
                .enterConfirmPassword(Text.ILLEGAL_PASSWORD).submitPassword();
        Assert.assertEquals(resetPasswordPage.passwordValidation(),
                Assertion.ASSERTION_PASSWORD_ILLEGAL.assertion,
                "Password contains illegal characters");
    }

}
