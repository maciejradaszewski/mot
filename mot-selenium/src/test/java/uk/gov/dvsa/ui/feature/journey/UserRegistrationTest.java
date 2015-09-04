package uk.gov.dvsa.ui.feature.journey;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Person;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.login.LoginPage;
import uk.gov.dvsa.ui.pages.userregistration.*;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class UserRegistrationTest extends BaseTest {

    @Test(groups = {"BVT", "Regression"}, description = "VM-11472")
    public void createUserAccountSuccessfully() throws IOException {

        String answer = "Answer";
        String password = "1Password2";
        String securityQuestion1 = "1";
        String securityQuestion2 = "6";

        //Go directly to the Create an account/before you start page (create an account/ page)
        CreateAnAccountPage createAnAccount = pageNavigator.goToCreateAnAccountPage();

        //Continue to details page
        DetailsPage detailsPage = createAnAccount.clickContinue();

        //Fill in details & continue to address page
        AddressPage addressPage = detailsPage.
                enterDetailsAndSubmitExpectingAddressPage
                        (Person.PERSON_1.getName(), Person.PERSON_1.getSurname(), Person.PERSON_1.getEmail(), Person.PERSON_1.getEmail());

        //Enter address
        Address address = Address.ADDRESS_ADDRESS1;
        SecurityQuestionOne securityQuestionOne = addressPage.enterAddressAndSubmitExpectingFirstSecurityQuestionPage(address.getLine1(), address.getLine2(), address.getLine3(), address.getTown(), address.getPostcode());

        //Security question 1
        SecurityQuestionTwo securityQuestionTwo =  securityQuestionOne.selectQuestionAndEnterAnswerExpectingSecurityQuestionTwoPage(securityQuestion1, answer);

        //Security question 2
        PasswordPage passwordPage  = securityQuestionTwo.selectQuestionAndEnterAnswerExpectingPasswordPage(securityQuestion2, answer);

        //Password
        SummaryPage summaryPage = passwordPage.enterPasswordAndRetypeExpectingSummaryPage(password, password);

        //Confirm summary and click create account
        AccountCreatedPage confirmationPage = summaryPage.clickCreateYourAccount();

        //Confirm 'account successfully created' text displayed
        assertThat(confirmationPage.isAccountCreatedTextDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11472")
    public void checkCreateAccountLinkExists() throws IOException {

        //Go to the main login page
        LoginPage loginPage = pageNavigator.goToLoginPage();

        //Ensure the create account link exists by clicking on this link
        CreateAnAccountPage createAnAccountPage = loginPage.clickCreateAnAccountLink();

        //Verify that the page returned is the create an account page
        assertThat(createAnAccountPage.selfVerify(), is(true));
    }

}