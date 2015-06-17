package com.dvsa.mot.selenium.datasource.braketest;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeTestType;
import com.dvsa.mot.selenium.datasource.BrakeTestConstants.NumberOfAxles;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestConfiguration4 {

    public final Map<BrakeTestConfigurationPageField, Object> entries;

    public BrakeTestConfiguration4(Map<BrakeTestConfigurationPageField, Object> entries) {
        super();
        this.entries = entries;
    }



    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_Roller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_NO_FIELDS_POPULATED() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_Roller3Axles() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        map.put(BrakeTestConfigurationPageField.PARKING_NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE1() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "500");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE2() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE3() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_NOT_APLICABLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE4() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE4_2Axles() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE5() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_CASE5_2_Axles() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_INVALIDWEIGHT() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "****");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_SINGLE, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Three.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_RollerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_RollerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_PlateAndPlate() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_PlateAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_PlateAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Plate);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_DecelerometerAndRoller() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1520");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_DecelerometerAndDecelerometer() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_DecelerometerAndGradient() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE,
                BrakeTestType.Decelerometer);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Gradient);
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        // VehicleType.Passenger
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_POST2010_Passenger() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> brakeTestConfigClass4_POST2010_Goods() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_VSI, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        map.put(BrakeTestConfigurationPageField.BREAK_LINE_TYPE_DUAL, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.NUMBER_OF_AXLES,
                NumberOfAxles.Two.toString().toLowerCase());
        return map;
    }

    // ENFORCEMENT TESTS
    public static Map<BrakeTestConfigurationPageField, Object> enforcement_CASE1() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        return map;
    }

    public static Map<BrakeTestConfigurationPageField, Object> enforcement_CASE2() {
        Map<BrakeTestConfigurationPageField, Object> map =
                new LinkedHashMap<BrakeTestConfigurationPageField, Object>();
        map.put(BrakeTestConfigurationPageField.SERVICE_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.PARKING_BRAKE_TEST_TYPE, BrakeTestType.Roller);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT_PRESENTED, Boolean.TRUE);
        map.put(BrakeTestConfigurationPageField.VEHICLE_WEIGHT, "1000");
        return map;
    }

}
