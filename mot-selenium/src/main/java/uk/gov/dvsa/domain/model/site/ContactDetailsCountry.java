package uk.gov.dvsa.domain.model.site;

public enum ContactDetailsCountry {
    ENGLAND("England", "GBENG"),
    SCOTLAND("Scotland", "GBSCT"),
    WALES("Wales", "GBWLS");

    private final String siteContactDetailsCountry;
    private final String siteContactDetailsCountryCode;

    ContactDetailsCountry(String siteContactDetailsCountry, String siteContactDetailsCountryCode) {
        this.siteContactDetailsCountryCode = siteContactDetailsCountryCode;
        this.siteContactDetailsCountry = siteContactDetailsCountry;
    }

    public String getSiteContactDetailsCountry() {
        return siteContactDetailsCountry;
    }

    public String getSiteContactDetailsCountryCode() {
        return siteContactDetailsCountryCode;
    }
}
