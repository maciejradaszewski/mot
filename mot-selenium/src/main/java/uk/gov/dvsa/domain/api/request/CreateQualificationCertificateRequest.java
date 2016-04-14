package uk.gov.dvsa.domain.api.request;

import com.fasterxml.jackson.annotation.JsonAutoDetect;
import com.fasterxml.jackson.annotation.JsonInclude;

@JsonInclude(JsonInclude.Include.NON_NULL)
@JsonAutoDetect(fieldVisibility = JsonAutoDetect.Visibility.ANY)
public class CreateQualificationCertificateRequest {

    protected String userId;
    protected String userName;
    protected String userPassword;
    protected String vehicleClassGroupCode;
    protected String certificateNumber;
    protected String dateOfQualification;
    protected String siteNumber;

    public CreateQualificationCertificateRequest(
            String userId,
            String username,
            String userPassword,
            String vehicleClassGroupCode,
            String certificateNumber,
            String dateOfQualification,
            String siteNumber
    ) {
        this.userId = userId;
        this.userName = username;
        this.userPassword = userPassword;
        this.vehicleClassGroupCode = vehicleClassGroupCode;
        this.certificateNumber = certificateNumber;
        this.dateOfQualification = dateOfQualification;
        this.siteNumber = siteNumber;
    }
}
