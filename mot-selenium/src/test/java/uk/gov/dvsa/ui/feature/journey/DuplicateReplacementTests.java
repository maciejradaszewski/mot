package uk.gov.dvsa.ui.feature.journey;


import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.ui.BaseTest;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class DuplicateReplacementTests extends BaseTest {

    @Test
    public void replacementCertificateDeclarationStatement() throws IOException, URISyntaxException {

        //Given I am a tester
        User tester = userData.createTester(1);
        Vehicle vehicle = vehicleData.getNewVehicle(tester);

        //And I have completed an Mot Test
        motUI.normalTest.conductTestPass(tester, vehicle);

        //When I create a replacement test certificate
        motUI.duplicateReplacementCertificate.createReplacementCertificate(tester, vehicle);

        //Then I should be presented with the declaration statement on the review page
        assertThat(motUI.duplicateReplacementCertificate.isDeclarationStatementDisplayed(), is(true));
    }
}