package uk.gov.dvsa.domain.model.mot;

public enum Fault {
    HORN_CONTROL_MISSING("Horn control missing");

    private String description;

    Fault(String description) {
        this.description = description;
    }

    public String getDescription(){
        return description;
    }
}
