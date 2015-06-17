package com.dvsa.mot.selenium.datasource;

import java.util.Calendar;

public class PlansAndDimensions {

    public final static PlansAndDimensions PLANS_AND_DIMENSIONS1 =
            new PlansAndDimensions("plan1234", "1", "January",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR)), "testbay4523", "12",
                    "March", String.valueOf(Calendar.getInstance().get(Calendar.YEAR) - 1));
    public final static PlansAndDimensions PLANS_AND_DIMENSIONS2 =
            new PlansAndDimensions("11112a", "1", "July",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR)), "testbay4523", "12",
                    "March", String.valueOf(Calendar.getInstance().get(Calendar.YEAR) - 1));
    public final static PlansAndDimensions PLANS_AND_DIMENSIONS_SITE_PLAN_FUTURE_DATE =
            new PlansAndDimensions("plan8889", "23", "April",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1), "testbay1111223",
                    "19", "May", String.valueOf(Calendar.getInstance().get(Calendar.YEAR) - 1));
    public final static PlansAndDimensions PLANS_AND_DIMENSIONS_FUTURE =
            new PlansAndDimensions("plan8889", "23", "April",
                    String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1), "testbay1111223",
                    "19", "May", String.valueOf(Calendar.getInstance().get(Calendar.YEAR) + 1));

    public final String sitePlanNumber;
    public final String sitePlanDay;
    public final String sitePlanMonth;
    public final String sitePlanYear;
    public final String testBayNumber;
    public final String testBayDay;
    public final String testBayMonth;
    public final String testBayYear;

    public PlansAndDimensions(String sitePlanNumber, String sitePlanDay, String sitePlanMonth,
            String sitePlanYear, String testBayNumber, String testBayDay, String testBayMonth,
            String testBayYear) {
        super();
        this.sitePlanNumber = sitePlanNumber;
        this.sitePlanDay = sitePlanDay;
        this.sitePlanMonth = sitePlanMonth;
        this.sitePlanYear = sitePlanYear;
        this.testBayNumber = testBayNumber;
        this.testBayDay = testBayDay;
        this.testBayMonth = testBayMonth;
        this.testBayYear = testBayYear;
    }

}


