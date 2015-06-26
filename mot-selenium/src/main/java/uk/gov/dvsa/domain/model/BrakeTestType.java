package uk.gov.dvsa.domain.model;

public enum BrakeTestType {
    ROLLER("Roller", "ROLLR"),
    PLATE("Plate", "PLATE"),
    DECELEROMETER("Decelerometer", "DECEL"),
    GRADIENT("Gradient", "GRADT");

    private final String dropDownName;
    private final String dropDownValue;

    BrakeTestType(String dropDownName, String dropDownValue) {
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
