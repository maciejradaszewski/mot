package uk.gov.dvsa.ui.feature.journey.account_administration;

import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.profile.NewPersonProfilePage;
import uk.gov.dvsa.ui.pages.profile.NewUserProfilePage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ChangePersonEmailTests extends BaseTest {

    private User areaOffice1User;
    private User vehicleExaminerUser;
    private User tester;
    private User schemeManager;
    private Site testSite;
    private AeDetails aeDetails;
    private User csco;
    private User aedm;
    private User siteManager;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        testSite = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        areaOffice1User = userData.createAreaOfficeOne("AreaOfficerOne");
        vehicleExaminerUser = userData.createVehicleExaminer("VehicleExaminer", false);
        tester = userData.createTester(testSite.getId());
        schemeManager = userData.createSchemeUser(false);
        aedm = userData.createAedm(aeDetails.getId(), "Test", false);
        siteManager = userData.createSiteManager(testSite.getId(), false);
        csco = userData.createCustomerServiceOfficer(false);
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that Trade user can edit their email from their profile page")
    public void tradeUserCanEditTheirEmailAddress() throws Exception {
        //Given I am logged in as a Tester and I am on the My Profile Page
        motUI.userRoute.viewYourProfile(tester);

        //When I edit my Email address
        String emailAddress = RandomDataGenerator.generateEmail(20, System.nanoTime());
        NewPersonProfilePage newPersonProfilePage = motUI.userRoute.changeEmail(emailAddress, emailAddress, "YOUR_PROFILE");

        //Then My Profile Email address will be amended
        assertThat(newPersonProfilePage.verifyEmailIsChanged(emailAddress), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that Trade user can cancel their email from change email page")
    public void dvsaUserCanCancelTheirEmailChange() throws IOException {
        //Given I am logged in as a Tester and I am on the My Profile Page
        motUI.userRoute.viewYourProfile(tester);

        //When I Cancel my Email address edit
        motUI.userRoute.page().clickChangeEmailLink().clickCancelButton(true);

        //Then I will be returned to My Profile Page
        assertThat(motUI.userRoute.page().isPageLoaded(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that DVSA user can cancel amending a users email change",
            dataProvider = "dvsaUserChangeEmailProvider")
    public void dvsaUserCanCancelTheirUserEmailChange(User user) throws IOException {
        //Given I am logged in as a Tester and I am on the My Profile Page
        motUI.userRoute.dvsaViewUserProfile(user, tester);

        //When I Cancel my Email address edit
        motUI.userRoute.page().clickChangeEmailLink().clickCancelButton(false);

        //Then I will be returned to My Profile Page
        assertThat(motUI.userRoute.page().isPageLoaded(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that Authorised user can change email on person profile",
            dataProvider = "dvsaUserChangeEmailProvider")
    public void dvsaUserCanChangeEmailOnOtherPersonProfile(User user) throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(user, tester);

        // When I am changing a name for a person
        String emailAddress = RandomDataGenerator.generateEmail(20, System.nanoTime());
        motUI.userRoute.changeEmail(emailAddress, emailAddress, "PERSON_PROFILE");

        // Then the success message should be displayed
        assertThat(motUI.userRoute.page().isSuccessMessageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that Authorised user should provide a valid email in order to update user information")
    public void dvsaUserShouldProvideValidEmails() throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(areaOffice1User, tester);

        // When I am trying to submit an an invalid email
        motUI.userRoute.changeEmail("ad%^&*£lkjfhadslkjhf", "ad%^&*£lkjfhadslkjhf", "INVALID_INPUT");

        // Then the error validation message should be displayed
        assertThat(motUI.userRoute.isValidationMessageOnChangeEmailPageDisplayed("EMAIL_VALID"), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that Authorised user should provide the same email and email confirmation in order to update user information")
    public void dvsaUserShouldProvideSameEmailsAndEmailConfirmation() throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(areaOffice1User, tester);

        // When I am trying to submit a different confirmation email
        motUI.userRoute.changeEmail("fred@bloggs.com", "barry@bloggs.com", "INVALID_INPUT");

        // Then the error validation message should be displayed
        assertThat(motUI.userRoute.isValidationMessageOnChangeEmailPageDisplayed("EMAIL_MATCH"), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-270"},
            testName = "NewProfile",
            description = "Test that Authorised user should provide incorrect email and valid email confirmation in order to update user information")
    public void dvsaUserShouldProvideInvalidEmailEmailsAndValidEmailConfirmation() throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(areaOffice1User, tester);

        // When I am trying to submit a different confirmation email
        motUI.userRoute.changeEmail("ffghjfgj", "barry@bloggs.com", "INVALID_INPUT");

        // Then the error validation message should be displayed
        assertThat(motUI.userRoute.isValidationMessageOnChangeEmailPageDisplayed("EMAIL_MATCH_AND_VALID"), is(true));
    }

    @DataProvider
    private Object[][] dvsaUserProvider() {
        return new Object[][] {
                {areaOffice1User, true},
                {vehicleExaminerUser, true},
                {csco, false},
                {schemeManager, true}
        };
    }

    @DataProvider
    private Object[][] dvsaUserChangeEmailProvider() {
        return new Object[][] {
                {areaOffice1User},
                {vehicleExaminerUser},
                {schemeManager}
        };
    }

    @DataProvider
    private Object[][] tradeUserProvider() {
        return new Object[][] {
                {aedm},
                {siteManager}
        };
    }
}


