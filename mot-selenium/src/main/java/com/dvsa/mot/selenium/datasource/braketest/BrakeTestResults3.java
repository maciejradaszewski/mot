package com.dvsa.mot.selenium.datasource.braketest;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestResults3 {

    public static Map<BrakeTestResultsPageField, Object> passVehicleUsedBefore1968Pass40() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "67");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "67");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "67");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "70");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passVehicleUsedBefore1968PassAbove40() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "89");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "77");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "77");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "84");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> vehicleUsedBefore1968FailBelow40() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "65");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "65");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "65");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "65");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passVehicleUsedAfter1968PassAbove50() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "150");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> vehicleUsedAfter1968FailBelow50() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "80");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "82");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "82");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "90");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> vehicle2SBPassControlAbove25() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "70");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "68");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "89");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "90");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> vehicle2SBFailEqualAbove30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "80");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "82");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "87");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "90");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> vehicle2SBFailControlsBelow30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "80");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "82");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "87");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "90");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passSingleWheelFront() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "250");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passSingleWheelFrontLocksOn() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "250");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE_LOCK, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passSingleWheelRear() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_SINGLE_WHEEL_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_SINGLE, "250");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passDecelerometer_Decelerometer() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_EFFICIENCY, "50");
        map.put(BrakeTestResultsPageField.PARKING_BRAKE_EFFICIENCY_DECELEROMETER, "16");
        return map;
    }
}
