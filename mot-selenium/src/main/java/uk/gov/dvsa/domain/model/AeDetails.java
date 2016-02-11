package uk.gov.dvsa.domain.model;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.databind.annotation.JsonSerialize;
import uk.gov.dvsa.helper.CompanyDetailsHelper;
import uk.gov.dvsa.helper.ContactDetailsHelper;

@JsonIgnoreProperties(ignoreUnknown = true)
@JsonSerialize
public class AeDetails {

    private int id;
    private String aeRef;
    private String aeName;

    private AeContactDetails aeContactDetails;
    private AeBusinessDetails aeBusinessDetails;

    public int getId() {
        return id;
    }

    public String getIdAsString() {
        return String.valueOf(id);
    }

    public String getAeRef() {
        return aeRef;
    }

    public String getAeName() {
        return aeName;
    }

    public AeDetails() {
        String email = ContactDetailsHelper.getEmail();
        aeContactDetails = new AeContactDetails(email, email, ContactDetailsHelper.getPhoneNumber());
        aeBusinessDetails = new AeBusinessDetails(
                CompanyDetailsHelper.getBusinessName(),
                CompanyDetailsHelper.getTradingName(),
                CompanyDetailsHelper.getBusinessType(),
                CompanyDetailsHelper.getCompanyNumber()
        );
    }

    public AeContactDetails getAeContactDetails() {
        return aeContactDetails;
    }

    public AeBusinessDetails getAeBusinessDetails() {
        return aeBusinessDetails;
    }

    @Override
    public String toString() {
        return "AeDetails{" +
                "id=" + id +
                ", aeRef='" + aeRef + '\'' +
                ", aeName='" + aeName + '\'' +
                '}';
    }
}
