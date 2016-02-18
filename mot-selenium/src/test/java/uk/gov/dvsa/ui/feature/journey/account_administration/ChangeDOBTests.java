package uk.gov.dvsa.ui.feature.journey.account_administration;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ChangeDOBTests extends BaseTest {

    private User areaOffice1User;
    private User vehicleExaminerUser;
    private User tester;
    private User schemeManager;
    private Site testSite;
    private AeDetails aeDetails;
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
    }

    @Test(groups = {"BVT", "Regression", "BL-927"},
            testName = "NewProfile",
            description = "Test that Authorised user can change date of birth on other person profile",
            dataProvider = "dvsaUserChangeDOBProvider")
    public void dvsaUserCanChangeDOBOnOtherPersonProfile(User user) throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(user, tester);

        // When I am changing a name for a person
        motUI.userRoute.changeDOB().changeDateOfBirth("01", "01", "1975", true);

        // Then the success message should be displayed
        assertThat(motUI.userRoute.page().isSuccessMessageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-927"},
            testName = "NewProfile",
            description = "Test that user cant change date of birth on own person profile",
            dataProvider = "userCantSeeChangeDOBLinkProvider")
    public void userCantSeeChangeDOBLinkOnOwnProfile(User user) throws IOException, URISyntaxException {

        //Given I'm on the New Person Profile page as logged user
        motUI.userRoute.viewYourProfile(user);

        //Then Change date of birth link should not be displayed
        assertThat(motUI.userRoute.page().isChangeDOBLinkIsDisplayed(), is(false));
    }

    @Test(groups = {"BVT", "Regression", "BL-927"},
            testName = "NewProfile",
            description = "Test that user should provide a valid day in order to change date of birth")
    public void userShouldProvideAValidDay() throws IOException, URISyntaxException {

        // Given I'm on the New Person Profile page as logged user
        motUI.userRoute.dvsaViewUserProfile(areaOffice1User, tester);

        // When I am trying to change a date of birth for a person with invalid day
        motUI.userRoute.changeDOB().changeDateOfBirth("", "01", "1975", false);

        // Then the error message should be displayed
        assertThat(motUI.userRoute.changeDOB().isValidationMessageOnDOBPageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-927"},
            testName = "NewProfile",
            description = "Test that user should provide a valid month in order to change date of birth")
    public void userShouldProvideAValidMonth() throws IOException, URISyntaxException {

        // Given I'm on the New Person Profile page as logged user
        motUI.userRoute.dvsaViewUserProfile(vehicleExaminerUser, tester);

        // When I am trying to change a date of birth for a person with invalid month
        motUI.userRoute.changeDOB().changeDateOfBirth("01", "", "1975", false);

        // Then the error message should be displayed
        assertThat(motUI.userRoute.changeDOB().isValidationMessageOnDOBPageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-927"},
            testName = "NewProfile",
            description = "Test that user should provide a valid year in order to change date of birth")
    public void userShouldProvideAValidYear() throws IOException, URISyntaxException {

        // Given I'm on the New Person Profile page as logged user
        motUI.userRoute.dvsaViewUserProfile(schemeManager, tester);

        // When I am trying to change a date of birth for a person with invalid year
        motUI.userRoute.changeDOB().changeDateOfBirth("01", "01", "", false);

        // Then the error message should be displayed
        assertThat(motUI.userRoute.changeDOB().isValidationMessageOnDOBPageDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "BL-59"},
            testName = "NewProfile",
            description = "Test that Authorised user can navigate to Change date of birth page and backward")
    public void dvsaUserCanNavigateToAndBackwardDOBPage() throws IOException {
        // Given I am on other person profile as an authorised user
        motUI.userRoute.dvsaViewUserProfile(areaOffice1User, tester);

        // When I am navigating to Change date of birth page and clicking on cancel and return link
        motUI.userRoute.page().clickChangeDOBLink().clickCancelAndReturnLink();

        // Then the person profile page should be displayed
        assertThat(motUI.userRoute.page().isPageLoaded(), is(true));
    }

    @DataProvider
    private Object[][] dvsaUserChangeDOBProvider() {
        return new Object[][] {
                {areaOffice1User},
                {vehicleExaminerUser},
                {schemeManager}
        };
    }

    @DataProvider
    private Object[][] userCantSeeChangeDOBLinkProvider() {
        return new Object[][] {
                {areaOffice1User},
                {vehicleExaminerUser},
                {schemeManager},
                {aedm},
                {siteManager},
                {tester}
        };
    }
}