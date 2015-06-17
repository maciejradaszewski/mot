package com.dvsa.mot.selenium.datasource;

import java.util.Date;

public class DvlaVehicle {

    public static final DvlaVehicle DVLA_VEHICLE_WITH_VALID_MAKE_AND_MODEL =
            new DvlaVehicle("29F29", "10D0E");

    private String registration;
    private String registrationValidationCharacter;
    private String vin;
    private String makeCode;
    private String modelCode;
    private String makeInFull;
    private String colour1Code;
    private String colour2Code;
    private String propulsionCode;
    private Integer designedGrossWeight;
    private Integer unladenWeight;
    private String engineNumber;
    private Integer engineCapacity;
    private Integer seatingCapacity;
    private Date manufactureDate;
    private Date firstRegistrationDate;
    private Boolean isSeriouslyDamaged;
    private String recentV5DocumentNumber;
    private Boolean isVehicleNewAtFirstRegistration;
    private String bodyTypeCode;
    private String wheelPlanCode;
    private String svaEmissionStandard;
    private String ctRelatedMark;
    private Integer vehicleId;
    private Integer dvlaVehicleId;
    private String euClassification;
    private Integer massInServiceWeight;


    public DvlaVehicle(String makeCode, String modelCode) {
        this.makeCode = makeCode;
        this.modelCode = modelCode;
    }


    public String getRegistration() {
        return registration;
    }

    public void setRegistration(String registration) {
        this.registration = registration;
    }

    public String getRegistrationValidationCharacter() {
        return registrationValidationCharacter;
    }

    public void setRegistrationValidationCharacter(String registrationValidationCharacter) {
        this.registrationValidationCharacter = registrationValidationCharacter;
    }

    public String getVin() {
        return vin;
    }

    public void setVin(String vin) {
        this.vin = vin;
    }

    public String getMakeCode() {
        return makeCode;
    }

    public void setMakeCode(String makeCode) {
        this.makeCode = makeCode;
    }

    public String getModelCode() {
        return modelCode;
    }

    public void setModelCode(String modelCode) {
        this.modelCode = modelCode;
    }

    public String getMakeInFull() {
        return makeInFull;
    }

    public void setMakeInFull(String makeInFull) {
        this.makeInFull = makeInFull;
    }

    public String getColour1Code() {
        return colour1Code;
    }

    public void setColour1Code(String colour1Code) {
        this.colour1Code = colour1Code;
    }

    public String getColour2Code() {
        return colour2Code;
    }

    public void setColour2Code(String colour2Code) {
        this.colour2Code = colour2Code;
    }

    public String getPropulsionCode() {
        return propulsionCode;
    }

    public void setPropulsionCode(String propulsionCode) {
        this.propulsionCode = propulsionCode;
    }

    public Integer getDesignedGrossWeight() {
        return designedGrossWeight;
    }

    public void setDesignedGrossWeight(Integer designedGrossWeight) {
        this.designedGrossWeight = designedGrossWeight;
    }

    public Integer getUnladenWeight() {
        return unladenWeight;
    }

    public void setUnladenWeight(Integer unladenWeight) {
        this.unladenWeight = unladenWeight;
    }

    public String getEngineNumber() {
        return engineNumber;
    }

    public void setEngineNumber(String engineNumber) {
        this.engineNumber = engineNumber;
    }

    public Integer getEngineCapacity() {
        return engineCapacity;
    }

    public void setEngineCapacity(Integer engineCapacity) {
        this.engineCapacity = engineCapacity;
    }

    public Integer getSeatingCapacity() {
        return seatingCapacity;
    }

    public void setSeatingCapacity(Integer seatingCapacity) {
        this.seatingCapacity = seatingCapacity;
    }

    public Date getManufactureDate() {
        return manufactureDate;
    }

    public void setManufactureDate(Date manufactureDate) {
        this.manufactureDate = manufactureDate;
    }

    public Date getFirstRegistrationDate() {
        return firstRegistrationDate;
    }

    public void setFirstRegistrationDate(Date firstRegistrationDate) {
        this.firstRegistrationDate = firstRegistrationDate;
    }

    public Boolean isSeriouslyDamaged() {
        return isSeriouslyDamaged;
    }

    public void setSeriouslyDamaged(Boolean isSeriouslyDamaged) {
        this.isSeriouslyDamaged = isSeriouslyDamaged;
    }

    public String getRecentV5DocumentNumber() {
        return recentV5DocumentNumber;
    }

    public void setRecentV5DocumentNumber(String recentV5DocumentNumber) {
        this.recentV5DocumentNumber = recentV5DocumentNumber;
    }

    public Boolean isVehicleNewAtFirstRegistration() {
        return isVehicleNewAtFirstRegistration;
    }

    public void setVehicleNewAtFirstRegistration(Boolean isVehicleNewAtFirstRegistration) {
        this.isVehicleNewAtFirstRegistration = isVehicleNewAtFirstRegistration;
    }

    public String getBodyTypeCode() {
        return bodyTypeCode;
    }

    public void setBodyTypeCode(String bodyTypeCode) {
        this.bodyTypeCode = bodyTypeCode;
    }

    public String getWheelPlanCode() {
        return wheelPlanCode;
    }

    public void setWheelPlanCode(String wheelPlanCode) {
        this.wheelPlanCode = wheelPlanCode;
    }

    public String getSvaEmissionStandard() {
        return svaEmissionStandard;
    }

    public void setSvaEmissionStandard(String svaEmissionStandard) {
        this.svaEmissionStandard = svaEmissionStandard;
    }

    public String getCtRelatedMark() {
        return ctRelatedMark;
    }

    public void setCtRelatedMark(String ctRelatedMark) {
        this.ctRelatedMark = ctRelatedMark;
    }

    public Integer getVehicleId() {
        return vehicleId;
    }

    public void setVehicleId(Integer vehicleId) {
        this.vehicleId = vehicleId;
    }

    public Integer getDvlaVehicleId() {
        return dvlaVehicleId;
    }

    public void setDvlaVehicleId(Integer dvlaVehicleId) {
        this.dvlaVehicleId = dvlaVehicleId;
    }

    public String getEuClassification() {
        return euClassification;
    }

    public void setEuClassification(String euClassification) {
        this.euClassification = euClassification;
    }

    public Integer getMassInServiceWeight() {
        return massInServiceWeight;
    }

    public void setMassInServiceWeight(Integer massInServiceWeight) {
        this.massInServiceWeight = massInServiceWeight;
    }

}
