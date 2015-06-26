package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;
import uk.gov.dvsa.domain.model.User;

@JsonInclude(JsonInclude.Include.NON_NULL)
@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
public class CreateAeRequest {
    private String diff;
    private String slots;
    private Requestor requestor;

    public CreateAeRequest(String namePrefix, User requestor) {
        this(namePrefix, requestor, 5);
    }

    public CreateAeRequest(String namePrefix, User requestor, Integer slots) {
        diff = namePrefix;
        this.slots = slots.toString();
        this.requestor = new Requestor(requestor);

    }
}
