package uk.gov.dvsa.domain.model.vehicle;

import java.util.Map;

public class Vehicle {

    private Map<String, String> vehicleData;
    private String registrationNumber;
    private String vehicleId;
    private String vin;
    private String make;
    private String makeOther;
    private String model;
    private String modelOther;
    private String makeModel;
    private String colour;
    private String secondaryColour;
    private String dateOfFirstUse;
    private String fuelType;
    private String testClass;
    private String countryOfRegistration;
    private String cylinderCapacity;
    private String transmissionType;
    private String bodyType;
    private String weight;

    public Vehicle(Map<String, String> vehicleData, String vehicleId) {
        this.vehicleData = vehicleData;
        this.vehicleId = vehicleId;
    }

    public String getRegistrationNumber() {
        return vehicleData.get("registrationNumber");
    }

    public String getVin() {
        return vehicleData.get("vin");
    }

    public String getMake() {
        return vehicleData.get("make");
    }

    public String getMakeOther() {
        return vehicleData.get("makeOther");
    }

    public String getModel() {
        return vehicleData.get("model");
    }

    public String getModelOther() {
        return vehicleData.get("modelOther");
    }

    public String getMakeModel() { return vehicleData.get("makeName")+" "+vehicleData.get("modelName"); }

    public String getColour() {
        return vehicleData.get("colour");
    }

    public String getSecondaryColour() {
        return vehicleData.get("secondaryColour");
    }

    public String getDateOfFirstUse() {
        return vehicleData.get("dateOfFirstUse");
    }

    public String getFuelType() {
        return vehicleData.get("fuelType");
    }

    public String getTestClass() {
        return vehicleData.get("testClass");
    }

    public String getCountryOfRegistration() {
        return vehicleData.get("countryOfRegistration");
    }

    public String getCylinderCapacity() {
        return vehicleData.get("cylinderCapacity");
    }

    public String getTransmissionType() {
        return vehicleData.get("transmissionType");
    }

    public String getBodyType() {
        return vehicleData.get("bodyType");
    }

    public String getVehicleId() {
        return vehicleId;
    }

    public String getWeight() {
        return vehicleData.get("weight");
    }
}
