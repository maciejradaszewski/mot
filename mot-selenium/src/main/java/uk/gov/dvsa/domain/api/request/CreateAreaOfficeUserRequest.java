package uk.gov.dvsa.domain.api.request;

public class CreateAreaOfficeUserRequest extends UserRequest {
    public CreateAreaOfficeUserRequest(String diff, boolean accountClaimRequired) {
        super(diff, accountClaimRequired);
    }
}
