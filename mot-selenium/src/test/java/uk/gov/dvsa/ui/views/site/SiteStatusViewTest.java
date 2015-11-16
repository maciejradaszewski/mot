package uk.gov.dvsa.ui.views.site;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.site.Status;
import uk.gov.dvsa.ui.BaseTest;
import java.io.IOException;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class SiteStatusViewTest extends BaseTest {
    private User areaOffice2User;

    @BeforeMethod(alwaysRun = true)
    private void setup() throws IOException {
        areaOffice2User = userData.createUserAsAreaOfficeTwo("AE2");
    }

    @Test
    public void updateVtsStatusSuccessfully() throws IOException {
        //Given I am on the VTS Details Page as Area Officer 2
        motUI.vts.vtsPage(areaOffice2User, "1");

        //And I change the status of a VTS
        String newStatus = motUI.vts.changeVtsStatus(Status.REJECTED);

        //Then the VTS status is updated
        motUI.vts.vtsSearchPage(areaOffice2User);
        motUI.vts.searchForAVtsByNumber("V1234");

        assertThat("The VTS status change is reflected in the results page",
                motUI.vts.getVtsStatus(),
                is(newStatus));
    }
}
