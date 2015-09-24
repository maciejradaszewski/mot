package uk.gov.dvsa.domain.model;

import uk.gov.dvsa.helper.ContactDetailsHelper;

public class Address {
    private String line1;
    private String line2;
    private String line3;
    private String town;
    private String county;
    private String postcode;


    public Address() {
        line1 = ContactDetailsHelper.getAddressLine1();
        line2 = ContactDetailsHelper.getAddressLine2();
        line3 = ContactDetailsHelper.getAddressLine3();

        town = ContactDetailsHelper.getCity();
        county = ContactDetailsHelper.getCity();
        postcode = ContactDetailsHelper.getPostCode();
    }

    public String getLine1() {
        return line1 != null ? line1 : "";
    }

    public String getLine2() {
        return line2 != null ? line2 : "";
    }

    public String getLine3() {
        return line3 != null ? line3 : "";
    }

    public String getTown() {
        return town != null ? town : "";
    }

    public String getCounty() {
        return county != null ? county : "";
    }

    public String getPostcode() {
        return postcode != null ? postcode : "";
    }

    public String getShortAddress() {
        return getLine1() + ", " + getTown();
    }

    public String getAddress() {
        return getLine1() + ", " + getLine2() + ", " + getTown() + ", " + getPostcode();
    }
}
