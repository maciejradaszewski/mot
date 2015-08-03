package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.dvsarolesandmanagement.DvsaManagesRolesPage;

import java.io.IOException;

import static org.hamcrest.CoreMatchers.is;
import static org.hamcrest.MatcherAssert.assertThat;

public class DvsaRolesAndManagementTest extends BaseTest {

    private User areaOfficeOne;
    private User userWithoutRole;
    private String userWithoutRoleId;

    @BeforeClass(alwaysRun = true)
    private void setUpUser() throws IOException {
        areaOfficeOne = userData.createAreaOfficeOne("AreaOfficerOne");
        userWithoutRole = userData.createUserWithoutRoles(false);
        userData.upgradeUserWithManageRoles(areaOfficeOne.getPersonId());
        userWithoutRoleId = String.valueOf(userWithoutRole.getId());
    }

    @Test(priority = 0, groups = {"BVT"}, description = "VM-10619 - AO1 assigniing role of AO2 to User without any role")
    public void notificationAndEventAreSentWhenInternalRoleIsAssigneSuccessfully() throws IOException {

        //Given that a DVSA area office one is able to navigate to user manage roles page
        DvsaManagesRolesPage dvsaManagesRolesPage = pageNavigator.goToDvsaManageRolesPage(areaOfficeOne, userWithoutRoleId);

        //When I assign and confirm area-office-2 role to a user without role
        dvsaManagesRolesPage.clickToAssignAreaOfficeInternalRole().clickConfirmAddRoleButton();

        // Then I should see Event is created against the user with new role assign
        assertThat("Event message is not generated", pageNavigator.goToDvsaEventHistoryPage(userWithoutRole, userWithoutRoleId).isEvenHistoryDisplayed(), is(true));

        //And I will receive notification message for assigning internal role
        assertThat("Notification message is not displayed", pageNavigator.gotoHomePage(userWithoutRole).isNotificationMessageDisplayed(), is(true));
    }
}
