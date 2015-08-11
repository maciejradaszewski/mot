package uk.gov.dvsa.ui.views;

import org.testng.annotations.Test;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;

public class DashBoardViewTest extends BaseTest {

    @Test(groups = {"Regression", "VM_9444"})
    public void doesNotDisplayRetestLink() throws IOException {

        //Given I am on the home page as a tester
        motUI.retest.gotoHomepageAs(userData.createTester(siteData.createSite().getId()));

        //I expect re-test a previous vehicle link not to be present
        motUI.retest.verifyRetestLinkNotPresent();
    }
}
