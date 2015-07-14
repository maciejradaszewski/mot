package uk.gov.dvsa.domain.model.mot;

public enum OdometerUnit {
    MILES("mi"),
    KILOMETRES("km");

    private final String value;

    OdometerUnit(String value) {
        this.value = value;
    }

    public String getValue(){
        return value;
    }
}
