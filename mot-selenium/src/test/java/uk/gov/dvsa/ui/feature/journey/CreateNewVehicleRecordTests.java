package uk.gov.dvsa.ui.feature.journey;

import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class CreateNewVehicleRecordTests extends BaseTest{


    @Test
    public void createNewVehicleRecordDeclarationStatement() throws IOException, URISyntaxException {

        //Given I am a tester
        User tester = userData.createTester(1);
        Vehicle vehicle = vehicleData.getNewVehicle(tester);

        //When I create a new vehicle record within a test
        motUI.normalTest.createNewVehicleRecord(tester, vehicle);

        //Then I should be presented with the declaration statement
        assertThat(motUI.normalTest.isDeclarationStatementDisplayed(), is(true));
    }

}
