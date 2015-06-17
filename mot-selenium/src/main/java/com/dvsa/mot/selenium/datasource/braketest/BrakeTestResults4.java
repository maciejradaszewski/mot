package com.dvsa.mot.selenium.datasource.braketest;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestResults4 {

    public static Map<BrakeTestResultsPageField, Object> allPass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.FALSE);

        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> allPassPlatePlate() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> allPass_3Axles() {
        Map<BrakeTestResultsPageField, Object> map = allPass();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE3_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE3_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.PARKING_TWO_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_TWO_OFFSIDE, "200");

        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> allFail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "50");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "50");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "50");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> allFailPlatePlate() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "50");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "50");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "50");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> sBFailOnly() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "20");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> pBFailOnly() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "20");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "20");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceOnly() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceAxl1Only() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceAxl2Only() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> sBEdgePass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "180");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "180");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "180");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> sBEdgeFail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "50");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "40");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "50");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "40");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> pBEdgePass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "103");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> pBEdgeFail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "50");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "40");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceEdgeAxl1Pass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "173");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "218");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "150");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceEdgeAxl1Fail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "152");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "218");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "150");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceEdgeAxl2Pass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "173");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "218");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "150");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceEdgeAxl2Fail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "152");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "218");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "150");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> vehicleFail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "40");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "40");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "40");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "40");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "60");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "30");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> allPassAllLocks() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "50");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "50");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> allFailAllLocks() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "10");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "10");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "10");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "10");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "10");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "10");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> brakeTestEntry_CASE1() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "145");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "145");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "145");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "145");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> brakeTestEntry_CASE2() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "140");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "140");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "140");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> imbalanceInAxle1() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "90");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "150");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }


    public static Map<BrakeTestResultsPageField, Object> endToEndTest50ServiceBrake() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "63");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "63");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "63");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "63");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> sept2010Goods50Pass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "95");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "95");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.FALSE);

        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> sept2010Passenger58Pass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "105");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "175");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.FALSE);

        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> sept2010Passenger50Pass() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "125");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "125");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "125");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "125");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.FALSE);

        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE_LOCK, Boolean.FALSE);
        return map;
    }

    //ENFORCEMENT TESTS
    public static Map<BrakeTestResultsPageField, Object> enforcement_CASE1() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> enforcement_CASE2() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_NEARSIDE, "100");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE1_OFFSIDE, "200");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_NEARSIDE, "60");
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_AXLE2_OFFSIDE, "100");
        map.put(BrakeTestResultsPageField.PARKING_ONE_NEARSIDE, "50");
        map.put(BrakeTestResultsPageField.PARKING_ONE_OFFSIDE, "200");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> failDecelerometer_Decelerometer() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.SERVICE_BRAKE1_EFFICIENCY, "50");
        map.put(BrakeTestResultsPageField.PARKING_BRAKE_EFFICIENCY_DECELEROMETER, "15");
        return map;
    }
}
