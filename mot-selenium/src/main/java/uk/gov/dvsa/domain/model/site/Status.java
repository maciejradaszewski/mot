package uk.gov.dvsa.domain.model.site;

public enum Status {
    APPROVED("Approved"),
    APPLIED("Applied"),
    RETRACTED("Retracted"),
    REJECTED("Rejected"),
    LAPSED("Lapsed"),
    EXTINCT("Extinct");

    private final String status;

    Status(String status) {
        this.status = status;
    }

    public String getText() {
        return status;
    }
}
