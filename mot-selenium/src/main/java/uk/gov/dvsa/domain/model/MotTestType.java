package uk.gov.dvsa.domain.model;

public enum MotTestType {
    TARGETED_REINSPECTION("Targeted Reinspection", "ER"),
    MOT_COMPLIANCE_SURVEY("MOT Compliance Survey", "EC"),
    INVERTED_APPEAL("Inverted Appeal", "EI"),
    STATUTORY_APPEAL("Statutory Appeal", "ES");

    private final String dropDownName;
    private final String dropDownValue;

    MotTestType(String dropDownName, String dropDownValue) {
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
