package uk.gov.dvsa.domain.model;

public enum VehicleClassGroup {
    A("A"), B("B");

    public final String name;

    VehicleClassGroup(String name) {
        this.name = name;
    }
}
