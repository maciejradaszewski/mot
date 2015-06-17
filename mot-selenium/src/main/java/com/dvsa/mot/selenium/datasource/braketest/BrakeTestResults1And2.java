package com.dvsa.mot.selenium.datasource.braketest;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestResults1And2 {


    public static Map<BrakeTestResultsPageField, Object> serviceBrakeControlPassA() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "145");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "90");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "190");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "85");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceBrakeControlPassB() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "185");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "110");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "197");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "130");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceBrakeControlPassC() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "200");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "200");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "100");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "100");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControls30Exactly() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "30");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "90");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "110");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "10");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControlsOver30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "45");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "115");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "125");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "25");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControlOver30AndOtherUnder30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "25");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "105");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "80");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "20");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> failBothControlsUnder30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "20");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "46");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "30");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "8");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControlEquals30failControlEqualsLess() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "25");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "95");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "95");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "4");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControlOver30AndFailOtherUnder30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "35");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "100");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "90");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "9");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> failBothControlsBetween25And30() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "15");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "87");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "79");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "17");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> failBothControlsUnder25() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "22");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "52");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "30");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "8");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> brakeControlOneFail() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "20");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "10");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "10");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "1");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> passControlsOver30WithSidecar() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "30");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "85");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_SIDECAR, "18");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "175");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "90");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_SIDECAR, "30");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> failControlsUnder30WithSidecar() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "20");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "41");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_SIDECAR, "9");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "37");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "15");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_SIDECAR, "5");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceBrakeControlFailA() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "19");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "95");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "9");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "90");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceBrakeControlFailB() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "45");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "21");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "66");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "19");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> serviceBrakeControlFailC() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT, "17");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR, "91");
        map.put(BrakeTestResultsPageField.CONTROL_ONE_REAR_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT, "90");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_FRONT_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR, "10");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_REAR_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientAboveAbove() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_ABOVE_30, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_ABOVE_30, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientAboveBetween() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_ABOVE_30, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_BETWEEN_30_AND_25, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientAboveBelow() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_ABOVE_30, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_BELOW_25, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientBetweenAbove() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_BETWEEN_30_AND_25, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_ABOVE_30, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientBetweenBetween() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_BETWEEN_30_AND_25, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_BETWEEN_30_AND_25, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientBetweenBelow() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_BETWEEN_30_AND_25, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_BELOW_25, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientBelowAbove() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_BELOW_25, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_ABOVE_30, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientBelowBetween() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_BELOW_25, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_BETWEEN_30_AND_25, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> gradientBelowBelow() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_BELOW_25, Boolean.TRUE);
        map.put(BrakeTestResultsPageField.CONTROL_TWO_BELOW_25, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> decelerometerAboveAbove() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_EFFICIENCY, "30");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_EFFICIENCY, "25");
        return map;
    }

    public static Map<BrakeTestResultsPageField, Object> decelerometerBelowBelow() {
        Map<BrakeTestResultsPageField, Object> map =
                new LinkedHashMap<BrakeTestResultsPageField, Object>();
        map.put(BrakeTestResultsPageField.CONTROL_ONE_EFFICIENCY, "28");
        map.put(BrakeTestResultsPageField.CONTROL_TWO_EFFICIENCY, "26");
        return map;
    }
}
