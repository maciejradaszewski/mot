package com.dvsa.mot.selenium.datasource.braketest;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeTestType;
import com.dvsa.mot.selenium.datasource.BrakeTestConstants.NumberOfAxles;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestConfiguration5 {

    public final Map<BrakeTestConfigurationPageField, Object> entries;

    public BrakeTestConfiguration5(Map<BrakeTestConfigurationPageField, Object> entries) {
        super();
        this.entries = entries;
    }



    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_Roller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_Roller_3Axles() {
        Map<BrakeTestConfigurationPageField, Object> map = brakeTestConfigClass5_Roller();
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        map.put(BrakeTestConfigurationPageField.PARKING_NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> editBrakeTestConfigClass5_Roller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> editBrakeTestConfigClass5_Roller_3Axles() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        map.put(BrakeTestConfigurationPageField.PARKING_NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_CASE2() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_CALCULATED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_CASE3() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_CALCULATED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_INVALIDWEIGHT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "****");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_WEIGHTTYPE_NOTSELECTED() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_RollerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_RollerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW_MAM, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_DecelerometerAndRoller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_CALCULATED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_DecelerometerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass5_DecelerometerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        return map;
    }

}
