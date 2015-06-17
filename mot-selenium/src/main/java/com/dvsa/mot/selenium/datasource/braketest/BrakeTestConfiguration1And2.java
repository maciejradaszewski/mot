package com.dvsa.mot.selenium.datasource.braketest;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeTestType;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestConfiguration1And2 {

    public final Map<BrakeTestConfigurationPageField, Object> entries;

    public BrakeTestConfiguration1And2(Map<BrakeTestConfigurationPageField, Object> entries) {
        super();
        this.entries = entries;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_CASE1() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "180");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "130");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "90");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_CASE2() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "170");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "120");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "60");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_CASE3() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "100");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "100");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "85");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_INVALIDWEIGHT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "******");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "250");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "80");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_INVALID_WEIGHT_REAR() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "120");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "////");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "80");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_INVALID_WEIGHT_RIDER() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "120");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "110");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "./././");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_WITH_SIDECAR() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "120");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "110");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "120");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_YES, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SIDECAR_WEIGHT, "175");
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_PLATE() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "120");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "110");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "120");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_DECELEROMETER() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Decelerometer);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_FLOOR() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Floor);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "120");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "110");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "120");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_NO, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_FLOOR_INVALID_WEIGHTS() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Floor);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "&&&&");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "*$&%");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "(*&£$");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_YES, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SIDECAR_WEIGHT, "*£&$");
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_NO_FIELDS_POPULATED() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Floor);
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_FRONT, "");
        map.put(BrakeTestConfigurationPageField.MACHINE_WEIGHT_REAR, "");
        map.put(BrakeTestConfigurationPageField.RIDER_WEIGHT, "");
        map.put(BrakeTestConfigurationPageField.IS_THERE_SIDECAR_YES, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SIDECAR_WEIGHT, "");
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClasses1And2_GRADIENT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        return map;
    }

}
