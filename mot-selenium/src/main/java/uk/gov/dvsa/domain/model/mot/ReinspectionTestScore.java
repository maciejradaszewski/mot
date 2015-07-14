package uk.gov.dvsa.domain.model.mot;

public enum ReinspectionTestScore {
    DISREGARDED("Disregard", "1"),
    OVERRULED("0 - Overruled, marginally wrong", "2"),
    OBVIOUSLY_WRONG("5 - Obviously wrong", "3"),
    SIGNIFICANTLY_WRONG("10 - Significantly wrong", "4"),
    NO_DEFECT("20 - No defect", "5"),
    NOT_TESTABLE("20 - Not testable", "7"),
    OTHER_MISSED("30 - Exs. corr/wear/damage missed", "8"),
    RISK_OF_INJURY_MISSED("40 - Risk of injury missed", "9");

    private final String dropDownName;
    private final String dropDownValue;

    ReinspectionTestScore(String dropDownName, String dropDownValue) {
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
