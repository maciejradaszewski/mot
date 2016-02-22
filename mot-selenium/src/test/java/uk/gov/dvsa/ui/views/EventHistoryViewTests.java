package uk.gov.dvsa.ui.views;

import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.events.HistoryType;

import java.io.IOException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class EventHistoryViewTests extends BaseTest {

    //TODO return test to Regression suite after stabilising
    @Test(groups = {"VM-5153", "VM-5154", "Unstable"})
    public void viewEventHistory() throws IOException {
        //Given I create Test AE
        AeDetails aeDetails = motApi.aeData.createNewAe("Test AE", 1);

        //When I view Test AE event history as Ao1
        motUI.showEventHistoryFor(HistoryType.AE, userData.createAreaOfficeOne("ao1"), aeDetails);

        //Then I should see a Create event
        motUI.eventHistory.containsEvent("DVSA Administrator Create AE");
    }
}
