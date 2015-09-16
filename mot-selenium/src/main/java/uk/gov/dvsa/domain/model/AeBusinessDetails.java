package uk.gov.dvsa.domain.model;


public class AeBusinessDetails {

    public String businessName;
    public String tradingName;
    public String businessType;
    public String companyNumber;

    public AeBusinessDetails(String businessName, String tradingName, String businessType, String companyNumber) {
        this.businessName = businessName;
        this.tradingName = tradingName;
        this.businessType = businessType;
        this.companyNumber = companyNumber;
    }

    public String getBusinessName() {
        return businessName;
    }

    public String getTradingName() {
        return tradingName;
    }

    public String getBusinessType() {
        return businessType;
    }

    public String getCompanyNumber() {
        return companyNumber;
    }
}
