package uk.gov.dvsa.domain.model;

public enum AEAuthStatus {
    APPLIED("Applied"),
    APPROVED("Approved"),
    LAPSED("Lapsed"),
    REJECTED("Rejected"),
    RETRACTED("Retracted"),
    SURRENDERED("Surrendered"),
    WITHDRAWN("Withdrawn");

    private final String status;

    AEAuthStatus(String status) {
        this.status = status;
    }

    public String getText() {
        return status;
    }
}
