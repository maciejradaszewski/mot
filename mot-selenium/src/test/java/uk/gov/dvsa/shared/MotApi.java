package uk.gov.dvsa.shared;

import org.joda.time.DateTime;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.MotTest;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.service.MotTestService;

import java.io.IOException;

public class MotApi extends MotTestService{

    public MotTest createTest(User requestor, int siteId, Vehicle vehicle, TestOutcome outcome,
                                 int mileage, DateTime issuedDate) throws IOException {
        return createMotTest(requestor, siteId, vehicle, outcome, mileage, issuedDate);
    }
}
