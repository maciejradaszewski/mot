package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class TradeManagingUserRolesTests extends BaseTest {

    private User siteManager;
    private AeDetails aeDetails;
    private Site site;
    private User tester;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        aeDetails = aeData.createAeWithDefaultValues();
        site = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(site.getId());
        siteManager = userData.createSiteManager(site.getId(), false);
    }

    @Test(groups = {"BVT", "Regression", "VM-12321"},
            description = "Verifies that trade user cant check roles via roles and associations link " +
                    "of other trade user")
    public void tradeUserCantViewAeAssociationOfOtherTradeUser() throws IOException {

        //Given I'm on the Vts details page
        VehicleTestingStationPage vehicleTestingStationPage = pageNavigator.gotoHomePage(siteManager).selectRandomVts();

        //When I click on assigned user
        vehicleTestingStationPage.chooseAssignedToVtsUser(tester.getId());

        //Then Roles and Associations link should not be displayed
        assertThat(motUI.manageRoles.isRolesAndAssociationsLinkDisplayedOnProfileOfPage(), is(false));
    }
}
