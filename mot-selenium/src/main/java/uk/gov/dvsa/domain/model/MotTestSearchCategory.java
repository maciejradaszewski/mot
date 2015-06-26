package uk.gov.dvsa.domain.model;

public enum MotTestSearchCategory {
    SITE_RECENT_TESTS("Site (recent tests)", "vts"),
    SITE_DATE_RANGE("Site (by date range)", "vtsDate"),
    TESTER("Tester (by date range)", "tester"),
    REGISTRATION("Registration (comparison available)", "vrm"),
    VIN("VIN/Chassis (comparison available)","vin");

    private final String dropDownName;
    private final String dropDownValue;

    MotTestSearchCategory(String dropDownName, String dropDownValue) {
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
