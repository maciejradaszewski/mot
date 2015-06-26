package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;
import uk.gov.dvsa.domain.model.User;

import java.util.List;

@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
@JsonInclude(JsonInclude.Include.NON_NULL)
public class CreateAedmRequest extends UserRequest{

    private List<Integer> aeIds;

    public CreateAedmRequest(List<Integer> aeIds, boolean accountClaimRequired) {
        this(aeIds, null, accountClaimRequired);
    }

    public CreateAedmRequest(List<Integer> aeIds, String namePrefix, boolean accountClaimRequired) {
        super(namePrefix, accountClaimRequired);
        this.aeIds = aeIds;
    }
}
