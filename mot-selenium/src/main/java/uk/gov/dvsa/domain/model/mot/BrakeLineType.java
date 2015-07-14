package uk.gov.dvsa.domain.model.mot;

public enum BrakeLineType {
    DUAL(true),
    SINGLE(true);

    private boolean select;

    BrakeLineType(boolean select) {
        this.select = select;
    }

    public boolean select() {
        return select;
    }
}
