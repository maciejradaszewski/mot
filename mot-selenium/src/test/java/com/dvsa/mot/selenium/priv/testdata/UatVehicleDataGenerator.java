package com.dvsa.mot.selenium.priv.testdata;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VehicleApi;
import com.dvsa.mot.selenium.framework.api.vehicle.IVehicleDataRandomizer;
import com.dvsa.mot.selenium.framework.api.vehicle.SequentialVehicleDataRandomizer;

import java.util.*;

import static com.dvsa.mot.selenium.datasource.Vehicle.*;
import static com.dvsa.mot.selenium.framework.api.MotTestApi.*;
import static com.google.common.collect.Iterators.cycle;

public class UatVehicleDataGenerator {

    private static final String VEH_DIFF_NO_HISTORY = "NH";
    private static final String VEH_DIFF_FULL_HISTORY_1 = "FH1";
    private static final String VEH_DIFF_FULL_HISTORY_2 = "FH2";
    private static final String VEH_DIFF_PARTAL_HISTORY_1 = "PH1";
    private static final String VEH_DIFF_PARTAL_HISTORY_2 = "PH2";

    private VehicleApi vehicleApi = new VehicleApi();
    private Map<String, IVehicleDataRandomizer> randomizerCache = new HashMap<>();
    private MotTestApi motTestApi = new MotTestApi();

    private static int getVehicleCount() {
        return Integer.valueOf(System.getProperty("uat.vehicle.data.count", "10"));
    }

    void genTestHistory(VtsDataCollector vtsDataCollector) {

        int vehicleCount = getVehicleCount();
        genVTRWithoutMotHistory(vehicleCount, VEH_DIFF_NO_HISTORY);
        genFullTestHistory1(vtsDataCollector, vehicleCount, VEH_DIFF_FULL_HISTORY_1);
        genFullTestHistory2(vtsDataCollector, vehicleCount, VEH_DIFF_FULL_HISTORY_2);
        genPartialTestHistory1(vtsDataCollector, vehicleCount, VEH_DIFF_PARTAL_HISTORY_1);
        genPartialTestHistory2(vtsDataCollector, vehicleCount, VEH_DIFF_PARTAL_HISTORY_2);
    }

    private void genVTRWithoutMotHistory(int vehicleCount, String diff) {
        genVTRPairs(VEHICLE_CLASS1_BALENO_2002, diff, vehicleCount);
        genVTRPairs(VEHICLE_CLASS2_CAPPUCCINO_2012, diff, vehicleCount);
        genVTRPairs(VEHICLE_CLASS3_HARLEY_DAVIDSON_1961, diff, vehicleCount);
        genVTRPairs(VEHICLE_CLASS4_ASTRA_2010, diff, vehicleCount);
        genVTRPairs(VEHICLE_CLASS5_STREETKA_1924, diff, vehicleCount);
        genVTRPairs(VEHICLE_CLASS7_MERCEDESBENZ_2005, diff, vehicleCount);
    }


    private Vehicle createVTR(Vehicle prototype, boolean before01092010, String diff) {
        String prefix =
                "UAT" + diff + "C" + prototype.vehicleClass.getId() + (before01092010 ? "B" : "A");
        IVehicleDataRandomizer randomizer = randomizerCache.get(prefix);
        if (randomizer == null) {
            randomizer = new SequentialVehicleDataRandomizer(prefix);
            randomizerCache.put(prefix, randomizer);
        }

        return vehicleApi.createVehicle(prototype, randomizer);
    }

    private List<Vehicle> genVTRPairs(Vehicle prototype, String prefix, int count) {
        List<Vehicle> vtrs = new ArrayList<>();
        for (int i = 0; i < count; i++) {
            for (int j = 0; j < 2; j++) {
                boolean isBefore01092010 = j == 1;
                vtrs.add(createVTR(prototype, isBefore01092010, prefix));
            }
        }
        return vtrs;
    }

