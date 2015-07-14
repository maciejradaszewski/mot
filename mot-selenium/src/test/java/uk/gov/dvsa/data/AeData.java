package uk.gov.dvsa.data;

import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.service.AeService;

import java.io.IOException;

public class AeData extends AeService{

    public AeData() {}

    public AeDetails createAeWithDefaultValues() throws IOException {
        return createAe("default", 7);
    }

    public AeDetails createNewAe(String namePrefix, int slots) throws IOException{
        return createAe(namePrefix, slots);
    }
}
