package com.dvsa.mot.selenium.datasource.braketest;

import java.util.HashMap;
import java.util.Map;

public class BrakeTestSummary3 {
    private static String PASS = "pass";
    private static String FAIL = "fail";
    private static String NOT_PERFORMED = "not performed";

    private static Map<String, String> fillMap(String serviceBrakeEfficiency,
            String serviceBrakeLock, String serviceBrakeResult, String imbalanceAxle1,
            String imbalanceAxle2, String imbalanceParking, String imbalanceResult,
            String parkingBrakeEfficiency, String parkingBrakeLock, String parkingBrakeResult) {
        Map<String, String> m = new HashMap<String, String>();
        if (serviceBrakeEfficiency != null)
            m.put(BrakeTestSummaryPageField.SERVICE_BRAKE_EFFICIENCY.getId(),
                    serviceBrakeEfficiency);
        if (serviceBrakeLock != null)
            m.put(BrakeTestSummaryPageField.SERVICE_BRAKE_LOCK.getId(), serviceBrakeLock);
        if (serviceBrakeResult != null)
            m.put(BrakeTestSummaryPageField.SERVICE_BRAKE_RESULT.getId(), serviceBrakeResult);
        if (imbalanceAxle1 != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_AXLE1.getId(), imbalanceAxle1);
        if (imbalanceAxle2 != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_AXLE2.getId(), imbalanceAxle2);
        if (imbalanceParking != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_PARKING_BRAKE.getId(), imbalanceParking);
        if (imbalanceResult != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_RESULT.getId(), imbalanceResult);
        if (parkingBrakeEfficiency != null)
            m.put(BrakeTestSummaryPageField.PARKING_BRAKE_EFFICIENCY.getId(),
                    parkingBrakeEfficiency);
        if (parkingBrakeLock != null)
            m.put(BrakeTestSummaryPageField.PARKING_BRAKE_LOCK.getId(), parkingBrakeLock);
        if (parkingBrakeResult != null)
            m.put(BrakeTestSummaryPageField.PARKING_BRAKE_RESULT.getId(), parkingBrakeResult);
        return m;
    }

    public static final Map<String, String> vehicleUsedBefore1968Pass40Results =
            fillMap("40", "0", PASS, null, "0", null, PASS, "14", "0", FAIL);
    public static final Map<String, String> vehicleUsedBefore1968PassAbove40Results =
            fillMap("48", "0", PASS, null, "0", null, PASS, "16", "0", PASS);
    public static final Map<String, String> vehicleUsedBefore1968FailBelow40Results =
            fillMap("39", "0", FAIL, null, "0", null, PASS, "13", "0", FAIL);
    public static final Map<String, String> passVehicleUsedAfter1968PassAbove50Results =
            fillMap("60", "0", PASS, null, "0", null, PASS, "30", "0", PASS);
    public static final Map<String, String> vehicleUsedAfter1968FailBelow50Results =
            fillMap("48", "0", FAIL, null, "0", null, PASS, "18", "0", PASS);
    public static final Map<String, String> passVehicle2SBPassControlAbove25Results =
            fillMap("45", "0", PASS, "-", null, null, NOT_PERFORMED, "80", "0", PASS);
    public static final Map<String, String> failVehicle2SBFailEqualAbove30Results =
            fillMap("24", "0", FAIL, "-", null, null, NOT_PERFORMED, "18", "0", PASS);
    public static final Map<String, String> failVehicle2SBFailControlsBelow30Results =
            fillMap("24", "0", FAIL, "-", null, null, NOT_PERFORMED, "16", "0", PASS);
    public static final Map<String, String> passSingleWheelFrontResults =
            fillMap("140", "0", PASS, null, "0", null, PASS, "50", "0", PASS);
    public static final Map<String, String> passSingleWheelFrontLocksOnResults =
            fillMap("140", "100", PASS, null, "0", null, PASS, "50", "100", PASS);
    public static final Map<String, String> passSingleWheelRearResults =
            fillMap("140", "0", PASS, "0", null, null, PASS, "50", "0", PASS);
    public static final Map<String, String> passDecelerometer_Decelerometer =
            fillMap("50", "-", PASS, null, null, null, NOT_PERFORMED, "16", "-", PASS);

}