    private void genFullTestHistory1ForVehicle(VtsDataCollector coll, Vehicle vehiclePrototype,
            int vehicleCount, Iterator<Integer> vtsIndices, String diff) {
        List<Vehicle> vehicles = genVTRPairs(vehiclePrototype, diff, vehicleCount);
        for (Vehicle v : vehicles) {
            genTest(coll, vtsIndices.next(), v,
                    new MotTestData(TestOutcome.PASSED, 1000, "2009-09-01"));
            genTest(coll, vtsIndices.next(), v,
                    new MotTestData(TestOutcome.PASSED, 2000, "2010-09-02"));
            genTest(coll, vtsIndices.next(), v,
                    new MotTestData(TestOutcome.PASSED, 3000, "2011-09-03"));
            genTest(coll, vtsIndices.next(), v,
                    new MotTestData(TestOutcome.PASSED, 4000, "2012-09-04"));
            genTest(coll, vtsIndices.next(), v,
                    new MotTestData(TestOutcome.PASSED, 5000, "2013-09-05"));
        }
    }

    private void genFullTestHistory1(VtsDataCollector coll, int vehicleCount, String diff) {

        genFullTestHistory1ForVehicle(coll, VEHICLE_CLASS1_BALENO_2002, vehicleCount,
                cycle(1, 4, 5, 6, 15), diff);
        genFullTestHistory1ForVehicle(coll, VEHICLE_CLASS2_CAPPUCCINO_2012, vehicleCount,
                cycle(16, 17, 32, 39, 4), diff);
        genFullTestHistory1ForVehicle(coll, VEHICLE_CLASS3_HARLEY_DAVIDSON_1961, vehicleCount,
                cycle(2, 4, 5, 7, 8), diff);
        genFullTestHistory1ForVehicle(coll, VEHICLE_CLASS4_ASTRA_2010, vehicleCount,
                cycle(2, 4, 5, 7, 8), diff);
        genFullTestHistory1ForVehicle(coll, VEHICLE_CLASS5_STREETKA_1924, vehicleCount,
                cycle(2, 4, 5, 7, 8), diff);
        genFullTestHistory1ForVehicle(coll, VEHICLE_CLASS7_MERCEDESBENZ_2005, vehicleCount,
                cycle(2, 4, 5, 7, 8), diff);
    }

    private void genHistoryItem(VtsDataCollector collector, List<Vehicle> vehicles,
            Integer vtsIndex, MotTestData motTestdata) {
        genHistoryItem(collector, vehicles, vtsIndex, motTestdata, null);
    }

    private void genHistoryItem(VtsDataCollector collector, List<Vehicle> vehicles,
            Integer vtsIndex, MotTestData motTestData, RetestData retestData) {
        for (Vehicle v : vehicles) {
            generateTest(collector, vtsIndex, v, motTestData, retestData);
        }
    }

