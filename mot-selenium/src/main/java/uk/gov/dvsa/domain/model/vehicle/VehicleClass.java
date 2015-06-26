package uk.gov.dvsa.domain.model.vehicle;

public enum VehicleClass {
    one("1"), two("2"), three("3"), four("4"), five("5"), seven("7");

    private final String vehicleClass;

    private VehicleClass(String id) {
        this.vehicleClass = id;
    }

    public String getId() {
        return this.vehicleClass;
    }

    public static VehicleClass getRandomClass(){
        return values()[(int) (Math.random() * values().length)];
    }
}
