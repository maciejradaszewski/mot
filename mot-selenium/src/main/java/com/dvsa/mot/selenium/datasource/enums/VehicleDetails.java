package com.dvsa.mot.selenium.datasource.enums;

public enum VehicleDetails {

    Kawasaki_ZX1400("No", "25 May 2013", "N/A", "25 May 2013", "N/A", "N/A", "2 Door Saloon"),
    Suzuki_Bandit("No", "N/A", "N/A", "N/A", "N/A", "N/A", "2 Door Saloon"),
    Indian_Scout("No", "N/A", "N/A", "N/A", "N/A", "N/A", "2 Door Saloon"),
    Piaggio_MP3("No", "N/A", "N/A", "N/A", "N/A", "N/A", "2 Door Saloon"),
    Mercedes_C300("No", "2 January 2010", "N/A", "2 January 2010", "N/A", "N/A", "2 Door Saloon"),
    BMW_Mini("No", "2 March 2010", "N/A", "2 March 2010", "N/A", "N/A", "2 Door Saloon"),
    VEHICLE_CLASS1_DAKOTA_1924("No","15 January 1925","N/A","15 January 1925","N/A","N/A", "2 Door Saloon");

    private final String declaredNew;
    private final String dateOfMake;
    private final String noSeats;
    private final String dateOfReg;
    private final String noSeatBelts;
    private final String dateOfSeatBelt;
    private final String modelType;

    private VehicleDetails(String declaredNew, String dateOfMake, String noSeats, String dateOfReg,
            String noSeatBelts, String dateOfSeatBelt, String modelType) {
        this.declaredNew = declaredNew;
        this.dateOfMake = dateOfMake;
        this.noSeats = noSeats;
        this.dateOfReg = dateOfReg;
        this.noSeatBelts = noSeatBelts;
        this.dateOfSeatBelt = dateOfSeatBelt;
        this.modelType = modelType;
    }

    public String getDeclaredNew() {
        return declaredNew;
    }

    public String getDateOfMake() {
        return dateOfMake;
    }

    public String getNoSeats() {
        return noSeats;
    }

    public String getDateOfReg() {
        return dateOfReg;
    }

    public String getNoSeatBelts() {
        return noSeatBelts;
    }

    public String getDateOfSeatBelt() {
        return dateOfSeatBelt;
    }

    public String getModelType() {
        return modelType;
    }
}
