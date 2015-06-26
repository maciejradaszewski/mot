package uk.gov.dvsa.domain.model;

public enum CancelTestReason {
    ACCIDENT_OR_ILLNESS("Accident or illness of tester", "need fitness training"),
    ABORTED_BY_VE("Aborted by VE", "says who?"),
    VEHICLE_REGISTERED_IN_ERROR("Vehicle registered in error", "negligence"),
    TEST_EQUIPMENT_ISSUE("Test equipment issue", "lack of budget"),
    VTS_INCIDENT("VTS incident", "No lunch Break"),
    INCORRECT_LOCATION("Incorrect location","Google Maps"),
    DANGEROUS_OR_CAUSE_DAMAGE("Inspection may be dangerous or cause damage", "Bomb Hazard");

    private final String reason;
    private final String description;

    private CancelTestReason(String reason, String description) {
        this.description = description;
        this.reason = reason;
    }

    public String getDescription() {
        return this.description;
    }

    public String getReason() {
        return reason;
    }
}
