package uk.gov.dvsa.domain.model.mot;

public enum ReinspectionScoreCategory {
    NOT_APPLICABLE("Not applicable", "1"),
    IMMEDIATE("Immediate", "2"),
    DELAYED("Delayed", "3"),
    INSPECTION_NOTICE("Inspection notice", "4");

    private final String dropDownName;
    private final String dropDownValue;

    ReinspectionScoreCategory(String dropDownName, String dropDownValue) {
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
