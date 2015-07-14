package uk.gov.dvsa.domain.model.mot;

public enum NumberOfAxles {
    TWO_AXLES("2 axles", "2"),
    THREE_AXLES("3 axles", "3");

    private final String dropDownText;
    private final String dropDownValue;

    NumberOfAxles(String dropDownText, String dropDownValue) {
        this.dropDownText = dropDownText;
        this.dropDownValue = dropDownValue;
    }

    public String getText() {
        return dropDownText;
    }

    public String getValue() {
        return dropDownValue;
    }
}
