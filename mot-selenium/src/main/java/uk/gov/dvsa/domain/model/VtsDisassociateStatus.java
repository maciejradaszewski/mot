package uk.gov.dvsa.domain.model;

public enum VtsDisassociateStatus {
    SURRENDERED("Surrendered", "SR"),
    PLATE("Withdrawn", "WD");

    private final String dropDownName;
    private final String dropDownValue;

    VtsDisassociateStatus(String dropDownName, String dropDownValue) {
        this.dropDownName = dropDownName;
        this.dropDownValue = dropDownValue;
    }

    public String getText() {
        return dropDownName;
    }

    public String getValue() {
        return dropDownValue;
    }
}
