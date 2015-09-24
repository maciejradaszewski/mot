package uk.gov.dvsa.ui.feature.journey;

import org.openqa.selenium.NoSuchElementException;
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

        //Given I am on the Create Account Details Page
        motUI.register.createAnAccount().details();

        //When I to enter my details
        motUI.register.completeDetails();

        //Then my account is created successfully
        assertThat(motUI.register.isAccountCreated(), is(true));
    }

    @Test(groups = {"BVT", "Regression"}, description = "VM-11472",
            enabled = false, expectedExceptions = NoSuchElementException.class)
    public void checkCreateAccountLinkExists() throws IOException {

        //Go to the main login page
        LoginPage loginPage = pageNavigator.goToLoginPage();

        //Ensure the create account link exists by clicking on this link
        CreateAnAccountPage createAnAccountPage = loginPage.clickCreateAnAccountLink();

        //Verify that the page returned is the create an account page
        assertThat(createAnAccountPage.selfVerify(), is(true));
    }

}