    private void genPartialTestHistory1(VtsDataCollector coll, int vehicleCount, String diff) {
        // class 1 ------
        List<Vehicle> vehsC1 = genVTRPairs(VEHICLE_CLASS1_BALENO_2002, diff, vehicleCount);
        genHistoryItem(coll, vehsC1, 1, new MotTestData(TestOutcome.FAILED, 5000, "2013-09-05"),
                new RetestData(RetestOutcome.PASSED, 5001, "2013-09-05"));
        // class 2 ------
        List<Vehicle> vehsC2 = genVTRPairs(VEHICLE_CLASS2_CAPPUCCINO_2012, diff, vehicleCount);
        genHistoryItem(coll, vehsC2, 4, new MotTestData(TestOutcome.FAILED, 5000, "2013-09-05"),
                new RetestData(RetestOutcome.PASSED, 5001, "2013-09-05"));
        // class 3 ------
        List<Vehicle> vehsC3 = genVTRPairs(VEHICLE_CLASS3_HARLEY_DAVIDSON_1961, diff, vehicleCount);
        genHistoryItem(coll, vehsC3, 3, new MotTestData(TestOutcome.PRS, 5000, "2013-09-05"));
        // class 4 ------
        List<Vehicle> vehsC4 = genVTRPairs(VEHICLE_CLASS4_ASTRA_2010, diff, vehicleCount);
        genHistoryItem(coll, vehsC4, 4, new MotTestData(TestOutcome.PASSED, 1000, "2012-09-01"));
        genHistoryItem(coll, vehsC4, 4, new MotTestData(TestOutcome.PASSED, 2000, "2013-09-02"));
        // class 5 ------
        List<Vehicle> vehsC5 = genVTRPairs(VEHICLE_CLASS5_STREETKA_1924, diff, vehicleCount);
        genHistoryItem(coll, vehsC5, 5, new MotTestData(TestOutcome.PASSED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC5, 5, new MotTestData(TestOutcome.PASSED, 2000, "2010-09-02"));

        // class 7 ------
        List<Vehicle> vehsC7 = genVTRPairs(VEHICLE_CLASS7_MERCEDESBENZ_2005, diff, vehicleCount);
        genHistoryItem(coll, vehsC7, 7, new MotTestData(TestOutcome.PASSED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC7, 7, new MotTestData(TestOutcome.PASSED, 2000, "2010-09-02"));
    }


    private void genPartialTestHistory2(VtsDataCollector coll, int vehicleCount, String diff) {
        MotTestData motTestData = new MotTestData(TestOutcome.PASSED, 1000, "2013-09-05");
        // class 1 ------
        List<Vehicle> vehsC1 = genVTRPairs(VEHICLE_CLASS1_BALENO_2002, diff, vehicleCount);
        genHistoryItem(coll, vehsC1, 1, motTestData);
        // class 2 ------
        List<Vehicle> vehsC2 = genVTRPairs(VEHICLE_CLASS2_CAPPUCCINO_2012, diff, vehicleCount);
        genHistoryItem(coll, vehsC2, 4, motTestData);
        // class 3 ------
        List<Vehicle> vehsC3 = genVTRPairs(VEHICLE_CLASS3_HARLEY_DAVIDSON_1961, diff, vehicleCount);
        genHistoryItem(coll, vehsC3, 15, motTestData);
        // class 4 ------
        List<Vehicle> vehsC4 = genVTRPairs(VEHICLE_CLASS4_ASTRA_2010, diff, vehicleCount);
        genHistoryItem(coll, vehsC4, 18, motTestData);
        // class 5 ------
        List<Vehicle> vehsC5 = genVTRPairs(VEHICLE_CLASS5_STREETKA_1924, diff, vehicleCount);
        genHistoryItem(coll, vehsC5, 20, motTestData);
        // class 7 ------
        List<Vehicle> vehsC7 = genVTRPairs(VEHICLE_CLASS7_MERCEDESBENZ_2005, diff, vehicleCount);
        genHistoryItem(coll, vehsC7, 21, motTestData);
    }


    private void genFullTestHistory2(VtsDataCollector coll, int vehicleCount, String diff) {

        // class1 -------------------------
        List<Vehicle> vehsC1 = genVTRPairs(VEHICLE_CLASS1_BALENO_2002, diff, vehicleCount);

        genHistoryItem(coll, vehsC1, 1, new MotTestData(TestOutcome.FAILED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC1, 1, new MotTestData(TestOutcome.PASSED, 1001, "2009-09-01"));
        genHistoryItem(coll, vehsC1, 4, new MotTestData(TestOutcome.FAILED, 2000, "2010-09-02"));
        genHistoryItem(coll, vehsC1, 4, new MotTestData(TestOutcome.PASSED, 2001, "2010-09-02"));
        genHistoryItem(coll, vehsC1, 5, new MotTestData(TestOutcome.FAILED, 3000, "2011-09-03"));
        genHistoryItem(coll, vehsC1, 5, new MotTestData(TestOutcome.PASSED, 3001, "2011-09-03"));
        genHistoryItem(coll, vehsC1, 6, new MotTestData(TestOutcome.FAILED, 4000, "2012-09-04"));
        genHistoryItem(coll, vehsC1, 6, new MotTestData(TestOutcome.PASSED, 4001, "2012-09-04"));
        genHistoryItem(coll, vehsC1, 9, new MotTestData(TestOutcome.FAILED, 5000, "2013-09-05"),
                new RetestData(RetestOutcome.PASSED, 5001, "2013-09-05"));

        // class2 -------------------------
        List<Vehicle> vehsC2 = genVTRPairs(VEHICLE_CLASS2_CAPPUCCINO_2012, diff, vehicleCount);
        genHistoryItem(coll, vehsC2, 6, new MotTestData(TestOutcome.FAILED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC2, 6, new MotTestData(TestOutcome.PASSED, 1001, "2009-09-01"));
        genHistoryItem(coll, vehsC2, 9, new MotTestData(TestOutcome.FAILED, 2000, "2010-09-02"));
        genHistoryItem(coll, vehsC2, 9, new MotTestData(TestOutcome.PASSED, 2001, "2010-09-02"));
        genHistoryItem(coll, vehsC2, 10, new MotTestData(TestOutcome.FAILED, 3000, "2011-09-03"));
        genHistoryItem(coll, vehsC2, 10, new MotTestData(TestOutcome.PASSED, 3001, "2011-09-03"));
        genHistoryItem(coll, vehsC2, 11, new MotTestData(TestOutcome.FAILED, 4000, "2012-09-04"));
        genHistoryItem(coll, vehsC2, 11, new MotTestData(TestOutcome.PASSED, 4001, "2012-09-04"));
        genHistoryItem(coll, vehsC2, 12, new MotTestData(TestOutcome.FAILED, 5000, "2013-09-05"),
                new RetestData(RetestOutcome.PASSED, 5001, "2013-09-05"));

        // class3 -------------------------
        List<Vehicle> vehsC3 = genVTRPairs(VEHICLE_CLASS3_HARLEY_DAVIDSON_1961, diff, vehicleCount);

        genHistoryItem(coll, vehsC3, 2, new MotTestData(TestOutcome.FAILED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC3, 2, new MotTestData(TestOutcome.PASSED, 1001, "2009-09-01"));
        genHistoryItem(coll, vehsC3, 3, new MotTestData(TestOutcome.FAILED, 2000, "2010-09-02"));
        genHistoryItem(coll, vehsC3, 3, new MotTestData(TestOutcome.PASSED, 2001, "2010-09-02"));
        genHistoryItem(coll, vehsC3, 7, new MotTestData(TestOutcome.FAILED, 3000, "2011-09-03"));
        genHistoryItem(coll, vehsC3, 7, new MotTestData(TestOutcome.PASSED, 3001, "2011-09-03"));
        genHistoryItem(coll, vehsC3, 12, new MotTestData(TestOutcome.FAILED, 4000, "2012-09-04"));
        genHistoryItem(coll, vehsC3, 12, new MotTestData(TestOutcome.PASSED, 4001, "2012-09-04"));
        genHistoryItem(coll, vehsC3, 12, new MotTestData(TestOutcome.PRS, 5000, "2013-09-05"));

        // class4 -------------------------
        List<Vehicle> vehsC4 = genVTRPairs(VEHICLE_CLASS4_ASTRA_2010, diff, vehicleCount);

        genHistoryItem(coll, vehsC4, 15, new MotTestData(TestOutcome.FAILED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC4, 15, new MotTestData(TestOutcome.PASSED, 1001, "2009-09-01"));
        genHistoryItem(coll, vehsC4, 16, new MotTestData(TestOutcome.FAILED, 2000, "2010-09-02"));
        genHistoryItem(coll, vehsC4, 16, new MotTestData(TestOutcome.PASSED, 2001, "2010-09-02"));
        genHistoryItem(coll, vehsC4, 17, new MotTestData(TestOutcome.FAILED, 3000, "2011-09-03"));
        genHistoryItem(coll, vehsC4, 17, new MotTestData(TestOutcome.PASSED, 3001, "2011-09-03"));
        genHistoryItem(coll, vehsC4, 18, new MotTestData(TestOutcome.FAILED, 4000, "2012-09-04"));
        genHistoryItem(coll, vehsC4, 18, new MotTestData(TestOutcome.PASSED, 4001, "2012-09-04"));
        genHistoryItem(coll, vehsC4, 20, new MotTestData(TestOutcome.FAILED, 5000, "2013-09-05"),
                new RetestData(RetestOutcome.PASSED, 5001, "2013-09-05"));

        // class5 -------------------------
        List<Vehicle> vehsC5 = genVTRPairs(VEHICLE_CLASS5_STREETKA_1924, diff, vehicleCount);

        genHistoryItem(coll, vehsC5, 15, new MotTestData(TestOutcome.FAILED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC5, 15, new MotTestData(TestOutcome.PASSED, 1001, "2009-09-01"));
        genHistoryItem(coll, vehsC5, 16, new MotTestData(TestOutcome.FAILED, 2000, "2010-09-02"));
        genHistoryItem(coll, vehsC5, 16, new MotTestData(TestOutcome.PASSED, 2001, "2010-09-02"));
        genHistoryItem(coll, vehsC5, 17, new MotTestData(TestOutcome.FAILED, 3000, "2011-09-03"));
        genHistoryItem(coll, vehsC5, 17, new MotTestData(TestOutcome.PASSED, 3001, "2011-09-03"));
        genHistoryItem(coll, vehsC5, 18, new MotTestData(TestOutcome.FAILED, 4000, "2012-09-04"));
        genHistoryItem(coll, vehsC5, 18, new MotTestData(TestOutcome.PASSED, 4001, "2012-09-04"));
        genHistoryItem(coll, vehsC5, 20, new MotTestData(TestOutcome.PRS, 5000, "2013-09-05"));


        // class7 -------------------------
        List<Vehicle> vehsC7 = genVTRPairs(VEHICLE_CLASS7_MERCEDESBENZ_2005, diff, vehicleCount);
        genHistoryItem(coll, vehsC7, 15, new MotTestData(TestOutcome.FAILED, 1000, "2009-09-01"));
        genHistoryItem(coll, vehsC7, 15, new MotTestData(TestOutcome.PASSED, 1001, "2009-09-01"));
        genHistoryItem(coll, vehsC7, 16, new MotTestData(TestOutcome.FAILED, 2000, "2010-09-02"));
        genHistoryItem(coll, vehsC7, 16, new MotTestData(TestOutcome.PASSED, 2001, "2010-09-02"));
        genHistoryItem(coll, vehsC7, 17, new MotTestData(TestOutcome.FAILED, 3000, "2011-09-03"));
        genHistoryItem(coll, vehsC7, 17, new MotTestData(TestOutcome.PASSED, 3001, "2011-09-03"));
        genHistoryItem(coll, vehsC7, 18, new MotTestData(TestOutcome.FAILED, 4000, "2012-09-04"));
        genHistoryItem(coll, vehsC7, 18, new MotTestData(TestOutcome.PASSED, 4001, "2012-09-04"));
        genHistoryItem(coll, vehsC7, 20, new MotTestData(TestOutcome.PRS, 5000, "2013-09-05"));
    }

    private void genTest(VtsDataCollector vtsDataCollector, Integer vtsIndex, Vehicle vehicle,
            MotTestData motTestData) {
        generateTest(vtsDataCollector, vtsIndex, vehicle, motTestData, null);
    }

    private void generateTest(VtsDataCollector vtsDataCollector, Integer vtsIndex, Vehicle vehicle,
            MotTestData motTestData, RetestData retestData) {

        VtsData vtsData = vtsDataCollector.vtsForIndex(vtsIndex);
        Login tester = vtsData.testerForVehicle(vehicle);
        motTestApi.createTest(tester, vehicle, vtsData.vtsId, motTestData, retestData);
        String description = String.format("Test done by [%s] for vehicle [%s] " +
                        "at VTS [no=%d,id=%d], result [%s]" +
                        (retestData != null ? " with retest" : ""), tester.username, vehicle.carReg,
                vtsIndex, vtsData.vtsId, motTestData.outcome);

        System.out.println(description);
    }

    static class VtsDataCollector {

        private HashMap<Integer, VtsData> map = new HashMap<>();

        VtsDataCollector addAll(VtsData... data) {
            assert data.length > 0;
            for (VtsData d : data) {
                map.put(d.index, d);
            }
            return this;
        }

        VtsData vtsForIndex(Integer index) {
            VtsData data = map.get(index);
            Objects.requireNonNull(data);
            return data;
        }
    }


    static class VtsData {

        public final int vtsId;
        public final int index;
        public final Map<TestGroup, List<Login>> testers;

        VtsData(int index, int vtsId) {
            this.vtsId = vtsId;
            this.index = index;
            this.testers = new HashMap<>();
        }

        VtsData addTesters(List<Login> testerList, TestGroup... groups) {
            assert groups.length > 0;
            for (TestGroup group : groups) {
                this.testers.put(group, testerList);
            }
            return this;
        }

        Login testerForVehicle(Vehicle vehicle) {
            TestGroup group = null;
            switch (vehicle.vehicleClass.getId()) {
                case "1":
                case "2":
                    group = TestGroup.group1;
                    break;
                case "3":
                case "4":
                case "5":
                case "7":
                    group = TestGroup.group2;
                    break;
            }
            return testers.get(group).get(0);
        }
    }
}
