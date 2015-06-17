package com.dvsa.mot.selenium.datasource.braketest;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeTestType;
import com.dvsa.mot.selenium.datasource.BrakeTestConstants.NumberOfAxles;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestConfiguration7 {

    public final Map<BrakeTestConfigurationPageField, Object> entries;

    public BrakeTestConfiguration7(Map<BrakeTestConfigurationPageField, Object> entries) {
        super();
        this.entries = entries;
    }



    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_Roller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "50000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> editBrakeTestConfigClass7_Roller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_CASE2() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_CASE3() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_CASE4() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_CASE4_3Axles() {
        Map<BrakeTestConfigurationPageField, Object> map = brakeTestConfigClass7_CASE4();
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        map.put(BrakeTestConfigurationPageField.PARKING_NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_CASE5() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "250");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_INVALIDWEIGHT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "****");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_WithoutWeightType() {
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

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_RollerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_RollerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_PlateAndPlate() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_PlateAndPlate_2Axles() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_PlateAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_PlateAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "2000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_DecelerometerAndRoller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_DGW, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "50000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_DecelerometerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass7_DecelerometerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        return map;
    }

}
