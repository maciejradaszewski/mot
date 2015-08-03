package uk.gov.dvsa.domain.api.request;

public class CreateAreaOfficeOneRequest extends UserRequest {
    public CreateAreaOfficeOneRequest(String diff, boolean accountClaimRequired) {
        super(diff, accountClaimRequired);
    }
}
