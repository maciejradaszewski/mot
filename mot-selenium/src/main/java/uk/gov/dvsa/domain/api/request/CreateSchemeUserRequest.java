package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;

@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
@JsonInclude(JsonInclude.Include.NON_NULL)
public class CreateSchemeUserRequest extends UserRequest {

    public CreateSchemeUserRequest(boolean accountClaimRequired) {
        super(accountClaimRequired);
    }
}
