package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;

@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
@JsonInclude(JsonInclude.Include.NON_NULL)
public class CreateManageRoleRequest {

    private String personId;

    public CreateManageRoleRequest(String personId) {
        this.personId = personId;
    }
}
