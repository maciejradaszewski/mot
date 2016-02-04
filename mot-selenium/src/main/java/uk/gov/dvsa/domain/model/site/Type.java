package uk.gov.dvsa.domain.model.site;

public enum Type {
    AREAOFFICE("Area Office", "AO"),
    VEHICLETESTINGSTATION("Vehicle Testing Station", "VTS"),
    TRAININGCENTRE("Training Centre", "CTC");

    private final String siteType;
    private final String siteTypeCode;

    Type(String siteType, String siteTypeCode) {
        this.siteTypeCode = siteTypeCode;
        this.siteType = siteType;
    }

    public String getSiteType() {
        return siteType;
    }

    public String getSiteTypeCode() {
        return siteTypeCode;
    }
}
