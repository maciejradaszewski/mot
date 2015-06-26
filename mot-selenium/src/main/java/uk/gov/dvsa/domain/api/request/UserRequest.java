package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;

@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
@JsonInclude(JsonInclude.Include.NON_NULL)
public abstract class UserRequest {

    private boolean accountClaimRequired;
    private String diff;

    public UserRequest(boolean accountClaimRequired) {
        this(null,accountClaimRequired);
    }

    public UserRequest(String diff, boolean accountClaimRequired) {
        this.accountClaimRequired = accountClaimRequired;
        this.diff = diff;
    }
}
