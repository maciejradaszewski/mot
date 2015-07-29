package uk.gov.dvsa.domain.api.request;

public class CreateAoRequest extends UserRequest {
    public CreateAoRequest(String diff, boolean accountClaimRequired) {
        super(diff, accountClaimRequired);
    }
}
