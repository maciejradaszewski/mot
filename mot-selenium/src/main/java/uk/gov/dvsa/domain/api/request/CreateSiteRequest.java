package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.google.common.base.Optional;
import uk.gov.dvsa.domain.model.User;

import java.util.Collection;

@JsonInclude(JsonInclude.Include.NON_NULL)
@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
public class CreateSiteRequest {
    private String aeId;
    private Collection<Integer> classes;
    private String siteName;
    private String email;
    private String addressLine1;
    private String diff;
    private Requestor requestor;

    public CreateSiteRequest(Optional<Integer> aeId, User requestor, String prefix) {
        this(aeId, null, requestor, prefix);
    }

    public CreateSiteRequest(Optional<Integer> aeId, String siteName, User requestor, String prefix) {
        if (aeId.isPresent()) {
            this.aeId = String.valueOf(aeId.get());
        }
        this.siteName = siteName;
        diff = prefix;
        this.requestor = new Requestor(requestor);
    }
}
