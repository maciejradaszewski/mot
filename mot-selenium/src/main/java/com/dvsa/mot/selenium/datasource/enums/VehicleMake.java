package com.dvsa.mot.selenium.datasource.enums;

public enum VehicleMake {
    BMW("BMW", "18811"),
    Citroen("CITROEN", "18821"),
    Ford("FORD", "18837"),
    Fuso("FUSO", "1883A"),
    HarleyDavidson("HARLEY DAVIDSON", "18840"),
    Hyundai("HYUNDAI", "1884D"),
    Indian("INDIAN", "1884E"),
    Jaguar("JAGUAR", "18855"),
    Kawasaki("KAWASAKI", "1885F"),
    MercedesBenz("MERCEDES", "1887E"),
    Peugeot("PEUGEOT", "1889E"),
    Piaggio("PIAGGIO", "188A0"),
    Porsche("PORSCHE", "188A2"),
    Renault("RENAULT", "188A9"),
    Subaru("SUBARU", "188C0"),
    Suzuki("SUZUKI", "188C2"),
    Vauxhall("VAUXHALL", "188D2"),
    Volkswagen("VOLKSWAGEN", "188D5"),
    Other("Other","other");

    private final String vehicleMake;
    private final String vehicleID;

    private VehicleMake(String vehicleMake, String vehicleID) {
        this.vehicleMake = vehicleMake;
        this.vehicleID = vehicleID;
    }

    public String getVehicleMake() {
        return this.vehicleMake;
    }

    public String getVehicleID() {
        return this.vehicleID;
    }

}
