package uk.gov.dvsa.ui.views;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.ProfilePage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ProfilePageViewTests extends BaseTest {

    private User tester;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        AeDetails aeDetails = aeData.createAeWithDefaultValues();
        Site site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(site.getId());
    }

    @Test(groups = {"Regression"}, description = "VM-10334")
    public void testerQualificationStatusDisplayedOnProfilePage() throws IOException {

        //Given I'm on the Your Profile Details page
        ProfilePage profilePage = pageNavigator.gotoProfilePage(tester);

        //Then I should be able to see the qualification status
        assertThat(profilePage.isTesterQualificationStatusDisplayed(), is(true));
    }

    @Test(groups = {"BVT", "Regression", "VM-12321"},
            description = "Verifies that trade user can check own roles via roles and associations link " +
                    "on it's own profile page")
    public void tradeUserCanViewHisOwnRolesAndAssociations() throws IOException {

        //Given I'm on the Your Profile Details page
        ProfilePage profilePage = pageNavigator.gotoProfilePage(tester);

        //When I click on Roles and Associations link
        profilePage.clickRolesAndAssociationsLink();

        //Then roles should be displayed
        assertThat(motUI.manageRoles.isRolesTableContainsValidTesterData(), is(true));
    }
}
