package uk.gov.dvsa.domain.model.vehicle;

public enum VehicleWeightType {
    BRAKE_TEST_WEIGHT(true, "1000"),
    PRESENTED_WEIGHT(true, "2000");

    private final boolean selected;
    private final String vehicleWeight;

    VehicleWeightType(boolean select, String vehicleWeight) {
        this.selected = select;
        this.vehicleWeight = vehicleWeight;
    }

    public boolean select() {
        return selected;
    }

    public String getVehicleWeight() {
        return vehicleWeight;
    }
}
