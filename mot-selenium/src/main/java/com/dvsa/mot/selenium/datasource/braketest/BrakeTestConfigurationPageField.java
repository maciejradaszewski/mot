package com.dvsa.mot.selenium.datasource.braketest;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.FieldType;

public enum BrakeTestConfigurationPageField {
    //Results Page
    //SERVICE BREAK
    BRAKE_TEST_TYPE("brakeTestType", FieldType.Dropdown), //Class 1
    SERVICE_BRAKE_TEST_TYPE("serviceBrake1TestType", FieldType.Dropdown), //Class 3, 4, 5, 7
    SERVICE_BREAK_ONE_CONTROL("numberOfServiceBrakeControlsOne", FieldType.Radiobutton), //Class 3
    SERVICE_BREAK_TWO_CONTROLS("numberOfServiceBrakeControlsTwo", FieldType.Radiobutton), //Class 3
    //PARKING BREAK
    PARKING_BRAKE_TEST_TYPE("parkingBrakeTestType", FieldType.Dropdown), //Class 3, 4, 5, 7
    PARKING_BRAKE_OPERATED_ON_ONE_WHEEL("parkingBrakeOperatedOnOne",
            FieldType.Radiobutton), //Class 3
    PARKING_BRAKE_OPERATED_ON_TWO_WHEELS("parkingBrakeOperatedOnTwo",
            FieldType.Radiobutton), //Class 3
    //VEHICLE WEIGHT
    MACHINE_WEIGHT_FRONT("vehicleWeightFront", FieldType.Input), //Class 1
    MACHINE_WEIGHT_REAR("vehicleWeightRear", FieldType.Input), //Class 1
    RIDER_WEIGHT("riderWeight", FieldType.Input), //Class 1
    VEHICLE_WEIGHT_VSI("weightType-VSI", FieldType.Radiobutton), //Class 3, 4
    VEHICLE_WEIGHT_PRESENTED("weightType-KERB", FieldType.Radiobutton), //Class 3, 4, 7
    VEHICLE_WEIGHT_NOT_APLICABLE("weightType-NA", FieldType.Radiobutton), //Class 3, 4
    VEHICLE_WEIGHT_DGW("weightType-DGW", FieldType.Radiobutton), //Class 7
    VEHICLE_WEIGHT_DGW_MAM("weightType-DGWM", FieldType.Radiobutton), //Class 5
    VEHICLE_WEIGHT_CALCULATED("weightType-CALC", FieldType.Radiobutton), //Class 5
    VEHICLE_WEIGHT("vehicleWeight", FieldType.Input), //Class 4, 5, 7
    WEIGHT_IS_UNLADEN("weightIsUnladen", FieldType.Checkbox), //Class 7
    //SIDECAR
    IS_THERE_SIDECAR_NO("isSidecarAttachedNo", FieldType.Radiobutton), //Class 1
    IS_THERE_SIDECAR_YES("isSidecarAttachedYes", FieldType.Radiobutton), //Class 1
    SIDECAR_WEIGHT("sidecarWeight", FieldType.Input),
    //BRAKE LINE TYPE
    BREAK_LINE_TYPE_DUAL("brakeLineTypeDual", FieldType.Radiobutton), // Class 3, 4, 5
    BREAK_LINE_TYPE_SINGLE("brakeLineTypeSingle", FieldType.Radiobutton), // Class 3, 4, 5
    //WHEEL
    POSITION_SINGLE_WHEEL_FRONT("positionOfSingleWheelFront", FieldType.Radiobutton), // Class 3
    POSITION_SINGLE_WHEEL_REAR("positionOfSingleWheelRear", FieldType.Radiobutton),   // Class 3
    //AXLES
    NUMBER_OF_AXLES("numberOfAxles", FieldType.Dropdown), // Class 4, 5, 7
    //PARKING AXLES
    PARKING_NUMBER_OF_AXLES("parkingBrakeNumberOfAxles", FieldType.Dropdown); //Class 4, 5, 7

    private final String id;
    private final FieldType fieldType;


    private BrakeTestConfigurationPageField(String id, FieldType fieldType) {
        this.id = id;
        this.fieldType = fieldType;
    }

    public String getId() {
        return id;
    }

    public FieldType getFieldType() {
        return fieldType;
    }
}
