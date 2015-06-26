package uk.gov.dvsa.helper;


import uk.gov.dvsa.ui.pages.module.BrakeTestResultsPageElements;

import java.util.LinkedHashMap;
import java.util.Map;

public class BrakeTestResultsHelper {

    public static Map<BrakeTestResultsPageElements, Object> allPass() {
        Map<BrakeTestResultsPageElements, Object> map =
                new LinkedHashMap<BrakeTestResultsPageElements, Object>();
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE1_NEARSIDE, "200");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE1_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE1_OFFSIDE, "250");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE1_OFFSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE2_NEARSIDE, "200");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE2_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE2_OFFSIDE, "200");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE2_OFFSIDE_LOCK, Boolean.FALSE);

        map.put(BrakeTestResultsPageElements.PARKING_ONE_NEARSIDE, "200");
        map.put(BrakeTestResultsPageElements.PARKING_ONE_NEARSIDE_LOCK, Boolean.FALSE);
        map.put(BrakeTestResultsPageElements.PARKING_ONE_OFFSIDE, "200");
        map.put(BrakeTestResultsPageElements.PARKING_ONE_OFFSIDE_LOCK, Boolean.FALSE);
        return map;
    }

    public static Map<BrakeTestResultsPageElements, Object> allFail() {
        Map<BrakeTestResultsPageElements, Object> map =
                new LinkedHashMap<BrakeTestResultsPageElements, Object>();
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE1_NEARSIDE, "20");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE1_OFFSIDE, "50");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE2_NEARSIDE, "20");
        map.put(BrakeTestResultsPageElements.SERVICE_BRAKE1_AXLE2_OFFSIDE, "50");
        map.put(BrakeTestResultsPageElements.PARKING_ONE_NEARSIDE, "20");
        map.put(BrakeTestResultsPageElements.PARKING_ONE_OFFSIDE, "50");
        return map;
    }
}
