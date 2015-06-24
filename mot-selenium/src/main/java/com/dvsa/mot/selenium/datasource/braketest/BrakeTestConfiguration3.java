package com.dvsa.mot.selenium.datasource.braketest;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeTestType;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestConfiguration3 {

    public final Map<BrakeTestConfigurationPageField, Object> entries;

    public BrakeTestConfiguration3(Map<BrakeTestConfigurationPageField, Object> entries) {
        super();
        this.entries = entries;
    }



    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_CASE1() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_NO_FIELDS_POPULATED() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_CASE2() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_REAR, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_INVALIDVSIWEIGHT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "****");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_INVALIDWEIGHT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_NOT_APLICABLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_CANCEL() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "250");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_RollerAndRoller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_RollerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_RollerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_PlateAndPlate() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_NOT_APLICABLE, Boolean.TRUE);
        //map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_DecelerometerAndRoller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_DecelerometerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass3_DecelerometerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.POSITION_SINGLE_WHEEL_FRONT, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_OPERATED_ON_ONE_WHEEL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.SERVICE_BREAK_ONE_CONTROL, Boolean.TRUE);
        return map;
    }

}
