package uk.gov.dvsa.ui.feature.journey.account_administration;

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

public class ChangePersonTelephoneTests extends BaseTest {

    private User areaOffice1User;
    private User areaOffice2User;
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
        areaOffice2User = userData.createAreaOfficeTwo("AreaOfficerTwo");
        vehicleExaminerUser = userData.createVehicleExaminer("VehicleExaminer", false);
        tester = userData.createTester(testSite.getId());
        schemeManager = userData.createSchemeUser(false);
        aedm = userData.createAedm(aeDetails.getId(), "Test", false);
        siteManager = userData.createSiteManager(testSite.getId(), false);
        csco = userData.createCustomerServiceOfficer(false);
    }

    @Test(groups = {"BVT", "Regression", "BL-931"},
            testName = "NewProfile",
            description = "Test that Trade user can edit their telephone from their profile page")
    public void tradeUserCanEditTheirTelephone() throws Exception {
        // Given I am logged in as a Tester and I am on the My Profile Page
        motUI.userRoute.viewYourProfile(tester);

        // When I edit my Telephone number
        String telephoneNumber = "+44 (0) 1225 200 123";
        NewPersonProfilePage newPersonProfilePage = motUI.userRoute.changeTelephone(telephoneNumber, "YOUR_PROFILE");

        // Then My Profile Telephone number will be amended with successful confirmation message
        assertThat(newPersonProfilePage.verifyTelephoneIsChanged(telephoneNumber), is(true));
        assertThat(newPersonProfilePage.verifyConfirmationMessage("Telephone number has been changed successfully."), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-931"},
            testName = "NewProfile",
            description = "Test that Trade user can cancel their telephone update from change telephone page")
    public void dvsaUserCanCancelTheirTelephoneChange() throws IOException {
        // Given I am logged in as a Tester and I am on the My Profile Page
        motUI.userRoute.viewYourProfile(tester);

        // When I Cancel my Telephone number edit
        motUI.userRoute.page().clickChangeTelephoneLink().clickCancelButton(true);

        // Then I will be returned to My Profile Page
        assertThat(motUI.userRoute.page().isPageLoaded(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-931"},
            testName = "NewProfile",
            description = "Test that DVSA user can cancel amending a users telephone number change",
            dataProvider = "dvsaUserChangeTelephoneProvider")
    public void dvsaUserCanCancelUserTelephoneChange(User user) throws IOException {
        // Given I am logged in as a DVSA User and User Profile Page
        motUI.userRoute.dvsaViewUserProfile(user, tester);

        // When I Cancel users Telephone number edit
        motUI.userRoute.page().clickChangeTelephoneLink().clickCancelButton(false);

        // Then I will be returned to User Page
        assertThat(motUI.userRoute.page().isPageLoaded(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-931"},
            testName = "NewProfile",
            description = "Test that Authorised user can change telephone number on person profile",
            dataProvider = "dvsaUserChangeTelephoneProvider")
    public void dvsaUserCanChangeTelephoneNumberOnOtherPersonProfile(User user) throws IOException {
        // Given I am on a Users profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(user, tester);

        // When I am changing telephone number for a person
        String telephoneNumber = "+44 (0) 1225 200 123";
        NewUserProfilePage newUserProfilePage = motUI.userRoute.changeTelephone(telephoneNumber, "PERSON_PROFILE");

        // Then My Profile Telephone number will be amended with successful confirmation message
        assertThat(newUserProfilePage.verifyTelephoneChanged(telephoneNumber), is(true));
        assertThat(newUserProfilePage.verifyConfirmationMessage("Telephone number has been changed successfully."), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-931"},
            testName = "NewProfile",
            description = "Test that Trade user cannot enter more than 24 Chars in the Telephone number")
    public void dvsaUserShouldProvideValidTelephoneNumber() throws IOException {
        // Given I am logged in as a Tester and I am on the My Profile Page
        motUI.userRoute.viewYourProfile(tester);

        // When I am trying to submit a telephone number greater than 24 Chars
        motUI.userRoute.changeTelephone("A123456789B123456789C12345", "INVALID_INPUT");

        // Then the error validation message should be displayed
        assertThat(motUI.userRoute.isValidationMessageOnChangeTelephonePageDisplayed("TELEPHONE_TOO_LARGE"), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-931"},
            testName = "NewProfile",
            description = "Test that Authorised user should provide an appropriate sized telephone number in order to update user information")
    public void dvsaUserShouldNotProvideInvalidSizedTelephoneNumber() throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(areaOffice1User, tester);

        // When I am trying to submit a telephone number greater than 24 Chars
        motUI.userRoute.changeTelephone("A123456789B123456789C12345", "INVALID_INPUT");

        // Then the error validation message should be displayed
        assertThat(motUI.userRoute.isValidationMessageOnChangeTelephonePageDisplayed("TELEPHONE_TOO_LARGE"), is(true));
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
    private Object[][] dvsaUserChangeTelephoneProvider() {
        return new Object[][] {
                {areaOffice1User},
                {areaOffice2User},
                {vehicleExaminerUser},
                {schemeManager},
                {csco},
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


