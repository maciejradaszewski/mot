package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.RandomDataGenerator;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchPage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchResultsPage;


import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.core.StringContains.containsString;

public class DVSAManagingUserRoles extends BaseTest {

    private User vehicleExaminer;
    private User areaOffice1User;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");
        vehicleExaminer = userData.createVehicleExaminer("ft-Enf-", false);
    }

    @Test(groups = {"BVT", "Regression"})
    public void areaOfficeUserAddsRoleToVeUser() throws IOException{

        //Given that I am on Manage roles page as a Area office 1 user
        pageNavigator.goToManageRolesPageViaUserSearch(areaOffice1User, vehicleExaminer);

        //When I add role of AO2
        motUI.manageRoles.addRole("AO2");

        //Then Ao2 role is added to user
        assertThat(motUI.manageRoles.confirmRemoveRoleAction(), containsString("added"));
    }

    @Test(groups = {"BVT", "Regression"})
    public void areaOfficeUserRemovesRoleFromVeUser() throws IOException{

        //Given that I am on Manage roles page as a Area office 1 user
        pageNavigator.goToManageRolesPageViaUserSearch(areaOffice1User, vehicleExaminer);

        //When I remove role
        motUI.manageRoles.removeRole("VE");

        //Then VE role is removed from user
        assertThat(motUI.manageRoles.confirmRemoveRoleAction(), containsString("removed"));
    }

    @Test(groups = {"BVT", "Regression", "VM-12168"},
            description = "Test that validates the authorised DVSA user can search for user by email")
    public void areaOfficeUserCanSearchForUserByEmail() throws IOException{

        //Given that I am on Search user page as a Area office 1 user
        UserSearchPage userSearchPage = pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by email
        userSearchPage.searchForUserByUserEmail(vehicleExaminer.getEmailAddress()).clickSearchButton(UserSearchResultsPage.class);

        //Then I should see the user details
        assertThat(motUI.manageRoles.isSearchResultAccurate(vehicleExaminer), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12168"},
            description = "Test that validates the authorised DVSA user cant search for user by invalid email")
    public void areaOfficeUserCantSearchForUserByInvalidEmail() throws IOException{

        //Given that I am on Search user page as a Area office 1 user
        UserSearchPage userSearchPage = pageNavigator.goToUserSearchPage(areaOffice1User);

        //When I search for user by invalid email
        userSearchPage.searchForUserByUserEmail(RandomDataGenerator.generateEmail(20, System.nanoTime()))
                .clickSearchButton(UserSearchPage.class);

        //Then I should see a Validation message
        assertThat(userSearchPage.isValidationMessageDisplayed(), is(true));
    }
}
