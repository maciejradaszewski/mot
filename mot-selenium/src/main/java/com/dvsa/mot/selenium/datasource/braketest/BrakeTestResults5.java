package com.dvsa.mot.selenium.datasource.braketest;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestResults5 {

    public static Map<BrakeTestResultsPageField, Object> passClass5() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakePassA() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "250");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakePassA_3Axles() {
        Map<BrakeTestResultsPageField, Object> map = serviceAndParkingBrakePassA();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE3_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE3_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_TWO_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_TWO_OFFSIDE, "250");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakePassB() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "175");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "175");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakePassC() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "500");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "500");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControls30Exactly() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "30");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "0");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "110");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "10");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "0");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakeFailA() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "150");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakeFailB() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "40");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "60");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "49");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "52");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "77");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "34");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceAndParkingBrakeFailC() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "199");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "111");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "101");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "80");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "173");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> rearAxleImbalance31Fail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "69");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "80");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "80");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> rearAxleImbalance30Pass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "75");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "80");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> axleBrakeImbalanceLessThan30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "145");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "180");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "180");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> axleBrakeImbalanceMoreThanEqualsNoLock() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "5");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "180");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "180");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> axleBrakeImbalanceMoreThanEquals30Locked() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "5");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "180");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "180");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> axleBrakeImbalanceExactly30NoLock() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "140");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "180");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "180");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> axleBrakeImbalanceExactly30Locked() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "140");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "180");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "180");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> rearAxleImbalance31FailAxle1() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "150");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "69");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "80");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "80");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> failDecelerometer_Gradient() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_EFFICIENCY, "49");
        map.put(BrakeTestResultsPageField.PARKING_BRAKE_EFFICIENCY_GRADIENT_PASS, true);
        return map;
    }

}
