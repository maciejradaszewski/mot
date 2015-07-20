package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;

@JsonInclude(JsonInclude.Include.NON_NULL)
@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
public class SpecialNoticeRequest {

    protected int specialNoticeContentId;
    protected String username;
    protected boolean isAcknowledged;

    public SpecialNoticeRequest(int contentId, String username, boolean isAcknowledged) {
        this.specialNoticeContentId = contentId;
        this.username = username;
        this.isAcknowledged = isAcknowledged;
    }
}
